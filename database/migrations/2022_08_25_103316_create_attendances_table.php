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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('atten_admin_no')->nullable();
            $table->string('atten_stu_name')->nullable();
            $table->string('atten_class')->nullable();
            $table->string('atten_year')->nullable();
            $table->string('atten_term')->nullable();
            $table->string('atten_mark_date')->nullable();
            $table->string('atten_submit_date')->nullable();
            $table->string('atten_status')->nullable();
            $table->string('atten_addeby')->nullable();
            $table->string('atten_class_name')->nullable();
            $table->string('atten_year_name')->nullable();
            $table->string('atten_term_name')->nullable();
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
        Schema::dropIfExists('attendances');
    }
};