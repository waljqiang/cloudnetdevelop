<?php

namespace App\Models;

class Model extends \Illuminate\Database\Eloquent\Model{
    public $timestamps = false;
    /**
     * 模型日期列的存储格式
     *
     * @var string
     */
    protected $dateFormat = 'U';
}
