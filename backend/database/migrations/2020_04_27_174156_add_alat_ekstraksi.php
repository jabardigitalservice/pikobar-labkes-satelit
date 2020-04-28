<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlatEkstraksi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ekstraksi', function (Blueprint $table) {
            $table->string('alat_ekstraksi')->nullable();
        });
        Schema::table('sampel', function (Blueprint $table) {
            $table->timestamp('waktu_sample_invalid')->index()->nullable();
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
