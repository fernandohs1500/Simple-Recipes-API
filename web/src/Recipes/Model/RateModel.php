<?php

namespace Recipes\Model;

use Core\Connection;
use Recipes\Exceptions\RateException;
use Recipes\Utils\Pagination;

class RateModel extends MainModel implements IRate
{
    public $conn;
    private $rcModel;

    public function __construct($year = null) {
        parent::__construct();
        $this->rcModel = $this->recipeModel; //TO DO
        $this->conn = Connection::getInstance()->getConnection();
    }

    public function getAllPagination($page, $per)
    {
        //PAGINATION
        $limit = $per > 1000 ? 1000 : $per;
        $offset = Pagination::getOffset($page, $limit);
        $totalRows = $this->getTotalRates();
        $pagination = Pagination::makePagination($totalRows, $page, $limit);

        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder->
        select('*')
            ->from('rate')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('id', 'ASC');

        $stmt = $this->conn->query($queryBuilder->getSQL());
        $rows = $stmt->fetchAll();

        return array('success' => 1, 'data' => $rows, 'pagination' => $pagination);

        return $return;
    }


    public function getAll()
    {
        $queryBuilder = $this->conn->createQueryBuilder();

        $queryBuilder->
            select('*')
            ->from('rate');

        $stmt = $this->conn->query($queryBuilder->getSQL());

        $rows = $stmt->fetchAll();
        return array('success' => 1, 'data' => $rows);
    }

    public function getTotalRates() {

        $queryBuilder = $this->conn->createQueryBuilder();

        $queryBuilder = $queryBuilder
            ->select('count(1) as qtd')
            ->from('rate');

        $stmt = $this->conn->query($queryBuilder->getSQL());
        $total = $stmt->fetch();

        return $total['qtd'];
    }


    public function create($rId, $rate)
    {
        try {

            $this->validRate($rId, $rate);

            $queryBuilder = $this->conn->createQueryBuilder();

            $queryBuilder
                ->insert('rate')
                ->values(
                    array(
                        'recipe_id' => '?',
                        'rate' => '?'
                    )
                );

            $prepare = $this->conn->prepare($queryBuilder->getSQL());

            //Avoiding SqlInjection
            $prepare->bindValue(1, $rId);
            $prepare->bindValue(2, $rate);
            $prepare->execute();

            return array('success' => 1, 'id' => $this->conn->lastInsertId());

        } catch(RateException $e) {
            return array('success' => 0, 'msg' => $e->getMessage());
        }
    }

    public function validRate($rId, $rate)
    {
        if (empty($rId)) {
            throw new RateException("Recipe cannot be empty!");
        }
        if (empty($rate)) {
            throw new RateException("name cannot be empty!");
        }
        if (!is_numeric($rate) || $rate > 5 || $rate < 1) {
            throw new RateException("rate must be a number. Min:1 - Max:5");
        }

        $isValidRecipe = empty($this->rcModel->getById($rId)) ? false : true;

        if (!$isValidRecipe) {
            throw new RateException("The Recipe could not be found!");
        }
    }
}