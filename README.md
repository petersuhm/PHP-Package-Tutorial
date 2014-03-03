PHP Package Development Like a Boss
===================================

* [Building the foundation](#building-the-foundation)
* [The posts](#the-posts)

## Building the foundation

In this section, we will setup the basic file skeleton that we need for our project. Our package is made up by the files located in the `guru` directory. Files outside this directory (such as `index.php`) are only used for development, and will not be published together with the package.

Go ahead and create the following files and directories:

```
|-- packages/
|   `-- guru/
|       |-- src/
|       |   `-- Petersuhm/
|       |       `-- Guru/
|       |           `-- Guru.php
|       |-- tests/
|       |   `-- GuruTest.php
|       |-- .gitignore
|       |-- composer.json
|       `-- phpunit.xml
`-- public/
    `-- index.php

```

The first file we will work on is our `composer.json` file. This file holds important information about our package and its (upcoming) dependencies. It should look like this:

```json
{
	"name": "petersuhm/guru",
	"description": "Flat file CMS package for PHP.",
	"authors": [
		{
			"name": "Peter Suhm",
			"email": "peter@suhm.dk"
		}
	],
	"require": {
		"php": ">=5.3.0"
	},
	"autoload": {
		"psr-0": {
			"Petersuhm\\Guru": "src/"
		}
	},
	"minimum-stability": "dev"
}
```

Now run `composer update`. Composer will create a vendor directory and create the necessary files for autoloading.

If you use Git for version control, you should make a `.gitignore` file and fill in the following:

```
/vendor
composer.phar
composer.lock
```

Next file is `Guru.php`, which contains our `Guru` class. This class will be the backbone of our package and be responsible for turning our markdown files into posts. For now, the file should look like this:

```php
# packages/guru/src/Petersuhm/Guru/Guru.php
<?php namespace Petersuhm\Guru;

class Guru {}
```

Since we will be usen a test-driven approach, obviously we need a test for every class we make. So go ahead and fill in the first test for our `Guru` class:

```php
# packages/guru/tests/GuruTest.php
<?php

use Petersuhm\Guru\Guru;

class GuruTest extends PHPUnit_Framework_TestCase {

	public function testIsInitializable()
	{
		$guru = new Guru();

		$this->assertInstanceOf('\Petersuhm\Guru\Guru', $guru);
	}
}
```

If you try and run `phpunit tests/` from the `guru` directory, you will see that I doesn't work. This is because we need to tell PHPUnit how to autoload our classes. We can do this in a `phpunit.xml` file. At the same time, we might as well add some extra configuration to enable colors and to tell PHPUnit where our testsuite is, so we only need to run `phpunit` in order to run it. Here is the content of our `phpunit.xml` file:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit colors="true"
         bootstrap="./vendor/autoload.php"
>
    <testsuites>
        <testsuite name="Flat Test Suite">
            <directory suffix=".php">./tests/</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

If you run `phpunit` from the guru directory, you should see a green/passing test. This means that our foundation is working. So far, so good.

## The posts

Our posts will be in the markdown format, including some meta data. We want them to look similar to this:

```
title: Hello World!
date: 2014-12-24
-------
# Hello World!

This is my **first** post.
```

_Hint: This is because we will be using Dayle Rees' [Kurenai](https://github.com/daylerees/kurenai) package later on._

Okay, so we need to represent this in a class. We will call it `Post`. Let's do it the TDD way and create a test:

```php
# packages/guru/tests/PostTest.php
<?php

use Petersuhm\Guru\Post;

class PostTest extends PHPUnit_Framework_TestCase {

	public function testIsInitializable()
	{
		$post = new Post();

		$this->assertInstanceOf('\Petersuhm\Guru\Post', $post);
	}
}
```

This will, of course, fail, since we don't have a `Post` class. I will not go through _all_ my TDD iterations here, so just go ahead and make the file, and see that you get a green test:

```php
# packages/guru/src/Petersuhm/Guru/Post.php
<?php namespace Petersuhm\Guru;

class Post {}
```

Now we need to add some fields to our posts. In addition to the `title`, `date` and `body`, we also need a `slug`, which will be the unique identifier of a post. Again, we start with the test:

```php
# packages/guru/tests/PostTest.php
...
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
```

And to get a nice green passing test, add the fields to the `Post` class:

```php
# packages/guru/src/Petersuhm/Guru/Post.php
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
```

Now we have a neat little class to represent our posts. It will come in handy later.
