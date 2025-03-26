<?php

namespace Src\Core;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

use function Src\Core\Utils\Helpers\getdir;

class Component
{
    static function loadComponents(string $component, array $variables)
    {
        $path = getdir(__DIR__) . '/../views';

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path)
        );

        $component = str_replace(".", "/", $component);
        $componentPath = getdir(__DIR__) . "/../views/$component.blade.php";

        $componentContent = htmlspecialchars(
            file_get_contents($componentPath)
        );

        foreach ($iterator as $file) {
            $name = $file->getBaseName('.blade.php');

            if (
                $file->isFile()
                && $file->getPathname() !== $componentPath
                && self::hasComponent($name, $componentContent)
            ) {
                $replacement = htmlspecialchars(
                    file_get_contents($file->getPathname())
                );

                $componentContent = self::replaceContents($name, $replacement, $componentContent);
            }
        }

        $componentContent = self::parseForEach($componentContent, $variables);


        $componentContent = str_replace(
            "}}",
            "",
            str_replace("{{", "", $componentContent)
        );

        echo htmlspecialchars_decode($componentContent);
    }

    private static function replaceContents(string $component, string $replacement, string $content)
    {
        $match = null;
        $pattern = "/&lt;$component *\/&gt;/";

        preg_match_all($pattern, $content, $match);

        return str_replace($match[0][0], $replacement, $content);
    }

    private static function parseForEach(string $content, $variables)
    {
        extract($variables);
        $matches = null;
        $pattern = "/@foreach\s*\(.*?\)\s*[\s\S]*?\s*@endforeach/";

        preg_match_all($pattern, $content, $matches);

        if ($matches[0]) {
            foreach ($matches[0] as $match) {
                $pattern = "/@foreach\s*\(.*?\)/";
                $foreach = null;
                preg_match($pattern, $match, $foreach);

                $foreachContent = str_replace(
                    "@endforeach",
                    "",
                    str_replace($foreach[0], "", $match)
                );

                $result = "";

                $foreachBlock = str_replace("@", "", $foreach[0]);

                eval("
                    $foreachBlock {
                        \$result .= \"$foreachContent\";
                    }
                ");

                $content = str_replace($match, $result, $content);
            }
        }

        return $content;
    }

    private static function hasComponent(string $search, string $content)
    {
        $match = null;
        $pattern = "/&lt;$search *\/&gt;/";

        preg_match_all($pattern, $content, $match);

        return sizeof($match[0]) !== 0;
    }

    private static function compile() {}

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
