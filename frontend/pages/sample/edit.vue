<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Pengambilan / Penerimaan Sampel Baru</portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Penerimaan atau Pengambilan Sampel">
          <h4 class="header-title mt-0 mb-1" v-if="selected_reg.reg_no!=null">No. Registrasi : #{{selected_reg.reg_no}}</h4>
          <h4 class="header-title mt-0 mb-1" v-if="selected_reg.reg_nik!=null">No. Induk Kependudukan : {{selected_reg.reg_nik}}</h4>
          <form @submit.prevent="submit" @keydown="form.onKeydown($event)">
            <input type="hidden" v-model="form.pen_noreg" />
            <input type="hidden" v-model="form.pen_nik" />

            <div class="form-group row mt-4">
              <label class="col-md-2">
                Sumber Sampel
                <span style="color:red">*</span>
              </label>
              <div class="col-md-6">
                <div class="form-check form-check-inline"
                  :class="{ 'is-invalid': form.errors.has('pen_sampel_sumber') }">
                  <label class="form-check-label">
                  <input
                    class="form-check-input"
                    type="radio"
                    v-model="form.pen_sampel_sumber"
                    value="Mandiri"
                  />
                  Mandiri</label>
                </div>
                <div class="form-check form-check-inline">
                  <label class="form-check-label">
                  <input
                    class="form-check-input"
                    type="radio"
                    v-model="form.pen_sampel_sumber"
                    value="Rujukan Dinkes"
                  />
                  Rujukan Dinkes</label>
                </div>
                <div class="form-check form-check-inline">
                  <label class="form-check-label">
                  <input
                    class="form-check-input"
                    type="radio"
                    v-model="form.pen_sampel_sumber"
                    value="Rujukan RS"
                  />
                  Rujukan RS</label>
                </div>
                <has-error :form="form" field="pen_sampel_sumber" />
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
                    :class="{ 'is-invalid': form.errors.has(`samples.${$index}.sam_jenis_sampel`) }">
                      <option :value="js.id" v-for="(js, $index2) in jenis_sampel" :key="$index2">{{ js.text }}</option>
                    </select>
                    <has-error :form="form" :field="`samples.${$index}.sam_jenis_sampel`"/>
                    <div v-if="sample.sam_jenis_sampel == 999999">
                      <small for="specify">Jenis Lainnya (isi apabila tidak tercantum diatas)</small>
                      <input
                        type="text"
                        class="form-control"
                        v-model="sample.sam_namadiluarjenis"
                        placeholder="isi apabila tidak tercantum"
                        :class="{ 'is-invalid': form.errors.has(`samples.${$index}.sam_namadiluarjenis`) }"
                      />
                      <has-error :form="form" :field="`samples.${$index}.sam_namadiluarjenis`"/>
                    </div>
                  </td>
                  <td>
                    <input class="form-control" type="text" v-model="sample.petugas_pengambil" 
                      :class="{ 'is-invalid': form.errors.has(`samples.${$index}.petugas_pengambil`) }"/>
                    <has-error :form="form" :field="`samples.${$index}.petugas_pengambil`"/>
                  </td>
                  <td>
                    <date-picker
                      placeholder="Pilih Tanggal"
                      format="d MMMM yyyy"
                      input-class="form-control"
                      :monday-first="true"
                      :wrapper-class="{ 'is-invalid': form.errors.has(`samples.${$index}.tanggalsampel`) }"
                      v-model="sample.tanggalsampel" />
                    <has-error :form="form" :field="`samples.${$index}.tanggalsampel`"/>
                  </td>
                  <td>
                    <input class="form-control" type="text" v-model="sample.pukulsampel" 
                      v-mask="'##\:##'" 
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
                <!-- <tr>
                  <td colspan="4"></td>
                  <td colspan="2">
                    <button class="btn btn-sm btn-secondary" @click.prevent="addSample()"><i class="fa fa-plus"></i> Tambah Sampel</button>
                  </td>
                </tr> -->
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
import axios from 'axios';
import { mapGetters } from "vuex";

export default {
  middleware: "auth",
  computed: mapGetters({
    jenis_sampel: "options/jenis_sampel",
  }),
   async asyncData({route, store}) {
    let error = false;
    let resp = await axios.get("/sample/edit/"+route.params.id);
    if (!store.getters['options/jenis_sampel'].length) {
      await store.dispatch('options/fetchJenisSampel')
    }
    let data = resp.data.result;
    let _sample = []
    // data.sampels.forEach(item => {
    //   _sample.push({
    //     id_sampel : item.id,
    //     nomor_sampel : item.nomor_sampel,
    //     sam_jenis_sampel:item.jenis_sampel_id,
    //     tanggalsampel:item.tanggal_pengambilan_sampel,
    //     pukulsampel:item.jam_pengambilan_sampel
    //   })
    // });
    // console.log(_sample)
    // console.log(data)
    return {
      // data:null,
      form: new Form({
        id : data.id,
        pen_noreg: null,
        pen_nik: null,
        pen_sampel_sumber: data.sumber_sampel,
        pen_penerima_sampel: data.penerima_sampel,
        pen_catatan: data.catatan,
        samples: data.sampels
      }),
      selected_reg: {}
    };
  },
  methods: {
    initForm() {
      this.form = new Form({
        pen_noreg: null,
        pen_nik: null,
        pen_sampel_sumber:null,
        pen_penerima_sampel: null,
        pen_catatan: null,
        samples: [{
          tanggalsampel: new Date,
          pukulsampel: (new Date).getHours()*100 + (new Date).getMinutes(),
        }],
      })
    },
    addSample() {
      this.form.samples.push({
        tanggalsampel: new Date,
        pukulsampel: (new Date).getHours()*100 + (new Date).getMinutes(),
        nomorsampel:null,
        id_sampel:null,
      })
    },
    async removeSample(index) {
      if (this.form.samples.length <= 1) {
        this.$toast.error('Jumlah sampel minimal satu', {
          duration: 5000
        })
        return
      }
      var _sam = this.form.samples[index].id_sampel;
      await axios.get(`sample/delete/${_sam}`)
      this.form.samples.splice(index, 1)
    },
    async submit() {
      // Submit the form.
      try {
        const response = await this.form.post("/sample/update/"+this.form.id);
        // this.$toast.success(response.data.message, {
        //   icon: 'check',
        //   iconPack: 'fontawesome',
        //   duration: 5000
        // })
        // this.initForm()
        this.$swal.fire("Success", "Berhasil update data", "success");
        this.$router.push('/sample')
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
  },
};
</script>
