<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPerujukId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('perujuk_id')
                ->foreign('perujuk_id')
                ->references('id')
                ->on('users')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL')
                ->nullable();
        });

        Schema::table('sampel', function (Blueprint $table) {
            $table->unsignedInteger('perujuk_id')
                ->foreign('perujuk_id')
                ->references('id')
                ->on('users')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL')
                ->nullable();
            $table->string('kode_kasus')->nullable();
        });

        Schema::table('register', function (Blueprint $table) {
            $table->unsignedInteger('perujuk_id')
                ->foreign('perujuk_id')
                ->references('id')
                ->on('users')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL')
                ->nullable();
            $table->string('kode_kasus')->nullable();
        });
        Schema::table('pasien', function (Blueprint $table) {
            $table->unsignedInteger('perujuk_id')
                ->foreign('perujuk_id')
                ->references('id')
                ->on('users')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL')
                ->nullable();
            $table->string('kode_kasus')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_perujuk_id_foreign');
            $table->dropColumn('perujuk_id');
        });

        Schema::table('sampel', function (Blueprint $table) {
            $table->dropForeign('sampel_perujuk_id_foreign');
            $table->dropColumn('perujuk_id');
            $table->dropColumn('kode_kasus');
        });

        Schema::table('register', function (Blueprint $table) {
            $table->dropForeign('register_perujuk_id_foreign');
            $table->dropColumn('perujuk_id');
            $table->dropColumn('kode_kasus');
        });
        Schema::table('pasien', function (Blueprint $table) {
            $table->dropForeign('pasien_perujuk_id_foreign');
            $table->dropColumn('perujuk_id');
            $table->dropColumn('kode_kasus');
        });
    }
}
