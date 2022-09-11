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
        Schema::create('start_attendances', function (Blueprint $table) {
            $table->id();
            $table->string('sta_admin_no')->nullable();
            $table->string('sta_stu_name')->nullable();
            $table->string('sta_class')->nullable();
            $table->string('sta_year')->nullable();
            $table->string('sta_term')->nullable();
            $table->string('sta_mark_date')->nullable();
            $table->string('sta_submit_date')->nullable();
            $table->string('sta_status')->nullable();
            $table->string('sta_addeby')->nullable();
            $table->string('sta_class_name')->nullable();
            $table->string('sta_year_name')->nullable();
            $table->string('sta_term_name')->nullable();
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
        Schema::dropIfExists('start_attendances');
    }
};