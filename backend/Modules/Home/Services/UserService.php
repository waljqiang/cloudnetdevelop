<?php
namespace Modules\Home\Services;

use App\Services\BaseService;
use Carbon\Carbon;
use Modules\Home\Repositories\UserRepository;
use Modules\Home\Repositories\UserCacheRepository;
use Modules\Home\Repositories\WorkgroupRepository;
use Mail;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class UserService extends BaseService{
	const ENCRYPTKEY = 'cloudnetlot';
	private $userRepository;

	public function __construct(UserRepository $userRepository){
		$this->userRepository = $userRepository;
	}

	//注册用户
	public function register($params){
		$time = Carbon::now()->timestamp;
		$data = [
			"username" => array_get($params,"username"),
			"password" => bcrypt(array_get($params,"password")),
			"mq_password" => array_get($params,"password"),
			"email" => array_get($params,"email"),
			"phonecode" => array_get($params,"phonecode"),
			"phone" => array_get($params,"phone"),
			"created_at" => $time,
			"updated_at" => $time
		];
		$user = $this->userRepository->makeModel()->create($data);
		return ["uid" => $user->id];
	}

}