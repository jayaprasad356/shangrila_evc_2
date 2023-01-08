<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Admin', 'as' => 'admin.'], function () {
    Route::get('lang/{locale}', 'LanguageController@lang')->name('lang');

    /*authentication*/
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('/code/captcha/{tmp}', 'LoginController@captcha')->name('default-captcha');
        Route::get('login', 'LoginController@login')->name('login');
        Route::post('login', 'LoginController@submit')->middleware('actch');
        Route::get('logout', 'LoginController@logout')->name('logout');
    });
    /*authentication*/

   

    Route::group(['middleware' => ['admin']], function () {
        Route::get('/fcm/{id}', 'DashboardController@fcm')->name('dashboard');     //test route
        Route::get('/', 'DashboardController@dashboard')->name('dashboard');
        Route::get('settings', 'SystemController@settings')->name('settings');

   
    });

        /*users*/
        Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => ['module:client_management']], function () {
            Route::get('list', 'UserController@list')->name('list');
            Route::post('search', 'ClientController@search')->name('search');
        });
        
       /*---endusers---*/

        /*settings*/
        Route::group(['prefix' => 'settings', 'as' => 'settings.', 'middleware' => ['module:client_management']], function () {
          
            Route::get('edit', 'SettingsController@edit')->name('edit');
            Route::post('update', 'SettingsController@update')->name('update');
        });
        
       /*---endsettings---*/


         /*evc_codes*/
         Route::group(['prefix' => 'codes', 'as' => 'codes.', 'middleware' => ['module:client_management']], function () {
            Route::get('add', 'CodeController@index')->name('add');
            Route::post('store', 'CodeController@store')->name('store');
            Route::get('list', 'CodeController@list')->name('list');
            Route::delete('delete/{id}', 'CodeController@delete')->name('delete');
            Route::post('search', 'CodeController@search')->name('search');
        });

        /*---endevc_codes---*/
        

        /*users*/
        Route::group(['prefix' => 'bills', 'as' => 'bills.', 'middleware' => ['module:client_management']], function () {
            Route::get('list', 'BillController@list')->name('list');
            Route::post('search', 'BillController@search')->name('search');
        });
        
        /*---endusers---*/
});

