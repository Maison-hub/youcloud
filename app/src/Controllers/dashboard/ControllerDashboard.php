<?php

namespace youcloud\Controllers\Dashboard;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig as Twig;

use youcloud\Services\ViaPdo\UserService;
use youcloud\Services\ViaPdo\UploadService;
use youcloud\Services\ViaPdo\Bdd;

class ControllerDashboard{

    protected $uploadService;
    protected $userService;

    public function __construct($uploadService) {
        $bdd = new Bdd();
        $this-> uploadService = new UploadService($bdd);
        $this-> userService = new UserService($bdd);
    }

    public function doIt(Request $request, Response $response){
        
        if(isset($_SESSION['userid'])){

            $user = $this->userService->getUser($_SESSION['userid']);

            $userFiles = $this->uploadService->getFiles($_SESSION['userid']);
            $data = [
                'user' => $user,
                'files' => $userFiles
            ];
        
            $view = Twig::fromRequest($request);
            return $view->render($response, 'user/dashboard.twig', $data);

        } else {
            $response->getBody()->write('Vous n\'êtes pas connecté');
            return $response;
        }
    }
} 
