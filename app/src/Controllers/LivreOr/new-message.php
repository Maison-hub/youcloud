<?php

namespace SlimDemo\Controllers\LivreOr;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig as Twig;

use SlimDemo\Services\newMessage as newMessageService;

class NewMessage{
    public function doIt(Request $request, Response $response): Response{
        /* Filtrer les données */

        $pseudo = trim($_POST['pseudo']??'');
        $message = trim($_POST['message']?? '');

        $erreur=[]
        $info = [];

        $info["message"] = $message;
        $info["pseudo"] = $pseudo;

        if ($message === ''){
            $erreur['message'] = "Le message ne peut pas être vide";
        }
        if ($pseudo === ''){
            $erreur['pseudo'] = "Le pseudo ne peut pas être vide";
        }

        if(count($erreur) >0){
            return $view->render($response, 'formulaire.html', ['erreur' => $erreur, 'info' => $info]);
        }else{
            return
        }


        $view = Twig::fromRequest($request);
        return $view->render($response, 'formulaire.html', );
    }
}