<?php

namespace youcloud\Controllers\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig as Twig;

class ControllerAuthLoginform{
    public function doIt(Request $request, Response $response): Response{
        $view = Twig::fromRequest($request);
        return $view->render($response, 'auth/loginform.twig');
    }
}  