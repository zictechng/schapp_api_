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
        Schema::create('application_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();
            $table->string('sub_body')->nullable();
            $table->longText('body_message')->nullable();
            $table->string('feature')->default(0);
            $table->string('feature_image')->nullable();
            $table->string('feature_thumbnail')->nullable();
            $table->string('belong_to')->nullable();
            $table->string('action_state')->nullable();
            $table->string('status')->nullable();
            $table->string('added_by')->nullable();
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
        Schema::dropIfExists('application_notifications');
    }
};