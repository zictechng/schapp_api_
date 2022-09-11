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
        Schema::create('generate_pins', function (Blueprint $table) {
            $table->id();
            $table->string('card_pin')->nullable();
            $table->string('card_serial')->nullable();
            $table->string('card_status')->nullable();
            $table->string('card_usage_count')->nullable();
            $table->string('card_usage_status')->nullable();
            $table->string('card_date')->nullable();
            $table->string('card_use_date')->nullable();
            $table->string('card_use_username')->nullable();
            $table->string('card_addedby')->nullable();
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
        Schema::dropIfExists('generate_pins');
    }
};