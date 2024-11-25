<?php

namespace SlimDemo\Controllers\LivreOr;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig as Twig;

class Formulaire{
    public function doIt(Request $request, Response $response): Response{
        $view = Twig::fromRequest($request);
        return $view->render($response, 'formulaire.html', );
    }
}