<?php

require "vendor/autoload.php";

use Halilagic\Application;
use Cicada\Routing\RouteCollection;
use Halilagic\Middleware\Authentication;
use Halilagic\Controllers\MainController;
use Halilagic\Controllers\LogInController;
use Halilagic\Controllers\AdminController;
use Symfony\Component\HttpFoundation\Request;

function getProtocol()
{
    $isSecure = false;
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
        $isSecure = true;
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
        $isSecure = true;
    }
    return $isSecure ? 'https' : 'http';
}
// $app = new Application($_SERVER['HOME'], 'main', getProtocol().'://'.$_SERVER['HTTP_HOST'])
$app = new Application($_SERVER['HOME'], $_SERVER['HTTP_HOST'], getProtocol().'://');

// Controllers
$adminController = new AdminController($app['adminService']);
$mainController = new MainController($app['twig'], $app['mainService']);
$logInController = new LogInController($app['twig'], $app['logInService']);
/** @var RouteCollection $adminCollection */
$logInCollection = $app['collection_factory'];
$logInCollection->after([Authentication::class,'authenticate']);

//Log In Controller routes
$app->get('/login',         [$logInController, 'logIn']);
$app->post('/login',        [$logInController, 'checkCredentials']);

//Main Controller routes
$app->get('/projects',                  [ $mainController, 'projects']);
$app->get('/projects/{language}',       [ $mainController, 'projectsBegin']);
$app->get('/',                          [ $mainController, 'indexBegin']);
$app->get('/{language}',                [ $mainController, 'index']);
$app->post('/mail',                     [ $mainController, 'sendMail']);

//Admin Controller routes
$app->post('/upload/{projectId}',                      [$adminController, 'uploadPictures']);
$app->post('/delete/{pictureId}',                      [$adminController, 'deletePicture']);
$app->post('/cancel',                                  [$adminController, 'cancelUploads']);
$app->post('/update-about/{projectId}/{language}',     [$adminController, 'updateProjectAbout']);
$app->post('/project',                                 [$adminController, 'createProject']);
$app->post('/project/{projectId}',                     [$adminController, 'deleteProject']);
$app->post('/make-thumbnail/{pictureId}',              [$adminController, 'makeThumbnail']);

// $app->addRouteCollection($logInCollection);

$app->exception(function(Exception $e, Request $request) {
    print_r($e->getMessage());
    throw $e;
});


$app->run();