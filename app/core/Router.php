<?php

class Router
{
    private App $app;
    private array $URL;
    private Controller $controller;
    private Controller $errorController;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->URL = explode("/", $this->app->URI_PATH);

        require_once $this->formatPath("_404");
        $this->errorController = new _404();
        $this->findController();
    }

    private function findController(): void
    {
        $route = ucfirst($this->URL[1]);
        $route = $route === 'Index.php' ? 'Home' : $route;
        $controller = $this->formatPath($route);

        if (file_exists($controller)) {
            $this->requireController($controller, $route);
            return;
        }

        $controller = $this->formatPath($route, true);

        if (file_exists($controller)) {
            $this->requireController($controller, $route);
            return;
        }

        Controller::HandleError($this->errorController);
    }

    private function requireController(string $controller, string $route)
    {
        require $controller;

        $controllerClass = $route;
        $this->controller = new $controllerClass();

        array_shift($this->URL);

        include_once __DIR__ . '/utils/response_methods.php';

        $reflection = new ReflectionClass($this->controller);

        $valid = Controller::handleMiddlewares(
            $reflection,
            new Request()
        );

        if ($valid) {
            $this->handleMatchRoute($reflection);
        }
    }

    protected function handleMatchRoute(ReflectionClass $reflection): void
    {
        $route = $this->URL;
        $route[0] = "";

        foreach ($reflection->getMethods() as $method) {
            $attributes = $method->getAttributes('Route');

            foreach ($attributes as $attribute) {
                $attr = $attribute->newInstance();

                if ($_SERVER["REQUEST_METHOD"] === $attr->method) {

                    if (sizeof($attr->path) === sizeof($route)) {
                        $params = [];

                        for ($i = 0; $i < sizeof($attr->path); $i++) {
                            if (str_contains($attr->path[$i], ":")) {
                                $key = str_replace(":", "", $attr->path[$i]);
                                $params[$key] = $route[$i];
                                $attr->path[$i] = $route[$i];
                            }
                        }

                        if ($attr->path === $route) {
                            Controller::getMethod($this->controller, $method, $params);

                            return;
                        }
                    }
                }
            }
        }

        Controller::HandleError($this->errorController);
    }

    private function formatPath(string $className, bool $withFolder = false): string
    {
        $folder = strtolower($className);
        return __DIR__ . "/../controllers/" . ($withFolder ? "$folder/$className" : $className) . ".php";
    }
}
