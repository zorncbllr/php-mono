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

    static function create(string $name, string $path)
    {
        $contents = str_replace('<?= $slot ?>', '${this.innerHTML}', file_get_contents($path));

        $lowered = strtolower($name);
        $capitalized = ucfirst($lowered);

        echo "
        <script>
            window.addEventListener('DOMContentLoaded', () => {

                try {
                    class {$capitalized} extends HTMLElement {
                        constructor() {
                            super();

                            this.innerHTML = `{$contents}`;
                        }
                    }

                    customElements.define('x-{$lowered}', {$capitalized});

                } catch (_) {}
            })
        </script>
        ";
    }
}
