<?php

if (!function_exists('api_response')) {
    /**
     * // API Response
     *
     * @param $condition
     * @param $value
     * @return null
     */
    function api_response($data,$status = 200,$message = "",$errors = [] )
    {   
        return response()->json([    
            'status' => $status,
            'msg' => $message ?? "",
            'err' => !empty($errors) ? $errors : (object)[],
            'data' => $data,
        ]);
    }
}

if (!function_exists('format_error')) {
    
    function format_error($validator)
    {   
        $errors = [];
        foreach ($validator->errors()->getMessages() as $key => $value){
            $errors[$key] = $value[0];
        }
        return api_response((object)[],400,'Fields are Missing',$errors);
    }
}


if (!function_exists('successResponse')) {
    
    function successResponse($msg)
    {   
        return api_response((object)[],200,$msg);
    }
}

if (!function_exists('errorResponse')) {
    
    function errorResponse($msg)
    {   
        return api_response((object)[],400,$msg);
    }
}


if (!function_exists('validateData')) {
    
    function validateData($request,$data,$msg = array())
    {   
        $vali = \Validator::make($request->all(),$data,$msg);
        $data_fields = [];
        foreach ($data as $key => $value) {
            $data_fields[] = $key;
        }
        return [
            'validator' => $vali,
            'fields' => $data_fields
        ];

    }
}