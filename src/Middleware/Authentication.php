<?php
namespace Halilagic\Middleware;

use Halilagic\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class Authentication
{

    public function __construct(){

    }

    public static function authenticate(Application $app, Request $request){
        /** @var Session $session **/
        $session = $request->getSession();
        
        if($request->getMethod() == 'POST'){
            var_dump($session);die();
            var_dump($session->get('user'));die();
        }
        // var_dump($request->getPathInfo(), $request->getMethod());die();
    }

    protected function storeUserSession($request){

    } 


}