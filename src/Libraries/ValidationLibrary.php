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
        'title', 'eng', 'srb', 'uploadedPics'
    ];


    public function __construct()
    {
        parent::__construct();
    }

    public function userValidator($request){

        $dataArray = $request->request->all();
        $val = $this->withData($dataArray);
        $val = $this->addUserRules($val);

        return $val->validate();
    }

    private function addUserRules($validation){
        return $validation->rule("required", ValidationLibrary::PROJECT_RULE)
                            ->message("'{field} is required'");
    }

}