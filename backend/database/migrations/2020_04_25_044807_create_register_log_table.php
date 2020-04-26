<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisterLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('register_id')->nullable();
            $table->string('register_status');
            $table->string('register_status_before');
            $table->unsignedInteger('user_id')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('register_id')->references('id')->on('register');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('register_status')->references('register_status')->on('status_register');
            $table->foreign('register_status_before')->references('register_status')->on('status_register');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('register_log');
    }
}
