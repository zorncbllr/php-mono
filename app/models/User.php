<?php

class User extends Model
{
	public $id;
	public function __construct($id = null)
	{

		$this->id = $id;

		// self::createTable('id INT AUTO_INCREMENT PRIMARY KEY');
	}
}
