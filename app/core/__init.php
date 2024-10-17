<?php

spl_autoload_register(function ($class) {
    $path = __DIR__ . "/../models/$class.php";

    if (file_exists($path)) {
        return require $path;
    }

    $path = __DIR__ . "/$class.php";

    if (file_exists($path)) {
        return require $path;
    }
});

require_once 'utils/annotations/Route.php';
require_once 'Middleware.php';
require_once 'utils/annotations/Middleware.php';
require_once 'utils/Request.php';

// spl_autoload_register(function ($middleware) {
//     $path = __DIR__ . "/../middlewares/$middleware.php";

//     if (file_exists($path)) {
//         require $path;
//     }
// });

require __DIR__ . '/../middlewares/AuthChecker.php';

$app = new App();
$database = new Database();
$router = new Router($app);
