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
        Schema::create('finance_reports', function (Blueprint $table) {
            $table->id();
            $table->float('amt')->default(0.0)->nullable();
            $table->string('type')->nullable();
            $table->string('qty')->nullable();
            $table->string('nature')->nullable();
            $table->string('disc')->nullable();
            $table->string('expense')->nullable();
            $table->string('addedby')->nullable();
            $table->string('status')->nullable();
            $table->string('fin_tid')->nullable();
            $table->string('add_date')->nullable();
            $table->string('approve_date')->nullable();
            $table->string('close_date')->nullable();
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
        Schema::dropIfExists('finance_reports');
    }
};