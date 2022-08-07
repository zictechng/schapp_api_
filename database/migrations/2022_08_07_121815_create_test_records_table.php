<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_records', function (Blueprint $table) {
            $table->id();
            $table->string('record_id')->nullable();
            $table->string('item_name')->nullable();
            $table->bigInteger('qty')->nullable();
            $table->string('unit_price')->nullable();
            $table->string('purch_price')->nullable();
            $table->string('selling_price')->nullable();
            $table->string('total')->nullable();
            $table->string('addby')->nullable();
            $table->string('rec_date')->nullable();
            $table->string('rec_status')->nullable();
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
        Schema::dropIfExists('test_records');
    }
};