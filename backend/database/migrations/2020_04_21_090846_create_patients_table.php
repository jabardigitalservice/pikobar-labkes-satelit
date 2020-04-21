<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('no_ktp', 16)->unique()->nullable();
            $table->string('no_sim', 50)->unique()->nullable();
            $table->string('no_kk', 30)->nullable();
            $table->date('birthdate');
            $table->string('birth_place', 100);
            $table->string('nationality', 100);
            $table->string('mobile_phone', 25)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('profession', 150)->nullable();
            $table->char('gender', 1);
            $table->unsignedInteger('city_id');
            $table->string('district', 100)->nullable();
            $table->string('village', 100)->nullable();
            $table->char('no_rw', 3)->nullable();
            $table->char('no_rt', 3)->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('patients');
    }
}
