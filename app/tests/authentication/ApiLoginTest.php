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
        $response = TestHelpers::login($this->client, array( 
            'email' => 'stevefranchak@gmail.com',
            'password' => 'test'
        ));
        $this->assertResponseStatus(200);
        
        $this->assertObjectHasAttribute('token', $response['parsedJson']);
        $this->assertInternalType('string', $response['parsedJson']->token);

        $expectedTokenKeyLength = Config::get('session.token_key_length');
        $this->assertTrue(strlen($response['parsedJson']->token) === $expectedTokenKeyLength);
    }

}
