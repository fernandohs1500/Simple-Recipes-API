<?php

use Symfony\Component\Routing;
use Symfony\Component\Routing\Route;
$routes = new Routing\RouteCollection();

$routes->add('Auth', new Route('/auth', array(
    '_controller' => 'Recipes\\Controller\\AuthController::authAction',
), array(), array(), '', array(), array('POST')));

$routes->add('Default', new Route('/', array(
    '_controller' => 'Recipes\\Controller\\RecipesController::indexAction',
), array(), array(), '', array(), array('GET', 'HEAD')));

$routes->add('ListAll', new Route('/recipes', array(
    '_controller' => 'Recipes\\Controller\\RecipesController::indexAction',
), array(), array(), '', array(), array('GET', 'HEAD')));

$routes->add('List', new Route('/recipes/page/{page}/per/{per}', array(
    '_controller' => 'Recipes\\Controller\\RecipesController::pageAction',
), array(), array(), '', array(), array('GET', 'HEAD')));

$routes->add('Create', new Route('/recipes', array(
    '_controller' => 'Recipes\\Controller\\RecipesController::createAction',
), array(), array(), '', array(), array('POST')));
//
$routes->add('Get', new Route('/recipes/{id}', array(
    '_controller' => 'Recipes\\Controller\\RecipesController::getAction',
), array(), array(), '', array(), array('GET', 'HEAD')));

$routes->add('Update', new Route('/recipes/{id}', array(
    '_controller' => 'Recipes\\Controller\\RecipesController::updateAction',
), array(), array(), '', array(), array('PUT', 'PATCH')));

$routes->add('Delete', new Route('/recipes/{id}', array(
    '_controller' => 'Recipes\\Controller\\RecipesController::deleteAction',
), array(), array(), '', array(), array('DELETE')));

$routes->add('RecipeSearch', new Route('/recipes/search', array(
    '_controller' => 'Recipes\\Controller\\RecipesController::searchAction',
), array(), array(), '', array(), array('POST')));

$routes->add('RatesAll', new Route('/rates', array(
    '_controller' => 'Recipes\\Controller\\RatesController::indexAction',
), array(), array(), '', array(), array('GET', 'HEAD')));

$routes->add('Rates', new Route('/rates/page/{page}/per/{per}', array(
    '_controller' => 'Recipes\\Controller\\RatesController::pageAction',
), array(), array(), '', array(), array('GET', 'HEAD')));

$routes->add('Rate', new Route('/rate/{recipeId}/{rate}', array(
    '_controller' => 'Recipes\\Controller\\RatesController::createAction',
), array(), array(), '', array(), array('POST')));

return $routes;