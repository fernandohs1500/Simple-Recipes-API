<?php

namespace Recipes\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Debug\Exception\FlattenException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


class ErrorController
{
    public function exceptionAction(FlattenException $exception)
    {
        $log = new Logger('General');
        $log->pushHandler(new StreamHandler('/var/log/nginx/error.log', Logger::ERROR));
        $log->error("[{$exception->getCode()}][{$exception->getLine()}] - {$exception->getMessage()}");

        return new JsonResponse(array('success' => 0, 'data' => '', 'msg' => 'Oops! Something went wrong.'));
    }
}