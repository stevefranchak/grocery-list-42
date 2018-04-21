<?php

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

    public static function createWithPayload($payload)
    {
        $key = self::generateKey(Config::get('session.token_key_length') ?: Constant::get('FALLBACK_TOKEN_KEY_LENGTH'));
        return self::create($key, $payload);
    }

    public static function create($key, $payload)
    {
        return new self($key, $payload);
    }

    public function store() {
        $expirationInMinutes = Config::get('session.lifetime');
        Redis::setex($this->key, $expirationInMinutes * Constant::get('SECONDS_IN_MINUTE'), serialize($this->payload));
    }

    public static function load($key = NULL)
    {
        $payload = Redis::get($key);

        if (!$payload) {
            return NULL;
        }

        return new self($key, unserialize($payload));
    }

    public static function getKeyFromRequest()
    {
        $authorizationHeaderContents = Request::header('Authorization');

        if (!$authorizationHeaderContents) {
            throw new DomainException('Authorization HTTP header is missing.');
        }

        if (!starts_with($authorizationHeaderContents, Constant::get('AUTHORIZATION_PREFIX'))) {
            throw new DomainException('Authorization HTTP header does not start with "Bearer ".');
        }

        $authorizationPrefixLength = strlen(Constant::get('AUTHORIZATION_PREFIX'));
        if (strlen($authorizationHeaderContents) - $authorizationPrefixLength !== Config::get('session.token_key_length')) {
            throw new DomainException('Unexpected token length.');
        }

        return substr($authorizationHeaderContents, $authorizationPrefixLength);
    }

}