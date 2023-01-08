<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

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

/*Route::get('/', function () {
return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');*/

Route::get('/', function () {
    return redirect(\route('admin.dashboard'));
});






//senang pay
Route::match(['get', 'post'], '/return-senang-pay', 'SenangPayController@return_senang_pay')->name('return-senang-pay');


/*Route::fallback(function () {
return redirect('/admin/auth/login');
});*/
Route::match(['get', 'post'], '/return-senang-pay', 'SenangPayController@return_senang_pay')->name('return-senang-pay');

Route::get('payment-success', 'PaymentController@success')->name('payment-success');
Route::get('payment-fail', 'PaymentController@fail')->name('payment-fail');

//bkash
Route::group(['prefix'=>'bkash'], function () {
    // Payment Routes for bKash
    Route::post('get-token', 'BkashPaymentController@getToken')->name('bkash-get-token');
    Route::post('create-payment', 'BkashPaymentController@createPayment')->name('bkash-create-payment');
    Route::post('execute-payment', 'BkashPaymentController@executePayment')->name('bkash-execute-payment');
    Route::get('query-payment', 'BkashPaymentController@queryPayment')->name('bkash-query-payment');
    Route::post('success', 'BkashPaymentController@bkashSuccess')->name('bkash-success');

    // Refund Routes for bKash
    Route::get('refund', 'BkashRefundController@index')->name('bkash-refund');
    Route::post('refund', 'BkashRefundController@refund')->name('bkash-refund');
});

// paymob
Route::post('/paymob-credit', 'PaymobController@credit')->name('paymob-credit');
Route::get('/paymob-callback', 'PaymobController@callback')->name('paymob-callback');

// The callback url after a payment
Route::get('mercadopago/home', 'MercadoPagoController@index')->name('mercadopago.index');
Route::post('mercadopago/make-payment', 'MercadoPagoController@make_payment')->name('mercadopago.make_payment');
Route::get('mercadopago/get-user', 'MercadoPagoController@get_test_user')->name('mercadopago.get-user');



Route::match(['get','post'], '/test',[\App\Http\Controllers\SenangPayController::class,'pay'])->name('test');
