<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsptsOqcvirTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tspts_oqcvir', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('lotapp_id')->comment = "From Production Runcard";
            $table->integer('sample_size');
            $table->integer('ok_qty');

            $table->dateTime('inspection_datetime');

            $table->integer('terminal_use')->comment = "1 - YES, 2 - NO, 3 - N/A";
            $table->integer('yd_label')->comment = "1 - WITH, 2 - WITHOUT, 3 - N/A";
            $table->integer('csh_coating')->comment = "1 - YES, 2 - NO, 3 - N/A";
            $table->integer('accessories_requirement')->comment = "1 - YES, 2 - NO, 3 - N/A";
            $table->integer('coc_requirement')->comment = "1 - YES, 2 - NO, 3 - N/A";

            $table->integer('result');
            $table->integer('inspector_id');

            $table->longText('remarks')->nullable();

            $table->timestamps();

            $table->integer('logdel')->comment = "0 - active, 1 - inactive";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tspts_oqcvir');
    }
}
