<?php
namespace Modules\License\Repositories;

use App\Repositories\BaseRepository;
use Modules\License\Entities\License;

class LicenseRepository extends BaseRepository{

	public function model(){
		return License::class;
	}

	public function addLicense($data){
		return $this->makeModel()->insertGetId($data);
	}

	public function save($data,$condition){
		return $this->makeModel()->where($condition)->update($data);
	}

}