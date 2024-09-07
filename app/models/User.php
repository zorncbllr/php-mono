<?php

class User extends Model
{
    public $id, $username, $password;

    public function __construct($id = null, $username = null, $password = null)
    {

        $this->id = $id;
        $this->username = $username;
        $this->password = $password;

        self::createTable("
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(100) NOT NULL
        ");
    }
}
