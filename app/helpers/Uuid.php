<?php

$uuidFactory = new \Ramsey\Uuid\UuidFactory();
$uuidFactory->setRandomGenerator(new \Ramsey\Uuid\Generator\RandomLibAdapter());
\Ramsey\Uuid\Uuid::setFactory($uuidFactory);

class Uuid {

    public static function __callStatic($name, $arguments)
    {
        return call_user_func(array('\Ramsey\Uuid\Uuid', $name))->toString();
    }

}
