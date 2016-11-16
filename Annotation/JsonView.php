<?php

namespace NT\RestBundle\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
class JsonView
{
    /**
     * @var integer
     */
    public $statusCode;

    /**
     * @var array
     */
    public $serializerGroups;

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return array
     */
    public function getSerializerGroups()
    {
        return $this->serializerGroups;
    }

    /**
     * @param $groups array
     * 
     * 
     */
    public function setSerializerGroups($groups)
    {
        $this->serializerGroups = $groups;
    }
}