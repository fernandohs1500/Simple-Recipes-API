<?php
require "../vendor/autoload.php";

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel;

$request                = Request::createFromGlobals();
$routes                 = include __DIR__.'/../src/routes.php';


/* -------------- Bootstrap Start ------------- */
$framework  = new \Core\Framework($routes);

$framework  = new HttpKernel\HttpCache\HttpCache(
    $framework,
    new HttpKernel\HttpCache\Store(__DIR__ . '/../cache')
);

$framework->handle($request)->send();