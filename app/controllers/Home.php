<?php

class Home extends Controller
{
	#[Middleware(new Auth)]
	#[Route(method: 'GET')]
	public function index(Request $request)
	{
		return view('Home');
	}
}
