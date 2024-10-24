<?php


class Home extends Controller
{
	#[Middleware(new Greet)]
	#[Route(method: 'GET')]
	public function index(Request $request)
	{
		return view('Home');
	}
}
