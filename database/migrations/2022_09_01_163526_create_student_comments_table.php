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
        Schema::create('student_comments', function (Blueprint $table) {
            $table->id();
            $table->string('comm_stu_number')->nullable();
            $table->string('comm_stu_name')->nullable();
            $table->string('comm_class')->nullable();
            $table->string('comm_year')->nullable();
            $table->string('comm_term')->nullable();
            $table->string('comm_comment')->nullable();
            $table->string('comm_prin_comment')->nullable();
            $table->string('comm_status')->nullable();
            $table->string('comm_addby')->nullable();
            $table->string('comm_date')->nullable();
            $table->string('comm_tid')->nullable();
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
        Schema::dropIfExists('student_comments');
    }
};