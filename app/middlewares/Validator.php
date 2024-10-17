<?php

use App\Core\Middleware;

class Validator extends Middleware
{
    static function runnable(Request $request)
    {
        echo 'validated...';
    }
}
