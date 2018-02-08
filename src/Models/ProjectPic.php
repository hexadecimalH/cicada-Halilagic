<?php

namespace Halilagic\Models;

use ActiveRecord\Model;

class ProjectPic extends Model
{
    static $table_name = 'project_pics';
    static $belongs_to = array(
        [
            'projects',
            'class_name' => 'Project'
        ]
    );

    public function serialize(){
        return $this->to_array();
    } 
}