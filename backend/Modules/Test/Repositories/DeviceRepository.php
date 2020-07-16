<?php
namespace Modules\Test\Repositories;

use App\Repositories\BaseRepository;
use Modules\Test\Entities\Device;

class DeviceRepository extends BaseRepository{

	public function model(){
		return Device::class;
	}

}