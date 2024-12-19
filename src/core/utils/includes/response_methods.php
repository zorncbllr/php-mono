<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

function json(mixed $data)
{
    header("Content-Type: application/json");
    echo json_encode($data);
}

function view(string $filename, array $data = [])
{
    header("Content-Type: text/html");

    $loader = new FilesystemLoader(__DIR__ . "/../../../views");
    $twig = new Environment($loader);

    echo $twig->render("{$filename}.twig", [...$data]);
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
    header("Content-Type: text/html");

    $loader = new FilesystemLoader(__DIR__ . "/../../../views/components");
    $twig = new Environment($loader);

    echo $twig->render("{$component}.twig", [...$data]);
}
