<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Helpers\DBManage;
use Illuminate\Http\Request;
use Validator;
class CustomerController extends Controller
{

	/**
     * Store a new Customers.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {

        $data = validateData($request, [
            'company_name' => 'required|string|max:150',
            'vat_number' => 'nullable|string|max:150',
            'phone' => 'nullable|numeric',
            'website' => 'nullable|string|max:150',

            'address' => 'nullable|string|max:150',
            'city' => 'nullable|string|max:150',
            'state' => 'nullable|string|max:150',
            'zip_code' => 'nullable|numeric',
            'city' => 'nullable|string',
        ]);

        if($data['validator']->fails()){
            return format_error($data['validator']);
        }
        
        try {

            $fields = $data['fields'];

            $modified_fields = [
                'added_by_id' => auth()->user()->id
            ];

            $db = DBManage::store(Customer::class,$request,$fields,$modified_fields);

            if($db){
                return successResponse("Customer Added.");
            }
            
        } catch (\Exception $e) {

            return errorResponse("Failed to add customer!");
        }

    }
    
}
