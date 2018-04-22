<?php

//TODO: Create a separate test DB and somehow segregate redis keys so that
//      executing any tests doesn't blow away actual data. Will need to call
//      Artisan's migrate in setUp once this is done.
class AuthTokenFilterTest extends TestCase {

    private $token = NULL;

    public function setUp()
    {
        parent::setUp();

        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);

        Route::any('_test/auth_filter', array(
            'as' => 'testAuthFilter',
            'before' => 'auth.token', 
            function() {
                return Response::json(array(), 200);
            }
        ));

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

    public function testSuccessfulAuth()
    {
        TestHelpers::makeAuthenticatedRequest($this->client, 'GET', URL::route('testAuthFilter'), $this->token);
        $this->assertResponseStatus(200);

        TestHelpers::makeAuthenticatedRequest($this->client, 'POST', URL::route('testAuthFilter'), $this->token);
        $this->assertResponseStatus(200);

        TestHelpers::makeAuthenticatedRequest($this->client, 'PATCH', URL::route('testAuthFilter'), $this->token);
        $this->assertResponseStatus(200);

        TestHelpers::makeAuthenticatedRequest($this->client, 'DELETE', URL::route('testAuthFilter'), $this->token);
        $this->assertResponseStatus(200);
    }

    public function testInvalidTokens()
    {
        TestHelpers::makeAuthenticatedRequest($this->client, 'DELETE', URL::route('logout'), '');
        $this->assertResponseStatus(401);

        TestHelpers::makeAuthenticatedRequest($this->client, 'DELETE', URL::route('logout'), 'lalalala');
        $this->assertResponseStatus(401);

        TestHelpers::makeAuthenticatedRequest($this->client, 'DELETE', URL::route('logout'), $this->token . 'a');
        $this->assertResponseStatus(401);

        TestHelpers::makeAuthenticatedRequest($this->client, 'DELETE', URL::route('logout'), 27);
        $this->assertResponseStatus(401);
    }

}
