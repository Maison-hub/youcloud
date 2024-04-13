<?php

namespace youcloud\Controllers\Upload;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\UploadedFile;
use Slim\Views\Twig as Twig;

use youcloud\Services\ViaPdo\UploadService;
use youcloud\Services\ViaPdo\Bdd;

class ControllerUpload{
    protected $uploadService;

    public function __construct($uploadService) {
        $bdd = new Bdd();
        $this-> uploadService = new UploadService($bdd);
    }

    public function doIt(Request $request, Response $response): Response{

        $uploadedFiles = $request->getUploadedFiles();
        $data = $request->getParsedBody();

        $title = $data['title'] ? $data['title'] : 'titre';
        $description = $data['description'] ? $data['description'] : 'une description';

        if (!empty($uploadedFiles['file'])) {
            $uploadedFile = $uploadedFiles['file'];
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {

                $path_parts = \pathinfo($uploadedFile->getClientFilename());
                $extension = $path_parts['extension'];
                $type = \EXTTOTYPE[$extension];
                $directory = __DIR__ . '/../../../storage'; 
                $filename = $this->moveUploadedFile($directory, $uploadedFile);
                $isConnected = $_SESSION['isConnected'] ? true : false;
                if($isConnected && isset($_SESSION['userid'])){
                    $userId = $_SESSION['userid'];
                    $location = $filename;
                    $originalName = $uploadedFile->getClientFilename();
                    $this->uploadService->addFile($userId, $title, $description, $location, $type, $originalName);
                    $response->getBody()->write('fichier ajouté');
                    return $response->withHeader('Location', '/dashboard')->withStatus(302);
                }
            } else {
                $response->getBody()->write('erreur');
            }
        }
        return $response;
        
    }
    function moveUploadedFile(string $directory, UploadedFile $uploadedFile)
    {
        $basename = bin2hex(random_bytes(8));
        $basename = $basename.'_'.$uploadedFile->getClientFilename();
        $filename =  $basename;
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);


        // pas besoin car extension deja dans $uploadedFile->getClientFilename()
        //$extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        //$filename = sprintf('%s.%0.8s', $basename, $extension);
        return $filename;
    }
}  