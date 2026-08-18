<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsptsPackingconfirmationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tspts_packingconfirmation', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('lotapp_id')->comment = "From Production Runcard";

            $table->integer('series_v_label')->comment = "1 - YES, 2 - NO, 3 - N/A";
            $table->integer('label_v_actual')->comment = "1 - YES, 2 - NO, 3 - N/A";
            $table->integer('silica_gel')->comment = "1 - WITH, 2 - WITHOUT, 3 - N/A";

            $table->dateTime('confirmation_datetime');
            $table->integer('operator_id');

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
        Schema::dropIfExists('tspts_packingconfirmation');
    }
}
