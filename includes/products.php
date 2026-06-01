<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

function product_find_by_slug(string $slug): ?array
{
    $stmt = db()->prepare('SELECT p.*, c.name AS category_name, c.slug AS category_slug
      FROM products p
      LEFT JOIN categories c ON c.id = p.category_id
      WHERE p.slug = ?
      LIMIT 1');
    $stmt->execute([$slug]);
    $p = $stmt->fetch();
    return $p ?: null;
}

function categories_all(): array
{
    return db()->query('SELECT * FROM categories ORDER BY name ASC')->fetchAll();
}

function products_search(array $params): array
{
    $where = [];
    $bind = [];

    if (!empty($params['category'])) {
        $where[] = 'c.slug = ?';
        $bind[] = $params['category'];
    }

    if (($params['availability'] ?? '') === 'in_stock') {
        $where[] = 'p.in_stock = 1';
    }

    if (($params['availability'] ?? '') === 'on_sale') {
        $where[] = 'p.is_on_sale = 1';
    }

    if (!empty($params['max_price'])) {
        $where[] = 'p.price_sale <= ?';
        $bind[] = (float)$params['max_price'];
    }

    $sortSql = 'p.created_at DESC';
    switch ($params['sort'] ?? 'date_desc') {
        case 'price_asc':
            $sortSql = 'p.price_sale ASC';
            break;
        case 'price_desc':
            $sortSql = 'p.price_sale DESC';
            break;
        case 'name_asc':
            $sortSql = 'p.name ASC';
            break;
        case 'date_asc':
            $sortSql = 'p.created_at ASC';
            break;
        default:
            $sortSql = 'p.created_at DESC';
    }

    $sql = 'SELECT p.*, c.name AS category_name, c.slug AS category_slug
      FROM products p
      LEFT JOIN categories c ON c.id = p.category_id';

    if ($where) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }

    $sql .= ' ORDER BY ' . $sortSql;

    $stmt = db()->prepare($sql);
    $stmt->execute($bind);
    return $stmt->fetchAll();
}
