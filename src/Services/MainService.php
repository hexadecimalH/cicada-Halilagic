<?php

namespace Halilagic\Services;

use PHPMailer;
use Halilagic\Models\Project;
use Halilagic\Models\ProjectPic;

class MainService
{
    /** @var PHPmailer $mail**/
    private $mail;
    private $email;
    private $password;

    public function __construct($credentials){
        $this->email = $credentials[0];
        $this->password = $credentials[1];
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
        $this->mail = new PHPMailer();
        $this->mail->SMTPDebug = 5;
        $this->mail->isSMTP();
        $this->mail->SMTPAuth = true;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Host = 'smtp.gmail.com';

        $this->mail->Username = $this->email;
        $this->mail->Password = $this->password;
        $this->mail->Port = 587;

        $this->mail->setFrom($clientMail, $clientName);
        $this->mail->addReplyTo($clientMail, $clientName);
        $this->mail->CharSet = 'UTF-8';

        $this->mail->isHTML();                                  // Set email format to HTML
        $mailContent = '<p style="text-align:center">'.htmlentities($content).'</p><br/><p> This mail has been sent from hcg.rs contact form</p>';
        $this->mail->Subject = $subject;
        $this->mail->Body    = $mailContent;
        $this->mail->AltBody = htmlentities($content);
        $this->mail->addAddress('info@hcg.rs', "Halilagic Info");     // Add a recipient
        $isSent = $this->mail->Send();

         if(!$isSent) {
            return false;
         }
         return true;
    }
}