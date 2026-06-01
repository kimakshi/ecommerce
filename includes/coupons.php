<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

function coupon_current_code(): string
{
    $c = $_SESSION['coupon_code'] ?? '';
    return is_string($c) ? trim($c) : '';
}

function coupon_clear(): void
{
    unset($_SESSION['coupon_code']);
}

function coupon_set_code(string $code): void
{
    $_SESSION['coupon_code'] = strtoupper(trim($code));
}

function coupon_find_by_code(string $code): ?array
{
    $code = strtoupper(trim($code));
    if ($code === '') {
        return null;
    }

    try {
        $stmt = db()->prepare('SELECT * FROM coupons WHERE code = ? LIMIT 1');
        $stmt->execute([$code]);
        $row = $stmt->fetch();
        return $row ?: null;
    } catch (Throwable $e) {
        return null;
    }
}

function coupon_validate_for_subtotal(?array $coupon, float $subtotal): array
{
    if (!$coupon) {
        return ['ok' => false, 'error' => 'Invalid coupon code.'];
    }

    if (!(int)$coupon['is_active']) {
        return ['ok' => false, 'error' => 'Coupon is not active.'];
    }

    $now = time();

    if (!empty($coupon['starts_at'])) {
        $ts = strtotime((string)$coupon['starts_at']);
        if ($ts !== false && $now < $ts) {
            return ['ok' => false, 'error' => 'Coupon not started yet.'];
        }
    }

    if (!empty($coupon['ends_at'])) {
        $ts = strtotime((string)$coupon['ends_at']);
        if ($ts !== false && $now > $ts) {
            return ['ok' => false, 'error' => 'Coupon expired.'];
        }
    }

    $min = (float)($coupon['min_subtotal'] ?? 0);
    if ($min > 0 && $subtotal < $min) {
        return ['ok' => false, 'error' => 'Minimum order value is ₹' . number_format($min, 0, '.', ',') . '.'];
    }

    $limit = (int)($coupon['usage_limit'] ?? 0);
    $used = (int)($coupon['used_count'] ?? 0);
    if ($limit > 0 && $used >= $limit) {
        return ['ok' => false, 'error' => 'Coupon usage limit reached.'];
    }

    return ['ok' => true];
}

function coupon_calculate_discount(?array $coupon, float $subtotal): float
{
    if (!$coupon) {
        return 0.0;
    }

    $type = (string)($coupon['type'] ?? 'fixed');
    $value = (float)($coupon['value'] ?? 0);

    $discount = 0.0;
    if ($type === 'percent') {
        $discount = ($subtotal * $value) / 100.0;
    } else {
        $discount = $value;
    }

    $max = (float)($coupon['max_discount'] ?? 0);
    if ($max > 0) {
        $discount = min($discount, $max);
    }

    $discount = max(0.0, min($discount, $subtotal));
    return round($discount, 2);
}
