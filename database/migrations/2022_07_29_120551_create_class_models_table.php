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
        Schema::create('class_models', function (Blueprint $table) {
            $table->id();
            $table->string('class_name');
            $table->string('added_by')->nullable();
            $table->string('status')->nullable();
            $table->string('action')->nullable();
            $table->text('record_date')->nullable();
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
        Schema::dropIfExists('class_models');
    }
};