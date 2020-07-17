<?php
namespace App\Helpers;

class DBManage{

	public static function store($model,$request,$fields,$modified_fields = array()){

		$model_db = new $model;
		foreach($fields as $field){
			$model_db->$field = $request->$field;
		}

		foreach($modified_fields as $field => $value){
			$model_db->$field = $value;
		}

		$model_db->save();
		return $model_db;
	}
 
	// Insert Multiple
	// public static function store($model,$fields_group,$fields,$modified_fields = array()){

	// 	$model_db = new $model;
	// 	$data_all = [];
	 
	// 	foreach($fields_group as $item){
	// 		$data = [];
	// 		foreach ($item as $key => $value) {
	// 			 if(in_array($key,$fields)){
	// 			 	$data[$key] = $value;
	// 			 }
	// 		}
	// 		$data_all[] = $data;
	// 	}

	// 	foreach($modified_fields as $field => $value){
	// 		$model_db->$field = $value;
	// 	}

	// 	$model_db->save();
	// 	return $model_db;
	// }
	// Insert Multiple


}