<?php

namespace App\Entities\Modules\Test\Repositories;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class TestRepository.
 *
 * @package namespace App\Entities\Modules\Test\Repositories;
 */
class TestRepository extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

}
