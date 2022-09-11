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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('assign_title')->nullable();
            $table->string('assign_sub_title')->nullable();
            $table->longText('assign_body')->nullable();
            $table->string('assign_class')->nullable();
            $table->string('assign_file')->nullable();
            $table->string('assign_type')->nullable();
            $table->string('assign_status')->nullable();
            $table->string('addby')->nullable();
            $table->bigInteger('assign_class_id')->nullable();
            $table->string('assign_submission_date')->nullable();
            $table->string('assign_date')->nullable();

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
        Schema::dropIfExists('assignments');
    }
};