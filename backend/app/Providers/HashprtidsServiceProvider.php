<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Utils\Hashids;

class HashprtidsServiceProvider extends ServiceProvider{
    /**
     * 服务提供者加是否延迟加载.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(){
        $this->app->singleton('Hashprtids', function ($app) {
            return new Hashids(config('hashids.salt'),20,config('hashids.alphabet'),'prt',config('hashids.enable'));
        });
    }

     /**
     * 获取由提供者提供的服务.
     *
     * @return array
     */
    public function provides(){
        return ['Hashprtids'];
    }
}
