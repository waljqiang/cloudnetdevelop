<?php
//云平台接口
Route::group(["middleware" => ["throttle:60,1","handle-response"],"namespace" => "Modules\Home\Http\Controllers"],function(){
	Route::post("user/register","UserController@register")->middleware("hash-encode:uid");//注册用户
/*	Route::post("user/password/sendmail","UserController@sendPasswordMail");//发送找回密码邮件
	Route::post("user/password/checkmail","UserController@checkPasswordMail");//校验找回密码邮件链接的有效性
	Route::post("user/password/reset","UserController@resetPassword");//重设密码*/
});
//云平台接口
Route::group(["middleware" => ["throttle:60,1","handle-response"],"namespace" => "Modules\Home\Http\Controllers"],function(){
	Route::post("client/info","ClientController@getClient");//获取客户端信息
});

