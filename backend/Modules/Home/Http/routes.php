<?php
//云平台接口
Route::group(["middleware" => ["throttle:60,1","handle-response"],"namespace" => "Modules\Home\Http\Controllers"],function(){
	Route::post("client/info","ClientController@getClient");
});

