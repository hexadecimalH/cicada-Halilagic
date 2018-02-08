<?php
namespace Halilagic\Libraries;


use \Gumlet\ImageResize;

class ImageManipulationLibrary
{
    public function __construct()
    {

    }

    public function resize($binaryContents){
        $image = ImageResize::createFromString($binaryContents);
        $image->resizeToWidth(200);
        $resizedBinaryContents = $image->getImageAsString();
        return $resizedBinaryContents;
    }

    public function resizeImage($binaryContents){
        $image = ImageResize::createFromString($binaryContents);
        $image->resizeToWidth(900);
        $resizedBinaryContents = $image->getImageAsString();

        return $resizedBinaryContents;
    }
    public function resizeThumbnail($binaryContents){
        $image = ImageResize::createFromString($binaryContents);
        $image->resizeToWidth(300);
        $resizedBinaryContents = $image->getImageAsString();
        return [
            'width' => $image->getDestWidth(),
            'height' => $image->getDestHeight(),
            'content' => $resizedBinaryContents
        ];
    }
}