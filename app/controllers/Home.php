<?php

class Home extends Controller
{

	#[Route(method: 'GET')]
	public function index(Request $request)
	{
		return view("Home");
	}
}
