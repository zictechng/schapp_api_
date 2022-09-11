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
        Schema::create('message_systems', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('receiver_user_id');
            $table->bigInteger('sender_user_id');
            $table->string('mes_nature')->nullable();
            $table->string('mes_title')->nullable();
            $table->longText('mes_body')->nullable();
            $table->string('mes_sender_name')->nullable();
            $table->string('mes_receiver_email')->nullable();
            $table->string('mes_file')->nullable();
            $table->string('mes_status')->nullable();
            $table->string('mes_delete_uid')->nullable();
            $table->string('mes_receiver_status')->nullable();
            $table->string('mes_send_date')->nullable();
            $table->string('mes_delete_date')->nullable();
            $table->string('mes_action')->nullable();
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
        Schema::dropIfExists('message_systems');
    }
};