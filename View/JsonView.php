<?php

namespace NT\RestBundle\View;

use JMS\Serializer\SerializationContext;
use NT\RestBundle\Util\CamelCaseConverter;
use Symfony\Component\HttpFoundation\JsonResponse;

class JsonView
{
    protected $data;

    protected $statusCode;

    protected $serializationContext;

    public static function create($data = null, $statusCode = null, $headers = null)
    {
        return new static($data, $statusCode, $headers);
    }

    public function __construct($data = null, $statusCode = null, $headers = null)
    {
        $this->setData($data);
        $this->setStatusCode($statusCode ?: 200);
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getData()
    {
        $result['code'] = $this->getStatusCode();
        if (is_array($this->data)) {
            $result['result'] = CamelCaseConverter::convertArrayKeysToUnderscore($this->data);
        } else {
            $result['result'] = $this->data;
        }


        return $result;
    }

    public function setSerializationContext(SerializationContext $context)
    {
        $this->serializationContext = $context;
        return $this;
    }

    public function getSerializationContext()
    {
        return $this->serializationContext;
    }
}