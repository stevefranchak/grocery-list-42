<?php

class ApiLogoutTest extends TestCase {
    
    private $token = NULL;

    public function setUp()
    {
        parent::setUp();

        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);

        $this->token = TestHelpers::login($this->client, array( 
            'email' => 'stevefranchak@gmail.com',
            'password' => 'reach4TEHsky'
        ))['token'];  
    }

    public function tearDown()
    {
        parent::tearDown();

        Redis::flushall();
        
        DB::table('users')->truncate();
    }

    public function testDoubleLogoutAttempts()
    {
        TestHelpers::makeAuthenticatedRequest($this->client, 'DELETE', URL::route('logout'), $this->token);
        $this->assertResponseStatus(204);

        TestHelpers::makeAuthenticatedRequest($this->client, 'DELETE', URL::route('logout'), $this->token);
        $this->assertResponseStatus(401);
    }

    public function testInvalidTokens()
    {
        TestHelpers::makeAuthenticatedRequest($this->client, 'DELETE', URL::route('logout'), NULL);
        $this->assertResponseStatus(401);

        TestHelpers::makeAuthenticatedRequest($this->client, 'DELETE', URL::route('logout'), 'this is a test!!@#$%^&*@()');
        $this->assertResponseStatus(401);

        TestHelpers::makeAuthenticatedRequest($this->client, 'DELETE', URL::route('logout'), 'a' . $this->token);
        $this->assertResponseStatus(401);

        // Want to make sure that after a bunch of failures, logout will work for a valid token
        TestHelpers::makeAuthenticatedRequest($this->client, 'DELETE', URL::route('logout'), $this->token);
        $this->assertResponseStatus(204);
    }

}
