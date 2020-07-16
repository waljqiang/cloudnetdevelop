<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Providers;
use Tymon\JWTAuth\Providers\LaravelServiceProvider;
use App\Utils\JWT\JWTGuard;

class JWTAuthServiceProvider extends LaravelServiceProvider{
	/**
	 * 功能描述：rewrite paremtn method:extendAuthGuard.
	 * @author waljqiang
	 * @date   2020-01-03
	 * @return
	 */
    protected function extendAuthGuard(){
        $this->app['auth']->extend('jwt', function ($app, $name, array $config) {
            $guard = new JWTGuard(
                $app['tymon.jwt'],
                $app['auth']->createUserProvider($config['provider']),
                $app['request']
            );

            $app->refresh('request', $guard, 'setRequest');

            return $guard;
        });
    }
}
