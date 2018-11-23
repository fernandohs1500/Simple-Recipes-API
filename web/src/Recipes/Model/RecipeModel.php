<?php

namespace Recipes\Model;

use Core\Connection;
use Recipes\Exceptions\RecipeException;
use Recipes\Utils\Pagination;

class RecipeModel extends MainModel implements IRecipe
{
    public $conn;

    public function __construct($year = null) {
        $this->conn = Connection::getInstance()->getConnection();
    }

    public function getAllPagination($page, $per)
    {
        //PAGINATION
        $limit = $per > 1000 ? 1000 : $per;
        $offset = Pagination::getOffset($page, $limit);
        $totalRows = $this->getTotalRecipes();
        $pagination = Pagination::makePagination($totalRows, $page, $limit);

        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder->
            select('*')
            ->from('recipe')
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
            ->from('recipe')
            ->orderBy('id', 'ASC');

        $stmt = $this->conn->query($queryBuilder->getSQL());
        $rows = $stmt->fetchAll();

        return array('success' => 1, 'data' => $rows);

        return $return;
    }

    public function getTotalRecipes() {

        $queryBuilder = $this->conn->createQueryBuilder();

        $queryBuilder = $queryBuilder
            ->select('count(1) as qtd')
            ->from('recipe');

        $stmt = $this->conn->query($queryBuilder->getSQL());
        $total = $stmt->fetch();

        return $total['qtd'];
    }

    public function getById($id)
    {
        $queryBuilder = $this->conn->createQueryBuilder();

        $queryBuilder->
        select('*')
            ->from('recipe')
            ->where("id= ?");

        $prepare = $this->conn->prepare($queryBuilder->getSQL());
        $prepare->bindValue(1, $id);
        $prepare->execute();

        $return = $prepare->fetch();

        if (!$return) {
            return array();
        }

        return $return;
    }

    public function create($recipe)
    {
        try {
            $this->validRecipe($recipe);

            $queryBuilder = $this->conn->createQueryBuilder();
            $queryBuilder
                ->insert('recipe')
                ->values(
                    array(
                        'name' => '?',
                        'prep_time' => '?',
                        'difficult' => '?',
                        'bol_vegetarian' => '?'
                    )
                );

            $prepare = $this->conn->prepare($queryBuilder->getSQL());

            $prepare->bindValue(1, $recipe['name']);
            $prepare->bindValue(2, $recipe['prep_time']);
            $prepare->bindValue(3, $recipe['difficult']);
            $prepare->bindValue(4, $recipe['bol_vegetarian']);
            $prepare->execute();

            return array('success' => 1, 'id' => $this->conn->lastInsertId());

        } catch (RecipeException $e) {
            return array('success' => 0, 'msg' => $e->getMessage());
        }
    }

    public function update($recipe, $id)
    {
        try {
            $this->validRecipe($recipe);
            $queryBuilder = $this->conn->createQueryBuilder();

            $queryBuilder
                ->update('recipe')
                ->set('name', '?')
                ->set('prep_time', '?')
                ->set('difficult', '?')
                ->set('bol_vegetarian', '?')
                ->where("id = ? ");

            $prepare = $this->conn->prepare($queryBuilder->getSQL());
            $prepare->bindValue(1, $recipe['name']);
            $prepare->bindValue(2, $recipe['prep_time']);
            $prepare->bindValue(3, $recipe['difficult']);
            $prepare->bindValue(4, $recipe['bol_vegetarian']);
            $prepare->bindValue(5, $id);
            $prepare->execute();

            return array('success' => 1, 'id' => $id);
        }catch(RecipeException $e) {
            return array('success' => 0, 'msg' => $e->getMessage());
        }
    }

    public function delete($id)
    {
        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder
            ->delete('recipe')
            ->where("id = ?");

        $prepare = $this->conn->prepare($queryBuilder->getSQL());
        $prepare->bindValue(1, $id);
        $prepare->execute();
    }

    public function searchRecipe($search)
    {
        if (empty($search)) {
            throw new RecipeException("Recipe cannot be empty!");
        }

        $queryBuilder = $this->conn->createQueryBuilder();

        $where = array();
        if (isset($search['name']) && !empty($search['name'])) {
            $where[] = "name LIKE :name";
        }
        if (isset($search['prep_time']) && !empty($search['prep_time'])) {
            $where[]= "prep_time=:prep_time";
        }
        if (isset($search['difficult']) && !empty($search['difficult'])) {
            $where[]= "difficult=:difficult";
        }
        if (isset($search['bol_vegetarian'])) {
            $bolVegetarian =
                ($search['bol_vegetarian'] == 'true' || $search['bol_vegetarian'] == 1)
                    ? 'true' : 'false';
            $where[]= "bol_vegetarian=:bol_vegetarian";
        }

        $where = implode(" AND ", $where);
        $where = ltrim( $where, ' AND ');

        $queryBuilder->
        select('*')
            ->from('recipe')
            ->where("{$where}");

        $prepare = $this->conn->prepare($queryBuilder->getSQL());

//        print_r($queryBuilder->getSQL()); die;

        if (isset($search['name']) && !empty($search['name'])) {
            $likeName = '%'.$search['name'].'%';
            $prepare->bindParam(':name', $likeName);
        }
        if (isset($search['prep_time']) && !empty($search['prep_time'])) {
            $prepare->bindParam('prep_time', $search['prep_time']);
        }
        if (isset($search['difficult']) && !empty($search['difficult'])) {
            $prepare->bindParam('difficult', $search['difficult']);
        }
        if (isset($search['bol_vegetarian'])) {
            $prepare->bindParam('bol_vegetarian', $bolVegetarian);
        }

        $prepare->execute();
        $return = $prepare->fetchAll();

        return $return;
    }

    public function validRecipe($recipe) {
        if(empty($recipe)) {
            throw new RecipeException("Recipe cannot be empty!");
        }
        if(empty($recipe['name'])) {
            throw new RecipeException("name cannot be empty!");
        }
        if(empty($recipe['prep_time'])) {
            throw new RecipeException("prep_time cannot be empty!");
        }
        if(empty($recipe['difficult'])) {
            throw new RecipeException("difficult cannot be empty!");
        }
        if(!isset($recipe['bol_vegetarian']) ||  $recipe['bol_vegetarian'] === '') {
            throw new RecipeException("bol_vegetarian cannot be empty!");
        }
        if(!is_numeric($recipe['prep_time']) || $recipe['prep_time'] > 120 || $recipe['prep_time'] <1) {
            throw new RecipeException("prep_time must be a number. Min:1 - Max:120");
        }
        if(!is_numeric($recipe['difficult']) || $recipe['difficult'] > 3 || $recipe['difficult'] <1) {
            throw new RecipeException("difficult must be a number. Min:1 - Max:3");
        }
        if(!($recipe['bol_vegetarian'] == 'true' || $recipe['bol_vegetarian'] == 'false')) {
            throw new RecipeException("bol_vegetarian must be a boolean. true OR false!");
        }
    }

}