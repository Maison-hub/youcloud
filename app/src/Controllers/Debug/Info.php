<?php
namespace youcloud\Controllers\Debug;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Info{
    public function doIt(Request $request, Response $response, $args){
        \ob_start();
        \phpinfo();
        $stuff = \ob_get_contents();
        \ob_end_clean();
        $response->getBody()->write($stuff);
        return $response;
    }
}