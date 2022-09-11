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
        Schema::create('psychomoto_domians', function (Blueprint $table) {
            $table->id();
            $table->string('effectiveness')->nullable();
            $table->string('neatness_score')->nullable();
            $table->string('craft_score')->nullable();
            $table->string('punctuality_score')->nullable();
            $table->string('sport_score')->nullable();
            $table->string('aff_year')->nullable();
            $table->string('aff_term')->nullable();
            $table->string('aff_class')->nullable();
            $table->string('aff_admin_number')->nullable();
            $table->string('aff_student_name')->nullable();
            $table->string('aff_addedby')->nullable();
            $table->string('aff_tid')->nullable();
            $table->string('aff_date')->nullable();
            $table->string('aff_status')->nullable();
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
        Schema::dropIfExists('psychomoto_domians');
    }
};