<?php


class Record extends Model
{
	private $id;
	public string $title, $content, $createdAt;

	public function __construct(string $title, string $content, string $createdAt, $id = null)
	{
		$this->id = $id;
		$this->title = $title;
		$this->content = $content;
		$this->createdAt = $createdAt;
	}

	public static function initRecord()
	{
		self::migrateModel('
			id <ADD YOUR CONFIGURATION>,
			title VARCHAR(255) NOT NULL,
			content VARCHAR(255) NOT NULL,
			createdAt VARCHAR(255) NOT NULL
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
}
