<?php

require "vendor/autoload.php";

use Halilagic\Application;
use Cicada\Routing\RouteCollection;
use Halilagic\Middleware\Authentication;
use Halilagic\Controllers\MainController;
use Halilagic\Controllers\LogInController;
use Halilagic\Controllers\AdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

//var_dump("hello");die();
// $app = new Application($_SERVER['HOME'], 'main', getProtocol().'://'.$_SERVER['HTTP_HOST'])
$app = new Application($_SERVER['HOME'], $_SERVER['HTTP_HOST'], getProtocol().'://');

// Controllers
$adminController = new AdminController($app['adminService'], $app['validationLibrary']);
// var_dump("hello");die();
$mainController = new MainController($app['twig'], $app['mainService']);
$logInController = new LogInController($app['twig'], $app['logInService']);


/** @var RouteCollection $adminRouteCollection */
$adminRouteCollection = $app['collection_factory'];

/** @var RouteCollection $mainRouteCollection */
$mainRouteCollection = $app['collection_factory'];

/** @var RouteCollection $logInRouteCollection */
$logInRouteCollection = $app['collection_factory'];


$adminRouteCollection->post('/upload-pictures',             [$adminController, 'uploadPictures'])->prefix('/admin');
$adminRouteCollection->get('/projects',                     [$adminController, 'getProjects'])->prefix('/admin');
$adminRouteCollection->post('/createproject',               [$adminController, 'createProject'])->prefix('/admin');
$adminRouteCollection->post('/delete-picture/{id}',         [$adminController, 'deletePicture'])->prefix('/admin');
$adminRouteCollection->post('/update-project',              [$adminController, 'updateProject'])->prefix('/admin');
//Log In Controller routes
$logInRouteCollection->get('/login',         [$logInController, 'logIn']);
$logInRouteCollection->post('/login',        [$logInController, 'checkCredentials']);


//Main Controller routes
$mainRouteCollection->get('/projects',                  [ $mainController, 'projects']);
$mainRouteCollection->get('/projects/{language}',       [ $mainController, 'projectsBegin']);
$mainRouteCollection->get('/',                          [ $mainController, 'indexBegin']);
$mainRouteCollection->get('/{language}',                [ $mainController, 'index']);
$mainRouteCollection->post('/mail',                     [ $mainController, 'sendMail']);


//Admin Controller routes
// $adminRouteCollection->post('/upload/{projectId}',                      [$adminController, 'uploadPictures']);

// $adminRouteCollection->post('/cancel',                                  [$adminController, 'cancelUploads']);
// $adminRouteCollection->post('/project',                                 [$adminController, 'createProject']);
// $adminRouteCollection->post('/project/{projectId}',                     [$adminController, 'deleteProject']);
// $adminRouteCollection->post('/make-thumbnail/{pictureId}',              [$adminController, 'makeThumbnail']);



//var_dump("hello");die();
$app->addRouteCollection($logInRouteCollection);

$app->addRouteCollection($adminRouteCollection);
$app->addRouteCollection($mainRouteCollection);
$app->exception(function(Exception $e, Request $request) {
    $msg ="Something went wrong. The incident has been logged and our code monkeys are on it.";
    return new Response($msg, Response::HTTP_INTERNAL_SERVER_ERROR);
});


$app->run();