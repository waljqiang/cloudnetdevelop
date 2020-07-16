<?php
namespace Modules\License\Services;

use App\Services\BaseService;
use Carbon\Carbon;
use App\Utils\Signature;
use Modules\License\Repositories\LicenseRepository;
use Illuminate\Support\Facades\Storage;

class LicenseService extends BaseService{
	private $licenseRepository;

	public function __construct(LicenseRepository $licenseRepository){
		$this->licenseRepository = $licenseRepository;
	}

	/**
	 * 功能描述：生成license
	 * @author waljqiang
	 * @date   2019-12-31
	 * @param  array     $params 参数数组
	 * @return
	 */
	public function generate($params){
		$company = array_get($params,'company');
		$domain = array_get($params,'domain');
		$expireIn = array_get($params,'expire_in');

		$time = Carbon::now();

		if(!($systemLicense = $this->licenseRepository->getInfos([['company_name',$company]],[],['*'],true))){
			if(empty($domain) || empty($expireIn)){
				throw new \Exception('domain or expire_in is empty',config('exceptions.PARAMS_INVALID'));
			}
			$domain = json_encode($domain);
			$expireIn = $time->copy()->addDays($expireIn)->timestamp;
			$license = Signature::encrypto(json_encode(['company' => $company,'domain' => $domain,'expireIn' => $expireIn]));
			$id = $this->licenseRepository->addLicense([
				'company_name' => $company,
				'domain' => $domain,
				'license' => $license,
				'expire_in' => $expireIn,
				'created_at' => $time->copy()->timestamp,
				'updated_at' => $time->copy()->timestamp
			]);
		}else{
			$domain = json_encode($domain);
			$expireIn = $time->copy()->addDays($expireIn)->timestamp;
			$license = Signature::encrypto(json_encode(['company' => $systemLicense->company_name,'domain' => $domain,'expireIn' => $expireIn]));
			$rs = $this->licenseRepository->save([
				'domain' => $domain,
				'expire_in' => $expireIn,
				'license' => $license,
				'updated_at' => $time->copy()->timestamp
			],['id' => $systemLicense->id]);
			$id = $rs ? $systemLicense->id : false;
		}

		if(!$id){
			throw new \Exception('Generate license failure',config('exceptions.MYSQL_EXEC_ERROR'));
		}

		if(!Storage::disk('public')->put('license.txt',$license,'private')){
			throw new \Exception('Generate license file failure',config('exceptions.FILE_PUT_ERROR'));
		}
		return ['file' => storage_path('app/public/license.txt')];
	}
}