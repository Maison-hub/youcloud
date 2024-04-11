<?php

namespace youcloud\Controllers\Auth;

use youcloud\Services\ViaPdo\UserService;
use youcloud\Services\ViaPdo\Bdd;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig as Twig;

class ControllerAuthRegister{
    protected $userService;

    public function __construct($userService) {
        $bdd = new Bdd();
        $this->userService = new UserService($bdd);
    }

    public function doIt(Request $request, Response $response): Response{
        $data = $request->getParsedBody();
        $username = $data['pseudo'];
        $password = $data['password'];


        $success = $this->userService->registerUser($username, $password);
        if ($success) {
            // Redirigez l'utilisateur vers la page de connexion
            $response->getBody()->write('utilisateur créé');
            $_SESSION['isConnected'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['userid'] = $this->userService->getUserId($username);
        } else {
            // Redirigez l'utilisateur vers la page d'inscription avec un message d'erreur
            $response->getBody()->write("Un utilisateur existe déjà");
        }
        return $response;
    }
}