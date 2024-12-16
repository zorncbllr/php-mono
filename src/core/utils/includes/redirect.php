<?php

class Redirect
{
    public function internal(string $path, Request $request, string $method)
    {
        new Router(new App($path), $request, $method);
    }
}
