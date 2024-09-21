<?php

class Generate
{
    static function createNewController(string $filename)
    {
        $class = ucfirst($filename);
        $directory = __DIR__ . "\\..\\..\\..\\controllers";

        if (!is_dir($directory)) {
            mkdir($directory);
            file_put_contents(
                $directory . "\\_404.php",
                "<?php\n\nclass _404 extends Controller {\n\tpublic static function error(){\n\n\t\treturn view('404');\n\t}\n}\n"
            );
        }

        file_put_contents(
            $directory . "\\$class.php",
            "<?php\n\nclass $class extends Controller {\n\n\t#[Route(method: 'GET')]\n\tpublic function index(Request \$request){\n\n\t\treturn '$class controller';\n\t}\n}\n"
        );

        exit();
    }

    static function createNewModule(string $filename)
    {
        $class = ucfirst($filename);
        $directory = __DIR__ . "\\..\\..\\..\\models";

        if (!is_dir($directory)) {
            mkdir($directory);
        }

        file_put_contents(
            $directory . "\\$class.php",
            "<?php\n\nclass $class extends Model {\npublic int \$id;\n\tpublic function __construct(int \$id = null){\n\n\t\t\$this->id = \$id;\n\n\t// self::createTable('id INT AUTO_INCREMENT PRIMARY KEY');\n\t}\n}"
        );

        exit();
    }

    static function createNewComponent(string $filename)
    {
        $comp = ucfirst($filename);
        $directory = __DIR__ . "\\..\\..\\..\\views\\components";

        if (!is_dir($directory)) {
            mkdir($directory);
        }

        file_put_contents(
            $directory . "\\$comp.com.php",
            "<div>\n\t<h1>$comp component</h1>\n<h1>"
        );

        exit();
    }

    static function createView(string $filename)
    {
        $view = ucfirst($filename);
        $directory = __DIR__ . "\\..\\..\\..\\views";

        if (!is_dir($directory)) {
            mkdir($directory);
            file_put_contents(
                $directory . "\\404.view.php",
                "<!DOCTYPE html>\n<html lang='en'>\n<head>\n\t<meta charset='UTF-8'>\n\t<meta name='viewport' content='width=device-width, initial-scale=1.0'>\n\t<title>404 | Page not Found</title>\n</head>\n<body>\n\t<h1>404 | Page not Found</h1>\n</body>\n</html>"
            );
        }

        file_put_contents(
            $directory . "\\$view.view.php",
            "<!DOCTYPE html>\n<html lang='en'>\n<head>\n\t<meta charset='UTF-8'>\n\t<meta name='viewport' content='width=device-width, initial-scale=1.0'>\n\t<title>$view</title>\n</head>\n<body>\n\t<h1>$view</h1>\n</body>\n</html>"
        );

        exit();
    }
}
