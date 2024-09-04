<?php

class App
{
    public string $URI_PATH;
    public function __construct()
    {
        $this->URI_PATH = isset($_GET["url"]) ? "/" . $_GET["url"] : "/Home";
    }

    static function debug_print(mixed $prop)
    {
        echo "<pre>";
        (is_array($prop) ? print_r($prop) : var_dump($prop));
        echo "</pre>";
    }
}
