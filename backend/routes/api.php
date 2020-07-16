<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::post('/client/refreshsecret',[
    	'uses' => 'ClientController@refreshSecret',
    	'middleware' => ["hash-decode:client_id","hash-encode:client_id"]
    ]);*/
Route::group(['middleware' => ['api'],'prefix' => 'system'],function(){
	Route::get('captcha','SystemController@getCaptcha');//获取验证码
	Route::post('captcha','SystemController@checkCaptcha');//校验验证码
	Route::get('countrycode','SystemController@getCountryCode');//获取国家区域码
});


