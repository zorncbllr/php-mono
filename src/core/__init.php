<?php

$config = require_once __DIR__ . '/../config/cors.config.php';

header("Access-Control-Allow-Origin: " . $config['origin']);
header("Access-Control-Allow-Methods: " . implode(', ', $config['allowed_methods']));
header("Access-Control-Allow-Headers: " . implode(', ', $config['allowed_headers']));

$directories = require __DIR__ . '/../config/autoload.config.php';

spl_autoload_register(
    function ($class)
    use ($directories) {
        foreach ($directories as $dir) {
            $path = __DIR__ . "$dir$class.php";

            if (file_exists($path)) {
                require_once $path;
            }
        }
    }
);

$app = new App();
$database = new Database();
$router = new Router($app);
