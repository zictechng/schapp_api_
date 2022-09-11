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
        Schema::create('start_graduations', function (Blueprint $table) {
            $table->id();
            $table->string('gs_st_admin')->nullable();
            $table->string('gs_st_name')->nullable();
            $table->string('gs_class')->nullable();
            $table->string('gs_year')->nullable();
            $table->string('gs_status')->nullable();
            $table->string('gs_added')->nullable();
            $table->string('gs_tid')->nullable();
            $table->string('gs_date')->nullable();

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
        Schema::dropIfExists('start_graduations');
    }
};