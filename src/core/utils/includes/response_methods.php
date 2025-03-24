<?php


function json(mixed $data)
{
    header("Content-Type: application/json");
    echo json_encode($data);
}

function view(string $view, array $data = [])
{
    header("Content-Type: text/html");

    extract($data);

    $path = __DIR__ . "/../../../views/{$view}.view.php";

    if (file_exists($path)) {
        require_once $path;
        Component::loadComponents();

        return;
    }

    $path = str_replace("{$filename}.view.php", "{$filename}.php", $path);

    if (file_exists($path)) {
        require_once $path;
        Component::loadComponents();

        return;
    }

    echo "<script>alert('Error: Could not find specified view.');</script>";
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

    extract($data);

    $path = __DIR__ . "/../../../views/components/{$component}.php";

    if (file_exists($path)) {
        return require_once $path;
    }

    echo "<script>alert('Error: Could not find specified component.');</script>";
}
