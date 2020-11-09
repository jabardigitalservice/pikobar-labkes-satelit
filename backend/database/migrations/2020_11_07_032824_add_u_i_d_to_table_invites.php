<?php

use App\Invite;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUIDToTableInvites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Invite::whereNotNull('id')->delete();
        Schema::table('invites', function (Blueprint $table) {
            $table->dropColumn('token');
        });
        Schema::table('invites', function (Blueprint $table) {
            $table->uuid('token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Invite::whereNotNull('id')->delete();
        Schema::table('invites', function (Blueprint $table) {
            $table->dropColumn('token');
        });
        Schema::table('invites', function (Blueprint $table) {
            $table->string('token', 20)->unique();
        });
    }
}
