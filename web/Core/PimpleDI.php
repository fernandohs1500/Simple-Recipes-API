<?php

namespace Core;

use Pimple\Container;
use Recipes\Model\RateImpl;
use Recipes\Model\RateModel;
use Recipes\Model\RecipeModel;
use Recipes\Model\UserModel;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PimpleDI
{
    private $container;

    protected function __construct()
    {
        $this->container = new Container();

        //List of services
        $this->container['recipeModel'] = function ($c) {
            return new RecipeModel();
        };

        $this->container['rateModel'] = function ($c) {
            return new RateModel();
        };

        $this->container['userModel'] = function ($c) {
            return new UserModel();
        };

    }

    public function __get($property) {
        return $this->container[$property];
    }

    public function __call($name, $arguments)
    {

        throw new HttpException(500, "This object method '$name' doesn't exist");
    }

}
