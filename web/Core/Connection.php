<?php

namespace Core;

use Doctrine\DBAL\Configuration;

class Connection
{
    private static $instance;
    private $connection;

    //Constructor
    private function __construct()
    {
        $config = new Configuration();
        $connectionParams = array(
            'dbname' => 'recipetest',
            'user' => 'root',
            'password' => '',
            'host' => 'postgres',
            'port' => '5432',
            'driver' => 'pdo_pgsql'
        );

        $this->_connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
    }

    public static function getInstance() {
        if(!self::$instance) { // If no instance then make one
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

}
