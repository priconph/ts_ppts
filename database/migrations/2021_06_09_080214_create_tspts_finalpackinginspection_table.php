<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsptsFinalpackinginspectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tspts_finalpackinginspection', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('lotapp_id')->comment = "From Production Runcard";

            $table->integer('operator_conformance_id');

            $table->integer('coc_requirement')->comment = "1 - YES, 2 - NO, 3 - N/A";
            $table->integer('result');
            $table->integer('inspector_id');
            $table->dateTime('inspection_datetime');

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
        Schema::dropIfExists('tspts_finalpackinginspection');
    }
}
