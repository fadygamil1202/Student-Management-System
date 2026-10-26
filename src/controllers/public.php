<?php
declare(strict_types=1);

/** Layer 1: the page opened by tapping the NFC card or the link. No login. */
function load_public_profile(string $token): array
{
    if (!rate_limit('public', (int)config('public_rate_limit', 20), 60)) {
        abort(429);
    }
    $row = q_one('SELECT p.*, s.first_name, s.date_of_birth, s.id AS sid FROM emergency_profiles p
                  JOIN students s ON s.id = p.student_id WHERE p.public_token = ?', [$token]);
    if (!$row || !$row['is_enabled']) {
        abort(404, t('public.not_found'));
    }
    return $row;
}

function log_scan(int $studentId, string $action): void
{
    q('INSERT INTO scan_logs (student_id, action, ip_hash, user_agent) VALUES (?,?,?,?)', [
        $studentId, $action, client_ip_hash(), mb_substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255),
    ]);
}

function public_emergency(string $token): void
{
    $p = load_public_profile($token);
    $sid = (int)$p['sid'];

    // Log the scan; notify guardians at most once per 10 minutes per student.
    $recent = q_one("SELECT 1 FROM scan_logs WHERE student_id=? AND action='view' AND created_at > NOW() - INTERVAL 10 MINUTE", [$sid]);
    log_scan($sid, 'view');
    if (!$recent) {
        foreach (q_all('SELECT user_id FROM guardians WHERE student_id=?', [$sid]) as $g) {
            notify((int)$g['user_id'], t('notify.scan', ['name' => $p['first_name'], 'time' => date('H:i')]));
        }
    }

    $hasGuardianPhone = (bool)q_one('SELECT 1 FROM guardians g JOIN users u ON u.id=g.user_id
                                     WHERE g.student_id=? AND u.phone IS NOT NULL AND u.phone<>""', [$sid]);

    header('X-Robots-Tag: noindex, nofollow');
    header('Cache-Control: no-store');
    view('public/emergency', [
        'title'    => t('public.title'),
        'p'        => $p,
        'token'    => $token,
        'age'      => $p['show_age'] ? age_from_dob($p['date_of_birth']) : null,
        'allergies'=> $p['show_allergies'] ? (json_decode($p['allergies'] ?? '[]', true) ?: []) : [],
        'canCall'  => $hasGuardianPhone,
    ], 'layout_plain');
}

/**
 * "Call parent": the number is never printed on the page. The click is logged,
 * guardians are notified, then the phone's dialer opens.
 * For full number masking (the caller never sees the number), connect a
 * telephony provider here — see README.
 */
function public_call_parent(string $token): void
{
    $p = load_public_profile($token);
    $sid = (int)$p['sid'];
    $g = q_one('SELECT u.id, u.phone FROM guardians g JOIN users u ON u.id=g.user_id
                WHERE g.student_id=? AND u.phone IS NOT NULL AND u.phone<>"" ORDER BY g.is_primary DESC LIMIT 1', [$sid]);
    if (!$g) abort(404, t('public.no_contact'));

    log_scan($sid, 'call_parent');
    foreach (q_all('SELECT user_id FROM guardians WHERE student_id=?', [$sid]) as $row) {
        notify((int)$row['user_id'], t('notify.call', ['name' => $p['first_name'], 'time' => date('H:i')]));
    }
    $phone = preg_replace('/[^0-9+]/', '', $g['phone']);
    header('Location: tel:' . $phone);
    exit;
}
