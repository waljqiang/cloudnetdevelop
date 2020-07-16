<?php
namespace Modules\License\Entities;

use App\Models\Model;

class License extends Model{
	protected $table = 'system_license';
	protected $primaryKey = 'id';

}