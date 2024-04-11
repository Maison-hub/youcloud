<?php

namespace youcloud\Controllers\File;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig as Twig;

use youcloud\Services\ViaPdo\UserService;
use youcloud\Services\ViaPdo\UploadService;
use youcloud\Services\ViaPdo\Bdd;

class ControllerDeletefile{

    protected $uploadService;
    protected $userService;

    public function __construct($uploadService) {
        $bdd = new Bdd();
        $this-> uploadService = new UploadService($bdd);
        $this-> userService = new UserService($bdd);
    }

    public function doIt(Request $request, Response $response, $arg){
        
        $fileId = $arg['id'];

        if(isset($_SESSION['userid'])){
            $fileSelect = $this->uploadService->fileMatchUser($_SESSION['userid'], $fileId);
            if ($fileSelect) {
                $this->uploadService->deleteFile($fileId);
                return $response->withHeader('Location', '/dashboard')->withStatus(301);
            }
            if($fileSelect == 'denied'){
                $response->getBody()->write('Vous n\'êtes pas autorisé à supprimer ce fichier');
                return $response;
            }else{
                $response->getBody()->write('Fichier introuvable');
                return $response;
            }
        } else {
            $response->getBody()->write('Vous n\'êtes pas connecté');
            return $response;
        }
        //redirect to dashboard
        return $response->withHeader('Location', '/dashboard')->withStatus(301);
        print_r('redirection?');

    }
} 