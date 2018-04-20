<?php

//TODO: Create a separate test DB and somehow segregate redis keys so that
//      executing any tests doesn't blow away actual data. Will need to call
//      Artisan's migrate in setUp once this is done.
class AuthTokenFilterTest extends TestCase {

    public function setUp()
    {
        parent::setUp();

        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);

        Route::any('_test/auth_filter', array(
            'as' => 'testAuthFilter',
            'before' => 'auth.token', 
            function() {
                return 'OK';
            }
        ));
    }

    public function tearDown()
    {
        parent::tearDown();

        Redis::flushall();

        DB::table('users')->truncate();
    }

    public function testSuccessfulAuth()
    {
        $this->client->request('POST', URL::route('login'), array(
            'email' => 'stevefranchak@gmail.com',
            'password' => 'test'
        ));

        $tokenKey = json_decode($this->client->getResponse()->getContent())->token;

        //TODO: Not entirely sure how to call the filter directly - probably need to encapsulate better
        Route::enableFilters();
        $this->client->request('GET', URL::route('testAuthFilter'), array(), array(), array(
            'HTTP_AUTHORIZATION' => Constant::get('AUTHORIZATION_PREFIX') . $tokenKey
        ));
        Route::disableFilters();

        echo $this->client->getResponse()->getContent();
        $this->assertTrue($this->client->getResponse()->isOk());
    }

}
