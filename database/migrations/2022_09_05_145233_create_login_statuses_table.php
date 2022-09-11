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
        Schema::create('login_statuses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('login_name')->nullable();
            $table->string('login_date')->nullable();
            $table->string('login_nature')->nullable();
            $table->string('login_uid')->nullable();
            $table->string('login_status')->default('0')->nullable();
            $table->string('logout_date')->nullable();
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
        Schema::dropIfExists('login_statuses');
    }
};