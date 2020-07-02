<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRegisterSwab extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('register', function (Blueprint $table) {
            $table->string('status',10)->nullable();
            $table->integer('swab_ke')->nullable();
            $table->date('tanggal_swab')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('register', function (Blueprint $table) {
            $table->dropColumn(['status']);
            $table->dropColumn(['swab_ke']);
            $table->dropColumn(['tanggal_swab']);
        });
    }
}
