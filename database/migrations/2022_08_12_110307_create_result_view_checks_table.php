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
        Schema::create('result_view_checks', function (Blueprint $table) {
            $table->id();
            $table->string('year')->nullable();
            $table->string('term')->nullable();
            $table->string('class')->nullable();
            $table->string('subject')->nullable();
            $table->string('category')->nullable();
            $table->string('status')->nullable();
            $table->string('reg_date')->nullable();
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
        Schema::dropIfExists('result_view_checks');
    }
};