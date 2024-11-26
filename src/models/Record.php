<?php

class Record extends Model
{
	private $id, $title, $content, $createdAt;
	public function __construct($id = null, $title = null, $content = null, $createdAt = null)
	{
		$this->id = $id;
		$this->title = $title;
		$this->content = $content;
		$this->createdAt = $createdAt;
	}

	public static function initRecord()
	{
		self::createTable('
			id INT PRIMARY KEY AUTO_INCREMENT,
			title VARCHAR(50) NOT NULL,
			content VARCHAR(255) NOT NULL,
			createdAt DATETIME DEFAULT CURRENT_TIMESTAMP
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

	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getContent()
	{
		return $this->content;
	}

	public function setContent($content)
	{
		$this->content = $content;
	}

	public function getCreatedAt()
	{
		return $this->createdAt;
	}

	public function setCreatedAt($createdAt)
	{
		$this->createdAt = $createdAt;
	}
}
