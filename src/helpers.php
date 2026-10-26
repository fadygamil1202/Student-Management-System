<?php
declare(strict_types=1);

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function url(string $path = ''): string
{
    return BASE_HREF . '/' . ltrim($path, '/');
}

/** Full link printed on the NFC card / QR. Uses app_url from config, or detects it. */
function public_link(string $token): string
{
    $base = rtrim((string)config('app_url', ''), '/');
    if ($base === '') {
        $scheme = (($_SERVER['HTTPS'] ?? '') === 'on') ? 'https' : 'http';
        $base = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . BASE_HREF;
    }
    return $base . '/e/' . $token;
}

/** Prefix root-relative links (href="/x", action="/x", src="/x") with the app's subfolder. */
function with_base(string $html): string
{
    if (BASE_HREF === '') return $html;
    return preg_replace('#\b(href|action|src)="/(?!/)#', '$1="' . BASE_HREF . '/', $html);
}

function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function take_flashes(): array
{
    $f = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $f;
}

// ---------- CSRF ----------
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    $sent = $_POST['_csrf'] ?? '';
    if (!is_string($sent) || !hash_equals(csrf_token(), $sent)) {
        http_response_code(403);
        exit(t('error.csrf'));
    }
}

// ---------- Views ----------
function view(string $template, array $data = [], string $layout = 'layout'): void
{
    extract($data, EXTR_SKIP);
    ob_start();
    require BASE_PATH . "/views/{$template}.php";
    $content = ob_get_clean();
    ob_start();
    require BASE_PATH . "/views/{$layout}.php";
    echo with_base(ob_get_clean());
}

function abort(int $code, ?string $message = null): never
{
    http_response_code($code);
    $message ??= t('error.' . $code);
    view('error', ['code' => $code, 'message' => $message]);
    exit;
}

// ---------- Misc ----------
function random_token(): string
{
    return bin2hex(random_bytes(16)); // 32 hex chars, 128 bits
}

function client_ip_hash(): string
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    return hash_hmac('sha256', $ip, (string)config('app_key', 'key'));
}

/** Returns false when the caller exceeded $limit hits within $windowSeconds. */
function rate_limit(string $bucket, int $limit, int $windowSeconds): bool
{
    $window = date('Y-m-d H:i:s', intdiv(time(), $windowSeconds) * $windowSeconds);
    $ip = client_ip_hash();
    q('INSERT INTO rate_limits (bucket, ip_hash, window_start, hits) VALUES (?,?,?,1)
       ON DUPLICATE KEY UPDATE hits = hits + 1', [$bucket, $ip, $window]);
    $row = q_one('SELECT hits FROM rate_limits WHERE bucket=? AND ip_hash=? AND window_start=?', [$bucket, $ip, $window]);
    // occasional cleanup
    if (random_int(1, 100) === 1) {
        q('DELETE FROM rate_limits WHERE window_start < (NOW() - INTERVAL 1 DAY)');
    }
    return (int)$row['hits'] <= $limit;
}

function age_from_dob(?string $dob): ?int
{
    if (!$dob) return null;
    try {
        return (new DateTime($dob))->diff(new DateTime('today'))->y;
    } catch (Exception) {
        return null;
    }
}

function input(string $key, string $default = ''): string
{
    $v = $_POST[$key] ?? $default;
    return is_string($v) ? trim($v) : $default;
}

function input_int(string $key): ?int
{
    $v = $_POST[$key] ?? null;
    return (is_string($v) && ctype_digit($v)) ? (int)$v : null;
}

function format_dt(?string $dt): string
{
    return $dt ? date('Y-m-d H:i', strtotime($dt)) : '';
}

const BLOOD_TYPES = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
const SEVERITIES = ['mild', 'moderate', 'severe'];

/** Parse "Peanuts|severe" lines into allergy records. */
function parse_allergies(string $text): array
{
    $out = [];
    foreach (preg_split('/\R/', $text) as $line) {
        $line = trim($line);
        if ($line === '') continue;
        [$name, $sev] = array_pad(array_map('trim', explode('|', $line, 2)), 2, 'moderate');
        $sev = ['خفيفة' => 'mild', 'متوسطة' => 'moderate', 'شديدة' => 'severe'][$sev] ?? strtolower($sev);
        if (!in_array($sev, SEVERITIES, true)) $sev = 'moderate';
        $out[] = ['name' => mb_substr($name, 0, 100), 'severity' => $sev];
    }
    return $out;
}

function allergies_to_text(?string $json): string
{
    $items = json_decode($json ?? '[]', true) ?: [];
    return implode("\n", array_map(fn($a) => $a['name'] . '|' . $a['severity'], $items));
}

function notify(int $userId, string $message): void
{
    q('INSERT INTO notifications (user_id, message) VALUES (?, ?)', [$userId, mb_substr($message, 0, 500)]);
}
