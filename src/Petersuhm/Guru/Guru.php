<?php namespace Petersuhm\Guru;

use Kurenai\DocumentParser;

class Guru {

	public $settings = array();

	public function __construct(DocumentParser $parser = null, $postResolver = null)
	{
		if ($parser === null)
			$this->parser = new DocumentParser;
		else
			$this->parser = $parser;

		if ($postResolver === null)
			$this->postResolver = function() { return new Post; };
		else
			$this->postResolver = $postResolver;
	}

	public function config($settings)
	{
		$this->settings = array_merge($this->settings, $settings);
	}

	public function posts()
	{
		$posts = array();

		$pattern = $this->settings['content_dir'] . '/*' . $this->settings['content_ext'];
		$files = glob($pattern);

		foreach ($files as $file)
		{
			$slug = basename($file, '.md');
			$source = file_get_contents($file);
			$document = $this->parser->parse($source);

			$post = call_user_func($this->postResolver);
			$post->title = $document->get('title');
			$post->date = $document->get('date');
			$post->slug = $slug;
			$post->body = $document->getHtmlContent();

			array_push($posts, $post);
		}

		return $posts;
	}

	public function post($slug)
	{
		$file = $this->settings['content_dir'] . '/' . $slug . $this->settings['content_ext'];
		$source = file_get_contents($file);
		$document = $this->parser->parse($source);

		$post = call_user_func($this->postResolver);
		$post->title = $document->get('title');
		$post->date = $document->get('date');
		$post->slug = $slug;
		$post->body = $document->getHtmlContent();

		return $post;
	}
}