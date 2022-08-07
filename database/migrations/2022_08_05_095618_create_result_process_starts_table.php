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
        Schema::create('result_process_starts', function (Blueprint $table) {
            $table->id();
            $table->string('school_year')->nullable();
            $table->string('school_term')->nullable();
            $table->string('class')->nullable();
            $table->string('school_category')->nullable();
            $table->string('subject')->nullable();
            $table->string('r_tid')->nullable();
            $table->string('addby')->nullable();
            $table->string('r_status')->nullable();
            $table->string('r_date')->nullable();
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
        Schema::dropIfExists('result_process_starts');
    }
};