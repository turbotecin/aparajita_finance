<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customers::all();
        return response()->json([
            'status' => "success", 
            'response' => $customers, 
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->inputData;
        $customerName = !empty($data['customerName']) ? $data['customerName'] : "";
        $customerAddress = !empty($data['customerAddress']) ? $data['customerAddress'] : "";
        $customerPhoneNo = !empty($data['customerPhoneNo']) ? $data['customerPhoneNo'] : "";
        $customerAadhar = !empty($data['customerAadhar']) ? $data['customerAadhar'] : "";
        $customerPan = !empty($data['customerPan']) ? $data['customerPan'] : "";
        $customerVotarNo = !empty($data['customerVotarNo']) ? $data['customerVotarNo'] : "";

        /* return response()->json([
            'error' => false, 
            'Customers' => $data['customerName'], 
        ], 200); */
        /* $validation = Validator::make($request->all(), [
            'name' => 'required', 
            'address' => 'required', 
            'phone_no' => 'required',
        ]);

        if ($validation->fails())
        {
            return response()->json([
                    'error' => true, 
                    'messages' => $validation->errors(), 
                ], 200);
        } 
        else
        { */
            $Customers = new Customers;
            $Customers->name = $customerName;
            $Customers->address = $customerAddress;
            $Customers->phone_no = $customerPhoneNo;
            $Customers->aadhar_no = $customerAadhar;
            $Customers->pan_no = $customerPan;
            $Customers->votar_card_no = $customerVotarNo;
            $Customers->status = 1;
            $Customers->save();
            return response()->json([
                'status' => "success",
                'msg' => "Customer added successfully." 
            ], 200);
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customers  $customers
     * @return \Illuminate\Http\Response
     */
    public function show($data)
    {
        $customer = Customers::where('phone_no',$data)->orWhere('aadhar_no',$data)->orWhere('pan_no',$data)->first();
        
        $status = is_null($customer) ? "error" : "success";
 
        return response()->json([
            'status' => $status,
            'response'  => $customer,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customers  $customers
     * @return \Illuminate\Http\Response
     */
    public function edit(Customers $customers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customers  $customers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customers $customers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customers  $customers
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customers $customers)
    {
        //
    }
}
