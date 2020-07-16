<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InitTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tablePrefix = DB::connection()->getTablePrefix();
        $fileInfo = file_get_contents(base_path("cloudnetlotdevelop20200710.sql"));
        $sqls = explode(";", $fileInfo);
        foreach ($sqls as $sql) {
            $sql = str_replace("cnldev_",$tablePrefix,trim($sql));
            if(!empty($sql)){
                DB::statement($sql);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
