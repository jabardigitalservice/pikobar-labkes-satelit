<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Detail Status</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link :to="'/ekstraksi/edit/' + this.data.id" class="btn btn-warning">
          <i class="fa fa-edit"></i> Ubah
        </router-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Informasi Ekstraksi &amp; Pengiriman Sampel">
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
                      <b>Nomor Sampel</b>
                    </td>
                    <td width="60%">
                      <span>{{data.nomor_sampel}}</span>
                    </td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Tanggal penerimaan sampel</b>
                    </td>
                    <td
                      width="60%"
                    >{{data.ekstraksi.tanggal_penerimaan_sampel | formatDate}} pada {{data.ekstraksi.jam_penerimaan_sampel}}</td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Petugas Penerima Sampel</b>
                    </td>
                    <td width="60%">{{data.ekstraksi.petugas_penerima_sampel}}</td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Catatan Penerimaan</b>
                    </td>
                    <td width="60%">{{data.ekstraksi.catatan_penerimaan}}</td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Operator Ektraksi</b>
                    </td>
                    <td width="60%">{{data.ekstraksi.operator_ekstraksi}}</td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Tanggal Mulai Ekstraksi</b>
                    </td>
                    <td
                      width="60%"
                    >{{data.ekstraksi.tanggal_mulai_ekstraksi | formatDate }} pada {{data.ekstraksi.jam_mulai_ekstraksi}}</td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Metode Ekstraksi</b>
                    </td>
                    <td width="60%">{{data.ekstraksi.metode_ekstraksi}}</td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Nama Kit Ekstraksi</b>
                    </td>
                    <td width="60%">{{data.ekstraksi.nama_kit_ekstraksi}}</td>
                  </tr>
                  <tr v-if="data.ekstraksi.metode_ekstraksi == 'Otomatis'">
                    <td width="30%" align="right">
                      <b>Alat Ekstraksi</b>
                    </td>
                    <td width="60%">{{data.ekstraksi.alat_ekstraksi}}</td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Dikirim ke Lab</b>
                    </td>
                    <td
                      width="60%"
                    >{{data.lab_pcr_nama}}</td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Nama Pengirim RNA</b>
                    </td>
                    <td width="60%">{{data.ekstraksi.nama_pengirim_rna }}</td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Tanggal Pengiriman RNA</b>
                    </td>
                    <td
                      width="60%"
                    >{{data.ekstraksi.tanggal_pengiriman_rna | formatDate }} pada {{data.ekstraksi.jam_pengiriman_rna }}</td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Catatan Pengiriman</b>
                    </td>
                    <td width="60%">{{data.ekstraksi.catatan_penerimaan}}</td>
                  </tr>
                </tbody>
              </table>

              <hr />
              <h3 class="header-title mt-2 mb-2">Riwayat Perubahan atau Pengiriman Kembali</h3>
              <table class="table table-bordered">
                <tr>
                  <th width="20%">Tanggal Perubahan</th>
                  <th>Keterangan</th>
                </tr>
                <tr v-if="data.log_ekstraksi.length == 0">
                  <td colspan="2"><em>Belum ada data terkait ekstraksi</em></td>
                </tr>
                <tr v-for="(item,idx) in data.log_ekstraksi" :key="idx">
                  <td>{{item.created_at | formatDateTime}}</td>
                  <td>{{item.metadata.catatan_penerimaan}} | {{item.metadata.catatan_pengiriman}}. {{item.description}}</td>
                </tr>
              </table>
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
    let resp = await axios.get("/v1/ekstraksi/detail/" + route.params.id);
    let data = resp.data.data
    if (!data.ekstraksi) {
      data.ekstraksi = {}
    }
    return { data };
  },
  head() {
    return {
      title: "Detil Ekstraksi dan Pengiriman Sampel"
    };
  }
};
</script>