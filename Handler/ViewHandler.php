<?php

namespace NT\RestBundle\Handler;


use JMS\Serializer\SerializationContext;
use NT\RestBundle\View\JsonView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ViewHandler
{
    protected $serializer;

    public function __construct(\JMS\Serializer\Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function handle(JsonView $view)
    {
        $result = $this->serializer->toArray($view->getData(), $view->getSerializationContext());

        $response = new Response($this->serializer->serialize($result, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


}