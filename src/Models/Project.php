<?php

namespace Halilagic\Models;

use ActiveRecord\Model;

class Project extends Model
{
    static $table_name = 'projects';
    static $has_many = [
        [   
            'project_pics',
            'class_name' => 'ProjectPic'
        ]
    ];

    public function serialize(){
        return $this->to_array([
            'only' => ['id', 'title','about'],
            'include' => [ 'project_pics' => ['only' =>  ['id', 'url', 'type', 'data_light_box', 'data_title']]]
        ]);
    }
}