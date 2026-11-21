<?php
/**
 * Expresshipping database bridge.
 * Keep this endpoint behind HTTPS. Keep connect.php OUTSIDE public_html.
 * Define $DB_BRIDGE_TOKEN (at least 32 characters) in that protected file.
 * It must create a mysqli connection in $conn, $mysqli, $con, or $connection.
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

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    bridgeRespond(405, ['ok' => false, 'error' => 'POST requests only.']);
}

$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
if (!$authHeader && function_exists('getallheaders')) {
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
}
if (!preg_match('/^Bearer\s+(.+)$/i', trim($authHeader), $matches)) {
    bridgeRespond(401, ['ok' => false, 'error' => 'Authentication required.']);
}
$providedToken = trim($matches[1]);

$connectionFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'connect.php';
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
foreach (['conn', 'mysqli', 'con', 'connection'] as $variableName) {
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
