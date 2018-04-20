<?php

class Constant {
    
    private static $constants = array(
        'API_PREFIX_URI' => 'api/v1',
        'AUTHORIZATION_PREFIX' => 'Bearer ',
        'FALLBACK_TOKEN_KEY_LENGTH' => 64,
        'SECONDS_IN_MINUTE' => 60,
    );

    public static function get($constantName)
    {
        if (!is_string($constantName)) {
            return NULL;
        }

        return array_get(self::$constants, $constantName);
    }

}