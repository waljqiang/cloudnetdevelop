<?php
namespace Modules\Home\Services;

use App\Services\BaseService;
use Carbon\Carbon;
use Modules\Home\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

class UserService extends BaseService{
	const ENCRYPTKEY = 'cloudnetlotdevelop';
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

	//申请成为开发者
	public function develop($user,$params){
		$rs = $this->userRepository->save([
			"name" => array_get($params,"name"),
			"idcard" => array_get($params,"idcard"),
			"enterprise" => array_get($params,"enterprise"),
			"enterprise_des" => array_get($params,"enterprise_des"),
			"enterprisecode" => array_get($params,"enterprise_code"),
			"updated_at" => Carbon::now()->timestamp
		],["id" => $user->id]);
		if(!$rs){
			throw new \Exception("Failure",config("exceptions.MYSQL_EXEC_ERROR"));
		}
		return [];
	}

}