<?php

const FALLBACK_TOKEN_KEY_LENGTH = 64;
const SECONDS_IN_MINUTE = 60;

class Token {
    
    public $key;

    public $payload;

    public function __construct($key, $payload)
    {
        $this->key = $key;
        $this->payload = $payload;
    }

    public static function generateKey($length = 32)
    {
        return str_random($length);
    }

    public static function createByEmail($email)
    {
        $key = self::generateKey(Config::get('session.token_key_length') ?: FALLBACK_TOKEN_KEY_LENGTH);
        $payload = array(
            'email' => $email,
            'userId' => \User::where('email', '=', $email)->first()->id,
        );
        
        return self::create($key, $payload);
    }

    public static function create($key, $payload)
    {
        //TODO: add validation for key and payload
        return new self($key, $payload);
    }

    public function store() {
        $expirationInMinutes = Config::get('session.lifetime');
        Redis::setex($this->key, $expirationInMinutes * SECONDS_IN_MINUTE, serialize($this->payload));
    }

}