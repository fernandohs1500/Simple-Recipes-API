<?php
/**
 * Created by PhpStorm.
 * User: fernando
 * Date: 10/11/18
 * Time: 13:24
 */

namespace Recipes\Model;


interface IRate
{
    function validRate($recipeId, $rate);
    function create($recipeId, $rate);
    function getAllPagination($page,$per);
    function getAll();
}