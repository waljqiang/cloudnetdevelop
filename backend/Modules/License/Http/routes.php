<?php
Route::group(['middleware' => ['api'],'prefix' => 'license','namespace' => 'Modules\License\Http\Controllers'],function(){
    Route::post('generate','LicenseController@generate');//生成license
});
