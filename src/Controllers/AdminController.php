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
    public $validationLibrary;

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
        $id = $request->request->get("project_id");
        $imageObjects = $this->adminService->uploadTemporaryImages($id, $images);

        return new JsonResponse($imageObjects);
    }

    public function createProject(Application $app, Request $request){
        $isValid = $this->validationLibrary->projectValidator($request);

        if($isValid->validate()){
            $title = $request->request->get('title');
            $about = $request->request->get('aboutenglish');//eng
            $aboutSrb = $request->request->get('about');// srb
            $uploadedPics = explode(",", $request->request->get('project_pics')); 
            /** @var Project $project */
            $project = $this->adminService->createNewProject($title, $about, $aboutSrb);
            $pictures = $this->adminService->assignProjectPictures($project['id'], $uploadedPics);
            $project['project_pics'] = $pictures;

            return new JsonResponse($project);
        }


        $errors = $isValid->errors();
        return new JsonResponse($errors, JsonResponse::HTTP_EXPECTATION_FAILED);

    }

    public function deletePicture(Application $app, Request $request, $id){
        $response = $this->adminService->deleteImageById($id);

        $status = $response ? Response::HTTP_ACCEPTED : Response::HTTP_CONFLICT;
        return new JsonResponse([], $status);
    }

    public function cancelUploads(Application $app, Request $request){
        $images = $request->request->all();
        $this->adminService->cancelImageUpload($images);
    }

    public function updateProject(Application $app, Request $request){

        $isValid = $this->validationLibrary->projectValidator($request);

        if( $isValid->validate()){
            $id = $request->request->get('id');
            $title = $request->request->get('title');
            $about = $request->request->get('aboutenglish');//eng
            $aboutSrb = $request->request->get('about');// srb
            $success = $this->adminService->updateExistingProject($id, $title, $about, $aboutSrb);
            $status = $success ? Response::HTTP_ACCEPTED : Response::HTTP_CONFLICT;
            return new JsonResponse([], $status);
        }

        $errors = $isValid->errors();
        return new JsonResponse($errors, JsonResponse::HTTP_EXPECTATION_FAILED);
    }



    public function deleteProject(Application $app, Request $request, $projectId){
        $this->adminService->deleteProject($projectId);
        
    }

    public function makeThumbnail(Application $app, Request $request, $pictureId){
        $image = $this->adminService->makeThumb($pictureId);
        var_dump($image);die();
    }


}