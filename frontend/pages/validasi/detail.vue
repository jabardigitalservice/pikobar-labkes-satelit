<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Detail Sampel</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link :to="'/validasi/edit/' + this.data.id" class="btn btn-warning">
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
                    <td width="47%" align="right">
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
                      <b>Nomor Induk Kependudukan</b>
                    </td>
                    <td width="60%">
                      <span>{{data.pasien.no_ktp }}</span>
                    </td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Tanggal Lahir Pasien</b>
                    </td>
                    <td width="60%">
                      <span>{{data.pasien.tanggal_lahir | formatDate }}</span>
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
                      <b>Lab Penerima</b>
                    </td>
                    <td width="60%">
                      <span>{{data.lab_pcr_nama}}</span>
                    </td>
                  </tr>

                  <tr>
                    <td width="30%" align="right">
                      <b>Validator</b>
                    </td>
                    <td width="60%">
                      <span>{{data.validator.nama}} (NIP. {{ data.validator.nip }})</span>
                    </td>
                  </tr>

                </tbody>
              </table>

              <hr />

              <div class="row">
                <div class="col-md-6" 
                  v-for="hasil in data.pemeriksaan_sampel" 
                  :key="hasil.id"
                >
                  <Ibox title="Pemeriksaan Sampel">

                    <table class="table">
                      <tbody>
                        <tr>
                          <td align="right" width="47%">
                            <b>Tanggal Penerimaan Sampel</b>
                          </td>
                          <td>{{ hasil.tanggal_penerimaan_sampel | formatDate }}</td>
                        </tr>
                        <tr>
                          <td align="right">
                            <b>Petugas Penerimaan Sampel RNA</b>
                          </td>
                          <td>{{ hasil.petugas_penerimaan_sampel_rna }}</td>
                        </tr>
                        <tr>
                          <td align="right">
                            <b>Operator Realtime PCR</b>
                          </td>
                          <td>{{ hasil.operator_realtime_pcr }}</td>
                        </tr>
                        <tr>
                          <td align="right">
                            <b>Tanggal Mulai Pemeriksaan</b>
                          </td>
                          <td>{{ hasil.tanggal_mulai_pemeriksaan | formatDate }}</td>
                        </tr>
                        <tr>
                          <td align="right">
                            <b>Jam Mulai Pemeriksaan</b>
                          </td>
                          <td>{{ hasil.jam_mulai_pemeriksaan }}</td>
                        </tr>
                        <tr>
                          <td align="right">
                            <b>Jam Selesai Pemeriksaan</b>
                          </td>
                          <td>{{ hasil.jam_selesai_pemeriksaan }}</td>
                        </tr>
                        <tr>
                          <td align="right">
                            <b>Metode Pemeriksaan</b>
                          </td>
                          <td>{{ hasil.metode_pemeriksaan }}</td>
                        </tr>
                        <tr>
                          <td align="right">
                            <b>Nama Kit Pemeriksaan</b>
                          </td>
                          <td>{{ hasil.nama_kit_pemeriksaan }}</td>
                        </tr>
                        <tr>
                          <td align="right">
                            <b>Tanggal Input Hasil</b>
                          </td>
                          <td>{{ hasil.tanggal_input_hasil | formatDate }}</td>
                        </tr>
                        <tr>
                          <td align="right">
                            <b>Jam Input Hasil</b>
                          </td>
                          <td>{{ hasil.jam_input_hasil }}</td>
                        </tr>
                        <tr>
                          <td align="right">
                            <b>Kesimpulan Pemeriksaan</b>
                          </td>
                          <td>{{ hasil.kesimpulan_pemeriksaan }}</td>
                        </tr>
                      </tbody>
                    </table>

                    <table class="table dt-responsive table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>Target Gen</th>
                          <th>CT Value</th>
                        </tr>
                      </thead>
                      <tbody class="field_wrapper">
                        <tr v-for="(hasil, $index) in hasil.hasil_deteksi" :key="$index">
                          <td>
                            <p class="form-control">
                              <b>
                                {{ hasil.target_gen }}
                              </b>
                            </p>
                          </td>
                          <td>
                            <p class="form-control">
                              <b>
                                {{ hasil.ct_value }}
                              </b>
                            </p>
                          </td>
                        </tr>
                      </tbody>
                    </table>

                    

                  </Ibox>
                </div>
              </div>
              
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
    let resp = await axios.get("/v1/validasi/detail/" + route.params.id);
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