<?php

namespace Recipes\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RecipesController extends MainController implements TokenAuthenticatedController
{
    private $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = $this->recipeModel; //Dependency Injection
    }

    /**
     * @api {get} /recipes/page/{page}/per/{per}
     * @apiName Get Recipes With pagination
     * @apiGroup Recipes
     * @apiSampleRequest {url}/recipes/page/1/per/2
     * @apiDescription List all recipes with pagination
     * @apiSuccess {boolean} success 1 - Success <br/> 0 - Fail
     * @apiSuccess {json} data List of all recipes
     * @apiParam {number} page The current page
     * @apiParam {number} per Qtd of recipes per page
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     * Content-Type: application/json
     *   {
     *   "success": 1,
     *   "data": {
     *   "success": 1,
     *   "data": [
     *   {
     *   "id": 1,
     *   "name": "Herby Pan-Seared Chicken",
     *   "prep_time": 60,
     *   "difficult": 3,
     *   "bol_vegetarian": false
     *   },
     *   {
     *   "id": 2,
     *   "name": "Herby Pan-Seared Chicken 2",
     *   "prep_time": 25,
     *   "difficult": 3,
     *   "bol_vegetarian": true
     *   }
     *   ],
     *   "pagination": {
     *   "total": 27,
     *   "current": "1",
     *   "next": 2,
     *   "prev": 1,
     *   "first": 1,
     *   "last": 14
     *   }
     *   }
     *   }
     */
    public function pageAction(Request $request, $page, $per)
    {
        $data = $this->model->getAllPagination($page, $per);
        return new JsonResponse(array('success' => 1, 'data' => $data));
    }

    /**
     * @api {get} /recipes
     * @apiName Get Recipes Without pagination
     * @apiGroup Recipes
     * @apiSampleRequest recipes
     * @apiDescription List all recipes
     * @apiSuccess {boolean} success 1 - Success <br/> 0 - Fail
     * @apiSuccess {String} data List of all recipes
     *
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     * Content-Type: application/json
     *   {
     *   "success": 1,
     *   "data": {
     *   "success": 1,
     *   "data": [
     *   {
     *   "id": 1,
     *   "name": "Herby Pan-Seared Chicken",
     *   "prep_time": 60,
     *   "difficult": 3,
     *   "bol_vegetarian": false
     *   },
     *   {
     *   "id": 2,
     *   "name": "Herby Pan-Seared Chicken 2",
     *   "prep_time": 25,
     *   "difficult": 3,
     *   "bol_vegetarian": true
     *   }
     *   ]
     *   }
     *   }
     */
    public function indexAction(Request $request)
    {
        $data = $this->model->getAll();
        return new JsonResponse(array('success' => 1, 'data' => $data));
    }


    /**
     * @api {post} /recipes
     * @apiName Create a recipe
     * @apiGroup Recipes
     * @apiSampleRequest {url}/recipes
     * @apiDescription Create a recipe
     * @apiSuccess {boolean} success 1 - Success <br/> 0 - Fail
     * @apiSuccess {json} data Recipe's id
     * @apiParam {string} name Id Recipe
     * @apiParam {number{1-120}} prep_time
     * @apiParam {number{1-3}} difficult
     * @apiParam {boolean} bol_vegetarian pontuation
     * @apiHeader (Authorization) {BearerToken} Authorization Access Token
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     * Content-Type: application/json
     * {"success":1,"data":"10"}
     */
    public function createAction(Request $request)
    {
        $result = $this->model->create($request->request->all());
        if ($result['success']) {
            return new JsonResponse(array('success' => 1, 'data' => $result['id']));
        }
        return new JsonResponse(array('success' => 0, 'msg' => $result['msg']));
    }

    /**
     * @api {get} /recipes/{id}
     * @apiName Get a recipe
     * @apiGroup Recipes
     * @apiSampleRequest {url}/recipes
     * @apiDescription Get a recipe
     * @apiSuccess {boolean} success 1 - Success <br/> 0 - Fail
     * @apiSuccess {json} data Recipe object
     * @apiParam {number} id Recipe ID
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     * Content-Type: application/json
     * {"success":1,"data":{"id":2,"name":"Teste","prep_time":14,"difficult":1,"bol_vegetarian":true}}
     */
    public function getAction(Request $request, $id)
    {
        $data = $this->model->getById($id);
        return new JsonResponse(array('success' => 1, 'data' => $data));
    }

    /**
     * @api {put} /recipes/{id}
     * @apiName Update a recipe
     * @apiGroup Recipes
     * @apiSampleRequest {url}/recipe/2
     * @apiDescription Update a recipe
     * @apiSuccess {boolean} success 1 - Success <br/> 0 - Fail
     * @apiSuccess {json} data Recipe's id
     * @apiParam {string} name Id Recipe
     * @apiParam {number{1-120}} prep_time
     * @apiParam {number{1-3}} difficult
     * @apiParam {boolean} bol_vegetarian pontuation
     * @apiHeader (Authorization) {BearerToken} Authorization Access Token
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     * Content-Type: application/json
     * {"success":1,"data":"10"}
     */
    public function updateAction(Request $request, $id)
    {
        $result = $this->model->update($request->request->all(), $id);
        if ($result['success']) {
            return new JsonResponse(array('success' => 1, 'data' => $result['id']));
        }
        return new JsonResponse(array('success' => 0, 'msg' => $result['msg']));
    }

    /**
     * @api {delete} /recipes/{id}
     * @apiName Delete a recipe
     * @apiGroup Recipes
     * @apiSampleRequest {url}/recipes
     * @apiSuccess {boolean} success 1 - Success <br/> 0 - Fail
     * @apiSuccess {boolean} data DELETED OR FAIL
     * @apiDescription Delete a recipe
     * @apiParam {number} id Recipe ID
     * @apiHeader (Authorization) {BearerToken} Authorization Access Token
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     * Content-Type: application/json
     * {"success":1,"data":"DELETED!"}
     */
    public function deleteAction(Request $request, $id)
    {
        $this->model->delete($id);
        return new JsonResponse(array('success' => 1, 'data' => 'DELETED!'));
    }


    /**
     * @api {post} recipes/search
     * @apiName Search for a recipe according to a json object model
     * @apiGroup Recipes
     * @apiSampleRequest {url}/recipes/search
     * @apiDescription Search for a recipe
     * @apiSuccess {boolean} success 1 - Success <br/> 0 - Fail
     * @apiSuccess {json} data List of recipes. Ex:<br/>
     *                   {
     *                   "name":"Teste",
     *                   "prep_time":14,
     *                   "difficult":1,
     *                   "bol_vegetarian":true
     *                   }
     * @apiParam {json} [name] name of recipe
     * @apiParam {number{1-120}} [prep_time] preparation time
     * @apiParam {number{1-3}} [difficult] Difificult
     * @apiParam {boolean} [bol_vegetarian] vegetarian - true or false
     * @apiHeader (Authorization) {BearerToken} Authorization Access Token
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     * Content-Type: application/json
     * {"success":1,"data":[{"id":2,"name":"Teste","prep_time":14,"difficult":1,"bol_vegetarian":true}]}
     */
    public function searchAction(Request $request)
    {
        $data = $request->getContent();
        $data = json_decode($data, true);
        $result = $this->model->searchRecipe($data);
        return new JsonResponse(array('success' => 1, 'data' => $result));
    }

}
