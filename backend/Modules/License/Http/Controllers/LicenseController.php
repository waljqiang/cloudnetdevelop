<?php

namespace Modules\License\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\License\Http\Requests\LicenseRequest;
use Modules\License\Services\LicenseService;

class LicenseController extends Controller{
    private $licenseService;
    
    public function __construct(LicenseService $licenseService){
        $this->licenseService = $licenseService;
    }

    /**
     * 功能描述：生成license
     * @author waljqiang
     * @date   2019-12-31
     * @param  LicenseRequest $request [description]
     * @return [type]                  [description]
     */
    public function generate(LicenseRequest $request){
        return $this->licenseService->generate($request->all());
    }

}
