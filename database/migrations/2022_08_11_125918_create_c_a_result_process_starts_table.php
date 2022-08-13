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
        Schema::create('c_a_result_process_starts', function (Blueprint $table) {
            $table->id();
            $table->string('year')->nullable();
            $table->string('term')->nullable();
            $table->string('class')->nullable();
            $table->string('subject')->nullable();
            $table->string('sch_category')->nullable();
            $table->string('tid_code')->nullable();
            $table->string('add_by')->nullable();
            $table->string('status')->nullable();
            $table->string('record_date')->nullable();
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
        Schema::dropIfExists('c_a_result_process_starts');
    }
};