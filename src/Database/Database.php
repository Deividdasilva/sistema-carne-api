<?php

namespace App\Database;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;

    public static function connect()
    {
        if (self::$instance === null) {
            $host = 'mysql';
            $dbname = 'phpdocker';
            $username = 'root';
            $password = 'root';

            try {
                $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
                self::$instance = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_PERSISTENT => false,
                ]);
            } catch (PDOException $e) {
                throw new Exception('Database connection error: ' . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
