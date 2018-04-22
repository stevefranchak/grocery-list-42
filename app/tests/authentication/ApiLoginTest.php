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
        
        DbTableHelpers::emptyTable('users');
    }

    public function testSuccessfulLogin()
    {
        $response = TestHelpers::login($this->client, array( 
            'email' => 'stevefranchak@gmail.com',
            'password' => 'reach4TEHsky'
        ));
        $this->assertResponseStatus(200);
        
        $this->assertObjectHasAttribute('token', $response['parsedJson']);
        $this->assertInternalType('string', $response['parsedJson']->token);

        $expectedTokenKeyLength = Config::get('session.token_key_length');
        $this->assertTrue(strlen($response['parsedJson']->token) === $expectedTokenKeyLength);
    }

    public function testLoginWithIncorrectEmail()
    {
        TestHelpers::login($this->client, array( 
            'email' => 'stevefranchak@yahoo.com',
            'password' => 'reach4TEHsky'
        ));
        $this->assertResponseStatus(401);

        TestHelpers::login($this->client, array( 
            'email' => 'steve@gmail.com',
            'password' => 'reach4TEHsky'
        ));
        $this->assertResponseStatus(401);

        TestHelpers::login($this->client, array( 
            'email' => 'stevefranchak@gmail.net',
            'password' => 'reach4TEHsky'
        ));
        $this->assertResponseStatus(401);
    }

    public function testLoginWithIncorrectPassword()
    {
        TestHelpers::login($this->client, array( 
            'email' => 'stevefranchak@gmail.com',
            'password' => 'reach4TEHsk'
        ));
        $this->assertResponseStatus(401);

        TestHelpers::login($this->client, array( 
            'email' => 'stevefranchak@gmail.com',
            'password' => 'reach4TEHsky2'
        ));
        $this->assertResponseStatus(401);

        TestHelpers::login($this->client, array( 
            'email' => 'stevefranchak@gmail.com',
            'password' => 'test6'
        ));
        $this->assertResponseStatus(401);

        TestHelpers::login($this->client, array( 
            'email' => 'stevefranchak@gmail.com',
            'password' => 'reach4tehsky'
        ));
        $this->assertResponseStatus(401);
    }

    public function testLoginWithIncorrectEmailAndPassword()
    {
        TestHelpers::login($this->client, array( 
            'email' => 'stevefranchak@yahoo.com',
            'password' => 'reach4TEHskyy'
        ));
        $this->assertResponseStatus(401);
    }

    public function testInvalidLoginInput()
    {
        TestHelpers::login($this->client, array());
        $this->assertResponseStatus(400);

        TestHelpers::login($this->client, array( 
            'email' => 'stevefranchak@gmail.com',
        ));
        $this->assertResponseStatus(400);

        TestHelpers::login($this->client, array( 
            'password' => 'reach4TEHsky'
        ));
        $this->assertResponseStatus(400);

        TestHelpers::login($this->client, array( 
            'email' => 'stevefranchak',
            'password' => 'reach4TEHsky'
        ));
        $this->assertResponseStatus(400);

        TestHelpers::login($this->client, array( 
            'email' => '@gmail.com',
            'password' => 'reach4TEHsky'
        ));
        $this->assertResponseStatus(400);

        TestHelpers::login($this->client, array( 
            'email' => '',
            'password' => ''
        ));
        $this->assertResponseStatus(400);

        TestHelpers::login($this->client, array( 
            'email' => 'stevefranchak@gmail.com',
            'password' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa' // 65 a's (one more than the max length of 64)
        ));
        $this->assertResponseStatus(400);
    }

    public function testLoginWithMaxPasswordLength()
    {
        $response = TestHelpers::login($this->client, array( 
            'email' => '64lengthpassword@omg.wow',
            'password' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa' // 64 a's
        ));
        $this->assertResponseStatus(200);
        
        $this->assertObjectHasAttribute('token', $response['parsedJson']);
        $this->assertInternalType('string', $response['parsedJson']->token);

        $expectedTokenKeyLength = Config::get('session.token_key_length');
        $this->assertTrue(strlen($response['parsedJson']->token) === $expectedTokenKeyLength);
    }

}
