<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Pengambilan / Penerimaan Sampel Baru</portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Penerimaan atau Pengambilan Sampel">
          <h4 class="header-title mt-0 mb-1">No. Registrasi : #{{selected_reg.reg_no}}</h4>
          <h4 class="header-title mt-0 mb-1">No. Induk Kependudukan : {{selected_reg.reg_no}}</h4>
          <form @submit.prevent="submit" @keydown="form.onKeydown($event)">
            <input type="hidden" v-model="form.pen_noreg" />

            <input type="hidden" v-model="form.pen_nik" />
            <div class="form-group row mt-4">
              <label class="col-md-2">
                Sampel Diambil
                <span style="color:red">*</span>
              </label>
              <div class="col-md-6">
                <div class="form-check form-check-inline"
                  :class="{ 'is-invalid': form.errors.has('pen_sampel_diambil') }">
                  <label class="form-check-label">
                  <input
                    class="form-check-input"
                    type="radio"
                    v-model="form.pen_sampel_diambil"
                    value="1"
                  />
                  Ya</label>
                </div>
                <div class="form-check form-check-inline">
                  <label class="form-check-label">
                  <input
                    class="form-check-input"
                    type="radio"
                    v-model="form.pen_sampel_diambil"
                    value="0"
                  />
                  Tidak</label>
                </div>
                <has-error :form="form" field="pen_sampel_diambil" />
              </div>
            </div>

            <div class="form-group row mt-4">
              <label class="col-md-2">
                Sampel Diterima
                <span style="color:red">*</span>
              </label>
              <div class="col-md-6">
                <div class="form-check form-check-inline"
                  :class="{ 'is-invalid': form.errors.has('pen_sampel_diterima') }">
                  <label class="form-check-label">
                  <input
                    class="form-check-input"
                    type="radio"
                    v-model="form.pen_sampel_diterima"
                    value="1"
                  />
                  Ya</label>
                </div>
                <div class="form-check form-check-inline">
                  <label class="form-check-label">
                  <input
                    class="form-check-input"
                    type="radio"
                    v-model="form.pen_sampel_diterima"
                    value="0"
                  />
                  Tidak</label>
                </div>
                <has-error :form="form" field="pen_sampel_diterima" />
              </div>
            </div>

            <div class="form-group row mt-4">
              <label class="col-md-2">
                Sampel Diambil dari Fasyankes Rujukan
                <span style="color:red">*</span>
              </label>
              <div class="col-md-6">
                <div class="form-check form-check-inline"
                  :class="{ 'is-invalid': form.errors.has('pen_sampel_diterima_dari_fas_rujukan') }">
                  <label class="form-check-label">
                  <input
                    class="form-check-input"
                    type="radio"
                    v-model="form.pen_sampel_diterima_dari_fas_rujukan"
                    value="1"
                  />
                  Ya</label>
                </div>
                <div class="form-check form-check-inline">
                  <label class="form-check-label">
                  <input
                    class="form-check-input"
                    type="radio"
                    v-model="form.pen_sampel_diterima_dari_fas_rujukan"
                    value="0"
                  />
                  Tidak</label>
                </div>
                <has-error :form="form" field="pen_sampel_diterima_dari_fas_rujukan" />
              </div>
            </div>

            <div class="form-group row mt-4">
              <label class="col-md-2">
                Petugas Penerima Sampel
                <small>Isi bila diterima dari fasyankes rujukan</small>
              </label>
              <div class="col-md-6">
                <input
                  class="form-control"
                  type="text"
                  v-model="form.pen_penerima_sampel "
                  placeholder="Nama Petugas Penerima Sampel"
                  :class="{ 'is-invalid': form.errors.has('pen_penerima_sampel') }"
                />
                <has-error :form="form" field="pen_penerima_sampel" />
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-2 col-form-label" for="gejlain">Catatan Lain</label>
              <div class="col-md-10">
                <textarea
                  class="form-control"
                  rows="5"
                  v-model="form.pen_catatan"
                  :class="{ 'is-invalid': form.errors.has('pen_catatan') }"
                  placeholder="Catatan(Catat bila kualitas sampel kurang baik, jumlah material terlalu sedikit, pengepakan atau pengiriman sampel kurang layak, atau pengambilan serum akut/konvalesen tidak sesuai rentang waktu, dll)"
                ></textarea>
                <has-error :form="form" field="pen_catatan" />
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-2 col-form-label">
                Nomor Ekstraksi
                <span style="color:red">*</span>
              </label>
              <div class="col-md-10">
                <input
                  class="form-control"
                  type="text"
                  v-model="form.pen_nomor_ekstraksi"
                  placeholder="Nomor Ekstraksi"
                  :class="{ 'is-invalid': form.errors.has('pen_nomor_ekstraksi') }"
                />
                <has-error :form="form" field="pen_nomor_ekstraksi" />
              </div>
            </div>
            <hr />
            <h4 class="mb-1 mt-0">
              Sampel
              <span style="color:red">*</span>
            </h4>
            <p>Dibawah ini adalah sampel yang diambil atau diterima, klik tambahkan sesuai dengan banyaknya sampel</p>
            <table class="table table-striped dt-responsive table-bordered" style="width:100%">
              <thead>
                <tr>
                  <th>Jenis Sampel</th>
                  <th>Petugas pengambil sampel</th>
                  <th>Tanggal</th>
                  <th>Pukul</th>
                  <th>Nomor sampel</th>
                  <th></th>
                </tr>
              </thead>
              <tbody class="field_wrapper">
                <tr v-for="(sample, $index) in form.samples" :key="$index">
                  <td>
                    <select class="form-control" v-model="sample.sam_jenis_sampel"
                    :class="{ 'is-invalid': form.errors.has(`samples.${$index}.sam_namadiluarjenis`) }">
                      <option value="1">Usap Nasofaring & Orofaring</option>
                      <option value="2">Sputum</option>
                      <option value="3">Bronchoalveolar Lavage</option>
                      <option value="4">Tracheal Aspirate</option>
                      <option value="5">Nasal Wash</option>
                      <option value="6">Jaringan Biopsi/Otopsi</option>
                      <option value="7">Serum Akut (kurang dari 7 hari demam)</option>
                      <option value="8">Serum konvalesen (2-3 minggu demam)</option>
                      <option value="9">Whole blood</option>
                      <option value="10">Plasma</option>
                      <option value="11">Fingerprick</option>
                      <option value="12">Jenis Sampel Lainnya (Sebutkan)</option>
                    </select>
                    <has-error :form="form" :field="`samples.${$index}.sam_namadiluarjenis`"/>
                    <div v-if="sample.sam_jenis_sampel == 12">
                      <small for="specify">Jenis Lainnya (isi apabila tidak tercantum diatas)</small>
                      <input
                        type="text"
                        class="form-control"
                        v-model="sample.sam_namadiluarjenis"
                        placeholder="isi apabila tidak tercantum"
                      />
                    </div>
                  </td>
                  <td>
                    <input class="form-control" type="text" v-model="sample.petugas_pengambil" 
                      :class="{ 'is-invalid': form.errors.has(`samples.${$index}.petugas_pengambil`) }"/>
                    <has-error :form="form" :field="`samples.${$index}.petugas_pengambil`"/>
                  </td>
                  <td>
                    <input class="form-control" type="text" v-model="sample.tanggalsampel" 
                      :class="{ 'is-invalid': form.errors.has(`samples.${$index}.tanggalsampel`) }"/>
                    <has-error :form="form" :field="`samples.${$index}.tanggalsampel`"/>
                  </td>
                  <td>
                    <input class="form-control" type="text" v-model="sample.pukulsampel" 
                      :class="{ 'is-invalid': form.errors.has(`samples.${$index}.pukulsampel`) }"/>
                    <has-error :form="form" :field="`samples.${$index}.pukulsampel`"/>
                  </td>
                  <td>
                    <input class="form-control" type="text" v-model="sample.nomorsampel" 
                      :class="{ 'is-invalid': form.errors.has(`samples.${$index}.nomorsampel`) }"/>
                    <has-error :form="form" :field="`samples.${$index}.nomorsampel`"/>
                  </td>
                  <td>
                    <button class="btn btn-sm btn-danger remove_field" @click.prevent="removeSample($index)">
                      <i class="uil-trash"></i>
                    </button>
                  </td>
                </tr>
                <tr>
                  <td colspan="4"></td>
                  <td colspan="2">
                    <button class="btn btn-sm btn-secondary" @click.prevent="addSample()"><i class="fa fa-plus"></i> Tambah Sampel</button>
                  </td>
                </tr>
              </tbody>
            </table>

            <div class="form-group row mt-4">
              <div class="col-md-4">
                <v-button :loading="form.busy" class="btn btn-md btn-primary block full-width m-b">
                  <i class="fa fa-save"></i> Simpan Data Sampel
                </v-button>
              </div>
            </div>
          </form>
        </Ibox>
      </div>
    </div>
  </div>
</template>
 
<script>
import Form from "vform";

export default {
  middleware: "auth",
  data() {
    return {
      form: new Form({
        pen_noreg: "",
        pen_nik: "",
        pen_sampel_diambil: "",
        pen_sampel_diambil: "",
        pen_sampel_diterima: "",
        pen_sampel_diterima: "",
        pen_sampel_diterima_dari_fas_rujukan: "",
        pen_sampel_diterima_dari_fas_rujukan: "",
        pen_penerima_sampel: "",
        pen_catatan: "",
        pen_nomor_ekstraksi: "",
        samples: [{

        }],
      }),
      selected_reg: {}
    };
  },
  methods: {
    initForm() {
      this.form = new Form({
        pen_noreg: "",
        pen_nik: "",
        pen_sampel_diambil: "",
        pen_sampel_diambil: "",
        pen_sampel_diterima: "",
        pen_sampel_diterima: "",
        pen_sampel_diterima_dari_fas_rujukan: "",
        pen_sampel_diterima_dari_fas_rujukan: "",
        pen_penerima_sampel: "",
        pen_catatan: "",
        pen_nomor_ekstraksi: "",
        samples: [{

        }],
      })
    },
    addSample() {
      this.form.samples.push({})
    },
    removeSample(index) {
      if (this.form.samples.length <= 1) {
        this.$toast.error('Jumlah sampel minimal satu', {
          duration: 5000
        })
        return
      }
      this.form.samples.splice(index, 1)
    },
    async submit() {
      // Submit the form.
      try {
        const response = await this.form.post("/sample/add");
        this.$toast.success(response.data.message, {
          icon: 'check',
          iconPack: 'fontawesome',
          duration: 5000
        })
        this.initForm()
      } catch (err) {
        if (err.response && err.response.data.code == 422) {
          this.$nextTick(() => {
            this.form.errors.set(err.response.data.error)
          })
          this.$toast.error('Mohon cek kembali formulir Anda', {
            icon: 'times',
            iconPack: 'fontawesome',
            duration: 5000
          })
        } else {
          this.$swal.fire("Terjadi kesalahan", "Silakan hubungi Admin", "error");
        }
        return;
      }
    }
  },
  head() {
    return { title: "Pengambilan / Penerimaan Sampel Baru" };
  }
};
</script>
