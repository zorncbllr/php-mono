<?php

use App\Core\Middleware;

class Docs extends Middleware
{
	static function runnable(Request $request, callable $next)
	{
		echo '<a
		style="text-decoration: none; color: gray; border: 1px solid gray; padding: 0.4rem 1rem; position: absolute; top: .5rem; left: .5rem"
		href="https://github.com/zorncbllr/Mono/blob/main/README.md">View Documentation</a>';

		return $next();
	}
}
