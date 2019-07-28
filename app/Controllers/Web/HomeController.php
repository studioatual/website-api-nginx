<?php

namespace StudioAtual\Controllers\Web;

use StudioAtual\Controllers\Controller;

class HomeController extends Controller
{
    public function index($request, $response, $args)
    {
        //return phpinfo();
        //$this->logger->addInfo('Opened Home!');
        return $this->view->render($response, 'home.twig',[]);
    }
}
