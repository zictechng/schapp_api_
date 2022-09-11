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
        Schema::create('graduations', function (Blueprint $table) {
            $table->id();
            $table->string('g_st_admin')->nullable();
            $table->string('g_st_name')->nullable();
            $table->string('g_class')->nullable();
            $table->string('g_year')->nullable();
            $table->string('g_status')->nullable();
            $table->string('g_added')->nullable();
            $table->string('g_tid')->nullable();
            $table->string('g_date')->nullable();
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
        Schema::dropIfExists('graduations');
    }
};