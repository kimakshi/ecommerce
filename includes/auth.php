<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

function auth_login(string $email, string $password): bool
{
    $stmt = db()->prepare('SELECT id, name, email, phone, password_hash, role FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $u = $stmt->fetch();

    if (!$u) {
        return false;
    }

    if (!password_verify($password, $u['password_hash'])) {
        return false;
    }

    unset($u['password_hash']);
    $_SESSION['user'] = $u;
    return true;
}

function auth_register(string $name, string $email, string $phone, string $password): array
{
    $name = trim($name);
    $email = trim(strtolower($email));
    $phone = trim($phone);

    if ($name === '' || $email === '' || $password === '') {
        return ['ok' => false, 'error' => 'Please fill all required fields.'];
    }

    $stmt = db()->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return ['ok' => false, 'error' => 'Email already exists.'];
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $ins = db()->prepare('INSERT INTO users (name, email, phone, password_hash, role) VALUES (?,?,?,?,\'customer\')');
    $ins->execute([$name, $email, $phone ?: null, $hash]);

    $_SESSION['user'] = [
        'id' => (int)db()->lastInsertId(),
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'role' => 'customer',
    ];

    return ['ok' => true];
}

function auth_logout(): void
{
    unset($_SESSION['user']);
}
