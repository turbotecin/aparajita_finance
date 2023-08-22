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
    public function index(/* Request $request */)
    {
        /* $param = $request->param;
        $loanCategoryId = $param['loanCategoryId'];
        $data = DB::table('loans')
                    ->leftjoin('customers','customers.id','=','loans.customer_id')
                    ->leftjoin('transactions as loanTrans','loanTrans.loan_id','=','loans.id')->where('loanTrans.ledger_id','=','1')
                    ->leftjoin('transactions as processingTrans','processingTrans.loan_id','=','loans.id')->where('processingTrans.ledger_id','=','2')
                    ->select('loans.id', 'loans.customer_id', 'loans.disbursement_date', 'customers.name', 'customers.address', 'customers.phone_no', 'loanTrans.amount as loan_amount', 'processingTrans.amount as processing_charge')
                    ->where('loans.loan_category_id', '=', $loanCategoryId)
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
        $loanAuctionCharge = !empty($data['loanAuctionCharge']) ? $data['loanAuctionCharge'] : "";
        $disbursementAmount = !empty($data['disbursementAmount']) ? $data['disbursementAmount'] : "";
        $loanCategoryId = !empty($data['loanCategoryId']) ? $data['loanCategoryId'] : "";
        $productList = !empty($data['productList']) ? $data['productList'] : "";

        /* return response()->json([
            'status' => "error",
            'data' => $data, 
            'msg' => $productList 
        ], 200);
        exit; */

        $Loan = new Loan;
        $Loan->disbursement_date = $disbursementDate;
        $Loan->customer_id = $customerId;
        $Loan->loan_category_id = $loanCategoryId;
        $Loan->additional_charge = $loanAdditionalCharge;
        $Loan->auction_charge = $loanAuctionCharge;
        $Loan->status = 1;

        $gold_value = DB::table('gold_price')->select('rate')->first();
        
        if($Loan->save()){
            $loanId = $Loan->id;

            $loanDisbursementEntry = new Transactions;
            $loanDisbursementEntry->date = $disbursementDate;
            $loanDisbursementEntry->customer_id = $customerId;
            $loanDisbursementEntry->loan_id = $loanId;
            $loanDisbursementEntry->loan_category_id = $loanCategoryId;
            $loanDisbursementEntry->ledger_id = 1; // 1 = Loan Disbursement
            $loanDisbursementEntry->amount = $loanAmount;
            $loanDisbursementEntry->status = 1;
            $loanDisbursementEntry->save();

            if(!empty($loanProcessingCharge)){
                $processingChargeEntry = new Transactions;
                $processingChargeEntry->date = $disbursementDate;
                $processingChargeEntry->customer_id = $customerId;
                $processingChargeEntry->loan_id = $loanId;
                $processingChargeEntry->loan_category_id = $loanCategoryId;
                $processingChargeEntry->ledger_id = 2; // 2 = Processing Charge
                $processingChargeEntry->amount = $loanProcessingCharge;
                $processingChargeEntry->status = 1;
                $processingChargeEntry->save();
            }

            /* if(!empty($loanAdditionalCharge)){
                $additionalChargeEntry = new Transactions;
                $additionalChargeEntry->date = $disbursementDate;
                $additionalChargeEntry->customer_id = $customerId;
                $additionalChargeEntry->loan_id = $loanId;
                $loanDisbursementEntry->loan_category_id = $loanCategoryId;
                $additionalChargeEntry->ledger_id = 3; // 3 = Additional Charge
                $additionalChargeEntry->amount = $loanAdditionalCharge;
                $additionalChargeEntry->status = 1;
                $additionalChargeEntry->save();
            } */

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
                        'gold_value' => $gold_value->rate,
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

    public function cashloanprint($loanId){
        $loanDetails = DB::table('loans')
                        ->leftjoin('customers','customers.id','=','loans.customer_id')
                        ->leftjoin('transactions as loanTrans','loanTrans.loan_id','=','loans.id')->where('loanTrans.ledger_id','=','1')
                        ->leftjoin('transactions as processingTrans','processingTrans.loan_id','=','loans.id')->where('processingTrans.ledger_id','=','2')
                        ->select('loans.*', 'customers.name', 'customers.address', 'customers.phone_no', 'loanTrans.amount as loan_amount', 'processingTrans.amount as processing_charge')
                        ->where('loans.id', '=', $loanId)
                        ->first();

        $tenure = 52;
        $rateOfInterest = 56;
        $paymentMode = 'Weekly';
        $loanAmount = $loanDetails->loan_amount;
        $loanProcessingCharge = $loanDetails->processing_charge;
        $loanDisbursedAmount = $loanAmount - $loanProcessingCharge;
        $loanDisburseDate = $loanDetails->disbursement_date;
        $installment = ($loanAmount * 3)/100;
        $interestRate_unknown = 11.034;

        $data = [];
        $opening = $loanAmount;
        for($i=$tenure; $i>=1; $i--){
            $nextDate = date( "d-m-Y", strtotime( "$loanDisburseDate +1 week" ) );
            $yearlyInterest = ($opening * $rateOfInterest) / 100;
            $interestRate_weekly = $i / $interestRate_unknown;

            $payable_interest = ($yearlyInterest * $interestRate_weekly)/100;
            $payable_principle = $installment - $payable_interest;
            $balance = $opening - $payable_principle;
            $data[] = array(
                // 'opening' => $opening,
                // 'roi' => $rateOfInterest,
                // 'yearlyInterest' => $yearlyInterest,
                'date' => $nextDate,
                'installment' => number_format($installment,2),
                'interest' => number_format($payable_interest,2),
                'principle' => number_format($payable_principle,2),
                'balance' => number_format($balance,2)
            );
            $opening = $balance;
            $loanDisburseDate = $nextDate;
        }
        return response()->json([
            'status' => "success", 
            'response' => array(
                'loanDetails' => array(
                    'loanId' => $loanDetails->id,
                    'loanDisburseDate' => date( "d-m-Y", strtotime($loanDisburseDate)),
                    'customerId' => $loanDetails->customer_id,
                    'customerName' => $loanDetails->name,
                    'customerAddress' => $loanDetails->address,
                    'customerPhoneNo' => $loanDetails->phone_no,
                    'loanAmount' => number_format($loanAmount,2),
                    'loanProcessingCharge' => number_format($loanProcessingCharge,2),
                    'loanDisbursedAmount' => number_format($loanDisbursedAmount,2),
                    'tenure' => $tenure,
                    'paymentMode' => $paymentMode,
                    'roi' => $rateOfInterest
                ),
                // 'loanDetails' => $loanDetails,
                'installmentDetails' => $data
            ), 
        ], 200);
    }

    public function goldloanprint($loanId){
        $loanDetails = DB::table('loans')
                        ->leftjoin('customers','customers.id','=','loans.customer_id')
                        ->leftjoin('transactions as loanTrans','loanTrans.loan_id','=','loans.id')->where('loanTrans.ledger_id','=','1')
                        ->leftjoin('transactions as processingTrans','processingTrans.loan_id','=','loans.id')->where('processingTrans.ledger_id','=','2')
                        ->select('loans.*', 'customers.name', 'customers.address', 'customers.phone_no', 'loanTrans.amount as loan_amount', 'processingTrans.amount as processing_charge')
                        ->where('loans.id', '=', $loanId)
                        ->first();

        // $loanAmount = 10000;
        $principalRecovery = 5000;
        $tenure = 18; // in months
        $paymentMode = 'Monthly';
        $rateOfInterest = 3; // in percentage

        $loanAmount = $loanDetails->loan_amount;
        $loanInterestAmount = ($loanAmount * $rateOfInterest)/100;
        $loanProcessingCharge = $loanDetails->processing_charge;
        $loanDisbursedAmount = $loanAmount - $loanProcessingCharge;
        $loanAdditionalCharge = $loanDetails->additional_charge;
        $loanAuctionCharge = $loanDetails->auction_charge;
        $loanDisburseDate = $loanDetails->disbursement_date;

        $data = [];
        $opening = $loanAmount;
        $nextDate = $loanDisburseDate;
        for($i=0; $i<$tenure; $i++){
            $date = date( "d-m-Y", strtotime( "$nextDate +1 month" ) );
            // $opening = $loanAmount;
            $interest = ($opening * $rateOfInterest)/100;
            $emi = $principalRecovery + $interest;
            $closing = $opening - $principalRecovery;
            
            $data[] = array(
                'date' => date( "M-Y", strtotime( "$nextDate +1 month" ) ),
                // 'opening' => $opening,
                'principal' => number_format($principalRecovery,2),
                'interest' => number_format($interest,2),
                'installment' => number_format($emi,2),
                'balance' => number_format($closing,2)
            );
            $opening = $closing;
            $nextDate = $date;
        }

        
        $loanProductDetails = DB::table('loan_products')
                        ->leftjoin('products','products.id','=','loan_products.product_id')
                        ->leftjoin('carets','carets.id','=','loan_products.caret_id')
                        ->select('loan_products.*', 'products.name as product_name', 'carets.name as caret_name')
                        ->where('loan_products.loan_id', '=', $loanId)
                        ->get();

        $gold_value = DB::table('gold_price')->select('rate')->first();

        return response()->json([
            'status' => "success", 
            'response' => array(
                'loanDetails' => array(
                    'loanId' => $loanDetails->id,
                    'loanDisburseDate' => date( "d-m-Y", strtotime($loanDisburseDate)),
                    'customerId' => $loanDetails->customer_id,
                    'customerName' => $loanDetails->name,
                    'customerAddress' => $loanDetails->address,
                    'customerPhoneNo' => $loanDetails->phone_no,
                    'loanAmount' => number_format($loanAmount,2),
                    'loanInterestAmount' => number_format($loanInterestAmount,2),
                    'loanProcessingCharge' => number_format($loanProcessingCharge,2),
                    'loanDisbursedAmount' => number_format($loanDisbursedAmount,2),
                    'loanAdditionalCharge' => number_format($loanAdditionalCharge,2),
                    'loanAuctionCharge' => number_format($loanAuctionCharge,2),
                    'principalRecovery' => number_format($principalRecovery,2),
                    'tenure' => $tenure,
                    'paymentMode' => $paymentMode,
                    'roi' => $rateOfInterest
                ),
                'installmentDetails' => $data,
                'loanProductDetails' => $loanProductDetails,
                // 'gold_value' => $gold_value->rate
            ), 
        ], 200);
    }
}
