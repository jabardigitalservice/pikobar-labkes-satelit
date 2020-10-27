<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnLastLoginOnUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dateTime('last_login_at')->nullable()->default(null);
            $table->dateTime('invited_at')->nullable()->default(null);
            $table->dateTime('register_at')->nullable()->default(null);
            $table->string('name')->nullable()->change();
            $table->string('username')->nullable()->change();
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
            $table->dropColumn('last_login_at');
            $table->dropColumn('invited_at');
            $table->dropColumn('register_at');
            $table->string('name')->change();
            $table->string('username')->change();
        });
    }
}
