<?php
/**
 * Expresshipping database bridge.
 * Keep this endpoint behind HTTPS. Keep connect.php OUTSIDE public_html.
 * Define $DB_BRIDGE_TOKEN (at least 32 characters) in that protected file.
 * It must create a mysqli connection in $Conn, $conn, $mysqli, $con, or $connection.
 * Default protected file: /home/CPANEL_USER/connect.php
 */
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('X-Content-Type-Options: nosniff');

function bridgeRespond(int $status, array $payload): void {
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Store page snapshots outside public_html. The default location is the
 * hosting account's home directory, alongside Connect.php.
 */
function bridgeBackupPage(array $row): string {
    $backupDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'db_bridge_backups';
    if (!is_dir($backupDir) && !mkdir($backupDir, 0700, true) && !is_dir($backupDir)) {
        throw new RuntimeException('Could not create the private backup directory.');
    }
    @chmod($backupDir, 0700);
    $id = (int) ($row['id'] ?? 0);
    if ($id < 1) {
        throw new RuntimeException('Cannot back up a page without a valid ID.');
    }
    $backupId = 'page-' . $id . '-' . gmdate('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.json';
    $payload = [
        'table' => 'pages',
        'primary_key' => 'id',
        'record_id' => $id,
        'created_at' => gmdate('c'),
        'row' => $row
    ];
    $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($json === false || file_put_contents($backupDir . DIRECTORY_SEPARATOR . $backupId, $json . PHP_EOL, LOCK_EX) === false) {
        throw new RuntimeException('Could not write the private page backup.');
    }
    @chmod($backupDir . DIRECTORY_SEPARATOR . $backupId, 0600);
    return $backupId;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    bridgeRespond(405, ['ok' => false, 'error' => 'POST requests only.']);
}

$authHeader = $_SERVER['HTTP_AUTHORIZATION']
    ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
    ?? $_SERVER['Authorization']
    ?? '';

// Fallback for hosting environments that strip the standard Authorization header.
if (!$authHeader) {
    $alternateToken = $_SERVER['HTTP_X_DB_BRIDGE_TOKEN'] ?? '';
    if (is_string($alternateToken) && $alternateToken !== '') {
        $authHeader = 'Bearer ' . $alternateToken;
    }
}
if (!$authHeader && function_exists('getallheaders')) {
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
}
if (!preg_match('/^Bearer\s+(.+)$/i', trim($authHeader), $matches)) {
    bridgeRespond(401, ['ok' => false, 'error' => 'Authentication required.']);
}
$providedToken = trim($matches[1]);

$connectionFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Connect.php';
$environmentPath = getenv('DB_BRIDGE_CONNECT_FILE');
if (is_string($environmentPath) && $environmentPath !== '') {
    $connectionFile = $environmentPath;
}
if (!is_file($connectionFile) || !is_readable($connectionFile)) {
    bridgeRespond(503, ['ok' => false, 'error' => 'Protected database connection file was not found.']);
}

try {
    require_once $connectionFile;
} catch (Throwable $e) {
    error_log('DB bridge connection include failed: ' . $e->getMessage());
    bridgeRespond(503, ['ok' => false, 'error' => 'Database connection could not be loaded.']);
}

$expectedToken = $DB_BRIDGE_TOKEN ?? (defined('DB_BRIDGE_TOKEN') ? constant('DB_BRIDGE_TOKEN') : '');
if (!is_string($expectedToken) || strlen($expectedToken) < 32) {
    bridgeRespond(503, ['ok' => false, 'error' => 'Bridge token is not configured.']);
}
if (!hash_equals($expectedToken, $providedToken)) {
    bridgeRespond(403, ['ok' => false, 'error' => 'Invalid authentication token.']);
}

$db = null;
foreach (['Conn', 'conn', 'mysqli', 'con', 'connection'] as $variableName) {
    if (isset($$variableName) && $$variableName instanceof mysqli) {
        $db = $$variableName;
        break;
    }
}
if (!$db || $db->connect_errno) {
    bridgeRespond(503, ['ok' => false, 'error' => 'A valid mysqli connection was not found.']);
}
$db->set_charset('utf8mb4');

$input = json_decode((string) file_get_contents('php://input'), true);
if (!is_array($input)) {
    bridgeRespond(400, ['ok' => false, 'error' => 'A valid JSON request body is required.']);
}

$action = $input['action'] ?? '';
try {
    if ($action === 'ping') {
        bridgeRespond(200, ['ok' => true, 'message' => 'Database bridge connected.']);
    }

    if ($action === 'tables') {
        $result = $db->query('SHOW TABLES');
        $tables = [];
        while ($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }
        bridgeRespond(200, ['ok' => true, 'tables' => $tables]);
    }

    if ($action === 'describe') {
        $table = $input['table'] ?? '';
        if (!is_string($table) || !preg_match('/^[A-Za-z0-9_]+$/', $table)) {
            bridgeRespond(400, ['ok' => false, 'error' => 'Invalid table name.']);
        }
        $quote = chr(96);
        $quotedTable = $quote . $db->real_escape_string($table) . $quote;
        $result = $db->query('DESCRIBE ' . $quotedTable);
        $columns = [];
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row;
        }
        bridgeRespond(200, ['ok' => true, 'table' => $table, 'columns' => $columns]);
    }


    // Back up one page record to a private directory outside public_html.
    if ($action === 'backup_page') {
        $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if (!$id) {
            bridgeRespond(400, ['ok' => false, 'error' => 'A valid page ID is required.']);
        }
        $stmt = $db->prepare('SELECT * FROM `pages` WHERE `id` = ? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        if (!$row) {
            bridgeRespond(404, ['ok' => false, 'error' => 'Page record not found.']);
        }
        $backupId = bridgeBackupPage($row);
        bridgeRespond(200, ['ok' => true, 'message' => 'Page backup created outside public_html.', 'backup_id' => $backupId, 'record_id' => (int) $id]);
    }

    // Update one pages row only. A full pre-update snapshot is written before
    // the SQL update is attempted. No arbitrary SQL writes are accepted.
    if ($action === 'update_page') {
        $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $fields = $input['fields'] ?? null;
        $allowedFields = [
            'Page_Name', 'Page_Contents', 'link', 'icon', 'parent', 'HasChild',
            'type', 'Cover_Photo', 'Description', 'styles_css', 'script_js',
            'Status', 'target', 'Date'
        ];
        $nullableFields = ['link', 'icon', 'Cover_Photo', 'Description'];
        if (!$id || !is_array($fields) || count($fields) === 0) {
            bridgeRespond(400, ['ok' => false, 'error' => 'A valid page ID and non-empty fields object are required.']);
        }
        if (count($fields) > count($allowedFields)) {
            bridgeRespond(400, ['ok' => false, 'error' => 'Too many fields supplied.']);
        }
        foreach ($fields as $field => $value) {
            if (!in_array($field, $allowedFields, true)) {
                bridgeRespond(400, ['ok' => false, 'error' => 'Field is not permitted: ' . (string) $field]);
            }
            if ($value === null && !in_array($field, $nullableFields, true)) {
                bridgeRespond(400, ['ok' => false, 'error' => 'This field cannot be null: ' . $field]);
            }
            if ($value !== null && !is_scalar($value)) {
                bridgeRespond(400, ['ok' => false, 'error' => 'Field values must be strings, numbers, booleans, or null.']);
            }
            if (is_string($value) && strlen($value) > 5000000) {
                bridgeRespond(413, ['ok' => false, 'error' => 'A field value exceeds the 5 MB limit.']);
            }
        }

        $db->begin_transaction();
        try {
            $selectStmt = $db->prepare('SELECT * FROM `pages` WHERE `id` = ? LIMIT 1 FOR UPDATE');
            $selectStmt->bind_param('i', $id);
            $selectStmt->execute();
            $result = $selectStmt->get_result();
            $oldRow = $result->fetch_assoc();
            $selectStmt->close();
            if (!$oldRow) {
                $db->rollback();
                bridgeRespond(404, ['ok' => false, 'error' => 'Page record not found.']);
            }

            $backupId = bridgeBackupPage($oldRow);
            $setParts = [];
            $values = [];
            foreach ($fields as $field => $value) {
                $setParts[] = '`' . $field . '` = ?';
                $values[] = is_bool($value) ? (int) $value : $value;
            }
            $sql = 'UPDATE `pages` SET ' . implode(', ', $setParts) . ' WHERE `id` = ?';
            $values[] = (int) $id;
            $types = str_repeat('s', count($fields)) . 'i';
            $updateStmt = $db->prepare($sql);
            $bindArgs = [$types];
            foreach ($values as $key => &$value) {
                $bindArgs[] = &$value;
            }
            unset($value);
            call_user_func_array([$updateStmt, 'bind_param'], $bindArgs);
            $updateStmt->execute();
            $affectedRows = $updateStmt->affected_rows;
            $updateStmt->close();
            $db->commit();

            bridgeRespond(200, [
                'ok' => true,
                'message' => 'Page updated. A pre-update backup was saved outside public_html.',
                'record_id' => (int) $id,
                'backup_id' => $backupId,
                'updated_fields' => array_keys($fields),
                'affected_rows' => $affectedRows
            ]);
        } catch (Throwable $e) {
            $db->rollback();
            throw $e;
        }
    }

    if ($action === 'select') {
        $sql = trim((string) ($input['sql'] ?? ''));
        if ($sql === '' || !preg_match('/^SELECT\b/i', $sql)) {
            bridgeRespond(400, ['ok' => false, 'error' => 'Only SELECT statements are allowed.']);
        }
        if (strpos($sql, ';') !== false || preg_match('/\b(INTO|OUTFILE|DUMPFILE|FOR\s+UPDATE|LOCK\s+IN\s+SHARE)\b/i', $sql)) {
            bridgeRespond(400, ['ok' => false, 'error' => 'This query contains a disallowed operation.']);
        }
        $result = $db->query($sql);
        if (!$result instanceof mysqli_result) {
            bridgeRespond(400, ['ok' => false, 'error' => 'The query did not return rows.']);
        }
        $rows = [];
        while (($row = $result->fetch_assoc()) !== null && count($rows) < 100) {
            $rows[] = $row;
        }
        bridgeRespond(200, ['ok' => true, 'rows' => $rows, 'returned' => count($rows), 'limit' => 100]);
    }

    bridgeRespond(400, ['ok' => false, 'error' => 'Unknown action.']);
} catch (Throwable $e) {
    error_log('DB bridge request failed: ' . $e->getMessage());
    bridgeRespond(400, ['ok' => false, 'error' => 'Database request failed. Check the server error log.']);
}
