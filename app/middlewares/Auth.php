<?php

use App\Core\Middleware;

class Auth extends Middleware
{
	static function runnable(Request $request)
	{
		echo 'Auth Middleware';
	}
}
