<?php

namespace youcloud\Controllers\Upload;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig as Twig;

class ControllerUploadform{
    public function doIt(Request $request, Response $response): Response{
        $view = Twig::fromRequest($request);
        return $view->render($response, 'upload/uploadform.twig');
    }
}  