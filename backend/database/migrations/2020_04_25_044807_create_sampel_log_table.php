<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampelLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sampel_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sampel_id')->nullable();
            $table->string('sampel_status');
            $table->string('sampel_status_before')->default('creation')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('sampel_id')->references('id')->on('sampel');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('sampel_status')->references('sampel_status')->on('status_sampel');
            $table->foreign('sampel_status_before')->references('sampel_status')->on('status_sampel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sampel_log');
    }
}
