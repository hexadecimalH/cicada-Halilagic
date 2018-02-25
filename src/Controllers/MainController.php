<?php

namespace Halilagic\Controllers;

use Halilagic\Application;
use Halilagic\Services\MainService;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class MainController
{
    /** @var \Twig_Environment $twig */
    private $twig;
    /** @var MainService $mainService */
    private $mainService;

    public function __construct($twig, $mainService)
    {
        $this->twig = $twig;
        $this->mainService = $mainService;
    }

    public function getProjectPictures(Application $app, Request $request){
        $projectPics = $this->mainService->getProjectPics();
        return new JsonResponse($projectPics);
    }

    public function index(Application $app, Request $request, $language){
        $lang = ($language == "") ? 'en' : $language;
        $projectPics = $this->mainService->getProjectPics();
        return $this->twig->render('index.twig', ['page' => 'index', 'pictures' => $projectPics, 'language' => $lang]);
    }

    public function indexBegin(Application $app, Request $request){

        $projectPics = $this->mainService->getProjectPics();
        return $this->twig->render('index.twig', ['page' => 'index', 'pictures' => $projectPics, 'language' => 'en']);
    }

    public function projects(Application $app, Request $request, $language){
        $projects = $this->mainService->getProjects();
//        var_dump($projects);die();
        return $this->twig->render('projects.twig',['page' => 'projects', 'projects' => $projects]);
    }

    public function projectsBegin(Application $app, Request $request, $language){
        $projects = $this->mainService->getProjects();
//        var_dump($projects);die();
        return $this->twig->render('projects.twig',['page' => 'projects', 'projects' => $projects,'language' => $language ]);
    }

    public function sendMail(Application $app, Request $request){
        $subject = $request->request->get('subject');
        $content = $request->request->get('content');
        $clientMail = $request->request->get('senderEmail');
        $clientName = $request->request->get('name');
        
        $isSent = $this->mainService->sendMail($clientMail, $clientName, $subject, $content);

        return new Response($isSent);
    }
}