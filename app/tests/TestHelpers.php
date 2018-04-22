<?php

class TestHelpers {

    public static function login($client, $credentials)
    {
        return self::makeRequest($client, 'POST', URL::route('login'), $credentials, array('includeToken' => True));
    }

    public static function makeAuthenticatedRequest($client, $method, $route, $token, $payload = array(), $options = array())
    {
        Route::enableFilters();
        $client->request($method, $route, $payload, array(), array(
            'HTTP_AUTHORIZATION' => Constant::get('AUTHORIZATION_PREFIX') . $token
        ));
        Route::disableFilters();

        return self::parseResponse($client, $options);
    }

    public static function makeRequest($client, $method, $route, $payload = array(), $options = array())
    {
        // Keep routes enabled to make sure there are no guards enforced on a route accidentally
        Route::enableFilters();
        $client->request($method, $route, $payload);
        Route::disableFilters();

        return self::parseResponse($client, $options);
    }

    public static function parseResponse($client, $options = array())
    {
        $parsedJson = NULL;
        $token = NULL;

        $includeToken = array_get($options, 'includeToken') || False;
        
        try {
            $parsedJson = json_decode($client->getResponse()->getContent());
            if ($includeToken) $token = $parsedJson->token;
        } finally {
            $response = array(
                'response' => $client->getResponse(),
                'parsedJson' => $parsedJson,
            );
            if ($includeToken) $response['token'] = $token;
            return $response;
        }
    }

}