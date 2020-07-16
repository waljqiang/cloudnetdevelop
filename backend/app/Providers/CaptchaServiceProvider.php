<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Nova\Captcha\Captcha;

class CaptchaServiceProvider extends ServiceProvider
{
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
    public function register()
    {
        $this->app->singleton('Captcha', function ($app) {
            return new Captcha(config('captcha'));
        });
    }

     /**
     * 获取由提供者提供的服务.
     *
     * @return array
     */
    public function provides(){
        return ['Captcha'];
    }
}
