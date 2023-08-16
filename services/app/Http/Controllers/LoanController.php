<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Transactions;
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
        /* $loans = Loan::all();
        return response()->json([
            'status' => "success", 
            'response' => $loans, 
        ], 200); */

        $data = DB::table('loans')
                    ->leftjoin('customers','customers.id','=','loans.customer_id')
                    ->leftjoin('transactions as loanTrans','loanTrans.loan_id','=','loans.id')->where('loanTrans.ledger_id','=','1')
                    ->leftjoin('transactions as processingTrans','processingTrans.loan_id','=','loans.id')->where('processingTrans.ledger_id','=','2')
                    ->select('loans.id', 'loans.customer_id', 'loans.disbursement_date', 'customers.name', 'customers.address', 'customers.phone_no', 'loanTrans.amount as loan_amount', 'processingTrans.amount as processing_charge')
                    ->where('loans.loan_category_id', '=', 2)
                    ->get();
        return response()->json([
            'status' => "success", 
            'response' => $data, 
        ], 200);
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

        $customerId = $data['customerId'];
        $disbursementDate = $data['disbursementDate'];
        $loanAmount = $data['loanAmount'];
        $loanProcessingCharge = $data['loanProcessingCharge'];
        $disbursementAmount = $data['disbursementAmount'];
        $loanCategoryId = $data['loanCategoryId'];

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

            $processingChargeEntry = new Transactions;
            $processingChargeEntry->date = $disbursementDate;
            $processingChargeEntry->customer_id = $customerId;
            $processingChargeEntry->loan_id = $loanId;
            $processingChargeEntry->ledger_id = 2; // 2 = Processing Charge
            $processingChargeEntry->amount = $loanProcessingCharge;
            $processingChargeEntry->status = 1;
            $processingChargeEntry->save();
        
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
    public function show(Loan $loan)
    {
        //
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
