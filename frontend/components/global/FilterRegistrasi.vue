<template>
    <Ibox title="Filter Data">

        <div class="form-group row">
            <div class="col-md-2">
                <label for="nama_pasien">Nama Pasien / NIK</label>
            </div>
            <div class="col-md-4">
                <input type="text" name="nama_pasien" v-model="params.nama_pasien" id="" class="form-control"
                    placeholder="Ketikkan Nama Pasien / NIK">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-2">
                <label for="nama_pasien">Tanggal Input</label>
            </div>
            <div class="col-md-4">
                <date-picker placeholder="Tanggal Mulai Input" format="d MMMM yyyy" input-class="form-control"
                    :monday-first="true" v-model="params.start_date" />
            </div>
            <div class="col-md-1 my-1">
                S.d
            </div>
            <div class="col-md-4">
                <date-picker placeholder="Tanggal Akhir Input" format="d MMMM yyyy" input-class="form-control"
                    :monday-first="true" v-model="params.end_date" />
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-2">
                <label for="">Kategori</label>
            </div>
            <div class="col-md-4">
                <input type="text" name="params.sumber_pasien" v-model="params.sumber_pasien" id="" class="form-control"
                    placeholder="Kategori">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-2">
                <label for="nama_pasien">Domisili</label>
            </div>
            <div class="col-md-4">
                <select class="form-control" type="text" name="reg_kota" placeholder="" v-model="params.kota">
                    <option :value="item.id" :key="idx" v-for="(item,idx) in optionKota">{{item.nama}}
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-2">
                <label for="nama_pasien">Nama Rumah Sakit/Dinkes</label>
            </div>
            <div class="col-md-4">
                <input type="text" name="params.instansi_pengirim_nama" v-model="params.instansi_pengirim_nama" id=""
                    class="form-control" placeholder="Nama Rumah Sakit/Dinkes">
            </div>
        </div>

         <div class="form-group row">
            <div class="col-md-2">
                <label for="nama_pasien">Status</label>
            </div>
            <div class="col-md-4">
                <select class="form-control" type="text" name="reg_kota" placeholder="" v-model="params.status">
                    <option :value="item.id" :key="idx" v-for="(item,idx) in status">{{item.nama}}
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-12 text-left">
                <button class="btn btn-primary" style="width:200px;margin-top:20px" @click="doFilter"><i
                        class="fa fa-eye"></i> Filter</button>
                <button class="btn btn-secondary" style="width:200px;margin-top:20px" @click="resetForm"><i
                        class="fa fa-close"></i> Reset</button>

            </div>
        </div>

    </Ibox>
</template>
<script>
    import axios from 'axios'
    let _this = null;
    export default {
        name: 'FilterRegistrasi',
        props: ['oid'],
        data() {
            return {
                status: [{
                    id: 'otg',
                    nama: 'OTG'
                }, {
                    id: 'odp',
                    nama: 'ODP'
                }, {
                    id: 'pdp',
                    nama: 'PDP'
                }, {
                    id: 'positif',
                    nama: 'Positif'
                }, {
                    id: 'tanpa_status',
                    nama: 'Tanpa Status'
                }],
                optFasyankes: [],
                params: {
                    nama_pasien: null,
                    instansi_pengirim_nama: null,
                    sumber_pasien: null,
                    nik: null,
                    start_date: null,
                    end_date: null,
                    kota: null,
                    status: null
                },
                optionKota: []
            }
        },
        methods: {
            doFilter() {
                // this.$bus.$emit('refresh-ajaxtable')
                this.$bus.$emit('refresh-ajaxtable2', this.oid, this.params);
            },
            async getKota() {
                const resp = await axios.get('/v1/list-kota-jabar');
                this.optionKota = resp.data;
            },
            async changeFasyankes(tipe) {
                // this.form.reg_nama_rs = null;
                let tp = tipe == "Dinkes" ? "dinkes" : "rumah_sakit";
                let resp = await axios.get('/v1/list-fasyankes-jabar?tipe=' + tp)
                this.optFasyankes = resp.data;
                this.optFasyankes.push({
                    id: 9999,
                    nama: 'Fasyankes Lainnya'
                })
            },
            resetForm() {
                this.params.nama_pasien = null;
                this.params.instansi_pengirim_nama = null;
                this.params.sumber_pasien = null;
                this.params.nik = null;
                this.params.start_date = null;
                this.params.end_date = null;
                this.params.kota = null;
                this.params.status = null;
                this.$bus.$emit('refresh-ajaxtable2', this.oid, this.params);
            }
        },
        mounted() {
            // if (this.oid == 'registrasi-rujukan') {
            //     this.params.jenis_registrasi = 'rujukan';
            // } else {
            //     this.params.jenis_registrasi = "mandiri";
            // }
        },
        created() {
            // alert(this.oid);
            _this = this;
            // if(this.oid == 'registrasi-rujukan') {
            //     this.jenis_registrasi = 'rujukan';
            // }
            this.getKota();
        },
        watch: {
            "params.reg_fasyankes_pengirim": function (newVal, oldVal) {
                this.changeFasyankes(this.params.reg_fasyankes_pengirim)
            },
        }
    }
</script>