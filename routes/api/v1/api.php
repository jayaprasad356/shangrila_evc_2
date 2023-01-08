<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Api\V1', 'middleware' => 'localization'], function () {



    //shangrilla web api's

    Route::group(['prefix' => 'settings'], function () {
        Route::get('/', 'SettingController@get_settings');
    });

    Route::post('/signup', 'ApiController@Signup');
    Route::post('login','AuthController@login');
    Route::post('/recharge','ApiController@Recharge');
    Route::post('/calculatebill','ApiController@Calculatebill');
    Route::post('/paybill','ApiController@Paybill');



});


