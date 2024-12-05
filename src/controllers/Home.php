<?php


class Home extends Controller
{
	#[Get()]
	#[Middleware(new Docs)]
	public function index(Request $request)
	{
		return view('Home', ['msg' => 'HELLO WORLD!']);
	}
}
