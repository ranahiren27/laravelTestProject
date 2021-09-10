<?php

namespace App\Helpers;

class FileUploader{

    public function __construct(){
        
    }

    public static function base64ImageUpload($path, $tempImageName, $tempImage, $isThumb = false)
    {
        $newPath = $path;
        $img = substr($tempImage, strpos($tempImage, ",")+1);
        $ext = pathinfo($tempImageName, PATHINFO_EXTENSION);
        $fileName = mt_rand().time() . '.' . $ext;
        $path .= $fileName;
        file_put_contents($path, base64_decode($img));

        // For main Image 1000 * 1000
        // self::mainImageSize($newPath, $fileName);

        // For medium Image 400 * 300
       // self::mediumImageSize($newPath, $fileName);

        // For small Image 80 * 50
        // self::smallImageSize($newPath, $fileName);

        return $fileName;
    }

    public static function base64ImageUploadStorage($path, $tempImageName, $tempImage, $isThumb = false)
    {
        $newPath = $path;
        $img = substr($tempImage, strpos($tempImage, ",")+1);
        $ext = pathinfo($tempImageName, PATHINFO_EXTENSION);
        $fileName = mt_rand().time() . '.' . $ext;
        $full_path = $path . $fileName;
        \Storage::put($full_path,base64_decode($img));

      //  file_put_contents($path, base64_decode($img));

        // For main Image 1000 * 1000
        // self::mainImageSize($newPath, $fileName);

        // For medium Image 400 * 300
       // self::mediumImageSize($newPath, $fileName);

        // For small Image 80 * 50
        // self::smallImageSize($newPath, $fileName);

        return $fileName;
    }

    public static function propertyImage($tempImageName, $tempImage){
        $propertyImagePath = public_path() . "/uploads/property/";
        //self::resizeImage($propertyImagePath,$tempImageName,$tempImage,$image);
        return self::base64ImageUpload($propertyImagePath,$tempImageName,$tempImage);
    }

    public static function expanceImage($path,$tempImageName, $tempImage){
        $expanceImagePath = public_path() . "/uploads/expance/";
        return self::base64ImageUpload($expanceImagePath,$tempImageName,$tempImage);
    }
    
    public static function expanceImage1($path,$tempImageName, $tempImage){
        return self::base64ImageUploadStorage($path,$tempImageName,$tempImage);
    }

    public static function roomImage($tempImageName, $tempImage){
        $roomImagePath = public_path() . "/uploads/room/";
        return self::base64ImageUpload($roomImagePath,$tempImageName,$tempImage);
    }

    public static function voucherImage($tempImageName, $tempImage){
        $path = public_path() . "/uploads/voucher/";
        if (!file_exists($path)) {
            mkdir(public_path() . "/uploads/voucher/");
        }
        $voucherImagePath = public_path() . "/uploads/voucher/";
        return self::base64ImageUpload($voucherImagePath,$tempImageName,$tempImage);
    }

    public static function mainImageSize($path, $fileName){
        // Save files in origional ratio
        $mainImagePath = $path.$fileName;
        $img = \Image::make($mainImagePath)->resize(1000, 1000);
        $img->save($path.$fileName);    
    }

    public static function mediumImageSize($path, $fileName){
        // Save files in Medium ratio
        $mediumImagePath = $path.$fileName;
        $img = \Image::make($mediumImagePath)->resize(400, 300);
        if (!file_exists($path.'/medium')) {
            mkdir($path.'/medium', 0777, true);
        }
        $img->save($path.'/medium/medium-'.$fileName);
    }

      public static function deleteImage($image, $path)
        {
            if (!empty($image) && \Storage::exists($path . $image)) {
                \Storage::delete($path . $image);
            }
            if (!empty($image) && \Storage::exists($path . '/thumb/' . $image)) {
                \Storage::delete($path . '/thumb/' . $image);
            }
            return true;
        }
    public static function smallImageSize($path, $fileName){
        // Save files in small ratio
        $smallImagePath = $path.$fileName;
        $img = \Image::make($smallImagePath)->resize(80, 50);
        if (!file_exists($path.'/small')) {
            mkdir($path.'/small', 0777, true);
        }
        $img->save($path.'/small/small-'.$fileName);   
    }
}
