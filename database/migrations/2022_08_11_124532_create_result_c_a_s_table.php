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
        Schema::create('result_c_a_s', function (Blueprint $table) {
            $table->id();
            $table->string('st_admin_id');
            $table->string('ca1')->nullable();
            $table->string('ca2')->nullable();
            $table->string('hrs_work')->nullable();
            $table->string('hrs_earned')->nullable();
            $table->string('ca_total')->nullable();
            $table->string('rst_year')->nullable();
            $table->string('rst_term')->nullable();
            $table->string('rst_subject')->nullable();
            $table->string('rst_category')->nullable();
            $table->string('rst_class')->nullable();
            $table->string('rst_tid')->nullable();
            $table->string('rst_date')->nullable();
            $table->string('rst_status')->nullable();
            $table->string('rst_addby')->nullable();
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
        Schema::dropIfExists('result_c_a_s');
    }
};