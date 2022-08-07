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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('surname');
            $table->string('other_name');
            $table->string('sex')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('dob')->nullable();
            $table->string('staff_id')->nullable();
            $table->string('school_category')->nullable();
            $table->string('qualification')->nullable();
            $table->string('acct_username')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('class')->nullable();
            $table->string('home_address')->nullable();
            $table->string('staff_image')->nullable();
            $table->string('addby')->nullable();
            $table->string('acct_status')->nullable();
            $table->string('acct_action')->nullable();
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
        Schema::dropIfExists('staff');
    }
};