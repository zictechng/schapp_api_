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
        Schema::create('result_saves', function (Blueprint $table) {
            $table->id();
            $table->string('admin_number')->nullable();
            $table->string('ca_1')->nullable();
            $table->string('ca_2')->nullable();
            $table->string('ca_3')->nullable();
            $table->string('ca_4')->nullable();
            $table->string('ca_5')->nullable();
            $table->string('ca_6')->nullable();
            $table->string('addby')->nullable();
            $table->string('res_status')->nullable();
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
        Schema::dropIfExists('result_saves');
    }
};