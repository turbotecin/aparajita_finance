<?php

namespace App\Http\Controllers;

use App\Models\Transactions;

use Illuminate\Http\Request;

use App\Http\Controllers\DashboardController;

class CommonController extends Controller
{
    public function list(Request $request){
        $data = $request->param;
        $fromDate = !empty($data['fromDate']) ? $data['fromDate'] : "";
        $toDate = !empty($data['toDate']) ? $data['toDate'] : "";

        //EXPENSES SIDE
        $loanDisbursement = "Loan Disbursement";

        // INCOMES SIDE
        $processingCharge = "Processing Charge";

        // PRODUCTS
        $goldLoan = "Gold Loan";
        $cashLoan = "Cash Loan";


        return response()->json([
            'status' => "success", 
            'response' => array(
                'from' => $fromDate,
                'to' => $toDate,
                'expenses' => array(
                    'loan_disbursement' => array(
                        'gold_loan' => (new DashboardController)->get_total_amount($goldLoan, $loanDisbursement),
                        'cash_loan' => (new DashboardController)->get_total_amount($cashLoan, $loanDisbursement),
                        'total' => (new DashboardController)->get_total_amount('', $loanDisbursement)
                    ),
                ),
                'incomes' => array(
                    'processing_charge' => array(
                        'gold_loan' => (new DashboardController)->get_total_amount($goldLoan, $processingCharge),
                        'cash_loan' => (new DashboardController)->get_total_amount($cashLoan, $processingCharge),
                        'total' => (new DashboardController)->get_total_amount('', $processingCharge)
                    ),
                ),
            ), 
        ], 200);
    }
}
