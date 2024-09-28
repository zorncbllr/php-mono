<?php

class User extends Model
{
	public $id, $name, $email, $password;
	public function __construct($id = null, $name = null, $email = null, $password = null)
	{
		$this->id = $id;
		$this->name = $name;
		$this->email = $email;
		$this->password = $password;

		self::createTable("
			id INT AUTO_INCREMENT,
			name VARCHAR(20) NOT NULL,
			email VARCHAR(50) NOT NULL UNIQUE,
			password VARCHAR(100) NOT NULL
		");
	}
}
