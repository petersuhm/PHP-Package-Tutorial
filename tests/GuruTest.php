<?php

use Petersuhm\Guru\Guru;

class GuruTest extends PHPUnit_Framework_TestCase {

	public function testIsInitializable()
	{
		$guru = new Guru();

		$this->assertInstanceOf('\Petersuhm\Guru\Guru', $guru);
	}
}