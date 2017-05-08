<?php

namespace Halilagic\Controllers;

use Halilagic\Application;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;

class LogInController
{
    /** @var \Twig_Environment $twig */
    private $twig;
    /** @var LogInService $logInService */
    private $logInService;

    public function __construct($twig, $logInService)
    {
        $this->twig = $twig;
        $this->logInService = $logInService;
    }

    public function logIn(){
        return $this->twig->render('log-in.twig', ['page' => 'login', 'message' => '']);
    }

    public function checkCredentials(Application $app, Request $request){
        $username = $request->request->get('username');
        $password = $request->request->get('password');
        
        $user = $this->logInService->validateCredentials($username, $password);
        
        if(!empty($user)){
            $projects = $this->logInService->getProjects();
            foreach($projects as &$project){
                $project['has_thumb'] = false;
                foreach($project['project_pics'] as $pic ){
                    if($pic['type'] == 'thumb'){
                        $project['has_thumb'] = true;
                    }
                }
            }
            /** @var $session Session **/
            $session = new Session();
            $session->start();
            $session->set('user',$user);
            $session->save();
            return $this->twig->render('admin-pannel.twig', ['page' => 'admin-pannel', 'projects' => $projects]);
        }

        return $this->twig->render('log-in.twig', ['page' => 'login', 'message' => 'Sorry ! You have entered wrong credentials' ]);
    }

    
}