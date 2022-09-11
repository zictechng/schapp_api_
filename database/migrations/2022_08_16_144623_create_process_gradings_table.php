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
        Schema::create('process_gradings', function (Blueprint $table) {
            $table->id();
            $table->string('stu_admin_no')->nullable();
            $table->string('stu_name')->nullable();
            $table->string('g_class')->nullable();
            $table->string('g_term')->nullable();
            $table->string('g_year')->nullable();
            $table->string('g_category')->nullable();
            $table->string('total_ca')->nullable();
            $table->string('g_exam')->nullable();
            $table->string('g_code')->nullable();
            $table->bigInteger('total_score')->default('0')->nullable();
            $table->string('g_position')->nullable();
            $table->string('g_addby')->nullable();
            $table->string('g_date')->nullable();
            $table->string('g_status')->nullable();
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
        Schema::dropIfExists('process_gradings');
    }
};