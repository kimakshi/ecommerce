<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function money_inr($amount): string
{
    $n = number_format((float)$amount, 0, '.', ',');
    return '₹' . $n;
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function require_login(): void
{
    if (!current_user()) {
        $next = $_SERVER['REQUEST_URI'] ?? '';
        if ($next === '' || preg_match('~^https?://~i', $next) || str_starts_with($next, '//')) {
            $next = 'account.php';
        }
        redirect('login.php?next=' . urlencode($next));
    }
}

function is_admin(): bool
{
    $u = current_user();
    return $u && ($u['role'] ?? '') === 'admin';
}

function require_admin(): void
{
    if (!is_admin()) {
        redirect('../login.php');
    }
}

function cart_get(): array
{
    return $_SESSION['cart'] ?? [];
}

function cart_set(array $cart): void
{
    $_SESSION['cart'] = $cart;
}

function cart_count(): int
{
    $cart = cart_get();
    $count = 0;
    foreach ($cart as $item) {
        $count += (int)($item['qty'] ?? 0);
    }
    return $count;
}
