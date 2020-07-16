<?php
namespace Modules\Home\Repositories;

use App\Repositories\BaseRepository;
use Modules\Home\Entities\Acl;

class AclRepository extends BaseRepository{
	public function model(){
		return Acl::class;
	}

}