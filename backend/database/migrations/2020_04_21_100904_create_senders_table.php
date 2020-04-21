<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('senders', function (Blueprint $table) {
            $table->id();
            $table->string('register_number')->unique();
            $table->unsignedInteger('dinkes_city_id');
            $table->string('fasyankes_type', 150);
            $table->unsignedInteger('fasyankes_id');
            $table->string('medical_record_code')->nullable();
            $table->string('doctor_name')->nullable();
            $table->string('phone_number', 30)->nullable();
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
        Schema::dropIfExists('senders');
    }
}
