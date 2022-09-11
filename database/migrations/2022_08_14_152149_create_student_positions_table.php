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
        Schema::create('student_positions', function (Blueprint $table) {
            $table->id();
            $table->string('sch_year')->nullable();
            $table->string('sch_term')->nullable();
            $table->string('sch_class')->nullable();
            $table->string('sch_category')->nullable();
            $table->string('stu_admin_number')->nullable();
            $table->string('tca_score')->nullable();
            $table->string('exam_score')->nullable();
            $table->string('total_score')->nullable();
            $table->string('class_total')->nullable();
            $table->string('user_code')->nullable();
            $table->string('position')->nullable();
            $table->string('add_by')->nullable();
            $table->string('student_name')->nullable();
            $table->string('p_date')->nullable();
            $table->string('p_status')->nullable();
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
        Schema::dropIfExists('student_positions');
    }
};