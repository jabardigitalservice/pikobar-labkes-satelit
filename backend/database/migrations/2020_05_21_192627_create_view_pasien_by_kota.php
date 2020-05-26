<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewPasienByKota extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("create view view_sampel_pasien_by_kota as select s.register_id,pr.pasien_id,s.nomor_register,s.nomor_sampel,k.nama as nama_kota
        from sampel as s
        left join pasien_register as pr on s.register_id=pr.register_id
        left join pasien as p on pr.pasien_id=p.id
        left join kota as k on k.id=p.kota_id
        where s.register_id is not null and p.id is not null");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('view_pasien_by_kota');
    }
}
