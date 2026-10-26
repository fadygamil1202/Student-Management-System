<?php
declare(strict_types=1);

function current_user(): ?array
{
    static $user = false;
    if ($user === false) {
        $id = $_SESSION['user_id'] ?? null;
        $user = $id ? q_one('SELECT * FROM users WHERE id = ? AND is_active = 1', [$id]) : null;
    }
    return $user;
}

function attempt_login(string $email, string $password): bool
{
    $user = q_one('SELECT * FROM users WHERE email = ? AND is_active = 1', [strtolower($email)]);
    if (!$user || !password_verify($password, $user['password_hash'])) {
        return false;
    }
    if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
        q('UPDATE users SET password_hash = ? WHERE id = ?', [password_hash($password, PASSWORD_DEFAULT), $user['id']]);
    }
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int)$user['id'];
    return true;
}

function logout(): void
{
    $lang = $_SESSION['lang'] ?? null;
    $_SESSION = [];
    session_regenerate_id(true);
    if ($lang) $_SESSION['lang'] = $lang;
}

function require_login(): array
{
    $user = current_user();
    if (!$user) {
        redirect('login');
    }
    return $user;
}

function require_role(string ...$roles): array
{
    $user = require_login();
    if (!in_array($user['role'], $roles, true)) {
        abort(403);
    }
    return $user;
}
