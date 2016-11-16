<?php

namespace NT\RestBundle\EventListener;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Util\ClassUtils;
use NT\RestBundle\Annotation\JsonView;
use NT\RestBundle\Handler\ViewHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ExceptionListener
{
    protected $handler;

    public function __construct(ViewHandler $handler)
    {
        $this->handler = $handler;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        /** @var $configuration JsonView */
        $configuration = $event->getRequest()->attributes->get('_json_view');
        if (!$configuration) {
            return;
        }

        if ($event->getException() instanceof HttpException) {
            $jsonView = \NT\RestBundle\View\JsonView::create(['message' => $event->getException()->getMessage()], $event->getException()->getStatusCode());
            $event->setResponse($this->handler->handle($jsonView));
        }
    }
}