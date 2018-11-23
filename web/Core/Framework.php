<?php

namespace Core;

use Recipes\Listeners\TokenListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Framework extends HttpKernel\HttpKernel
{
    private $routes;
    private $context;

    public function __construct($routes)
    {

        $this->routes           = $routes;
        $this->context          = new Routing\RequestContext("/web");
        $matcher                = new Routing\Matcher\UrlMatcher($this->routes, $this->context);
        $requestStack           = new RequestStack();
        $controllerResolver     = new HttpKernel\Controller\ControllerResolver();
        $argumentResolver       = new HttpKernel\Controller\ArgumentResolver();

        $dispatcher             = new EventDispatcher();

        /* -------------- Events ------------- */
        $dispatcher->addSubscriber(new HttpKernel\EventListener\RouterListener($matcher, $requestStack));
        $dispatcher->addSubscriber(new HttpKernel\EventListener\ResponseListener('UTF-8'));
        $dispatcher->addSubscriber(new HttpKernel\EventListener\ExceptionListener('Recipes\Controller\ErrorController::exceptionAction'));

        //Validation of token jwt
        $dispatcher->addSubscriber(new TokenListener());

        parent::__construct($dispatcher, $controllerResolver, $requestStack, $argumentResolver);
    }

    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true) {

        /* -------------- Attributes for Controller/Model------------- */
        $UrlGenerator = new UrlGenerator($this->routes, $this->context);

        $request->attributes->set('_urlGenerator', $UrlGenerator);
        $request->attributes->set('_urlGenerator_AbsoluteUrl', UrlGeneratorInterface::ABSOLUTE_URL);

        return parent::handle($request, $type, $catch);
    }
}