<?php

namespace youcloud\Controllers\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ControllerAuthLogout{


    public function doIt(Request $request, Response $response): Response{
        //set the session isConnected to false and redirect to login page
        $_SESSION['isConnected'] = false;
        $response = new \Slim\Psr7\Response();
        return $response->withHeader('Location', '/login')->withStatus(302);
    }
}