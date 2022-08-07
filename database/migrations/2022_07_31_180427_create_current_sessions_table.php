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
        Schema::create('current_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('running_session');
            $table->string('session_status')->nullable();
            $table->string('session_addedby')->nullable();
            $table->string('session_date')->nullable();
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
        Schema::dropIfExists('current_sessions');
    }
};