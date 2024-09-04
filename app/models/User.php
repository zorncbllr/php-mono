<?php

class User extends Model
{
    public $id, $username, $email, $createdAt;

    public function __construct($username = null, $email = null, $createdAt = null, $id = null)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->createdAt = $createdAt;

        self::createTable("
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL,
            createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ");
    }
}
