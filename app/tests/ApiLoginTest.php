<?php

class ApiLoginTest extends TestCase {

    public function setUp()
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
    }

    public function tearDown()
    {
        parent::tearDown();
        Redis::flushall();
        DB::table('users')->truncate();
    }

	public function testSuccessfulLogin()
	{
		$this->client->request('POST', URL::route('login'), array(
            'email' => 'stevefranchak@gmail.com',
            'password' => 'test'
        ));

        $this->assertTrue($this->client->getResponse()->isOk());

        $jsonResponse = json_decode($this->client->getResponse()->getContent());
        $this->assertObjectHasAttribute('token', $jsonResponse);
        $this->assertInternalType('string', $jsonResponse->token);
        
        $expectedTokenKeyLength = Config::get('session.token_key_length');
        $this->assertTrue(strlen($jsonResponse->token) === $expectedTokenKeyLength);
	}

}
