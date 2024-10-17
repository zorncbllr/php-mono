<?php

use App\Core\Middleware;

class AuthChecker extends Middleware
{
    #[Override]
    static function runnable(Request $request, callable $next)
    {
        echo 'in runnable';
    }
}
