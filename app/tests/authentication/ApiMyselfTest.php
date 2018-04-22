<?php

class ApiMyselfTest extends TestCase {
    
    private $token;

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

    public function testSuccessfulMyself()
    {
        $response = TestHelpers::makeAuthenticatedRequest($this->client, 'GET', URL::route('ping'), $this->token);
        $this->assertResponseStatus(200);

        $this->assertObjectHasAttribute('email', $response['parsedJson']);
        $this->assertInternalType('string', $response['parsedJson']->email);
        $this->assertObjectHasAttribute('accountId', $response['parsedJson']);
        $this->assertInternalType('string', $response['parsedJson']->accountId);

        $this->assertEquals($response['parsedJson']->email, 'stevefranchak@gmail.com');
        $this->assertEquals($response['parsedJson']->accountId, '5d67531e-a672-4c08-a9a4-870b6df6ee32');

        // Stretching sanity check - make sure password and id are never accidentally exposed
        $this->assertObjectNotHasAttribute('password', $response['parsedJson']);
        $this->assertObjectNotHasAttribute('id', $response['parsedJson']);
    }

    public function testMyselfWithInvalidToken()
    {
        Redis::flushall(); // not allowing risk of collision
        
        TestHelpers::makeAuthenticatedRequest($this->client, 'GET', URL::route('ping'), Token::generateKey(Config::get('session.token_key_length')));
        $this->assertResponseStatus(401);
    }
}