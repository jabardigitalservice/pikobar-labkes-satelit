<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLabSatelit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sampel', function (Blueprint $table) {
            $table->unsignedInteger('lab_satelit_id')->nullable();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('lab_satelit_id')->nullable();
        });
        Schema::table('register', function (Blueprint $table) {
            $table->unsignedInteger('lab_satelit_id')->nullable();
        });
        Schema::table('ekstraksi', function (Blueprint $table) {
            $table->unsignedInteger('lab_satelit_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sampel', function (Blueprint $table) {
            $table->dropColumn(['lab_satelit_id']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['lab_satelit_id']);
        });
        Schema::table('register', function (Blueprint $table) {
            $table->dropColumn(['lab_satelit_id']);
        });
        Schema::table('ekstraksi', function (Blueprint $table) {
            $table->dropColumn(['lab_satelit_id']);
        });
        
    }
}
