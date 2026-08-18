<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDrawingRefsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drawing_refs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('document_code');
            $table->string('document_no');
            $table->string('series');
            $table->string('station');
            $table->string('process');
            $table->string('rev');
            $table->string('remarks');
            $table->string('status');
            $table->bigInteger('logdel')->nullable()->default(0)->comment = "0 - active, 1 - inactive";
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drawing_refs');
    }
}
