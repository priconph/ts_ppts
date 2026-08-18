<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsptsPreliminarypackinginspectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tspts_preliminarypackinginspection', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('lotapp_id')->comment = "From Production Runcard";

            $table->integer('packing_type')->comment = "1 - BOX, 2 - TRAY, 3 - CYLINDER, 4 - PALLET CASE";
            $table->integer('unit_condition')->comment = "1 - ESAFOAM, 2 - DOWN, 3 - UP";
            $table->string('packing_code')->comment = "Auto Generated";
            $table->integer('series_v_label')->comment = "1 - YES, 2 - NO, 3 - N/A";
            $table->integer('label_v_actual')->comment = "1 - YES, 2 - NO, 3 - N/A";
            $table->integer('silica_gel')->comment = "1 - WITH, 2 - WITHOUT, 3 - N/A";
            $table->integer('supervisor_conformance')->comment = "1 - YES, 2 - NO, 3 - N/A";

            $table->dateTime('inspection_datetime');
            $table->integer('inspector_id');

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
        Schema::dropIfExists('tspts_preliminarypackinginspection');
    }
}
