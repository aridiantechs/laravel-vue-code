<?php
namespace App\Helpers;

class AppHelper{

    // Add Orginal Suffix 
    public static function addOrdinalNumberSuffix($num) {
        if (!in_array(($num % 100),array(11,12,13))){
          switch ($num % 10) {
            // Handle 1st, 2nd, 3rd
            case 1:  return $num.'st';
            case 2:  return $num.'nd';
            case 3:  return $num.'rd';
          }
        }
        return $num.'th';
    }

    // File upload 
    public static function fileUpload($file,$options = array(),$disk = 'public',$path = 'uploads',$prefix = 'file') {
         if($file){

            // options
            $options['name'] = $options['name'] ?? "";
            $options['delete'] = $options['delete'] ?? false;
            $options['delete_file'] = $options['delete_file'] ?? "";
            $options['delete_remove_path'] = $options['delete_remove_path'] ?? "";
            // options

    
            if(!($options['name'])){
                $options['name'] = $prefix.'-'.time().'.'.$file->getClientOriginalExtension();
            }else{
                $options['name'] = $options['name'].$file->getClientOriginalExtension();
            }
            $path = \Illuminate\Support\Facades\Storage::disk($disk)->putFileAs($path, $file, $options['name']);

            if($path){

                if(($options['delete'] == true) && $options['delete_file']){
                    // Delete File if delete option set to TRUE
                    self::deleteUpload($options['delete_file'],$disk,$options['delete_remove_path']);
                }

                if($disk == 'public'){
                    return '/storage/'.$path;
                }
                return $path;
            }else{
                return null;
            }
         }
    }

    // Delete File 
    public static function deleteUpload($filename,$disk = 'public',$remove_path = '') {
         if($filename){
            if($remove_path){
                $filename = str_replace($remove_path,'',$filename);
            }
            return \Illuminate\Support\Facades\Storage::disk($disk)->delete($filename);
         }
    }
}