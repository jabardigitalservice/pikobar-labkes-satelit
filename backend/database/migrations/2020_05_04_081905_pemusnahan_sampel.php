<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PemusnahanSampel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sampel', function (Blueprint $table) {
            $table->boolean('is_musnah_ekstraksi')->default(false);
            $table->boolean('is_musnah_pcr')->default(false);
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
            $table->dropColumn('is_musnah_ekstraksi');
            $table->dropColumn('is_musnah_pcr');
        });
    }
}
