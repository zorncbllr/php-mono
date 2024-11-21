<?php

declare(strict_types=1);

session_start();

$config = (require_once __DIR__ . '/src/config/config.php')['cors'];

header("Access-Control-Allow-Origin: " + $config['origin']);
header("Access-Control-Allow-Methods: " + implode(', ', $config['allowed_methods']));
header("Access-Control-Allow-Headers: " + implode(', ', $config['allowed_headers']));

require_once __DIR__ . '/src/core/__init.php';
