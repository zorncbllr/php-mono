<?php

class User extends Model
{
	private $id, $name, $email, $password;
	public function __construct($id = null, $name = null, $email = null, $password = null)
	{
		$this->id = $id;
		$this->name = $name;
		$this->email = $email;
		$this->password = $password;

		self::createTable("
			id INT AUTO_INCREMENT PRIMARY KEY,
			name VARCHAR(20) NOT NULL,
			email VARCHAR(40) NOT NULL,
			password VARCHAR(100) NOT NULL
		");
	}

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function setPassword($password)
	{
		$this->password = $password;
	}
}
