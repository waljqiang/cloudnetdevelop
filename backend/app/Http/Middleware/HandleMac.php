<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class HandleMac{

    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next,$beforeOrAfter = 'before',...$keys){
        if($beforeOrAfter == 'before'){
            $request = $this->beforeHandle($request,$keys);
        }

        $response = $next($request);

        if($beforeOrAfter == 'after' && $response instanceof Response){
            $contents = $response->getOriginalContent();
            $contents = $this->afterHandle($contents,$keys);
            $response->setContent($contents);
        }

        return $response;
    }

    private function beforeHandle($request,$keys){
        if(!empty($keys)){
            foreach ($keys as $key) {
                if(isset($request->{$key})){
                    if(is_array($request->{$key})){
                        $macs = [];
                        foreach ($request->{$key} as $value) {
                            if(!preg_match("/^[A-F0-9]{2}(:[A-F0-9]{2}){5}$/",$value)){
                                throw new \Exception('Device mac format error',config('exceptions.MAC_REGEX'));
                            }
                            $macs[] = parseMac($value);
                        }
                        $request->merge([$key => $macs]);
                    }else{
                        $value = $request->{$key};
                        if(!preg_match("/^[A-F0-9]{2}(:[A-F0-9]{2}){5}$/",$value)){
                            throw new \Exception('Device mac format error',config('exceptions.MAC_REGEX'));
                        }
                        $value = parseMac($value);
                        $request->merge([$key => $value]);
                    }   
                }
            }
        }
        return $request;
    }

    private function afterHandle($contents,$keys){
        if($contents instanceof Model){
            $contents = $this->afterHandle($contents->toArray(),$keys);
        }elseif($contents instanceof Collection){
            $contents = $this->afterHandle($contents->toArray(),$keys);
        }elseif(is_array($contents) && !empty($contents)){
            foreach ($contents as $k => $v){
                if(is_string($v)){
                    if(in_array($k,$keys)){
                        $contents[$k] = setMac($v);
                    }
                }else{
                   $contents[$k] =$this->afterHandle($v,$keys); 
                }
            }
        }else{
            
        }
        return $contents;
    }

}
