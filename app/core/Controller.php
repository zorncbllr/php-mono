<?php

class Controller
{
    static function getMethod(Controller $controller, ReflectionMethod $method, array $param = [])
    {
        function json(array | object $data)
        {
            header("Content-Type: application/json");
            echo json_encode($data);
            exit();
        }

        function view(string $filename, array $data = [])
        {
            header("Content-Type: text/html");

            require(__DIR__ . "\\..\\views\\$filename.view.php");
            exit();
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
