<?php

namespace Halilagic\Services;

use Halilagic\Models\User;
use Halilagic\Models\Project;
use Symfony\Component\HttpFoundation\Session\Session;

class LogInService
{
    public function __construct()
    {
    }
    public function validateCredentials($username, $password){
        $user = User::find('all', ['conditions' => ['username LIKE ? AND password_salt LIKE ?', $username, $password ]]);
        return $user;
    }

    public function getProjects(){
        /** @var Project[] $projects **/
        $projects = Project::find('all', ['include' => ['project_pics']]);
        $projectSerialized = [];
        foreach($projects as $project){
            $projectSerialized[] = $project->serialize();
        }
        return $projectSerialized;
    }
}