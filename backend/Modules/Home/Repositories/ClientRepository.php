<?php
namespace Modules\Home\Repositories;

use App\Repositories\BaseRepository;
use Modules\Home\Entities\Client;

class ClientRepository extends BaseRepository{
	public function model(){
		return Client::class;
	}

}