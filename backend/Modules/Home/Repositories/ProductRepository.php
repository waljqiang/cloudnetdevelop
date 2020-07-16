<?php
namespace Modules\Home\Repositories;

use App\Repositories\BaseRepository;
use Modules\Home\Entities\Product;

class AclRepository extends BaseRepository{
	public function model(){
		return Product::class;
	}

}