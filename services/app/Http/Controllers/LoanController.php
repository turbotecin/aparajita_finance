<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Transactions;
use App\Models\LoanProducts;
use Illuminate\Http\Request;
use DB;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* $data = DB::table('loans')
                    ->leftjoin('customers','customers.id','=','loans.customer_id')
                    ->leftjoin('transactions as loanTrans','loanTrans.loan_id','=','loans.id')->where('loanTrans.ledger_id','=','1')
                    ->leftjoin('transactions as processingTrans','processingTrans.loan_id','=','loans.id')->where('processingTrans.ledger_id','=','2')
                    ->select('loans.id', 'loans.customer_id', 'loans.disbursement_date', 'customers.name', 'customers.address', 'customers.phone_no', 'loanTrans.amount as loan_amount', 'processingTrans.amount as processing_charge')
                    ->where('loans.loan_category_id', '=', 2)
                    ->get();
        return response()->json([
            'status' => "success", 
            'response' => $data, 
        ], 200); */
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        $customerId = !empty($data['customerId']) ? $data['customerId'] : "";
        $disbursementDate = !empty($data['disbursementDate']) ? $data['disbursementDate'] : "";
        $loanAmount = !empty($data['loanAmount']) ? $data['loanAmount'] : "";
        $loanProcessingCharge = !empty($data['loanProcessingCharge']) ? $data['loanProcessingCharge'] : "";
        $loanAdditionalCharge = !empty($data['loanAdditionalCharge']) ? $data['loanAdditionalCharge'] : "";
        $disbursementAmount = !empty($data['disbursementAmount']) ? $data['disbursementAmount'] : "";
        $loanCategoryId = !empty($data['loanCategoryId']) ? $data['loanCategoryId'] : "";
        $productList = !empty($data['productList']) ? $data['productList'] : "";

        /* return response()->json([
            'status' => "error",
            'msg' => $productList 
        ], 200);
        exit; */

        $Loan = new Loan;
        $Loan->disbursement_date = $disbursementDate;
        $Loan->customer_id = $customerId;
        $Loan->loan_category_id = $loanCategoryId;
        $Loan->status = 1;
        
        if($Loan->save()){
            $loanId = $Loan->id;

            $loanDisbursementEntry = new Transactions;
            $loanDisbursementEntry->date = $disbursementDate;
            $loanDisbursementEntry->customer_id = $customerId;
            $loanDisbursementEntry->loan_id = $loanId;
            $loanDisbursementEntry->ledger_id = 1; // 1 = Loan Disbursement
            $loanDisbursementEntry->amount = $loanAmount;
            $loanDisbursementEntry->status = 1;
            $loanDisbursementEntry->save();

            if(!empty($loanProcessingCharge)){
                $processingChargeEntry = new Transactions;
                $processingChargeEntry->date = $disbursementDate;
                $processingChargeEntry->customer_id = $customerId;
                $processingChargeEntry->loan_id = $loanId;
                $processingChargeEntry->ledger_id = 2; // 2 = Processing Charge
                $processingChargeEntry->amount = $loanProcessingCharge;
                $processingChargeEntry->status = 1;
                $processingChargeEntry->save();
            }

            if(!empty($loanAdditionalCharge)){
                $additionalChargeEntry = new Transactions;
                $additionalChargeEntry->date = $disbursementDate;
                $additionalChargeEntry->customer_id = $customerId;
                $additionalChargeEntry->loan_id = $loanId;
                $additionalChargeEntry->ledger_id = 3; // 3 = Additional Charge
                $additionalChargeEntry->amount = $loanAdditionalCharge;
                $additionalChargeEntry->status = 1;
                $additionalChargeEntry->save();
            }

            if(!empty($productList)){
                foreach ($productList as $key => $value) {
                    $data = array(
                        'loan_id' => $loanId, 
                        'customer_id' => $customerId, 
                        'product_id' => $value['productId'],
                        'product_qty' => $value['productQty'],
                        'product_weight' => $value['productWeight'],
                        'caret_id' => $value['caretId'],
                        'caret_percentage' => $value['caretPercentage'],
                        'product_value' => $value['productValue'],
                    );
                    LoanProducts::insert($data);   
                }
            }
        
            return response()->json([
                'status' => "success",
                'msg' => "Loan created successfully." 
            ], 200);

        }else{
            return response()->json([
                'status' => "error",
                'msg' => "Loan not created successfully." 
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = DB::table('loans')
                    ->leftjoin('customers','customers.id','=','loans.customer_id')
                    ->leftjoin('transactions as loanTrans','loanTrans.loan_id','=','loans.id')->where('loanTrans.ledger_id','=','1')
                    ->leftjoin('transactions as processingTrans','processingTrans.loan_id','=','loans.id')->where('processingTrans.ledger_id','=','2')
                    ->select('loans.id', 'loans.customer_id', 'loans.disbursement_date', 'customers.name', 'customers.address', 'customers.phone_no', 'loanTrans.amount as loan_amount', 'processingTrans.amount as processing_charge')
                    ->where('loans.loan_category_id', '=', $id)
                    ->get();
        return response()->json([
            'status' => "success", 
            'response' => $data, 
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function edit(Loan $loan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loan $loan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loan $loan)
    {
        //
    }
}
