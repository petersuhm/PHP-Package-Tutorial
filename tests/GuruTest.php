<?php

use Petersuhm\Guru\Guru;
use Petersuhm\Guru\Post;
use Mockery as m;

class GuruTest extends PHPUnit_Framework_TestCase {

	public function tearDown()
	{
		m::close();
	}

	public function setUp()
	{
		$this->parser = m::mock('\Kurenai\DocumentParser');
		$this->postResolver = function () { return new PostStub(); };
	}

	public function testIsInitializable()
	{
		$guru = new Guru();

		$this->assertInstanceOf('\Petersuhm\Guru\Guru', $guru);
	}

	public function testConfig()
	{
		$settings = array('key' => 'value');
		$guru = new Guru();

		$guru->config($settings);

		$this->assertEquals($guru->settings, $settings);
	}

	public function testPosts()
	{
		$directory = __DIR__ . '/fixtures';
		$files = array(
			$directory . '/first-post.md',
			$directory . '/second-post.md'
		);
		$posts = array(new PostStub, new PostStub);

		for ($i = 0; $i < 2; $i++)
		{
			$posts[$i]->title = 'A title';
			$posts[$i]->date = '2014-12-24';
			$posts[$i]->slug = basename($files[$i], '.md');
			$posts[$i]->body = '<h1>Some content</h1>';

			$document = m::mock('\Kurenai\Document');
			$document->shouldReceive('get')->with('title')->andReturn('A title');
			$document->shouldReceive('get')->with('date')->andReturn('2014-12-24');
			$document->shouldReceive('getHtmlContent')->andReturn('<h1>Some content</h1>');

			$source = file_get_contents($files[$i]);
			$this->parser->shouldReceive('parse')->with($source)->andReturn($document);
		}

		$guru = new Guru($this->parser, $this->postResolver);
		$guru->config(array(
			'content_dir' => $directory,
			'content_ext' => '.md'
		));

		$this->assertEquals($guru->posts(), array($posts[0], $posts[1]));
	}

	public function testPost()
	{
		$directory = __DIR__ . '/fixtures';
		$file = $directory . '/second-post.md';

		$post = new PostStub();
		$post->title = 'A title';
		$post->date = '2014-12-24';
		$post->slug = 'second-post';
		$post->body = '<h1>Some content</h1>';

		$document = m::mock('\Kurenai\Document');
		$document->shouldReceive('get')->with('title')->andReturn('A title');
		$document->shouldReceive('get')->with('date')->andReturn('2014-12-24');
		$document->shouldReceive('getHtmlContent')->andReturn('<h1>Some content</h1>');

		$source = file_get_contents($file);
		$this->parser->shouldReceive('parse')->with($source)->andReturn($document);

		$guru = new Guru($this->parser, $this->postResolver);
		$guru->config(array(
			'content_dir' => $directory,
			'content_ext' => '.md'
		));

		$this->assertEquals($guru->post('second-post'), $post);
	}
}

class PostStub {}