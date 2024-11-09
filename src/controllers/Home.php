<?php


class Home extends Controller
{
	#[Middleware(new Docs)]
	#[Route(method: 'GET')]
	public function index(Request $request)
	{
		return view('Home', ['msg' => 'HELLO WORLD!']);
	}
}
