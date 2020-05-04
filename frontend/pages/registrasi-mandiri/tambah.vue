<template>
    <div class="wrapper wrapper-content">
        <portal to="title-name">
            Registrasi Pasien
        </portal>
        <portal to="title-action">
            <div class="title-action">
                <nuxt-link to="/registrasi/mandiri" class="btn btn-primary">Kembali</nuxt-link>
            </div>
        </portal>

        <div class="row">
            <div class="col-lg-12">
                <Ibox title="Penerimaan atau Pengambilan Sampel">
                    <form @submit.prevent="submit" @keydown="form.onKeydown($event)">
                        <div class="form-group row mt-4">
                            <label class="col-md-2">
                                Nomor Registrasi
                                <span style="color:red">*</span>
                            </label>
                            <div class="col-md-6" :class="{ 'is-invalid': form.errors.has('reg_no') }">
                                <input class="form-control" type="text" name="reg_no" placeholder="Nomor Registrasi"
                                    required v-model="form.reg_no" disabled />
                                <has-error :form="form" field="reg_no" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">Kewarganegaraan <span style="color:red;">*</span></label>
                            <div class="col-md-6">
                                <select v-model="form.reg_kewarganegaraan"
                                    :class="{ 'is-invalid':form.errors.has('reg_kewarganegaraan') }"
                                    class="multisteps-form__input form-control col-md-6" name="reg_kewarganegaraan">
                                    <option value="WNI">WNI</option>
                                    <option value="WNA">WNA</option>
                                </select>
                                <has-error :form="form" field="reg_kewarganegaraan" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">
                                Sumber Pasien
                                <span style="color:red">*</span>
                            </label>
                            <div class="col-md-6">
                                <div class="form-check form-check-inline"
                                    :class="{ 'is-invalid': form.errors.has('reg_sumberpasien') }">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="radio" v-model="form.reg_sumberpasien"
                                            value="Mandiri" />
                                        Mandiri</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="radio" v-model="form.reg_sumberpasien"
                                            value="Dinkes" />
                                        Dinkes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="radio" v-model="form.reg_sumberpasien"
                                            value="RDT" />
                                        RDT</label>
                                </div>
                                <has-error :form="form" field="reg_sumberpasien" />
                            </div>
                        </div>

                        <hr>
                        <h4 class="mb-1 mt-0">
                            Identitas Pasien
                        </h4>
                        <p>Lengkapi Form dengan Identitas Pasien</p>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">
                                Nama Pasien
                                <span style="color:red">*</span>
                            </label>
                            <div class="col-md-6" :class="{ 'is-invalid': form.errors.has('reg_nama_pasien') }">
                                <input class="form-control" type="text" name="reg_nama_pasien" placeholder="" required
                                    v-model="form.reg_nama_pasien" />
                                <has-error :form="form" field="reg_nama_pasien" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">
                                NIK
                                <span style="color:red">*</span>
                            </label>
                            <div class="col-md-6" :class="{ 'is-invalid': form.errors.has('reg_nik') }">
                                <input class="form-control" type="text" name="reg_nik" placeholder="" required
                                    v-model="form.reg_nik" maxlength="16" />
                                <has-error :form="form" field="reg_nik" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">
                                Tempat Lahir
                                <span style="color:red">*</span>
                            </label>
                            <div class="col-md-6" :class="{ 'is-invalid': form.errors.has('reg_tempatlahir') }">
                                <input class="form-control" type="text" name="reg_tempatlahir" placeholder="" required
                                    v-model="form.reg_tempatlahir" />
                                <has-error :form="form" field="reg_tempatlahir" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">
                                Tanggal Lahir
                                <span style="color:red">*</span>
                            </label>
                            <div class="col-md-6" :class="{ 'is-invalid': form.errors.has('reg_tgllahir') }">
                                <dropdown-datepicker 
                                    v-model="form.reg_tgllahir"
                                    :minYear="1900"
                                    :daySuffixes="false"
                                    :maxYear="(new Date).getFullYear()"
                                    displayFormat="dmy"
                                    sortYear="asc"
                                    :default-date="nik_tgl"
                                    ref="tgl_lahir"
                                    :wrapper-class="form.errors.has('reg_tgllahir') ? 'is-invalid' : ''"
                                ></dropdown-datepicker>
                                <has-error :form="form" field="reg_tgllahir" />
                            </div>
                        </div>

                         <div class="form-group row mt-4">
                            <label class="col-md-2">
                                Jenis Kelamin
                                <span style="color:red">*</span>
                            </label>
                            <div class="col-md-6">
                                <div class="form-check form-check-inline"
                                    :class="{ 'is-invalid': form.errors.has('reg_jk') }">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="radio" v-model="form.reg_jk"
                                            value="L" />
                                        Laki-laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="radio" v-model="form.reg_jk"
                                            value="P" />
                                        Perempuan</label>
                                </div>
                                <has-error :form="form" field="reg_jk" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">
                                No. Telp / HP
                                <span style="color:red">*</span>
                            </label>
                            <div class="col-md-6" :class="{ 'is-invalid': form.errors.has('reg_nohp') }">
                                <input class="form-control" type="text" name="reg_nohp" placeholder="" required
                                    v-model="form.reg_nohp" />
                                <has-error :form="form" field="reg_nohp" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">
                                Kota / Kabupaten
                                <span style="color:red">*</span>
                            </label>
                            <div class="col-md-6" :class="{ 'is-invalid': form.errors.has('reg_kota') }">
                                <select class="form-control" type="text" name="reg_kota" placeholder="" required
                                    v-model="form.reg_kota">
                                    <option :value="item.id" :key="idx" v-for="(item,idx) in optionKota">{{item.nama}}
                                    </option>
                                </select>
                                <has-error :form="form" field="reg_kota" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">
                                Kecamatan
                            </label>
                            <div class="col-md-6" :class="{ 'is-invalid': form.errors.has('reg_kecamatan') }">
                                <!-- <select class="form-control" type="text" name="reg_kecamatan" placeholder=""
                                    required v-model="form.reg_kecamatan" >
                                    <option :value="item.id" :key="idx" v-for="(item,idx) in optionKecamatan">{{item.nama}}</option>
                                </select> -->
                                <input v-model="form.reg_kecamatan" class="form-control" type="text"
                                    name="reg_kecamatan" />
                                <has-error :form="form" field="reg_kecamatan" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">
                                Kelurahan / Desa
                            </label>
                            <div class="col-md-6" :class="{ 'is-invalid': form.errors.has('reg_kelurahan') }">
                                <!-- <select class="form-control" type="text" name="reg_kelurahan" placeholder=""
                                    required v-model="form.reg_kelurahan" >
                                    <option :value="item.id" :key="idx" v-for="(item,idx) in optionKelurahan">{{item.nama}}</option>
                                </select> -->
                                <input class="multisteps-form__input form-control" type="text" name="reg_kelurahan"
                                     v-model="form.reg_kelurahan" />
                                <has-error :form="form" field="reg_kelurahan" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">
                                Alamat
                                <span style="color:red">*</span>
                            </label>
                            <div class="col-md-6" :class="{ 'is-invalid': form.errors.has('reg_alamat') }">
                                <textarea class="multisteps-form__input form-control" type="text" name="reg_alamat"
                                    required v-model="form.reg_alamat"></textarea>
                                <has-error :form="form" field="reg_alamat" />
                            </div>
                        </div>

                        <div class="row mt-2">
                            <label class="col-md-2">
                                RT / RW
                            </label>
                            <div class="input-group col-md-2" :class="{ 'is-invalid':form.errors.has('reg_rt') }">
                                <span class="input-group-addon bootstrap-touchspin-postfix input-group-append">
                                    <span class="input-group-text">RT </span>
                                </span>
                                <input class="form-control" type="number" name="reg_rt" v-model="form.reg_rt" min="1" step="1" max="999">
                            </div>
                            <div class="input-group col-md-2" :class="{ 'is-invalid':form.errors.has('reg_rw') }">
                                <div class="input-group-addon bootstrap-touchspin-postfix input-group-append">
                                    <span class="input-group-text">RW </span>
                                </div>
                                <input class="form-control" type="number" name="reg_domisilirw" min="1" step="1" max="999"
                                    v-model="form.reg_rw" />
                            </div>
                            <has-error :form="form" field="reg_rt" />
                            <has-error :form="form" field="reg_rw" />
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">
                                Suhu (°C)
                            </label>
                            <div class="col-md-6">
                                <input class="form-control" type="text" v-model="form.reg_suhu " placeholder=""
                                    :class="{ 'is-invalid': form.errors.has('reg_suhu') }" v-mask="'##,##'"/>
                                <has-error :form="form" field="reg_suhu" />
                            </div>
                        </div>
                        <hr>
                        <h4 class="mb-1 mt-0">
                            Identitas Sampel
                        </h4>

                        <div class="form-group row" v-for="(item,idx) in form.reg_sampel" :key="idx">
                            <label class="col-md-2 col-form-label" for="sampel">Nomor Sampel {{idx+1}}</label>
                            <div class="col-md-4">
                                <input class="form-control" type="text" name="pen_sampel" v-model="item.nomor"
                                    :class="{ 'is-invalid':form.errors.has(`reg_sampel.${idx}`) }" required />
                            </div>
                            <div class="col-md-2 mt-2">
                                <span>
                                    <button class="btn btn-danger btn-xs" type="button"
                                        v-show="idx || ( !idx && form.reg_sampel.length > 1)">
                                        <i class="fa fa-minus-circle" @click="removeSample(idx)"></i></button>
                                    <button class="btn btn-success btn-xs" type="button" @click="addSample(idx)"
                                        v-show="idx == form.reg_sampel.length-1"><i
                                            class="fa fa-plus-circle"></i></button>
                                </span>
                            </div>
                            <has-error :form="form" :field="`reg_sampel.${idx}`" />
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">
                                Keterangan Lainnya
                            </label>
                            <div class="col-md-6" :class="{ 'is-invalid': form.errors.has('reg_keterangan') }">
                                <textarea class="form-control" type="text" name="reg_keterangan"
                                    v-model="form.reg_keterangan" rows="6"></textarea>
                                <has-error :form="form" field="reg_keterangan" />
                            </div>
                        </div>

                        <hr />
                        <h4 class="mb-1 mt-0">Riwayat Kunjungan</h4>
                        <p>Isi pada baris yang merupakan kali kunjungan saat ini.</p>
                        <div class="form-group row mt-4" :class="{ 'is-invalid':form.errors.has('reg_kunke') }">
                            <label class="col-md-2">Kunjungan Ke <span style="color:red;">*</span></label>
                            <div class="col-md-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="kunke1" name="reg_kunke" value="1"
                                        required v-model="form.reg_kunke">
                                    <label class="form-check-label" for="kunke1">Ke-1</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="kunke2" name="reg_kunke" value="2"
                                        v-model="form.reg_kunke">
                                    <label class="form-check-label" for="kunke2">Ke-2</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="kunke3" name="reg_kunke" value="3"
                                        v-model="form.reg_kunke">
                                    <label class="form-check-label" for="kunke3">Ke-3</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="kunke4" name="reg_kunke" value="4"
                                        v-model="form.reg_kunke">
                                    <label class="form-check-label" for="kunke4">Ke-4</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="kunke5" name="reg_kunke" value="5"
                                        v-model="form.reg_kunke">
                                    <label class="form-check-label" for="kunke5">Ke-5</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="kunke6" name="reg_kunke" value="6"
                                        v-model="form.reg_kunke">
                                    <label class="form-check-label" for="kunke6">Ke-6</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="kunke7" name="reg_kunke" value="7"
                                        v-model="form.reg_kunke">
                                    <label class="form-check-label" for="kunke7">Ke-7</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <div class="col-md-4" :class="{ 'is-invalid':form.errors.has('reg_tanggalkunjungan') }">
                                <label>Tanggal Kunjungan</label>
                                <date-picker placeholder="Tanggal Kunjungan" format="d MMMM yyyy"
                                    input-class="form-control" :monday-first="true"
                                    :wrapper-class="{ 'is-invalid': form.errors.has('reg_tanggalkunjungan') }"
                                    v-model="form.reg_tanggalkunjungan" />
                                <has-error :form="form" field="reg_tanggalkunjungan" />
                            </div>
                            <div class="col-md-6" :class="{ 'is-invalid':form.errors.has('reg_rsfasyankes') }">
                                <label>Rumah Sakit / Fasyankes</label>
                                <input class="form-control" type="text" name="reg_rsfasyankes"
                                    placeholder="Nama RS/Fasyankes" v-model="form.reg_rsfasyankes" />
                                <has-error :form="form" field="reg_rsfasyankes" />
                            </div>
                        </div>

                        <div class="form-group row mt-4 text-center justify-content-center">
                            <v-button :loading="form.busy" class="btn btn-md btn-primary block  m-b">
                                <i class="fa fa-save"></i> Simpan Data Register
                            </v-button>
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

    export default {
        middleware: "auth",
        data() {
            return {
                form: new Form({
                    reg_no: null,
                    reg_kewarganegaraan: null,
                    reg_sumberpasien: null,
                    reg_nama_pasien: null,
                    reg_nik: null,
                    reg_tempatlahir: null,
                    reg_tgllahir: null,
                    reg_nohp: null,
                    reg_kota: null,
                    reg_kecamatan: null,
                    reg_kelurahan: null,
                    reg_alamat: null,
                    reg_rt: null,
                    reg_rw: null,
                    reg_suhu: null,
                    reg_sampel: [{
                        nomor: null
                    }],
                    reg_keterangan: null,
                    reg_gejpanas:null,
                    reg_gejpenumonia:null,
                    reg_gejbatuk:null,
                    reg_gejnyeritenggorokan:null,
                    reg_gejsesaknafas:null,
                    reg_gejpilek:null,
                    reg_gejlesu:null,
                    reg_gejsakitkepala:null,
                    reg_gejdiare:null,
                    reg_gejmualmuntah:null,
                    reg_gejlain:null,
                    reg_jk:null,
                    reg_tanggalkunjungan: null,
                    reg_kunke: null,
                    reg_rsfasyankes: null,

                }),
                selected_reg: {},
                optionKota: [],
                optionKecamatan: [],
                optionKelurahan: [],
                nik_tgl: null,
            };
        },
        methods: {
            async getNoreg() {
                let resp = await axios.get('/v1/register/noreg')
                this.form.reg_no = resp.data
            },
            initForm() {
               this.form =  new Form({
                    reg_no: null,
                    reg_kewarganegaraan: null,
                    reg_sumberpasien: null,
                    reg_nama_pasien: null,
                    reg_nik: null,
                    reg_tempatlahir: null,
                    reg_tgllahir: null,
                    reg_nohp: null,
                    reg_kota: null,
                    reg_kecamatan: null,
                    reg_kelurahan: null,
                    reg_alamat: null,
                    reg_rt: null,
                    reg_rw: null,
                    reg_suhu: null,
                    reg_sampel: [{
                        nomor: null
                    }],
                    reg_keterangan: null,
                    reg_gejpanas:null,
                    reg_gejpenumonia:null,
                    reg_gejbatuk:null,
                    reg_gejnyeritenggorokan:null,
                    reg_gejsesaknafas:null,
                    reg_gejpilek:null,
                    reg_gejlesu:null,
                    reg_gejsakitkepala:null,
                    reg_gejdiare:null,
                    reg_gejmualmuntah:null,
                    reg_gejlain:null,
                    reg_jk:null,
                    
                    reg_tanggalkunjungan: null,
                    reg_kunke: null,
                    reg_rsfasyankes: null,
                })
            },
            addSample() {
                // console.log(this.form.reg_sampel)
                this.form.reg_sampel.push({
                    nomor: ""
                })
            },
            removeSample(index) {
                this.form.reg_sampel.splice(index, 1)
            },
            async getKota() {
                const resp = await axios.get('/v1/list-kota-jabar');
                this.optionKota = resp.data;
            },
            async submit() {
                // Submit the form.
                try {
                    const response = await this.form.post("/v1/register/mandiri");
                    this.$toast.success(response.data.message, {
                        icon: 'check',
                        iconPack: 'fontawesome',
                        duration: 5000
                    })
                    // console.log('Response : ', response);
                    this.initForm();
                    this.getNoreg();
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
            return {
                title: "Pengambilan / Penerimaan Sampel Baru"
            };
        },
        created() {
            this.getKota()
            this.getNoreg();
        },
        watch: {
            "form.reg_kota": function (newVal, oldVal) {

            },
            "form.reg_nik": function (newVal, oldVal) {
                if (newVal && newVal.length >= 12) {
                    let dd = parseInt(newVal.substr(6,2))
                    if (dd >= 40) {
                        this.form.reg_jk = 'P'
                        dd -= 40
                    } else {
                        this.form.reg_jk = 'L'
                    }
                    let mm = parseInt(newVal.substr(8,2))
                    let yy = parseInt(newVal.substr(10,2))
                    if (yy <= 30) {
                        let str = '' + (2000+yy) +'-'+ ('' + mm).padStart(2,'0') +'-'+ ('' + dd).padStart(2,'0')
                        this.form.reg_tgllahir = str
                        this.nik_tgl = str
                    } else {
                        let str = '' + (1900+yy) +'-'+ ('' + mm).padStart(2,'0') +'-'+ ('' + dd).padStart(2,'0')
                        this.form.reg_tgllahir = str
                        this.nik_tgl = str
                    }
                    this.$nextTick(() => {
                        this.$refs.tgl_lahir.init();
                    })
                }
            },

        }
    };
</script>