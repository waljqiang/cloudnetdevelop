<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Utils\ValidatorExtend;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ValidatorExtend::adds();
        if(env("APP_DEBUG")){
            DB::listen(function ($query) {
                $tmp = str_replace('?', '"'.'%s'.'"', $query->sql);
                $qBindings = [];
                foreach ($query->bindings as $key => $value) {
                    if (is_numeric($key)) {
                        $qBindings[] = $value;
                    } else {
                        $tmp = str_replace(':'.$key, '"'.$value.'"', $tmp);
                    }
                }
                $tmp = vsprintf($tmp, $qBindings);
                $tmp = str_replace("\\", "", $tmp);
                logger('[sql][' . $tmp . ']execute time: ' . $query->time . 'ms');
            });
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }
}
