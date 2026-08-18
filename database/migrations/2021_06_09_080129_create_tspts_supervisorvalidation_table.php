<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsptsSupervisorvalidationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tspts_supervisorvalidation', function (Blueprint $table) {
            $table->bigIncrements('id');
             $table->integer('lotapp_id')->comment = "From Production Runcard";
            
            $table->integer('series_v_label')->comment = "1 - YES, 2 - NO, 3 - N/A";
            $table->integer('label_v_actual')->comment = "1 - YES, 2 - NO, 3 - N/A";

            $table->dateTime('validation_datetime');
            $table->integer('supervisor_id');

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
        Schema::dropIfExists('tspts_supervisorvalidation');
    }
}
