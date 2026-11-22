<?php
declare(strict_types=1);
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function spin_json(int $status, array $data): never {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') spin_json(405, ['ok'=>false,'message'=>'POST required']);

require_once __DIR__ . '/../Connect.php';
if (!isset($Conn) || !($Conn instanceof mysqli)) spin_json(503, ['ok'=>false,'message'=>'Database unavailable']);
$Conn->set_charset('utf8mb4');

$Conn->query("CREATE TABLE IF NOT EXISTS spin_rewards (
 id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(120) NOT NULL,
 reward_type VARCHAR(30) NOT NULL DEFAULT 'discount',
 reward_value DECIMAL(12,2) NOT NULL DEFAULT 0,
 probability DECIMAL(8,3) NOT NULL DEFAULT 1,
 max_coupons INT UNSIGNED NULL DEFAULT NULL,
 issued_count INT UNSIGNED NOT NULL DEFAULT 0,
 expiry_days INT UNSIGNED NOT NULL DEFAULT 30,
 is_active TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 INDEX idx_spin_reward_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$Conn->query("CREATE TABLE IF NOT EXISTS spin_coupons (
 id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 reward_id INT UNSIGNED NULL,
 reward_name VARCHAR(120) NOT NULL,
 reward_type VARCHAR(30) NOT NULL,
 reward_value DECIMAL(12,2) NOT NULL DEFAULT 0,
 coupon_code VARCHAR(32) NOT NULL UNIQUE,
 spin_token_hash CHAR(64) NOT NULL UNIQUE,
 session_key CHAR(64) NULL UNIQUE,
 phone VARCHAR(32) NULL,
 spun_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 claimed_at DATETIME NULL,
 expires_at DATETIME NULL,
 status ENUM('unclaimed','active','redeemed','expired') NOT NULL DEFAULT 'unclaimed',
 redeemed_at DATETIME NULL,
 INDEX idx_spin_coupon_phone (phone),
 INDEX idx_spin_coupon_status (status),
 INDEX idx_spin_coupon_expiry (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$check = $Conn->query("SELECT COUNT(*) AS total FROM spin_rewards");
$total = $check ? (int)$check->fetch_assoc()['total'] : 0;
if ($total === 0) {
    $seed = $Conn->prepare("INSERT INTO spin_rewards (name,reward_type,reward_value,probability,expiry_days,is_active) VALUES
      ('5% OFF','percent',5,45,30,1),('10% OFF','percent',10,30,30,1),('15% OFF','percent',15,15,30,1),('Free Shipping','free_shipping',0,10,14,1)");
    if ($seed) { $seed->execute(); $seed->close(); }
}

$input = json_decode(file_get_contents('php://input') ?: '{}', true);
if (!is_array($input)) spin_json(400, ['ok'=>false,'message'=>'Invalid request']);
$action = (string)($input['action'] ?? '');

function spin_admin(): bool { return !empty($_SESSION['User_id']); }
function spin_csrf_ok(array $input): bool {
    return isset($_SESSION['spin_admin_csrf'], $input['csrf'])
        && hash_equals((string)$_SESSION['spin_admin_csrf'], (string)$input['csrf']);
}
function spin_coupon_code(mysqli $db): string {
    do {
        $code = 'EXP-' . strtoupper(bin2hex(random_bytes(4)));
        $stmt = $db->prepare('SELECT id FROM spin_coupons WHERE coupon_code=? LIMIT 1');
        $stmt->bind_param('s', $code); $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0; $stmt->close();
    } while ($exists);
    return $code;
}

if ($action === 'spin') {
    $sessionKey = hash('sha256', session_id());
    $existing = $Conn->prepare("SELECT spin_token_hash,reward_name,reward_type,reward_value,status FROM spin_coupons WHERE session_key=? LIMIT 1");
    $existing->bind_param('s', $sessionKey); $existing->execute();
    $prior = $existing->get_result()->fetch_assoc(); $existing->close();
    if ($prior) spin_json(409, ['ok'=>false,'message'=>'You have already used your spin in this browser session.']);

    $Conn->begin_transaction();
    try {
        $result = $Conn->query("SELECT id,name,reward_type,reward_value,probability,max_coupons,issued_count,expiry_days
            FROM spin_rewards WHERE is_active=1 AND (max_coupons IS NULL OR issued_count < max_coupons) ORDER BY id FOR UPDATE");
        $rewards = [];
        $weightTotal = 0.0;
        while ($r = $result->fetch_assoc()) {
            $r['probability'] = max(0, (float)$r['probability']);
            if ($r['probability'] > 0) { $weightTotal += $r['probability']; $rewards[] = $r; }
        }
        if (!$rewards || $weightTotal <= 0) throw new RuntimeException('No prizes are currently available.');
        $roll = random_int(1, 1000000) / 1000000 * $weightTotal;
        $cursor = 0.0; $selected = $rewards[count($rewards)-1];
        foreach ($rewards as $reward) {
            $cursor += $reward['probability'];
            if ($roll <= $cursor) { $selected = $reward; break; }
        }
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);
        $code = spin_coupon_code($Conn);
        $rewardId = (int)$selected['id'];
        $name = (string)$selected['name'];
        $type = (string)$selected['reward_type'];
        $value = (float)$selected['reward_value'];
        $insert = $Conn->prepare("INSERT INTO spin_coupons (reward_id,reward_name,reward_type,reward_value,coupon_code,spin_token_hash,session_key) VALUES (?,?,?,?,?,?,?)");
        $insert->bind_param('isdssss', $rewardId, $name, $type, $value, $code, $tokenHash, $sessionKey);
        if (!$insert->execute()) throw new RuntimeException('Could not create your spin record.');
        $insert->close();
        $inc = $Conn->prepare("UPDATE spin_rewards SET issued_count=issued_count+1 WHERE id=?");
        $inc->bind_param('i', $rewardId); $inc->execute(); $inc->close();
        $Conn->commit();
        $segments = array_map(static fn($r)=>['id'=>(int)$r['id'],'name'=>$r['name']], $rewards);
        spin_json(200, ['ok'=>true,'token'=>$token,'reward'=>['id'=>$rewardId,'name'=>$name,'type'=>$type,'value'=>$value],'segments'=>$segments,'selected_index'=>array_search($rewardId,array_column($segments,'id'),true)]);
    } catch (Throwable $e) {
        $Conn->rollback();
        spin_json(400, ['ok'=>false,'message'=>$e->getMessage()]);
    }
}

if ($action === 'claim') {
    $token = (string)($input['token'] ?? '');
    $phone = trim((string)($input['phone'] ?? ''));
    if (!preg_match('/^[+0-9() .-]{7,24}$/', $phone)) spin_json(422, ['ok'=>false,'message'=>'Enter a valid phone number.']);
    if (!preg_match('/^[a-f0-9]{64}$/', $token)) spin_json(422, ['ok'=>false,'message'=>'Your spin session is invalid. Please spin again.']);
    $hash = hash('sha256', $token);
    $stmt = $Conn->prepare("UPDATE spin_coupons SET phone=?,claimed_at=NOW(),status=IF(expires_at IS NOT NULL AND expires_at<NOW(),'expired','active'),expires_at=DATE_ADD(NOW(), INTERVAL (SELECT expiry_days FROM spin_rewards WHERE spin_rewards.id=spin_coupons.reward_id) DAY) WHERE spin_token_hash=? AND status='unclaimed' AND session_key=?");
    $sessionKey = hash('sha256', session_id());
    $stmt->bind_param('sss', $phone, $hash, $sessionKey);
    $stmt->execute(); $changed = $stmt->affected_rows; $stmt->close();
    if ($changed !== 1) spin_json(409, ['ok'=>false,'message'=>'This spin has already been claimed or has expired.']);
    $stmt = $Conn->prepare("SELECT coupon_code,reward_name,reward_type,reward_value,expires_at FROM spin_coupons WHERE spin_token_hash=? LIMIT 1");
    $stmt->bind_param('s', $hash); $stmt->execute(); $coupon = $stmt->get_result()->fetch_assoc(); $stmt->close();
    spin_json(200, ['ok'=>true,'coupon'=>$coupon]);
}

if (str_starts_with($action, 'admin_')) {
    if (!spin_admin()) spin_json(401, ['ok'=>false,'message'=>'Administrator login required.']);
    if (!spin_csrf_ok($input)) spin_json(403, ['ok'=>false,'message'=>'Security token expired. Refresh the page.']);
    if ($action === 'admin_list') {
        $rewards=[]; $res=$Conn->query("SELECT * FROM spin_rewards ORDER BY id DESC");
        while($r=$res->fetch_assoc()) $rewards[]=$r;
        $coupons=[]; $res=$Conn->query("SELECT id,coupon_code,reward_name,phone,status,spun_at,expires_at,redeemed_at FROM spin_coupons ORDER BY id DESC LIMIT 200");
        while($r=$res->fetch_assoc()) $coupons[]=$r;
        spin_json(200,['ok'=>true,'rewards'=>$rewards,'coupons'=>$coupons]);
    }
    if ($action === 'admin_save_reward') {
        $id=(int)($input['id']??0); $name=trim((string)($input['name']??'')); $type=(string)($input['reward_type']??'discount');
        $value=(float)($input['reward_value']??0); $prob=(float)($input['probability']??0);
        $max=($input['max_coupons']??'')===''?null:max(1,(int)$input['max_coupons']);
        $days=max(1,min(3650,(int)($input['expiry_days']??30))); $active=!empty($input['is_active'])?1:0;
        if($name===''||strlen($name)>120||!in_array($type,['percent','fixed','free_shipping','other'],true)||$value<0||$prob<0||$prob>100000) spin_json(422,['ok'=>false,'message'=>'Check the reward details and probability.']);
        if($id>0){
            $s=$Conn->prepare("UPDATE spin_rewards SET name=?,reward_type=?,reward_value=?,probability=?,max_coupons=?,expiry_days=?,is_active=? WHERE id=?");
            $s->bind_param('ssddiiii',$name,$type,$value,$prob,$max,$days,$active,$id);
        }else{
            $s=$Conn->prepare("INSERT INTO spin_rewards (name,reward_type,reward_value,probability,max_coupons,expiry_days,is_active) VALUES (?,?,?,?,?,?,?)");
            $s->bind_param('ssddiii',$name,$type,$value,$prob,$max,$days,$active);
        }
        $ok=$s->execute();$s->close(); spin_json($ok?200:500,['ok'=>$ok,'message'=>$ok?'Reward saved.':'Could not save reward.']);
    }
    if ($action === 'admin_delete_reward') {
        $id=(int)($input['id']??0); $s=$Conn->prepare("DELETE FROM spin_rewards WHERE id=?");$s->bind_param('i',$id);$ok=$s->execute();$s->close();
        spin_json($ok?200:500,['ok'=>$ok,'message'=>$ok?'Reward removed.':'Could not remove reward.']);
    }
    if ($action === 'admin_search_coupon') {
        $term = '%' . trim((string)($input['query'] ?? '')) . '%';
        $s = $Conn->prepare("SELECT id,coupon_code,reward_name,phone,status,spun_at,expires_at,redeemed_at FROM spin_coupons WHERE coupon_code LIKE ? OR phone LIKE ? ORDER BY id DESC LIMIT 100");
        $s->bind_param('ss',$term,$term); $s->execute(); $rows=[];
        $res=$s->get_result(); while($row=$res->fetch_assoc()) $rows[]=$row; $s->close();
        spin_json(200,['ok'=>true,'coupons'=>$rows]);
    }
    if ($action === 'admin_redeem') {
        $code=strtoupper(trim((string)($input['code']??'')));
        $s=$Conn->prepare("UPDATE spin_coupons SET status='redeemed',redeemed_at=NOW() WHERE coupon_code=? AND status='active' AND (expires_at IS NULL OR expires_at>=NOW())");
        $s->bind_param('s',$code);$s->execute();$n=$s->affected_rows;$s->close();
        spin_json(200,['ok'=>$n===1,'message'=>$n===1?'Coupon marked as redeemed.':'Coupon not found, expired or already redeemed.']);
    }
}
spin_json(400,['ok'=>false,'message'=>'Unknown action.']);
