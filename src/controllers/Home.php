<?php

class Home extends Controller
{
	#[Get()]
	public function index(Request $request)
	{
		return view("welcome");
	}
}
