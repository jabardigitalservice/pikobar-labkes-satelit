<?php

use App\Models\LabSatelit;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class ModifyTableLabSatelit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lab_satelit', function (Blueprint $table) {
            $table->dropColumn('alamat');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->foreignId('provinsi_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreign('provinsi_id')->references('id')->on('provinsi')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('type')->nullable();
            $table->boolean('is_check')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lab_satelit', function (Blueprint $table) {
            $table->dropColumn('is_check');
            $table->dropColumn('type');
            $table->dropColumn('user_id');
            $table->dropColumn('provinsi_id');
            $table->string('alamat')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
        });
    }
}
