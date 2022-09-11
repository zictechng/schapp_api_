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
        Schema::create('start_promotions', function (Blueprint $table) {
            $table->id();
            $table->string('stu_adm_number')->nullable();
            $table->string('stu_name')->nullable();
            $table->string('stu_class')->nullable();
            $table->string('stu_next_class')->nullable();
            $table->string('stu_status')->nullable();
            $table->string('stu_tid')->nullable();
            $table->string('stu_date')->nullable();
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
        Schema::dropIfExists('start_promotions');
    }
};