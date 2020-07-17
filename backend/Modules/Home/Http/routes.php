<?php
//注册
Route::group(["middleware" => ["throttle:60,1","handle-response"],"namespace" => "Modules\Home\Http\Controllers"],function(){
	Route::post("user/register","UserController@register")->middleware("hash-encode:uid");//注册用户
	Route::post("auth/token","AuthController@getToken")->name("login");//获取access_token
});

//认证相关
Route::group(["prefix" => "auth","middleware" => ["cloudnetlot","auth:cloudnetlot"],"namespace" => "Modules\Home\Http\Controllers"],function(){
	Route::post("token/refresh","AuthController@refreshToken");//刷新access_token
	Route::get("token/destroy","AuthController@destroyToken");//销毁token
});

//
/*Route::group(["middleware" => ["throttle:60,1","handle-response"],"namespace" => "Modules\Home\Http\Controllers"],function(){
	Route::post("client/info","ClientController@getClient");//获取客户端信息
});*/

