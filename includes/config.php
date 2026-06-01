<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('APP_NAME', 'Chaatku');

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'kimakshi');
define('DB_USER', 'root');
define('DB_PASS', '');

define('BASE_URL', '');

define('UPLOAD_DIR', __DIR__ . '/../uploads');
define('PRODUCT_UPLOAD_DIR', UPLOAD_DIR . '/products');
