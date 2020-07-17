<?php
Route::group(['middleware' => ['cloudnetlotdevelop'],'prefix' => 'test','namespace' => 'Modules\Test\Http\Controllers'],function(){
    Route::post('testmac',[
    	'uses' => 'TestController@testMac',
    	'middleware' => ['handle-mac:before,mac,macs','handle-mac:after,dev_mac,macs']
    ]);
    Route::get('testlimit','TestController@testLimit')->middleware('limit:100,300');
});
