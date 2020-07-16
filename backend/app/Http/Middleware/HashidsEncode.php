<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HashidsEncode{

    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next,$keys = 'id'){
        $response = $next($request);
        if($response instanceof Response){
            $content = $response->getOriginalContent();
            $keys = explode("#",$keys);
            if(!empty($keys)){
                $hashids = app('Hashids');
                foreach ($keys as $key) {
                    if(isset($content[$key])){
                        $content[$key] = $hashids->encodeHash($content[$key]);
                    }
                }
            }
            $response->setContent($content);
        }
        return $response;
    }

}
