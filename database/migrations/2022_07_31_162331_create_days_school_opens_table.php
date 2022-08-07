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
        Schema::create('days_school_opens', function (Blueprint $table) {
            $table->id();
            $table->string('days_open');
            $table->string('open_term')->nullable();
            $table->string('open_year')->nullable();
            $table->string('open_status')->nullable();
            $table->string('open_date')->nullable();
            $table->string('open_addedby')->nullable();
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
        Schema::dropIfExists('days_school_opens');
    }
};