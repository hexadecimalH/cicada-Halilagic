<?php

namespace Halilagic\Services;

use PHPMailer;
use Halilagic\Models\Project;
use Halilagic\Models\ProjectPic;

class MainService
{
    /** @var PHPmailer $mail**/
    private $mail;

    public function __construct(){
        $this->mail = new PHPMailer(true);
        $this->mail->SMTPDebug = 5;
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'contactformhcg@gmail.com';
        $this->mail->Password = 'ahmet.halilagic';
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Port = 587;
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

    public function getProjectPics(){
        /** @var ProjectPic $pictures **/
        $projectPics = ProjectPic::find('all');
        $pictures = [];
        foreach($projectPics as $picture){
            if($picture->type == 'normal')
            $pictures[] = $picture->serialize();
        }
        return $pictures;
    }

    public function sendMail($clientMail, $clientName, $subject, $content){
        $this->mail->setFrom($clientMail, $clientName);
        $this->mail->addAddress('zenovicharis@live.com');     // Add a recipient
        $this->mail->addReplyTo($clientMail, $clientName);
        $this->mail->CharSet = 'UTF-8';

        $this->mail->isHTML(true);                                  // Set email format to HTML
        $mailContent = '<p style="text-align:center">'.htmlentities($content).'</p><br/><p> This mail has been sent from hcg.rs contact form</p>';
        $this->mail->Subject = $subject;
        $this->mail->Body    = $mailContent;
        $this->mail->AltBody = htmlentities($content);
        

         if(!$this->mail->Send()) {
             var_dump($this->mail->ErrorInfo);die();
            return false;
         }
         return true;
    }
}