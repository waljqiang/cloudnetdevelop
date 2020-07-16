<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HashidsDecode{

    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next,$keys = 'id'){
        $keys = explode("#",$keys);
        if(!empty($keys)){
            foreach ($keys as $key) {
                if(isset($request->{$key})){
                    $value = $this->decode($request->{$key});
                    $value && $request->merge([$key => $value]);
                }
            }
        }
        
        return $response = $next($request);
    }

    private function decode($datas){
        $res = "";
        $hashids = app('Hashids');
        if(is_array($datas)){
            foreach ($datas as $data) {
                $value = $hashids->decodeHash($data);
                if(!empty($value)){
                    $res[] = count($value) == 1 ? $value[0] : $value;
                }
            }
        }else{
            $value = $hashids->decodeHash($datas);
            if(!empty($value)){
                $res = count($value) == 1 ? $value[0] : $value;
            }
        }
        return $res;
    }

}
