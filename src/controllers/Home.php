<?php

namespace Src\Controllers;

use Src\Core\Controller;
use Src\Core\Utils\Annotations\Get;
use Src\Core\Utils\Annotations\Middleware;
use Src\Core\Utils\Request;
use Src\Middlewares\Auth;

class Home extends Controller
{
	#[Get()]
	public function index(Request $request)
	{
		return view("welcome");
	}
}
