<?php
namespace Recipes\Entity;

class Recipe
{
    private $id;
    private $name;
    private $prep_time;
    private $difficult;
    private $bol_vegetarian;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPrepTime()
    {
        return $this->prep_time;
    }

    /**
     * @param mixed $prep_time
     */
    public function setPrepTime($prep_time)
    {
        $this->prep_time = $prep_time;
    }

    /**
     * @return mixed
     */
    public function getDifficult()
    {
        return $this->difficult;
    }

    /**
     * @param mixed $difficult
     */
    public function setDifficult($difficult)
    {
        $this->difficult = $difficult;
    }

    /**
     * @return mixed
     */
    public function getBolVegetarian()
    {
        return $this->bol_vegetarian;
    }

    /**
     * @param mixed $bol_vegetarian
     */
    public function setBolVegetarian($bol_vegetarian)
    {
        $this->bol_vegetarian = $bol_vegetarian;
    }



}