<?php

namespace Halilagic\Controllers;

use Halilagic\Application;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class MainController
{
    /** @var \Twig_Environment $twig */
    private $twig;
    private $mainService;

    public function __construct($twig, $mainService)
    {
        $this->twig = $twig;
        $this->mainService = $mainService;
    }

    public function index(){
        $projectPics = $this->mainService->getProjectPics();
        return $this->twig->render('index.twig', ['page' => 'index', 'pictures' => $projectPics ]);
    }

    public function projects(){
        $projects = $this->mainService->getProjects();
        return $this->twig->render('projects.twig',['page' => 'projects', 'projects' => $projects]);
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