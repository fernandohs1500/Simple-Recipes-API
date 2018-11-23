<?php

namespace Recipes\Model;

use Core\Connection;

class UserModel
{
    public $conn;

    public function __construct($year = null) {
        $this->conn = Connection::getInstance()->getConnection();
    }

    public function getUserByCredentials($login, $passwd)
    {
        $queryBuilder = $this->conn->createQueryBuilder();

        $queryBuilder->
        select('*')
            ->from('public.user')
            ->where("login=? and passwd=?");

        $prepare = $this->conn->prepare($queryBuilder->getSQL());
        $prepare->bindValue(1, $login);
        $prepare->bindValue(2, strtoupper(hash('sha512', $passwd)));
        $prepare->execute();

        return $prepare->fetch();
    }
}