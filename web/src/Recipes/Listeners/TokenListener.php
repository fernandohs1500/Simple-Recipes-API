<?php

namespace Recipes\Listeners;

use Core\JWTWrapper;
use Recipes\Controller\TokenAuthenticatedController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class TokenListener implements EventSubscriberInterface
{

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof TokenAuthenticatedController) {

            //GET METHODS ARE NOT PROTECTED
            if(!$event->getRequest()->isMethod('GET')) {

                $authorization = $event->getRequest()->headers->get('authorization');

                list($jwt) = sscanf($authorization, 'Bearer %s');

                if ($jwt) {
                    try {
                        //Auth if it's valid
                        $dataToken = JWTWrapper::decode($jwt);
                        //ADD auth_token in request
                        $event->getRequest()->request->set('auth_token', $dataToken);
                    } catch(Exception $ex) {
                        throw new AccessDeniedHttpException('This action needs a valid token!');
                    }

                } else {
                    throw new AccessDeniedHttpException('Token not informed!');
                }
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }
}