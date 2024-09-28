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

    static function createNewModel(string $filename)
    {
        $class = ucfirst($filename);
        $directory = __DIR__ . "\\..\\..\\..\\models";

        if (!is_dir($directory)) {
            mkdir($directory);
        }

        file_put_contents(
            $directory . "\\$class.php",
            "<?php\n\nclass $class extends Model {\nprivate \$id;\n\tpublic function __construct(\$id = null){\n\n\t\t\$this->id = \$id;\n\n\t// self::createTable('id INT AUTO_INCREMENT PRIMARY KEY');\n\t}\n}"
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
            "<div>\n\t<h1>$comp component</h1>\n<div>"
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

    static function fillSchema(string $filename)
    {
        $path = self::getPath($filename);
        $constructorBlocks = self::getconstructorBlocks($filename);
        $attrs = self::getAttributes($filename);

        $initAttrs = "";
        $gettersAndSetters = "\n";

        $constructorBlocks[2] = explode("}", $constructorBlocks[2])[0] . "\n\t}";

        foreach ($attrs as $index => $attr) {
            $copy = str_replace("$", "", $attr);
            $gettersAndSetters .= self::gettersAndSetters($copy);
            $initAttrs .= "\n\t\t\$this->$copy = $attr;" . ($index < (sizeof($attrs) - 1) ? "" : "\n");
        }

        $constructorBlocks[2] = $initAttrs . $constructorBlocks[2];
        $constructorBlocks[1] = self::getConstructParams($filename);

        $content = "";

        foreach ($constructorBlocks as $index => $block) {
            $content .= $block . ($index < sizeof($constructorBlocks) - 1 ? " {" : "");
        }

        $content .= $gettersAndSetters . "\n}";

        file_put_contents($path, $content);
        echo ucfirst($filename) . " Model Schema updated.";

        exit();
    }

    private static function getAttributes(string $filename): array
    {
        $constructorBlocks = self::getconstructorBlocks($filename);

        $attr = trim(explode(";", $constructorBlocks[1])[0]);
        $attr = str_replace("private ", "", $attr);

        return explode(", ", $attr);
    }

    private static function getconstructorBlocks(string $filename): array
    {
        $path = self::getPath($filename);
        $content = file_get_contents($path);

        return explode("{", $content);
    }

    private static function getPath(string $filename)
    {
        $filename = ucfirst($filename) . ".php";
        $directory = __DIR__ . "\\..\\..\\..\\models\\";
        return $directory . $filename;
    }

    private static function getConstructParams(string $filename)
    {
        $constructorBlocks = self::getconstructorBlocks($filename);
        $constructor = explode("(", $constructorBlocks[1]);

        $attrs = self::getAttributes($filename);

        $finalParam = "";

        foreach ($attrs as $index => $attr) {
            $finalParam .= "$attr = null" . ($index != sizeof($attrs) - 1 ? ", " : "");
        }

        $result = $constructor[0] . "($finalParam)";

        return $result;
    }

    private static function gettersAndSetters(string $attribute)
    {
        $getter = "\n\tpublic function get" . ucfirst($attribute) . "() {\n\t\treturn \$this->$attribute;\n\t}\n";
        $setter = "\n\tpublic function set" . ucfirst($attribute) . "(\$$attribute) {\n\t\t\$this->$attribute = \$$attribute;\n\t}\n";

        return $getter . $setter;
    }
}
