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

        $componentContent = htmlspecialchars_decode($componentContent);

        $componentContent = self::parseForEach($componentContent, $variables);

        $componentContent = self::parseVariables($componentContent, $variables);

        echo $componentContent;
    }

    private static function parseVariables(string $content, array $variables): string
    {
        $pattern = '/\{\{\s*\$[a-zA-Z_][a-zA-Z0-9_]*\s*\}\}/';
        $matches = null;

        preg_match_all($pattern, $content, $matches);

        if (sizeof($matches[0]) == 0) {
            return $content;
        }

        foreach ($matches[0] as $match) {
            $pattern = '/[a-zA-Z_][a-zA-Z0-9_]*/';
            $varname = null;
            preg_match($pattern, $match, $varname);

            $content = str_replace(
                $match,
                htmlspecialchars($variables[$varname[0]]),
                $content
            );
        }

        return $content;
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
                $pattern = '/@foreach\s*\(.*?\)/';
                $foreach = null;
                preg_match($pattern, $match, $foreach);

                $foreachContent = str_replace(
                    "@endforeach",
                    "",
                    str_replace($foreach[0], "", $match)
                );

                $result = "";

                $pattern = '/@foreach\s*\(\s*\$.*as\s*/';
                $argArray = null;
                preg_match($pattern, $foreach[0], $argArray);

                $argVar = str_replace(")", "", str_replace($argArray[0], "", $foreach[0]));

                preg_match('/\$\w*/', $argArray[0], $argArray);

                $argArray = $argArray[0];

                eval("foreach($argArray as $argVar) {
                    \$result .= self::parseVariables(\$foreachContent, [
                        '" . str_replace('$', '', $argVar) . "' => $argVar
                    ]);
                }");

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
