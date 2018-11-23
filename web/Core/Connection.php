<?php

namespace Core;

use Doctrine\DBAL\Configuration;

class Connection
{
    private static $_instance;
    private $_connection;

    //Constructor
    private function __construct()
    {
        $config = new Configuration();
        $connectionParams = array(
            'dbname' => 'hellofresh',
            'user' => 'hellofresh',
            'password' => 'hellofresh',
            'host' => 'postgres',
            'port' => '5432',
            'driver' => 'pdo_pgsql'
        );

        $this->_connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
    }

    public static function getInstance() {
        if(!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    // Magic method clone is empty to prevent duplication of connection
    private function __clone() {}
    // Get mysqli connection

    public function getConnection() {
        return $this->_connection;
    }

}
