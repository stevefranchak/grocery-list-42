<?php

class GlobalUserToken {

    static $token = NULL;

    public static function get()
    {
        return self::$token;
    }

    public static function set($token)
    {
        self::$token = $token;
    }

}