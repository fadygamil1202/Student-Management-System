<?php
declare(strict_types=1);

function home(): void
{
    redirect(current_user() ? 'dashboard' : 'login');
}

function login_form(): void
{
    if (current_user()) redirect('dashboard');
    view('auth/login', ['title' => t('login.title')], 'layout_plain');
}

function login_submit(): void
{
    if (!rate_limit('login', (int)config('login_rate_limit', 10), 900)) {
        flash('error', t('login.too_many'));
        redirect('login');
    }
    if (attempt_login(input('email'), (string)($_POST['password'] ?? ''))) {
        redirect('dashboard');
    }
    flash('error', t('login.invalid'));
    redirect('login');
}

function logout_submit(): void
{
    logout();
    redirect('login');
}

function switch_lang(string $lang): void
{
    set_lang($lang);
    $back = $_SERVER['HTTP_REFERER'] ?? '/';
    $path = parse_url($back, PHP_URL_PATH) ?: '/';
    // only redirect within this site
    header('Location: ' . (str_starts_with($path, '/') && !str_starts_with($path, '//') ? $path : '/'));
    exit;
}

function dashboard(): void
{
    $user = require_login();
    $sid = $user['school_id'];
    $data = ['title' => t('nav.dashboard'), 'user' => $user];

    if ($user['role'] === 'admin') {
        $data['stats'] = [
            'students' => q_one('SELECT COUNT(*) n FROM students WHERE school_id=?', [$sid])['n'],
            'teachers' => q_one("SELECT COUNT(*) n FROM users WHERE school_id=? AND role='teacher'", [$sid])['n'],
            'parents'  => q_one("SELECT COUNT(*) n FROM users WHERE school_id=? AND role='parent'", [$sid])['n'],
            'scans'    => q_one('SELECT COUNT(*) n FROM scan_logs l JOIN students s ON s.id=l.student_id
                                 WHERE s.school_id=? AND l.created_at > NOW() - INTERVAL 7 DAY', [$sid])['n'],
        ];
        $data['recent_scans'] = q_all('SELECT l.*, s.first_name, s.last_name FROM scan_logs l
                                       JOIN students s ON s.id=l.student_id WHERE s.school_id=?
                                       ORDER BY l.created_at DESC LIMIT 10', [$sid]);
    } elseif ($user['role'] === 'teacher') {
        $data['classes'] = q_all('SELECT c.id, c.name, GROUP_CONCAT(sub.name ORDER BY sub.name SEPARATOR ", ") subjects,
                                  (SELECT COUNT(*) FROM students st WHERE st.class_id=c.id) students
                                  FROM teacher_assignments ta JOIN classes c ON c.id=ta.class_id
                                  JOIN subjects sub ON sub.id=ta.subject_id
                                  WHERE ta.teacher_id=? GROUP BY c.id, c.name ORDER BY c.name', [$user['id']]);
        $data['pending_insights'] = q_one("SELECT COUNT(*) n FROM ai_insights i JOIN students s ON s.id=i.student_id
                                    JOIN teacher_assignments ta ON ta.class_id=s.class_id AND ta.teacher_id=?
                                    WHERE i.status='draft'", [$user['id']])['n'];
    } else {
        $data['children'] = q_all('SELECT s.*, c.name class_name FROM guardians g JOIN students s ON s.id=g.student_id
                                   LEFT JOIN classes c ON c.id=s.class_id WHERE g.user_id=? ORDER BY s.first_name', [$user['id']]);
        $data['unread'] = q_one('SELECT COUNT(*) n FROM notifications WHERE user_id=? AND is_read=0', [$user['id']])['n'];
    }
    view('dashboard/' . $user['role'], $data);
}

function notifications(): void
{
    $user = require_login();
    $items = q_all('SELECT * FROM notifications WHERE user_id=? ORDER BY created_at DESC LIMIT 100', [$user['id']]);
    q('UPDATE notifications SET is_read=1 WHERE user_id=?', [$user['id']]);
    view('notifications', ['title' => t('nav.notifications'), 'items' => $items]);
}

function account_form(): void
{
    $user = require_login();
    view('account', ['title' => t('nav.account'), 'user' => $user]);
}

function account_submit(): void
{
    $user = require_login();
    $name = input('name');
    $phone = input('phone');
    if ($name === '') {
        flash('error', t('error.required', ['field' => t('field.name')]));
        redirect('account');
    }
    q('UPDATE users SET name=?, phone=? WHERE id=?', [$name, $phone ?: null, $user['id']]);

    $new = (string)($_POST['new_password'] ?? '');
    if ($new !== '') {
        if (!password_verify((string)($_POST['current_password'] ?? ''), $user['password_hash'])) {
            flash('error', t('account.wrong_password'));
            redirect('account');
        }
        if (mb_strlen($new) < 8) {
            flash('error', t('account.password_short'));
            redirect('account');
        }
        q('UPDATE users SET password_hash=? WHERE id=?', [password_hash($new, PASSWORD_DEFAULT), $user['id']]);
    }
    flash('success', t('saved'));
    redirect('account');
}
