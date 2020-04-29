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
                </tbody>
              </table>

              <hr />

              <div class="row">
                <div class="col-md-6" 
                  v-for="hasil in data.pemeriksaan_sampel" 
                  :key="hasil.id"
                >
                  <Ibox title="Pemeriksaan Sampel">

                    <div class="form-group">
                      <label>Tanggal Input Hasil</label>
                      <p class="form-control">
                        <b>
                          {{ hasil.tanggal_input_hasil | formatDate }}
                        </b>
                      </p>
                    </div>

                    <div class="form-group">
                      <label>Jam Input Hasil</label>
                      <p class="form-control">
                        <b>
                          {{ hasil.jam_input_hasil }}
                        </b>
                      </p>
                    </div>

                    <div class="form-group">
                      <label>Tanggal Mulai Pemeriksaan</label>
                      <p class="form-control">
                        <b>{{ hasil.tanggal_mulai_pemeriksaan | formatDate }}</b>
                      </p>
                    </div>

                    <div class="form-group">
                      <label>Metode Pemeriksaan</label>
                      <p class="form-control">
                        <b>{{ hasil.metode_pemeriksaan }}</b>
                      </p>
                    </div>

                    <div class="form-group">
                      <label>Kesimpulan Pemeriksaan</label>
                      <p class="form-control">
                        <b>{{ hasil.kesimpulan_pemeriksaan }}</b>
                      </p>
                    </div>

                    <div class="form-group">
                      <label>Catatan Pemeriksaan</label>
                      <p class="form-control">
                        <b>{{ hasil.catatan_pemeriksaan }}</b>
                      </p>
                    </div>

                    <table class="table table-striped dt-responsive table-bordered" style="width:100%">
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