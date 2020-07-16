<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\SystemRequest;
use Illuminate\Routing\Controller;
use App\Services\SystemService;

class SystemController extends Controller{
    private $systemService;

    public function __construct(SystemService $systemService){
        $this->systemService = $systemService;
    }

    public function getCaptcha(Request $request){
    	echo $this->systemService->getCaptcha();
    	exit(-1);
    }

    public function checkCaptcha(SystemRequest $request){
    	return $this->systemService->checkCaptcha($request->all());
    }

    public function getCountryCode(Request $request){
        return $this->systemService->getCountryCode($request->all());
    }

}
