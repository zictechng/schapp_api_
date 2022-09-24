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
        Schema::create('system_setups', function (Blueprint $table) {
            $table->id();
            $table->string('sch_name')->nullable();
            $table->string('sch_name_short')->nullable();
            $table->string('sch_phone')->nullable();
            $table->string('sch_email')->nullable();
            $table->string('sch_logo')->nullable();
            $table->string('sch_banner')->nullable();
            $table->string('sch_favicon')->nullable();
            $table->string('sch_action')->nullable();
            $table->boolean('app_state')->default(0);
            $table->boolean('app_student_section')->default(0);
            $table->boolean('app_staff_section')->default(0);
            $table->boolean('app_admin_section')->default(0);
            $table->string('addby')->nullable();
            $table->string('add_date')->nullable();
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
        Schema::dropIfExists('system_setups');
    }
};