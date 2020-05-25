<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewPeriksaSampel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("DROP VIEW IF EXISTS view_durasi_periksa_sampel");
        \DB::statement("DROP VIEW IF EXISTS view_durasi_proses_sampel");
        \DB::statement("DROP VIEW IF EXISTS view_pemeriksaansampel");

        \DB::statement("create view view_durasi_periksa_sampel as 
        select pr.*,p.created_at,s.waktu_pcr_sample_analyzed,s.waktu_pcr_sample_analyzed::date-p.created_at::date as durasi_periksa_sampel
        from pasien as p
        left join pasien_register as pr on p.id=pr.pasien_id
        left join sampel as s on s.register_id=pr.register_id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('view_periksa_sampel');
    }
}
