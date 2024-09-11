<?php

class Users extends Controller {

	#[Route(method: 'GET')]
	public function index(Request $request){

		return 'Users controller';
	}
}
