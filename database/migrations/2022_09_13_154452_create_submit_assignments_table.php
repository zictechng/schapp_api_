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
        Schema::create('submit_assignments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('assign_id')->nullable();
            $table->bigInteger('student_id')->nullable();
            $table->string('assign_code')->nullable();
            $table->string('assign_file_name')->nullable();
            $table->string('assign_message')->nullable();
            $table->text('assign_scores')->nullable();
            $table->string('assign_remark')->nullable();
            $table->string('assign_status')->nullable();
            $table->string('assign_submit_date')->nullable();
            $table->string('assign_updated_date')->nullable();
            $table->string('assign_file_path')->nullable();
            $table->string('assign_submit_code')->nullable();
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
        Schema::dropIfExists('submit_assignments');
    }
};