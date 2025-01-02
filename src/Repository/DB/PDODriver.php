<?php

declare(strict_types=1);

namespace Wolf\Authentication\Oauth2\Repository\DB;

class PDODriver extends \PDO
{

    private static $pdo = null;

    private function __construct($dsn, $username = null, $password = null, $options = [])
    {
        parent::__construct($dsn, $username, $password, $options);
    }

    public static function getInstance($dsn, $username = null, $password = null, $options = [])
    {
        if (self::$pdo === null) {
            self::$pdo = new static($dsn, $username, $password, $options);
            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }

        return self::$pdo;
    }

}
