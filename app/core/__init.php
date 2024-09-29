<?php

spl_autoload_register(function ($model) {
    $modelPath = __DIR__ . "\\..\\models\\$model.php";

    if (file_exists($modelPath)) {
        require $modelPath;
    }
});

require_once 'App.php';
require_once 'Database.php';
require_once 'Model.php';
require_once 'utils/annotations/Route.php';
require_once 'utils/Request.php';
require_once 'Controller.php';
require_once 'Router.php';

$app = new App();
$router = new Router($app);
