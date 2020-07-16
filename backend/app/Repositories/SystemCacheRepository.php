<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Redis;

class SystemCacheRepository{
	const SYSTEM_CAPTCHA = 'system:captcha:';//验证码存储地址
	const SYSTEM_QUEQUE_CLIENTIP = 'system:queue:clientip';

	public function setCaptcha($code,$ttl = null){
		return !empty($ttl) ? Redis::setex(self::SYSTEM_CAPTCHA . $code['code'],$ttl,serialize($code)) : Redis::set(self::SYSTEM_CAPTCHA . $code['code'],serialize($code));
	}

	public function getCaptcha($code){
		return unserialize(Redis::get(self::SYSTEM_CAPTCHA . $code));
	}

	public function deleteCaptcha($key){
		return Redis::del(self::SYSTEM_CAPTCHA . $key);
	}

	public function pushClientInfo($data){
		return Redis::lpush(self::SYSTEM_QUEQUE_CLIENTIP,json_encode($data));
	}
}