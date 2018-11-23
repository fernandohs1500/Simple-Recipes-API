<?php

namespace Recipes\Controller;
 
use Core\Framework;
use Core\JWTWrapper;
use Recipes\Model\UserModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends MainController
{
    private $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = $this->userModel; //Dependency Injection
    }


    /**
     * @api {post} /auth
     * @apiName Authentication
     * @apiGroup Auth
     * @apiSampleRequest {url}/auth
     * @apiDescription Token auth - By default the expiration time is 30 minutes
     * @apiSuccess {boolean} success 1 - Success <br/> 0 - Fail
     * @apiSuccess {json} access_token Token with expiration time
     * @apiParam {string} User="hellofresh" User
     * @apiParam {string} Pass="hellofresh" password
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     * Content-Type: application/json
     * {"success":1,"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1NDE4ODU0NzQsImlzcyI6ImxvY2FsaG
    *  9zdCIsImV4cCI6MTU0MTg4NzI3NCwibmJmIjoxNTQxODg1NDczLCJkYXRhIjp7ImlkIjoxLCJsb2dpbiI6ImhlbGxvZnJlc2gifX0.1tN
     *  e04JkFwE__6IYBvcUnDsPGG_h_klhBsBbe6Zcp_A","msg":"OK"}
     */
    public function authAction(Request $request)
    {
        $data = $request->request->all();
        $user = $this->model->getUserByCredentials($data['user'], $data['pass']);

        if(!empty($user)) {
            // It's valid, generate token
            $jwt = JWTWrapper::encode([
                'expiration_sec' => 1800, //30 minutes
                'iss' => 'localhost',
                'userdata' => [
                    'id' => $user['id'],
                    'login' => $user['login']
                ]
            ]);

            return new JsonResponse(array('success' => 1, 'access_token' => $jwt, 'msg' => 'OK'));
        }

        return new JsonResponse(array('success' => 0, 'access_token' => '', 'msg' => 'Invalid credentials '));
    }
}
