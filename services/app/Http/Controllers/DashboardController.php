<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Carets;
use App\Models\Loan;
use App\Models\LoansCategory;
use App\Models\AccountLedger;
use App\Models\Transactions;
use DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function list()
    {
        $response = array();

        $gold_rate = DB::table('gold_price')->select('rate')->get();
        $products = Products::select('id', 'name')->get();
        $carets = Carets::select('id', 'name', 'percentage')->get();

        // $loan_category_id = $this->get_loanCatId('Cash Loan');
        // $ledger_id = $this->get_ledgerId('Loan Disbursement');

        // $gold_loan = Transactions::where('loan_category_id', '=', $loan_category_id->id)->where('ledger_id', '=', $ledger_id->id)->sum('amount');
        /* $amount = Transactions::where([
            ['loan_category_id',$loan_category_id->id],
            ['ledger_id',$ledger_id->id]
        ])->sum('amount'); */

        $response['status'] = 'success';
		$response['msg'] = '';
		$response['response']['gold_rate'] = number_format($gold_rate[0]->rate,2);
		$response['response']['products'] = $products;
		$response['response']['carets'] = $carets;
		// $response['response']['loan_cat'] = $loan_category_id;
		// $response['response']['ledger_id'] = $ledger_id;
		$response['response']['total_amount'] = array();
		$response['response']['total_amount']['cash_loan'] = $this->get_total_amount('Cash Loan', 'Loan Disbursement');
		$response['response']['total_amount']['gold_loan'] = $this->get_total_amount('Gold Loan', 'Loan Disbursement');
		$response['response']['total_amount']['micro_loan'] = 0;
		$response['response']['total_amount']['rd'] = 0;
		$response['response']['total_amount']['daily_savings'] = 0;
        
        return $response;
    }

    public function get_total_amount($loan_cat_name, $ledger_name){
        $loan_category_id = $this->get_loanCatId($loan_cat_name);
        $ledger_id = $this->get_ledgerId($ledger_name);
        $amount = Transactions::where('loan_category_id', '=', $loan_category_id->id)
                            ->where('ledger_id', '=', $ledger_id->id)
                            ->sum('amount');
        return number_format($amount,2);
    }

    public function get_loanCatId($loan_cat_name){
        return LoansCategory::select('id')->where('name', '=', $loan_cat_name)->first();
    }

    public function get_ledgerId($ledger_name){
        return AccountLedger::select('id')->where('name', '=', $ledger_name)->first();
    }
}
