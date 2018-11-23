<?php

namespace Recipes\Controller;
 
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RatesController extends MainController
{
    private $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = $this->rateModel; //TO DO
    }

    /*
     * Basic Example
     *
     * This is a basic example for apiDoc.
     * Documentation blocks without @api (like this block) will be ignored.
     */

    /**
     * @api {get} rates
     * @apiName Get Rates without pagination
     * @apiGroup Rates
     * @apiSampleRequest rates
     * @apiDescription List all avaliations
     * @apiSuccess {boolean} success 1 - Success <br/> 0 - Fail
     * @apiSuccess {String} data List of all avaliations
     *
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     * Content-Type: application/json
     * {
     *  "success": 1,
     *  "data": {
     *  "success": 1,
     *  "data": [
     *  {
     *  "id": 1,
     *  "recipe_id": 1,
     *  "rate": 2
     *  },
     *  {
     *  "id": 2,
     *  "recipe_id": 2,
     *  "rate": 5
     *  },
     *  {
     *  "id": 3,
     *  "recipe_id": 4,
     *  "rate": 4
     *  }
     *  ]
     *  }}
     */

    public function indexAction(Request $request)
    {
        $data = $this->model->getAll();
        return new JsonResponse(array('success' => 1, 'data' => $data));
    }

    /**
     * @api {get} /rates/page/{page}/per/{per}
     * @apiName Get Rates With pagination
     * @apiGroup Rates
     * @apiSampleRequest {url}/rates/page/1/per/2
     * @apiDescription List all avaliations with pagination
     * @apiSuccess {boolean} success 1 - Success <br/> 0 - Fail
     * @apiSuccess {json} data  List of all avaliations
     * @apiParam {number} page The current page
     * @apiParam {number} per Qtd of rates per page
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     * Content-Type: application/json
     *  {
     *   "success": 1,
     *   "data": {
     *   "success": 1,
     *   "data": [
     *   {
     *   "id": 1,
     *   "recipe_id": 1,
     *   "rate": 2
     *   },
     *   {
     *   "id": 2,
     *   "recipe_id": 2,
     *   "rate": 5
     *  }
     *   ],
     *   "pagination": {
     *   "total": 4,
     *   "current": "1",
     *   "next": 2,
     *   "prev": 1,
     *   "first": 1,
     *   "last": 2
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
     * @api {post} /rate/{recipeId}/{rate}
     * @apiName Rate a recipe
     * @apiGroup Rates
     * @apiSampleRequest {url}/rate/1/2
     * @apiDescription Rate a recipe
     * @apiSuccess {boolean} success 1 - Success <br/> 0 - Fail
     * @apiSuccess {json} data Recipe's ID
     * @apiParam {number} recipeId Id Recipe
     * @apiParam {number{1-5}} rate number
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     * Content-Type: application/json
     * {"success":1,"data":"24"}
     */
    public function createAction(Request $request, $recipeId, $rate)
    {
        $result = $this->model->create($recipeId, $rate);
        if ($result['success']) {
            return new JsonResponse(array('success' => 1, 'data' => $result['id']));
        }
        return new JsonResponse(array('success' => 0, 'msg' => $result['msg']));
    }
}
