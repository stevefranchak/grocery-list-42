<?php

//TODO: Create a separate test DB and somehow segregate redis keys so that
//      executing any tests doesn't blow away actual data. Will need to call
//      Artisan's migrate in setUp once this is done.
//TODO: Add other tests, especially negative testing.
//NOTE: This is functional testing of the route. TODO: create unit tests.
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