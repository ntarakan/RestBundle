<?php

namespace NT\RestBundle\Controller;

use NT\RestBundle\View\JsonView;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RestController extends Controller
{
    public function handleView(JsonView $view)
    {
        return $this->get('nt_rest.view_handler')->handle($view);
    }

    public function view($data, $code)
    {
        return new JsonView($data, $code);
    }
}
