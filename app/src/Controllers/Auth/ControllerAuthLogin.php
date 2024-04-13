<?php

namespace youcloud\Controllers\Auth;

use youcloud\Services\ViaPdo\UserService;
use youcloud\Services\ViaPdo\Bdd;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig as Twig;

class ControllerAuthLogin{
    protected $userService;

    public function __construct($userService) {
        $bdd = new Bdd();
        $this->userService = new UserService($bdd);
    }

    public function doIt(Request $request, Response $response): Response{

        $data = $request->getParsedBody();
        $username = $data['pseudo'];
        $password = $data['password'];

        $success = $this->userService->verifyUser($username, $password);
        if ($success) {

            $_SESSION['isConnected'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['userid'] = $this->userService->getUserId($username);
            return $response->withHeader('Location', '/dashboard')->withStatus(302);
        } else {
            // Redirigez l'utilisateur vers la page d'inscription avec un message d'erreur
            $response->getBody()->write("Mauvais identifiant");
        }
        return $response;

    }
}