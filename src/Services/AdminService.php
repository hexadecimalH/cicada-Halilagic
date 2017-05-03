<?php

namespace Halilagic\Services;

use Halilagic\Models\User;
use Halilagic\Models\Project;
use Halilagic\Models\ProjectPic;

class AdminService
{
    public $imageStorageService;

    public function __construct($imageStorageService)
    {
        $this->imageStorageService = $imageStorageService; 
    }

    public function storeProjectImages($projectId, $images){
        $project = Project::first(['conditions' => ['id = ?', $projectId]]);
        $title = strtolower(preg_replace('/\s+/', '', $project->title));
        $urls = $this->imageStorageService->storeImages($title, $images);
        $images = [];
        foreach($urls as $url){
            $images[] = ProjectPic::create(['project_id'=> $projectId,
                                'url' => $url,
                                'type'=> 'normal',
                                'data_title' => 'Interior Design',
                                'data_light_box' => $project->title]);
        }
        return $images;
    }

    public function deletePicture($pictureId){
        $picture = ProjectPic::find($pictureId);
        $this->imageStorageService->removeContent($picture->url);
        $picture->delete();
    }

    public function cancelImageUpload($images){
        foreach($images as $image){
            $img = json_decode($image);
            $this->deletePicture($img->id);
        }
    }

    public function updateAboutSection($id, $text){
        $project = Project::find($id);
        $project->about = $text;
        $project->save();
    }

    public function createNewProject($title, $about){
        $project = Project::create(['title' => $title, 'about' => $about]);
        
        return $project->serialize();
    }

    public function deleteProject($projectId){
        $project = Project::find($projectId);
        // ProjectPic::find('')
        $project->delete();
    }

}