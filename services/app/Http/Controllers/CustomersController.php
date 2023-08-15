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
            $Customers->name = $data['customerName'];
            $Customers->address = $data['customerAddress'];
            $Customers->phone_no = $data['customerPhoneNo'];
            $Customers->aadhar_no = $data['customerAadhar'];
            $Customers->pan_no = $data['customerPan'];
            $Customers->votar_card_no = $data['customerVotarNo'];
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
