<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnRegisterPerujukId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sampel', function (Blueprint $table) {
            $table->foreignId('register_perujuk_id')
                ->nullable()
                ->constrained('register_perujuk')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');
        });
        Schema::table('register', function (Blueprint $table) {
            $table->foreignId('register_perujuk_id')
                ->nullable()
                ->constrained('register_perujuk')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');
        });
        Schema::table('pasien', function (Blueprint $table) {
            $table->foreignId('register_perujuk_id')
                ->nullable()
                ->constrained('register_perujuk')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');
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
            $table->dropForeign('sampel_register_perujuk_id_foreign');
            $table->dropColumn('register_perujuk_id');
        });

        Schema::table('register', function (Blueprint $table) {
            $table->dropForeign('register_register_perujuk_id_foreign');
            $table->dropColumn('register_perujuk_id');
        });

        Schema::table('pasien', function (Blueprint $table) {
            $table->dropForeign('pasien_register_perujuk_id_foreign');
            $table->dropColumn('register_perujuk_id');
        });
    }
}
