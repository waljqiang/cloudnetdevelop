<?php
/**
 * Created by PhpStorm.
 * User: emmanuel
 * Date: 17-8-4
 * Time: 下午7:21
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Utils\Signature;
use Carbon\Carbon;

class License{

    protected $except = [

    ];

    public function __construct(){
    	
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     * @throws \Exception
     */
    public function handle(Request $request, Closure $next, $guard = null){
    	if(env('SELF_BUILD')){
            $this->checkLicense($request);
        }
        return $next($request);
    }

    private function checkLicense($request){
        try{
            $license = Storage::disk('public')->get('license.txt');
        }catch(\Exception $e){
            throw new \Exception("No License",config('exceptions.LICENSE_NO'));
        }

        $data = json_decode(Signature::decrypto($license));

        if(!$data){
            throw new \Exception("The license is invalid", config('exceptions.LICENSE_INVALID'));        
        }

        $host = env('APP_HOST');
        $domain = json_decode($data->domain,true);

        if(!$domain || !in_array($host,$domain)){
            throw new \Exception('The license is invalid',config('exceptions.LICENSE_INVALID'));
        }

        if(Carbon::now()->timestamp > $data->expireIn){
            throw new \Exception('The license is expired',config('exceptions.LICENSE_EXPIRE_IN'));
        }
    }

    /**
     * @param Request $request
     * @return bool
     */
    protected function inExceptArray(Request $request){
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return true;
            }
        }
        return false;
    }
}
