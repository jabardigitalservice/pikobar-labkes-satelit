<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRegistersStatusTimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('register', function (Blueprint $table) {
            $table->timestamp('waktu_waiting_sample')->index()->nullable();
            $table->timestamp('waktu_sample_taken')->index()->nullable();
            $table->timestamp('waktu_extraction_sample_extracted')->index()->nullable();
            $table->timestamp('waktu_extraction_sample_reextract')->index()->nullable();
            $table->timestamp('waktu_extraction_sample_sent')->index()->nullable();
            $table->timestamp('waktu_pcr_sample_received')->index()->nullable();
            $table->timestamp('waktu_pcr_sample_analyzed')->index()->nullable();
            $table->timestamp('waktu_sample_verified')->index()->nullable();
            $table->timestamp('waktu_sample_valid')->index()->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
