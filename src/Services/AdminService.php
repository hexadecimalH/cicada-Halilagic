<?php

namespace Halilagic\Services;

use Halilagic\Models\Project;
use Halilagic\Models\ProjectPic;
use Halilagic\Services\ImageStorageService;
use Symfony\Component\Config\Definition\Exception\Exception;

class AdminService
{
    public $imageStorageService;

    public function __construct($imageStorageService)
    {
        /** @var ImageStorageService imageStorageService */
        $this->imageStorageService = $imageStorageService;
    }

    public function getProjects()
    {
        /** @var Project[] $projects */
        $projects = Project::find('all', ['include' => ['project_pics']]);

        $projects = array_map(function ($el) {
            return $el->serialize();
        }, $projects);
        return $projects;
    }

    public function uploadTemporaryImages($id = null, $images)
    {

        $urls = $this->imageStorageService->storeImages("temp", $images);
        $imagesObjects = []; 
        foreach ($urls as $url) {
            $pic = ProjectPic::create([
                'project_id' => $id,
                'url' => $url,
                'type' => 'normal',
                'data_title' => 'Interior Design']);
            $imagesObjects[] = $pic->to_array();
        }

        return $imagesObjects;
    }

    public function storeProjectImages($projectId, $images)
    {
        $project = Project::first(['conditions' => ['id = ?', $projectId]]);
        $title = strtolower(preg_replace('/\s+/', '', $project->title));
        $urls = $this->imageStorageService->storeImages($title, $images);
        $images = [];
        foreach ($urls as $url) {
            $images[] = ProjectPic::create(['project_id' => $projectId,
                'url' => $url,
                'type' => 'normal',
                'data_title' => 'Interior Design',
                'data_light_box' => $project->title]);
        }
        return $images;
    }

    public function deletePicture($pictureId)
    {
        $picture = ProjectPic::find($pictureId);
        $this->imageStorageService->removeContent($picture->url);
        $picture->delete();
    }

    public function deleteImageById($id){
        /** @var ProjectPic $picture */
        $picture = ProjectPic::find($id);
        try{
            $this->imageStorageService->removeContent($picture->url);
            $picture->delete();
            return true;
        } catch (Exception $e){
            return false;
        }
    }

    public function deleteImageByUrl($url)
    {
        /** @var ProjectPic $picture */
        $picture = ProjectPic::find("first", ["conditions" => ["url LIKE ?", $url]]);
        try{
            $this->imageStorageService->removeContent($picture->url);
            $picture->delete();
            return true;
        } catch (Exception $e){
            return false;
        }
    }

    public function cancelImageUpload($images)
    {
        foreach ($images as $image) {
            $img = json_decode($image);
            $this->deletePicture($img->id);
        }
    }

    public function updateAboutSection($id, $text, $language)
    {
        $project = Project::find($id);
        if ($language == "en") {
            $project->aboutenglish = $text;
        } else {
            $project->about = $text;
        }

        $project->save();
    }

    public function createNewProject($title, $about, $aboutSrb)
    {
        $project = Project::create(['title' => $title, 'about' => $aboutSrb, 'aboutenglish' => $about]);

        return $project->serialize();
    }

    public function assignProjectPictures($id, $uploadedPics){
        $pictures = [];
        foreach($uploadedPics as $picId){
            /** @var ProjectPic $picture */
            $picture = ProjectPic::find($picId);
            $picture->project_id = $id;
            $picture->save();
            $pictures[] = $picture->serialize();
        }
        return $pictures;
    }

    public function deleteProject($projectId)
    {
        $project = Project::find($projectId);
        $pictures = ProjectPic::all(['conditions' => ['project_id', $project->id]]);
        foreach ($pictures as $picture) {
            $this->imageStorageService->removeContent($picture->url);
            $picture->delete();
        }
        $project->delete();
    }

    public function makeThumb($id)
    {
        $picture = ProjectPic::find($id);
        $proj = Project::find($picture->project_id);
        $title = str_replace(" ", "", $proj->title);
        $resizedImagePath = $this->imageStorageService->makeThumb(strtolower($title), $picture);
        $this->imageStorageService->removeContent($picture->url);
        $picture->delete();

        $thumbImage = ProjectPic::create(['project_id' => $proj->id,
            'url' => $resizedImagePath,
            'type' => 'thumb',
            'data_title' => 'Interior Design',
            'data_light_box' => $proj->title]);
        return $thumbImage;
    }

    public function updateExistingProject($id, $title, $about, $aboutSrb){
        /** @var Project $project */
        $project = Project::find($id);
        try{
            $project->title = $title;
            $project->about = $aboutSrb;
            $project->aboutenglish = $about;
            $project->save();
            return true;
        } catch (Exception $e){
            return false;
        }
    }

}