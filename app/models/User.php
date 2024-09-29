<?php

class User extends Model
{
	private int $id;
	private string $name, $email, $password;
	private $date;
	public function __construct(
		int $id,
		string $name,
		string $email,
		string $password,
		$date
	) {

		$this->id = $id;
		$this->name = $name;
		$this->email = $email;
		$this->password = $password;
		$this->date = $date;
	}
	public static function initUser()
	{
		self::createTable('
			id INT AUTO_INCREMENT PRIMARY KEY,
			name VARCHAR(20) NOT NULL,
			email VARCHAR(20) NOT NULL,
			password VARCHAR(20) NOT NULL,
			date <ADD YOUR CONFIGURATION>
		');
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

	public function getDate()
	{
		return $this->date;
	}

	public function setDate($date)
	{
		$this->date = $date;
	}
}
