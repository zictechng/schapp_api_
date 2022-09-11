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
        Schema::create('assign_classes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cls_teacher_id')->nullable();
            $table->bigInteger('cls__class_id')->nullable();
            $table->string('cls__teacher_name')->nullable();
            $table->string('cls__class_name')->nullable();
            $table->string('cls__status')->nullable();
            $table->string('cls__tid')->nullable();
            $table->string('cls__addby')->nullable();
            $table->string('cls__date')->nullable();
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
        Schema::dropIfExists('assign_classes');
    }
};