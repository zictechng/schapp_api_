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
        Schema::create('test_saves', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->string('state_details')->nullable();
            $table->string('class_details')->nullable();
            $table->string('message_details')->nullable();
            $table->string('tran_code')->nullable();
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
        Schema::dropIfExists('test_saves');
    }
};