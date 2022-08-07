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
        Schema::create('school_resumptions', function (Blueprint $table) {
            $table->id();
            $table->string('start_date');
            $table->string('close_date');
            $table->string('next_resumption')->nullable();
            $table->string('school_year');
            $table->string('school_term');
            $table->string('added_by')->nullable();
            $table->string('status')->nullable();
            $table->string('add_date')->nullable();

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
        Schema::dropIfExists('school_resumptions');
    }
};