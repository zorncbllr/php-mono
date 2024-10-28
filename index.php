<?php

declare(strict_types=1);

session_start();

$config = (require_once __DIR__ . '/src/config/config.php')['cors'];

$origin = $config['origin'];
$methods = implode(', ', $config['allowed_methods']);
$headers = implode(', ', $config['allowed_headers']);

header("Access-Control-Allow-Origin: $origin");
header("Access-Control-Allow-Methods: $methods");
header("Access-Control-Allow-Headers: $headers");

require_once __DIR__ . '/src/core/__init.php';
