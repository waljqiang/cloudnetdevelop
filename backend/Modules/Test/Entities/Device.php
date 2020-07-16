<?php
namespace Modules\Test\Entities;

use App\Models\Model;

class Device extends Model{
	protected $table = 'device';
	protected $primaryKey = 'id';
}