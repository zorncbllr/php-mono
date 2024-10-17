<?php

class Home extends Controller
{
	#[Middleware(new AuthChecker)]
	#[Route(method: 'GET')]
	public function index(Request $request)
	{
		return view('Home');
	}
}
