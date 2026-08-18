<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdRuncardAccessoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_runcard_accessories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('prod_runcard_id');
            $table->unsignedBigInteger('issuance_id');
            $table->string('item_code')->nullable();
            $table->string('item_desc')->nullable();
            $table->string('quantity');
            $table->string('usage_per_socket');
            $table->unsignedBigInteger('counted_by')->nullable();
            $table->date('counted_by_date')->nullable();
            $table->unsignedBigInteger('checked_by')->nullable();
            $table->date('checked_by_date')->nullable();
            $table->unsignedBigInteger('qc_inspector')->nullable();
            // $table->unsignedBigInteger('tbl_wbs')->default(1)->comment = '1-tbl_wbs_kit_issuance, 2-tbl_sakidashi_kit_issuance';
            // $table->unsignedBigInteger('for_emboss')->default(0)->comment = '0-false, 1-true';
            $table->unsignedBigInteger('status')->default(1)->comment = '1-active, 2-inactive';
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('last_updated_by');
            $table->timestamps();

            // Foreign Keys
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('last_updated_by')->references('id')->on('users');
            $table->foreign('prod_runcard_id')->references('id')->on('production_runcards');
            $table->foreign('counted_by')->references('id')->on('users');
            $table->foreign('checked_by')->references('id')->on('users');
            $table->foreign('qc_inspector')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prod_runcard_accessories');
    }
}
