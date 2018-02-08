<?php

namespace Halilagic\Controllers;

use Halilagic\Application;
use Halilagic\Models\Project;
use Halilagic\Services\AdminService;
use Halilagic\Libraries\ValidationLibrary;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminController
{
    /** @var AdminService $adminService **/
    private $adminService;

    /** @var ValidationLibrary $validationLibrary **/
    private $validationLibrary;

    public function __construct($adminService, $validationLibrary){
        $this->adminService = $adminService;
        $this->validationLibrary = $validationLibrary;

    }

    public function getProjects(Application $app, Request $request){
        $projects = $this->adminService->getProjects();

        return new JsonResponse($projects);
    }

    public function uploadPictures(Application $app, Request $request){
        $images = $request->files->all();
        $imageUrls = $this->adminService->uploadTemporaryImages($images);

        return new JsonResponse($imageUrls);
    }

    public function createProject(Application $app, Request $request){
        $isValid = $this->validationLibrary->userValidator($request);

        if($isValid){
            $title = $request->request->get('title');
            $about = $request->request->get('eng');
            $aboutSrb = $request->request->get('srb');
            $uploadedPics = explode(",", $request->request->get('uploadedPics'));
            /** @var Project $project */
            $project = $this->adminService->createNewProject($title, $about, $aboutSrb);
            $pictures = $this->adminService->assignProjectPictures($project['id'], $uploadedPics);
            $project['project_pics'] = $pictures;
            return new JsonResponse($project);
        }
            return new JsonResponse("Validation Failed",400);

    }

    public function deletePicture(Application $app, Request $request){
        $pictureUrl = $request->request->get("url");
        $response = $this->adminService->deleteImageByUrl($pictureUrl);

        return new JsonResponse([], $response ? 204 : 409);
    }

    public function cancelUploads(Application $app, Request $request){
        $images = $request->request->all();
        $this->adminService->cancelImageUpload($images);
    }

    public function updateProjectAbout(Application $app, Request $request, $projectId, $language){
        $text = $request->request->get('text');
        $this->adminService->updateAboutSection($projectId, $text, $language);
    }



    public function deleteProject(Application $app, Request $request, $projectId){
        $this->adminService->deleteProject($projectId);
        
    }

    public function makeThumbnail(Application $app, Request $request, $pictureId){
        $image = $this->adminService->makeThumb($pictureId);
        var_dump($image);die();
    }


}