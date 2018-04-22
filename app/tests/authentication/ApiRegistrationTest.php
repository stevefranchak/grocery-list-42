<?php

class ApiRegistrationTest extends TestCase {
    
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

    public function testSuccessfulRegistration()
    {
        $response = TestHelpers::makeRequest($this->client, 'POST', URL::route('register'), array(
            'email' => 'stevefranchak@yahoo.com',
            'password' => 'thisisacoolpassword',
        ));
        $this->assertResponseStatus(201);

        $this->assertObjectHasAttribute('email', $response['parsedJson']);
        $this->assertInternalType('string', $response['parsedJson']->email);
        $this->assertObjectHasAttribute('accountId', $response['parsedJson']);
        $this->assertInternalType('string', $response['parsedJson']->accountId);

        // Stretching sanity check - make sure password and id are never accidentally exposed
        $this->assertObjectNotHasAttribute('password', $response['parsedJson']);
        $this->assertObjectNotHasAttribute('id', $response['parsedJson']);

        $this->assertEquals($response['parsedJson']->email, 'stevefranchak@yahoo.com');
        $this->assertRegExp('/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$/', $response['parsedJson']->accountId);

        // Now make sure we can log in!
        $response = TestHelpers::login($this->client, array( 
            'email' => 'stevefranchak@yahoo.com',
            'password' => 'thisisacoolpassword',
        ));
        $this->assertResponseStatus(200);
        
        $this->assertObjectHasAttribute('token', $response['parsedJson']);
        $this->assertInternalType('string', $response['parsedJson']->token);

        $expectedTokenKeyLength = Config::get('session.token_key_length');
        $this->assertTrue(strlen($response['parsedJson']->token) === $expectedTokenKeyLength);
    }

    public function testDuplicateUserRegistrationAttempt()
    {
        TestHelpers::makeRequest($this->client, 'POST', URL::route('register'), array(
            'email' => 'stevefranchak@gmail.com',
            'password' => 'reach4TEHsky',
        ));
        $this->assertResponseStatus(400);
    }

    public function testSuccessfulRegistrationWithShortestAllowedPassword()
    {
        $response = TestHelpers::makeRequest($this->client, 'POST', URL::route('register'), array(
            'email' => 'stevefranchak@yahoo.com',
            'password' => '4short',
        ));
        $this->assertResponseStatus(201);
    }

    public function testSuccessfulRegistrationWithLongestAllowedPassword()
    {
        $response = TestHelpers::makeRequest($this->client, 'POST', URL::route('register'), array(
            'email' => 'stevefranchak@yahoo.com',
            'password' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', // 64 a's         
        ));
        $this->assertResponseStatus(201);
    }

    public function testInvalidRegistrationInput()
    {
        $response = TestHelpers::makeRequest($this->client, 'POST', URL::route('register'), array());
        $this->assertResponseStatus(400);

        $response = TestHelpers::makeRequest($this->client, 'POST', URL::route('register'), array(
            'email' => '',
            'password' => '',
        ));
        $this->assertResponseStatus(400);

        $response = TestHelpers::makeRequest($this->client, 'POST', URL::route('register'), array(
            'email' => 'stevefranchak@yahoo.com',
        ));
        $this->assertResponseStatus(400);

        $response = TestHelpers::makeRequest($this->client, 'POST', URL::route('register'), array(
            'password' => 'thisisacoolpassword',
        ));
        $this->assertResponseStatus(400);

        $response = TestHelpers::makeRequest($this->client, 'POST', URL::route('register'), array(
            'email' => 'thisisnotavalidemailaddress',
            'password' => 'thisisacoolpassword',
        ));
        $this->assertResponseStatus(400);

        $response = TestHelpers::makeRequest($this->client, 'POST', URL::route('register'), array(
            'email' => 'stevefranchak@yahoo.com',
            'password' => '2shor',
        ));
        $this->assertResponseStatus(400);

        $response = TestHelpers::makeRequest($this->client, 'POST', URL::route('register'), array(
            'email' => 'stevefranchak@yahoo.com',
            'password' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', // 65 a's (one more than the max length of 64)
        ));
        $this->assertResponseStatus(400);
    }
}