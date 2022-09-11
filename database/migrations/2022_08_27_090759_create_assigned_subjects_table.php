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
        Schema::create('assigned_subjects', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sub_teacher_id')->nullable();
            $table->bigInteger('sub_subject_id')->nullable();
            $table->string('sub_teacher_name')->nullable();
            $table->string('sub_subject_name')->nullable();
            $table->string('sub_status')->nullable();
            $table->string('sub_tid')->nullable();
            $table->string('sub_addby')->nullable();
            $table->string('sub_date')->nullable();
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
        Schema::dropIfExists('assigned_subjects');
    }
};