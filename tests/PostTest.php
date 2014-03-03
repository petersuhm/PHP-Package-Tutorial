<?php

use Petersuhm\Guru\Post;

class PostTest extends PHPUnit_Framework_TestCase {

	public function testIsInitializable()
	{
		$post = new Post();

		$this->assertInstanceOf('\Petersuhm\Guru\Post', $post);
	}

	public function testInstantiatesPost()
	{
		$title = 'First post';
		$date = '2014-12-24';
		$slug = 'first-post';
		$body = '<h1>First post</h1><p>This is my first post.</p>';

		$post = new Post($title, $date, $slug, $body);

		$this->assertEquals($post->title, $title);
		$this->assertEquals($post->date, $date);
		$this->assertEquals($post->slug, $slug);
		$this->assertEquals($post->body, $body);
	}
}