<?php

class Home extends Controller
{
	#[Middleware(new Validator)]
	#[Route(method: 'GET')]
	public function index(Request $request)
	{
		return view('Home');
	}
}
