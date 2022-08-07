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
        Schema::create('activitity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('m_username')->nullable();
            $table->string('m_action')->nullable();
            $table->string('m_status')->nullable();
            $table->string('m_details')->nullable();
            $table->text('m_date')->nullable();
            $table->bigInteger('m_uid')->nullable();
            $table->string('m_device_name')->nullable();
            $table->string('m_broswer')->nullable();
            $table->string('m_device_number')->nullable();
            $table->string('m_location')->nullable();
            $table->string('m_ip')->nullable();
            $table->string('m_city')->nullable();
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
        Schema::dropIfExists('activitity_logs');
    }
};