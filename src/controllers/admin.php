<?php
declare(strict_types=1);

function admin_classes(): void
{
    $user = require_role('admin');
    $sid = $user['school_id'];
    view('admin/classes', [
        'title'       => t('nav.classes'),
        'classes'     => q_all('SELECT c.*, (SELECT COUNT(*) FROM students s WHERE s.class_id=c.id) n FROM classes c WHERE school_id=? ORDER BY name', [$sid]),
        'subjects'    => q_all('SELECT * FROM subjects WHERE school_id=? ORDER BY name', [$sid]),
        'teachers'    => q_all("SELECT id, name FROM users WHERE school_id=? AND role='teacher' AND is_active=1 ORDER BY name", [$sid]),
        'assignments' => q_all('SELECT ta.id, u.name teacher, c.name class, sub.name subject FROM teacher_assignments ta
                                JOIN users u ON u.id=ta.teacher_id JOIN classes c ON c.id=ta.class_id
                                JOIN subjects sub ON sub.id=ta.subject_id WHERE c.school_id=? ORDER BY c.name, sub.name', [$sid]),
    ]);
}

function admin_class_create(): void
{
    $user = require_role('admin');
    $name = input('name');
    if ($name !== '') {
        q('INSERT INTO classes (school_id, name) VALUES (?,?)', [$user['school_id'], $name]);
        flash('success', t('saved'));
    }
    redirect('admin/classes');
}

function admin_subject_create(): void
{
    $user = require_role('admin');
    $name = input('name');
    if ($name !== '') {
        q('INSERT INTO subjects (school_id, name) VALUES (?,?)', [$user['school_id'], $name]);
        flash('success', t('saved'));
    }
    redirect('admin/classes');
}

function admin_assignment_create(): void
{
    $user = require_role('admin');
    $sid = $user['school_id'];
    $t = input_int('teacher_id');
    $c = input_int('class_id');
    $s = input_int('subject_id');
    $ok = $t && $c && $s
        && q_one("SELECT 1 FROM users WHERE id=? AND school_id=? AND role='teacher'", [$t, $sid])
        && q_one('SELECT 1 FROM classes WHERE id=? AND school_id=?', [$c, $sid])
        && q_one('SELECT 1 FROM subjects WHERE id=? AND school_id=?', [$s, $sid]);
    if (!$ok) {
        flash('error', t('error.invalid'));
    } else {
        q('INSERT IGNORE INTO teacher_assignments (teacher_id, class_id, subject_id) VALUES (?,?,?)', [$t, $c, $s]);
        flash('success', t('saved'));
    }
    redirect('admin/classes');
}

function admin_assignment_delete(int $id): void
{
    $user = require_role('admin');
    q('DELETE ta FROM teacher_assignments ta JOIN classes c ON c.id=ta.class_id WHERE ta.id=? AND c.school_id=?', [$id, $user['school_id']]);
    flash('success', t('saved'));
    redirect('admin/classes');
}

function admin_users(): void
{
    $user = require_role('admin');
    view('admin/users', [
        'title' => t('nav.users'),
        'users' => q_all('SELECT id, role, name, email, phone, is_active, created_at FROM users WHERE school_id=? ORDER BY role, name', [$user['school_id']]),
        'me'    => $user,
    ]);
}

function admin_user_create(): void
{
    $admin = require_role('admin');
    $role = input('role');
    $name = input('name');
    $email = strtolower(input('email'));
    $phone = input('phone');
    $password = (string)($_POST['password'] ?? '');

    if (!in_array($role, ['admin', 'teacher', 'parent'], true) || $name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('error', t('error.invalid'));
        redirect('admin/users');
    }
    if (mb_strlen($password) < 8) {
        flash('error', t('account.password_short'));
        redirect('admin/users');
    }
    if (q_one('SELECT 1 FROM users WHERE email=?', [$email])) {
        flash('error', t('users.email_taken'));
        redirect('admin/users');
    }
    q('INSERT INTO users (school_id, role, name, email, phone, password_hash) VALUES (?,?,?,?,?,?)',
      [$admin['school_id'], $role, $name, $email, $phone ?: null, password_hash($password, PASSWORD_DEFAULT)]);
    flash('success', t('users.created'));
    redirect('admin/users');
}

function admin_user_toggle(int $id): void
{
    $admin = require_role('admin');
    if ($id === (int)$admin['id']) {
        flash('error', t('users.cannot_self'));
        redirect('admin/users');
    }
    q('UPDATE users SET is_active = 1 - is_active WHERE id=? AND school_id=?', [$id, $admin['school_id']]);
    flash('success', t('saved'));
    redirect('admin/users');
}
