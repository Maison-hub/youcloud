<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use Slim\Routing\RouteCollectorProxy;

use Twig\Extension\AbstractExtension;  
use Twig\Extension\GlobalsInterface;



//Middleware
use youcloud\Middleware\Auth\AuthMiddleware;

// Twig
use Slim\Views\Twig as Twig;
use Slim\Views\TwigMiddleware as TwigMiddleware;

require __DIR__ . '/vendor/autoload.php';

include_once 'src/config/configfile.php';

include_once 'src/config/bddconfig.php';

session_start();

if (!isset($_SESSION['isConnected'])) {
    $_SESSION['isConnected'] = false;
}

$app = AppFactory::create();


// Create Twig
$twig = Twig::create(__DIR__.'/templates', ['cache' => false, 'debug' => true, 'strict_variables' => true]);
$twig->offsetSet('isConnected', $_SESSION['isConnected']);


// Add Twig-View Middleware
$app->add(TwigMiddleware::create($app, $twig));

// Add CORS Middleware
$app->add(function (Request $request, RequestHandler $handler){
    $response = $handler->handle($request);
    return $response->withHeader('Access-control-allow-origin', '*');
});

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("YouCloud");
    return $response;
});



$app->get('/debug/info', \SlimDemo\Controllers\Debug\Info::class.':doIt');

$app->get('/formulaire', \SlimDemo\Controllers\LivreOr\Formulaire::class.':doIt')->setName('message');


//page de login
$app->get('/login', \youcloud\Controllers\Auth\ControllerAuthLoginform::class.':doIt')->setName('login');
//send login data
$app->post('/login-data', \youcloud\Controllers\Auth\ControllerAuthLogin::class.':doIt')->setName('login-data');
// page d inscription
$app->get('/register', \youcloud\Controllers\Auth\ControllerAuthRegisterform::class.':doIt')->setName('register');
//send register data
$app->post('/register-data', \youcloud\Controllers\Auth\ControllerAuthRegister::class.':doIt')->setName('register-data');

//logout
$app->get('/logout', \youcloud\Controllers\Auth\ControllerAuthLogout::class.':doIt')->setName('logout');

$app->group('', function (RouteCollectorProxy $group) {

    $group->get('/test', \youcloud\Controllers\Debug\Info::class.':doIt')->setName('auth-test');

    //upload file
    $group->get('/upload', \youcloud\Controllers\Upload\ControllerUploadform::class.':doIt')->setName('upload');

    $group->post('/upload-data', \youcloud\Controllers\Upload\ControllerUpload::class.':doIt')->setName('upload-data');

    //page de dashboard
    $group->get('/dashboard', \youcloud\Controllers\Dashboard\ControllerDashboard::class.':doIt')->setName('dashboard');

    //delete file
    $group->get('/delete/{id}', \youcloud\Controllers\File\ControllerDeletefile::class.':doIt')->setName('delete');

    //getfile
    $group->get('/getfile/{id}', \youcloud\Controllers\File\ControllerGetfile::class.':doIt')->setName('getfile');


})->add(new AuthMiddleware());

//simple route return the valuer of session variable isConnected
$app->get('/testno', function (Request $request, Response $response, $args) {
    $response->getBody()->write($_SESSION['isConnected'] ? 'true' : 'false');
    return $response;
});

$app->post('/new-message', \SlimDemo\Controllers\LivreOr\NewMessage::class.':doIt')->setName('new-message');

$app->run();