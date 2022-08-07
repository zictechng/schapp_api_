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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('surname')->nullable();
            $table->string('other_name');
            $table->string('sex')->nullable();
            $table->string('dob')->nullable();
            $table->string('state')->nullable();
            $table->string('lga')->nullable();
            $table->string('country')->nullable();
            $table->string('last_sch_attend')->nullable();
            $table->string('last_class_attend')->nullable();
            $table->string('class_apply')->nullable();
            $table->string('schooling_type')->nullable();
            $table->string('academic_year')->nullable();
            $table->string('school_category')->nullable();
            $table->string('st_admin_number')->nullable();
            $table->string('st_image')->nullable();
            $table->string('guardia_name')->nullable();
            $table->string('guardia_email')->nullable();
            $table->string('guardia_number')->nullable();
            $table->string('guardia_address')->nullable();
            $table->string('staff_zone')->nullable();
            $table->string('staff_depart')->nullable();
            $table->string('staff_rank')->nullable();
            $table->string('health_issue')->nullable();
            $table->string('reg_date')->nullable();
            $table->string('acct_status')->nullable();
            $table->string('acct_action')->nullable();
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
        Schema::dropIfExists('students');
    }
};