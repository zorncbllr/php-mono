<?php

use App\Core\Middleware;

class Greet extends Middleware
{
	static function runnable(Request $request, callable $next)
	{
		echo 'WELCOME TO MONO!';

		return $next();
	}
}
