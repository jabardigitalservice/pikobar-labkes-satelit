<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Detail Sampel</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link :to="'/verifikasi/edit/' + this.data.id" class="btn btn-warning">
          <i class="fa fa-edit"></i> Ubah
        </router-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Informasi Sampel">
          <div class="row">
            <div class="col-md-12">
              <table class="table">
                <tbody>
                  <tr>
                    <td width="30%" align="right">
                      <b>Nomor Registrasi</b>
                    </td>
                    <td width="60%">
                      <span>{{data.nomor_register}}</span>
                    </td>
                  </tr>

                  <tr>
                    <td width="30%" align="right">
                      <b>Pasien</b>
                    </td>
                    <td width="60%">
                      <span>{{data.pasien.nama_depan}} {{data.pasien.nama_belakang}}</span>
                    </td>
                  </tr>

                  <tr>
                    <td width="30%" align="right">
                      <b>Nomor Sampel</b>
                    </td>
                    <td width="60%">
                      <span>{{data.nomor_sampel}}</span>
                    </td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Tanggal input hasil</b>
                    </td>
                    <td
                      width="60%" 
                    >{{data.pemeriksaan_sampel.tanggal_input_hasil | formatDate}} pada {{data.pemeriksaan_sampel.jam_input_hasil}}</td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Petugas Penerima Sampel RNA</b>
                    </td>
                    <td
                      width="60%">{{data.pemeriksaan_sampel.petugas_penerima_sampel_rna}}
                    </td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Kesimpulan Hasil</b>
                    </td>
                    <td
                      width="60%">{{data.pemeriksaan_sampel.kesimpulan_pemeriksaan}}</td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Catatan penerimaan</b>
                    </td>
                    <td
                      width="60%">{{data.pemeriksaan_sampel.catatan_penerimaan}}</td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Catatan pemeriksaan</b>
                    </td>
                    <td
                      width="60%">{{data.pemeriksaan_sampel.catatan_pemeriksaan}}</td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Status Sampel</b>
                    </td>
                    <td
                      width="60%">{{data.status.deskripsi}}</td>
                  </tr>
                </tbody>
              </table>

              <hr />
              
            </div>
          </div>
        </Ibox>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  middleware: "auth",
  async asyncData({route}) {
    let resp = await axios.get("/v1/verifikasi/detail/" + route.params.id);
    let data = resp.data.data

    if (!data.pasien) {
      data.pasien = {}
    }

    return { data };
  },
  head() {
    return {
      title: "Detil Sampel Hasil Pemeriksaan"
    };
  }
};
</script>