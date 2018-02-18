<?php
/**
 * Created by PhpStorm.
 * User: haris
 * Date: 1.2.18
 * Time: 22:13
 */

namespace Halilagic\Libraries;

use Valitron;

class ValidationLibrary extends Valitron\Validator
{
    const PROJECT_RULE = [
        'title', 'aboutenglish', 'about', 'project_pics'
    ];

    const PROJECT_UPDATE_RULE = [
        'title', 'aboutenglish', 'about'
    ];

    const UPLOAD_URI = "upload";

    public function __construct()
    {
        parent::__construct();
    }

    public function projectValidator($request){
        $uri = $_SERVER['REDIRECT_URL'];
        $dataArray = $request->request->all();
        $val = $this->withData($dataArray);

        
        $val = strpos($uri, ValidationLibrary::UPLOAD_URI) == 0? 
             $this->addProjectUpdateRules($val) : $this->addProjectRules($val);

        return $val;
    }

    private function addProjectRules($validation){
        return $validation->rule("required", ValidationLibrary::PROJECT_RULE)
                            ->message("'{field} is required'");
    }

    private function addProjectUpdateRules($validation){
        return $validation->rule("required", ValidationLibrary::PROJECT_UPDATE_RULE)
                            ->message("'{field} is required'");
    }

}