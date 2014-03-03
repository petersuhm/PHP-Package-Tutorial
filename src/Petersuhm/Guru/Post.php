<?php namespace Petersuhm\Guru;

class Post {

	public $title;
	public $date;
	public $slug;
	public $body;

	public function __construct($title = '', $date = '', $slug = '', $body = '')
	{
		$this->title = $title;
		$this->date = $date;
		$this->slug = $slug;
		$this->body = $body;
	}
}