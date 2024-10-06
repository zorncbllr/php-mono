<?php

class User extends Model
 {
	public string $name, $email;
	private $id, $created_at;
	private string $password;

	public function __construct(
		string $name, 
		string $email, 
		string $password, 
		$id = null, 
		$created_at = null, 
	) {

		$this->name = $name;
		$this->email = $email;
		$this->id = $id;
		$this->created_at = $created_at;
		$this->password = $password;

	}
	public static function initUser() {
		self::createTable('
			name VARCHAR(20) NOT NULL,
			email VARCHAR(20) NOT NULL,
			id <ADD YOUR CONFIGURATION>,
			created_at <ADD YOUR CONFIGURATION>,
			password VARCHAR(20) NOT NULL
		');
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getCreated_at() {
		return $this->created_at;
	}

	public function setCreated_at($created_at) {
		$this->created_at = $created_at;
	}

	public function getPassword() {
		return $this->password;
	}

	public function setPassword($password) {
		$this->password = $password;
	}

}