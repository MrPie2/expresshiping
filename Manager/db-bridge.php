<?php
declare(strict_types=1);

/**
 * Expresshiping database bridge.
 *
 * Install this endpoint only after rotating the credentials currently exposed
 * in Connect.php. The config file must live OUTSIDE DOCUMENT_ROOT.
 *
 * Expected config file:
 * dirname($_SERVER['DOCUMENT_ROOT']) . '/expresshiping-bridge-config.php'
 *
 * It must return:
 * [
 *   'db_host' => 'localhost',
 *   'db_user' => '...',
 *   'db_pass' => '...',
 *   'db_name' => '...',
 *   'token' => 'a-long-random-secret',
 *   'audit_log' => '/absolute/path/outside/public_html/expresshiping-bridge-audit.jsonl'
 * ]
 */

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('X-Content-Type-Options: nosniff');

function bridge_response(int $status, array $body): never
{
    http_response_code($status);
    echo json_encode($body, JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    bridge_response(405, ['ok' => false, 'error' => 'POST required']);
}

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
if (!$isHttps) {
    bridge_response(400, ['ok' => false, 'error' => 'HTTPS is required']);
}

$raw = file_get_contents('php://input', false, null, 0, 2 * 1024 * 1024 + 1);
if ($raw === false || strlen($raw) > 2 * 1024 * 1024) {
    bridge_response(413, ['ok' => false, 'error' => 'Request too large']);
}

$input = json_decode($raw, true);
if (!is_array($input)) {
    bridge_response(400, ['ok' => false, 'error' => 'Invalid JSON']);
}

$configPath = dirname((string)($_SERVER['DOCUMENT_ROOT'] ?? __DIR__))
    . DIRECTORY_SEPARATOR . 'expresshiping-bridge-config.php';

if (!is_file($configPath) || !is_readable($configPath)) {
    bridge_response(503, ['ok' => false, 'error' => 'Bridge configuration is not installed']);
}

$config = require $configPath;
if (!is_array($config)) {
    bridge_response(503, ['ok' => false, 'error' => 'Invalid bridge configuration']);
}

$expectedToken = (string)($config['token'] ?? '');
$authHeader = (string)($_SERVER['HTTP_AUTHORIZATION'] ?? '');
if ($authHeader === '' && function_exists('getallheaders')) {
    $headers = getallheaders();
    $authHeader = (string)($headers['Authorization'] ?? $headers['authorization'] ?? '');
}
$providedToken = preg_match('/^Bearer\s+(.+)$/i', $authHeader, $matches)
    ? trim($matches[1]) : '';

if ($expectedToken === '' || $providedToken === '' || !hash_equals($expectedToken, $providedToken)) {
    bridge_response(401, ['ok' => false, 'error' => 'Unauthorized']);
}

$action = (string)($input['action'] ?? '');
if (!in_array($action, ['ping', 'read_page', 'update_page'], true)) {
    bridge_response(400, ['ok' => false, 'error' => 'Unsupported action']);
}

mysqli_report(MYSQLI_REPORT_OFF);
$db = @new mysqli(
    (string)($config['db_host'] ?? 'localhost'),
    (string)($config['db_user'] ?? ''),
    (string)($config['db_pass'] ?? ''),
    (string)($config['db_name'] ?? '')
);

if ($db->connect_errno) {
    error_log('Expresshiping bridge: database connection failed (' . $db->connect_errno . ')');
    bridge_response(503, ['ok' => false, 'error' => 'Database connection failed']);
}
$db->set_charset('utf8mb4');

if ($action === 'ping') {
    bridge_response(200, ['ok' => true, 'message' => 'Authenticated bridge is reachable']);
}

$id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
]);
if ($id === false || $id === null) {
    bridge_response(400, ['ok' => false, 'error' => 'A valid page id is required']);
}

$select = $db->prepare(
    'SELECT id, Page_Name, Page_Contents, styles_css, script_js FROM pages WHERE id = ? LIMIT 1'
);
if (!$select) {
    bridge_response(500, ['ok' => false, 'error' => 'Could not prepare page lookup']);
}
$select->bind_param('i', $id);
$select->execute();
$result = $select->get_result();
$page = $result ? $result->fetch_assoc() : null;
$select->close();

if (!$page) {
    bridge_response(404, ['ok' => false, 'error' => 'Page not found']);
}

if ($action === 'read_page') {
    bridge_response(200, ['ok' => true, 'page' => $page]);
}

if (($input['confirm'] ?? false) !== true) {
    bridge_response(400, ['ok' => false, 'error' => 'Set confirm=true to approve this update']);
}

$allowedFields = ['Page_Contents', 'styles_css', 'script_js'];
$changes = $input['changes'] ?? null;
if (!is_array($changes) || !$changes) {
    bridge_response(400, ['ok' => false, 'error' => 'No page changes supplied']);
}

foreach ($changes as $field => $value) {
    if (!in_array($field, $allowedFields, true) || !is_string($value)) {
        bridge_response(400, ['ok' => false, 'error' => 'Only Page_Contents, styles_css and script_js may be updated as strings']);
    }
}

$setParts = [];
$values = [];
foreach ($changes as $field => $value) {
    $setParts[] = '`' . $field . '` = ?';
    $values[] = $value;
}
$values[] = $id;
$types = str_repeat('s', count($changes)) . 'i';

$logPath = (string)($config['audit_log'] ?? '');
if ($logPath === '' || !str_starts_with($logPath, dirname((string)$_SERVER['DOCUMENT_ROOT']) . DIRECTORY_SEPARATOR)) {
    bridge_response(503, ['ok' => false, 'error' => 'An audit log path outside the web root must be configured']);
}

$audit = [
    'timestamp' => gmdate('c'),
    'page_id' => $id,
    'page_name' => $page['Page_Name'],
    'before' => array_intersect_key($page, $changes),
    'after' => $changes
];
$logLine = json_encode($audit, JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE) . PHP_EOL;
if (@file_put_contents($logPath, $logLine, FILE_APPEND | LOCK_EX) === false) {
    bridge_response(503, ['ok' => false, 'error' => 'Could not write audit record; update was not applied']);
}

$update = $db->prepare(
    'UPDATE pages SET ' . implode(', ', $setParts) . ' WHERE id = ? LIMIT 1'
);
if (!$update) {
    bridge_response(500, ['ok' => false, 'error' => 'Could not prepare page update']);
}
$params = [];
foreach ($values as $key => $value) {
    $params[$key] = &$values[$key];
}
array_unshift($params, $types);
call_user_func_array([$update, 'bind_param'], $params);

if (!$update->execute()) {
    error_log('Expresshiping bridge: page update failed (' . $update->errno . ')');
    $update->close();
    bridge_response(500, ['ok' => false, 'error' => 'Page update failed']);
}
$update->close();

bridge_response(200, [
    'ok' => true,
    'message' => 'Approved page fields updated',
    'page_id' => $id,
    'updated_fields' => array_keys($changes)
]);
