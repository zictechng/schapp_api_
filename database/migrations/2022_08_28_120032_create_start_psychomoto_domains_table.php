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
        Schema::create('start_psychomoto_domains', function (Blueprint $table) {
            $table->id();
            $table->string('saff_year')->nullable();
            $table->string('saff_term')->nullable();
            $table->string('saff_class')->nullable();
            $table->string('saff_status')->nullable();
            $table->string('saff_tid')->nullable();
            $table->string('saff_addby')->nullable();
            $table->string('saff_date')->nullable();
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
        Schema::dropIfExists('start_psychomoto_domains');
    }
};