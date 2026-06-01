<?php
require_once __DIR__ . '/includes/auth.php';

auth_logout();
redirect('index.php');
