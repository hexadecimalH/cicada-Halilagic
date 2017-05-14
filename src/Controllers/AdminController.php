<?php

namespace Halilagic\Controllers;

use Halilagic\Application;
use Halilagic\Services\AdminService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminController
{
    /** @var AdminService $adminService **/
    private $adminService;

    public function __construct($adminService){
        $this->adminService = $adminService;
    }
    public function deletePicture(Application $app, Request $request, $pictureId){
        $this->adminService->deletePicture($pictureId);

        return new Response();
    }
    public function uploadPictures(Application $app, Request $request, $projectId){
         $images = $request->files->all();
         $images = $this->adminService->storeProjectImages($projectId, $images);
         $serializedImages = [];
         foreach($images as $image){
             $serializedImages[] = $image->serialize();
         }
         return new JsonResponse($serializedImages);
    }

    public function cancelUploads(Application $app, Request $request){
        $images = $request->request->all();
        $this->adminService->cancelImageUpload($images);
    }

    public function updateProjectAbout(Application $app, Request $request, $projectId, $language){
        $text = $request->request->get('text');
        $this->adminService->updateAboutSection($projectId, $text, $language);
    }

    public function createProject(Application $app, Request $request){
        $title = $request->request->get('title');
        $about = $request->request->get('about');

        $project = $this->adminService->createNewProject($title, $about);
        return new JsonResponse($project);
    }

    public function deleteProject(Application $app, Request $request, $projectId){
        $this->adminService->deleteProject($projectId);
        
    }

    public function makeThumbnail(Application $app, Request $request, $pictureId){
        $image = $this->adminService->makeThumb($pictureId);
        var_dump($image);die();
    }


}