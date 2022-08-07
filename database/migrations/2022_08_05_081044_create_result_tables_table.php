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
        Schema::create('result_tables', function (Blueprint $table) {
            $table->id();
            $table->string('admin_number');
            $table->string('academic_year')->nullable();
            $table->string('academy_term')->nullable();
            $table->string('subject')->nullable();
            $table->string('class')->nullable();
            $table->string('school_category')->nullable();
            $table->string('first_ca')->nullable();
            $table->string('second_ca')->nullable();
            $table->string('earn_hrs')->nullable();
            $table->string('hrs_work')->nullable();
            $table->string('tca_score')->nullable();
            $table->string('exam_scores')->nullable();
            $table->bigInteger('total_scores')->nullable();
            $table->string('grade')->nullable();
            $table->string('remark')->nullable();
            $table->string('position')->nullable();
            $table->string('average_scores')->nullable();
            $table->string('class_total')->nullable();
            $table->string('tid_code')->nullable();
            $table->string('username')->nullable();
            $table->string('student_name')->nullable();
            $table->string('result_date')->nullable();
            $table->string('result_action')->nullable();
            $table->string('result_status')->nullable();
            $table->string('result_lowest')->nullable();
            $table->string('result_highest')->nullable();
            $table->string('result_action_date')->nullable();
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
        Schema::dropIfExists('result_tables');
    }
};