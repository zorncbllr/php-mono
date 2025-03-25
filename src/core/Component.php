<?php

class Component
{
    static function loadComponents()
    {
        $path = __DIR__ . "/../views/components";
        $iterator = new DirectoryIterator($path);


        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $name = $file->getBasename(".php");

                self::create($name, "$path/$name.php");
            }
        }
    }

    private static function create(string $name, string $path)
    {
        $contents = str_replace('{{ $slot }}', '${this.innerHTML}', file_get_contents($path));

        $match = null;
        $js = null;

        preg_match_all('/@props\(.*\)/', $contents, $match);

        eval("\$js = self::get" . ucfirst(str_replace("@", "", $match[0][0])) . ";");

        $contents = str_replace($match[0][0], "", $contents);

        $lowered = strtolower($name);
        $capitalized = self::dashToCamel(ucfirst($lowered));

        include(__DIR__ . '/utils/includes/template.php');
    }

    private static function getProps(array $props): string
    {
        $res = "";

        foreach ($props as $prop) {
            $res .= "const {$prop} = this.getAttribute('{$prop}');\n";
        }

        return $res;
    }

    private static function dashToCamel($string)
    {
        $words = explode('-', $string);
        $res = "";

        for ($i = 0; $i < sizeof($words); $i++) {
            $res .= ucfirst($words[$i]);
        }

        return $res;
    }
}
