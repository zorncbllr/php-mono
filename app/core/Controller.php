<?php

class Controller
{
    static function getMethod(Controller $controller, ReflectionMethod $method, array $param = [])
    {
        function json(array | object $data)
        {
            header("Content-Type: application/json");
            echo json_encode($data);
        }

        function view(string $filename, array $data = [])
        {
            header("Content-Type: text/html");

            $path = __DIR__ . "\\..\\views\\$filename";
            require file_exists("$path.view.php") ? "$path.view.php" : "$path.php";
        }

        function redirect(string $location)
        {
            header("Location: $location");
            exit();
        }

        function component(string $component, array $data = [])
        {
            $path = __DIR__ . "\\..\\views\\components\\$component";
            include_once file_exists("$path.com.php") ? "$path.com.php" : "$path.php";
        }

        $request = new Request($param);

        $response = call_user_func_array([
            $controller,
            $method->getName()
        ], [
            ("request" | "req" ? $request : null),
        ]);

        if (is_string($response)) {
            echo $response;
        } elseif (is_array($response) || is_object($response)) {
            json($response);
        }
    }

    static function HandleError(Controller $controller)
    {
        $method = new ReflectionMethod("_404::error");

        self::getMethod($controller, $method);
    }
}
