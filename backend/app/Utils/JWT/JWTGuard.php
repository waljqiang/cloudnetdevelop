<?php
namespace App\Utils\JWT;
use Tymon\JWTAuth\JWTGuard as Base;

class JWTGuard extends Base{
	/**
	 * 功能描述：重写attempt方法，增加额外验证参数
	 * @author waljqiang
	 * @date   2020-01-03
	 * @param  array      $credentials [description]
	 * @param  boolean    $login       [description]
	 * @return [type]                  [description]
	 */
    public function attempt(array $credentials = [], $login = true,$otherValidate=[]){
        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

        if ($this->hasValidCredentials($user, $credentials,$otherValidate)) {
            return $login ? $this->login($user) : true;
        }

        return false;
    }

    /**
     * 功能描述：重写身份验证方法，增加status、level验证
     * @author waljqiang
     * @date   2020-01-03
     * @param  [type]     $user        [description]
     * @param  [type]     $credentials [description]
     * @return boolean                 [description]
     */
    protected function hasValidCredentials($user, $credentials,$otherValidate = []){
    	if(!($user !== null && $this->provider->validateCredentials($user, $credentials))){
    		throw new \Exception("The username or password is incorrect",config('exceptions.USER_PASSWORD_ERROR'));		
    	}
    	$this->validateOther($user,$credentials,$otherValidate);
    	return true;
    }

    /**
     * 功能描述：验证status及level
     * @author waljqiang
     * @date   2020-01-06
     * @param  [type]     $user          [description]
     * @param  [type]     $credentials   [description]
     * @param  [type]     $otherValidate [description]
     * @return [type]                    [description]
     */
    protected function validateOther($user,$credentials,$otherValidate){
    	if(isset($otherValidate['status']) && $user->status != $otherValidate['status']){
    		throw new \Exception("The status of the user is not allowed",config('exceptions.USER_STATUS_NO_ALLOWED'));
    	}
    	if(isset($otherValidate['level']) && $user->level < $otherValidate['level']){
    		throw new \Exception("User level not allowed",config('exceptions.USER_LEVEL_NO_ALLOWED'));
    	}
    }
}
