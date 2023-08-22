<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\CommonController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/appdata', [DashboardController::class, 'list']);
Route::resource('books', BookController::class);
Route::resource('products', ProductController::class);
Route::resource('customers', CustomersController::class);
Route::resource('loans', LoanController::class);


/* Route::get('loans', [LoanController::class, 'index']);
Route::get('loans/{id}', [LoanController::class, 'show']);
Route::post('loans', [LoanController::class, 'store']);
Route::put('loans/{loanId}', [LoanController::class, 'update']);
Route::delete('loans/{loanId}', [LoanController::class, 'delete']); */
// Route::get('loans/print/{$loanId}', [LoanController::class, 'print']);
/* Route::get('loans/print/{$loanId}', function($loanId) {
    return $loanId;
}); */

Route::get('/cashloanprint/{loanId}', [LoanController::class, 'cashloanprint']);
Route::get('/goldloanprint/{loanId}', [LoanController::class, 'goldloanprint']);
Route::post('/cashflow/', [CommonController::class, 'list']);
