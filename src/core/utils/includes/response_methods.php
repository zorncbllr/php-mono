<?php

function json(mixed $data)
{
    header("Content-Type: application/json");
    echo json_encode($data);
}

function view(string $filename, array $data = [])
{
    header("Content-Type: text/html");

    if (!empty($data)) {
        extract($data, EXTR_OVERWRITE);
    }

    $path = __DIR__ . "/../../views/$filename";
    require file_exists("$path.view.php") ? "$path.view.php" : "$path.php";
}

function redirect($location = null)
{
    if ($location) {
        header("Location: $location");
        exit();
    } else {
        include_once('redirect.php');
        return new Redirect();
    }
}

function component(string $component, array $data = [])
{
    if (!empty($data)) {
        extract($data, EXTR_OVERWRITE);
    }

    $path = __DIR__ . "/../../views/components/$component";
    include_once file_exists("$path.com.php") ? "$path.com.php" : "$path.php";
}
