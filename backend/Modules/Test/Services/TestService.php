<?php
namespace Modules\Test\Services;

use App\Services\BaseService;
use Modules\Test\Repositories\DeviceRepository;

class TestService extends BaseService{
	private $deviceRepository;
	private $messageCacheRepository;

	public function __construct(DeviceRepository $deviceRepository){
		$this->deviceRepository = $deviceRepository;
	}

	public function getDevices($user,$condition = [], $with = [], $columns = ['*'], $unique = false){
		array_push($condition,['user_id',$user->id]);
		return $this->deviceRepository->getInfos($condition,$with,$columns,$unique);
	}
}