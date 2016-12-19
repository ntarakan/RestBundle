<?php

namespace NT\RestBundle\EventListener;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Util\ClassUtils;
use JMS\Serializer\SerializationContext;
use NT\RestBundle\Annotation\JsonView;
use NT\RestBundle\Handler\ViewHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class JsonViewResponseListener
{
    protected $reader;

    protected $handler;

    public function __construct($reader, ViewHandler $handler)
    {
        /** @var AnnotationReader $reader */
        $this->reader = $reader;
        $this->handler = $handler;
    }

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        /** @var $configuration JsonView */
        $configuration = $event->getRequest()->attributes->get('_json_view');
        if (!$configuration) {
            return;
        }

        $jsonView = \NT\RestBundle\View\JsonView::create($event->getControllerResult(), $configuration->getStatusCode(), $configuration->getSerializerGroups());

//        $result['code'] = $configuration->getStatusCode();

        if ($configuration->getSerializerGroups()) {
            $context = $jsonView->getSerializationContext() ?: new SerializationContext();
            $context->setGroups($configuration->getSerializerGroups());
        }
        $context = $jsonView->getSerializationContext() ?: new SerializationContext();
        $context->setSerializeNull(true);
        $jsonView->setSerializationContext($context);


        $event->setResponse($this->handler->handle($jsonView));
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony2 but it may happen.
         * If it is a class, it comes in array format
         *
         */
        if (!is_array($controller)) {
            return;
        }

        list($controllerObject, $methodName) = $controller;

        $annotation = 'NT\RestBundle\Annotation\JsonView';

        // Get method annotation
        $controllerReflectionObject = new \ReflectionObject($controllerObject);
        $reflectionMethod = $controllerReflectionObject->getMethod($methodName);
        $methodAnnotation = $this->reader->getMethodAnnotation($reflectionMethod, $annotation);
        if ($methodAnnotation) {
            $event->getRequest()->attributes->set('_json_view', $methodAnnotation);
        }
    }
}