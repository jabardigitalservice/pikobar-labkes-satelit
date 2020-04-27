<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Detil Sampel - PCR dan Ekstraksi</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link :to="'/pcr/edit/' + this.data.id" class="btn btn-warning">
          <i class="fa fa-edit"></i> Ubah
        </router-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-md-6">
        <Ibox title="Informasi Detil RT-PCR">
          <table class="table">
            <tbody>
              <tr>
                <td width="40%" align="right">
                  <b>Tanggal penerimaan sampel</b>
                </td>
                <td
                  width="60%"
                >{{data.pcr.tanggal_penerimaan_sampel | formatDate}} pada {{data.pcr.jam_penerimaan_sampel}}</td>
              </tr>
              <tr>
                <td width="40%" align="right">
                  <b>Petugas Penerima Sampel</b>
                </td>
                <td width="60%">{{data.pcr.petugas_penerima_sampel_rna}}</td>
              </tr>
              <tr>
                <td width="40%" align="right">
                  <b>Catatan Penerimaan</b>
                </td>
                <td width="60%">{{data.pcr.catatan_penerimaan}}</td>
              </tr>
              <tr>
                <td width="40%" align="right">
                  <b>Operator Realtime PCR</b>
                </td>
                <td width="60%">{{data.pcr.operator_real_time_pcr}}</td>
              </tr>
              <tr>
                <td width="40%" align="right">
                  <b>Tanggal Mulai PCR</b>
                </td>
                <td
                  width="60%"
                >{{data.pcr.tanggal_mulai_pemeriksaan | formatDate }} pada {{data.pcr.jam_mulai_pcr}} sampai dengan {{data.pcr.jam_selesai_pcr}}</td>
              </tr>
              <tr>
                <td width="40%" align="right">
                  <b>Metode PCR</b>
                </td>
                <td width="60%">{{data.pcr.metode_pemeriksaan}}</td>
              </tr>
              <tr>
                <td width="40%" align="right">
                  <b>Nama Kit PCR</b>
                </td>
                <td width="60%">{{data.pcr.nama_kit_pemeriksaan}}</td>
              </tr>
              <tr>
                <td width="40%" align="right">
                  <b>Target Gen</b>
                </td>
                <td width="60%">{{data.pcr.target_gen }}</td>
              </tr>
              <tr>
                <td width="40%" align="right">
                  <b>Hasil Deteksi</b>
                </td>
                <td width="60%">
                  <table
                    class="table dt-responsive table-bordered"
                    style="width:100%"
                  >
                    <thead>
                      <tr>
                        <th>Target Gen</th>
                        <th>CT Value</th>
                      </tr>
                    </thead>
                    <tbody class="field_wrapper">
                      <tr v-for="(hasil, $index) in data.pcr.hasil_deteksi" :key="$index">
                        <td>
                          {{ hasil.target_gen }}
                        </td>
                        <td>
                          {{ hasil.ct_value }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
              <tr>
                <td width="40%" align="right">
                  <b>Grafik</b>
                </td>
                <td width="60%">
                  <a
                    :href="url"
                    target="_blank"
                    v-for="(url, $index) in data.pcr.grafik"
                    :key="$index"
                    class="thumbnail-wrapper"
                  >
                    <img :src="url" />
                  </a>
                </td>
              </tr>
              <tr>
                <td width="40%" align="right">
                  <b>Kesimpulan Pemeriksaan</b>
                </td>
                <td width="60%">{{data.pcr.kesimpulan_pemeriksaan }}</td>
              </tr>
              <tr>
                <td width="40%" align="right">
                  <b>Catatan Pemeriksaan</b>
                </td>
                <td width="60%">{{data.pcr.catatan_pemeriksaan}}</td>
              </tr>
            </tbody>
          </table>
        </Ibox>
      </div>
      <div class="col-md-6">
        <Ibox title="Informasi Detil Ekstraksi">
          <table class="table">
            <tbody>
              <tr>
                <td width="40%" align="right">
                  <b>Nomor Registrasi</b>
                </td>
                <td width="60%">
                  <span>{{data.nomor_register}}</span>
                </td>
              </tr>
              <tr>
                <td width="40%" align="right">
                  <b>Nomor Sampel</b>
                </td>
                <td width="60%">
                  <span>{{data.nomor_sampel}}</span>
                </td>
              </tr>
              <tr>
                <td width="40%" align="right">
                  <b>Tanggal penerimaan sampel</b>
                </td>
                <td
                  width="60%"
                >{{data.ekstraksi.tanggal_penerimaan_sampel | formatDate}} pada {{data.ekstraksi.jam_penerimaan_sampel}}</td>
              </tr>
              <tr>
                <td width="40%" align="right">
                  <b>Petugas Penerima Sampel</b>
                </td>
                <td width="60%">{{data.ekstraksi.petugas_penerima_sampel}}</td>
              </tr>
              <tr>
                <td width="40%" align="right">
                  <b>Catatan Penerimaan</b>
                </td>
                <td width="60%">{{data.ekstraksi.catatan_penerimaan}}</td>
              </tr>
              <tr>
                <td width="40%" align="right">
                  <b>Operator Ektraksi</b>
                </td>
                <td width="60%">{{data.ekstraksi.operator_ekstraksi}}</td>
              </tr>
              <tr>
                <td width="40%" align="right">
                  <b>Tanggal Mulai Ekstraksi</b>
                </td>
                <td
                  width="60%"
                >{{data.ekstraksi.tanggal_mulai_ekstraksi | formatDate }} pada {{data.ekstraksi.jam_mulai_ekstraksi}}</td>
              </tr>
              <tr>
                <td width="40%" align="right">
                  <b>Metode Ekstraksi</b>
                </td>
                <td width="60%">{{data.ekstraksi.metode_ekstraksi}}</td>
              </tr>
              <tr>
                <td width="40%" align="right">
                  <b>Nama Kit Ekstraksi</b>
                </td>
                <td width="60%">{{data.ekstraksi.nama_kit_ekstraksi}}</td>
              </tr>
              <tr>
                <td width="40%" align="right">
                  <b>Dikirim ke Lab</b>
                </td>
                <td width="60%">{{data.lab_pcr_nama}}</td>
              </tr>
              <tr>
                <td width="40%" align="right">
                  <b>Nama Pengirim RNA</b>
                </td>
                <td width="60%">{{data.ekstraksi.nama_pengirim_rna }}</td>
              </tr>
              <tr>
                <td width="40%" align="right">
                  <b>Tanggal Pengiriman RNA</b>
                </td>
                <td
                  width="60%"
                >{{data.ekstraksi.tanggal_pengiriman_rna | formatDate }} pada {{data.ekstraksi.jam_pengiriman_rna }}</td>
              </tr>
              <tr>
                <td width="40%" align="right">
                  <b>Catatan Pengiriman</b>
                </td>
                <td width="60%">{{data.ekstraksi.catatan_penerimaan}}</td>
              </tr>
            </tbody>
          </table>
        </Ibox>
      </div>
      <div class="col-md-12">
        <Ibox title="Riwayat Perubahan atau Pengiriman Kembali">
          <table class="table table-bordered">
            <tr>
              <th width="20%">Tanggal Perubahan</th>
              <th>Keterangan</th>
            </tr>
            <tr v-if="data.log_pcr.length == 0">
              <td colspan="2">
                <em>Belum ada data terkait ekstraksi</em>
              </td>
            </tr>
            <tr v-for="(item,idx) in data.log_pcr" :key="idx">
              <td>{{item.created_at | formatDateTime}}</td>
              <td>{{item.metadata.catatan_penerimaan}} | {{item.metadata.catatan_pemeriksaan}}. {{item.description}}</td>
            </tr>
          </table>
        </Ibox>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  middleware: "auth",
  async asyncData({ route }) {
    let resp = await axios.get("/v1/pcr/detail/" + route.params.id);
    let data = resp.data.data;
    if (!data.log_pcr) {
      data.log_pcr = [];
    }
    if (!data.ekstraksi) {
      data.ekstraksi = {};
    }
    if (!data.pcr) {
      data.pcr = {};
    }
    return { data };
  },
  head() {
    return {
      title: "Detil Sampel - PCR dan Ekstraksi"
    };
  }
};
</script>