<?php
namespace App\Services;

use App\Services\BaseService;
use App\Repositories\SystemCacheRepository;
use App\Repositories\CountryCodeRepository;
use Illuminate\Support\Facades\DB;

class SystemService extends BaseService{
	private $systemCacheRepository;
	private $countryCodeRepository;

	public function __construct(SystemCacheRepository $systemCacheRepository,CountryCodeRepository $countryCodeRepository){
		$this->systemCacheRepository = $systemCacheRepository;
		$this->countryCodeRepository = $countryCodeRepository;
	}

	public function getCaptcha(){
		$captcha = app("Captcha");
		$code = $captcha->entry();
		$this->systemCacheRepository->setCaptcha($code,config("captcha.expire"));
		return $captcha->getImage();
	}

	public function checkCaptcha($params){
		$code = array_get($params,"code");
		$captcha = app("Captcha");
		$key = $captcha->authCode($code);
		$sysCode = $this->systemCacheRepository->getCaptcha($key);
		if(!$captcha->check($sysCode,$code)){
			throw new \Exception("The captcha is invalid",config("exceptions.CAPTCHA_INVALID"));
		}
		$this->systemCacheRepository->deleteCaptcha($key);
		return [];
	}

	public function getCountryCode($params){
		$datas = $this->countryCodeRepository->getInfos();
		return [
			"total" => $datas->count(),
			"list" => $datas
		];
	}

}