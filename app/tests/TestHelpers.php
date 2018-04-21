<?php

class TestHelpers {

    public static function login($client, $credentials)
    {
        $client->request('POST', URL::route('login'), $credentials);

        return self::parseResponse($client, True);
    }

    public static function makeAuthenticatedRequest($client, $method, $route, $token)
    {
        Route::enableFilters();
        $client->request($method, $route, array(), array(), array(
            'HTTP_AUTHORIZATION' => Constant::get('AUTHORIZATION_PREFIX') . $token
        ));
        Route::disableFilters();

        return self::parseResponse($client);
    }

    public static function parseResponse($client, $includeToken = False)
    {
        $parsedJson = NULL;
        $token = NULL;
        
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