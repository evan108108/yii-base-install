<?php
class SiteTest extends WebTestCase
{
	public $fixtures = array(
		'users'=>'User',
	);

	public function testIndex()
	{
		$this->open('');
		$this->assertTextPresent('Congratulations!');
	}

}
