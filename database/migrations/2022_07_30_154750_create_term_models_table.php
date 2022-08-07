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
        Schema::create('term_models', function (Blueprint $table) {
            $table->id();
            $table->string('term_name');
            $table->string('add_by')->nullable();
            $table->string('t_status')->nullable();
            $table->string('t_date')->nullable();
            $table->string('t_action')->nullable();
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
        Schema::dropIfExists('term_models');
    }
};