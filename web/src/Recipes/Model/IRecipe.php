<?php
/**
 * Created by PhpStorm.
 * User: fernando
 * Date: 10/11/18
 * Time: 13:24
 */

namespace Recipes\Model;


interface IRecipe
{
    function validRecipe($recipe);
    function getAllPagination($page,$per);
    function getAll();
    function getById($recipeId);
    function create($recipe);
    function update($recipe, $recipeId);
    function delete($recipeId);
    function searchRecipe($search);
}