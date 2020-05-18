<template>
    <Ibox title="Filter Data">

        <!-- <div class="form-group row">
            <div class="col-md-2">
                <label for="nama_pasien">Nomor Sampel</label>
            </div>
            <div class="col-md-4">
                <input type="text" name="nomor_sampel" v-model="params.nomor_sampel" id="" class="form-control"
                    placeholder="Scan / Ketik No. Sampel">
            </div>
        </div> -->

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

        <!-- <div class="form-group row" v-if="oid=='registrasi-mandiri'">
            <div class="col-md-2">
                <label for="nama_pasien">Sumber Pasien</label>
            </div>
            <div class="col-md-4">
                <select name="sumber_pasien" class="form-control" v-model="params.sumber_pasien">
                    <option value="" selected>Semua Sumber</option>
                    <option value="Mandiri">Mandiri</option>
                    <option value="Dinkes">Dinkes</option>
                    <option value="RDT">RDT</option>
                </select>
            </div>
        </div> -->
        
        <!-- <template v-if="oid=='registrasi-rujukan'">
        <div class="form-group row mt-4">
            <label class="col-md-2">Instansi Pengirim
            </label>

            <div class="col-md-6">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="reg_fasyankes_pengirim" id="fasyanrs"
                        value="Rumah Sakit" v-model="params.reg_fasyankes_pengirim">
                    <label class="form-check-label" for="fasyanrs">Rumah Sakit</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="reg_fasyankes_pengirim" id="fasyandinkes"
                        value="Dinkes" v-model="params.reg_fasyankes_pengirim">
                    <label class="form-check-label" for="fasyandinkes">Dinkes</label>
                </div>
            </div>
        </div>

        <div class="form-group row mt-4">
            <label class="col-md-2">Nama Rumah Sakit / Fasyankes
            <div class="col-md-6">
                <v-select :options="optFasyankes" label="nama" :value="params.reg_fasyankes_id"
                    v-model="params.reg_nama_rs"></v-select>
            </div>
        </div>

        <div class="mt-4" id="inputrslain" v-if="params.reg_fasyankes_id && params.reg_fasyankes_id.id==9999">
            <div class="form-group row">
                <label class="col-md-2"></label>
                <div class="col-md-6">
                    <input class=" form-control" type="text" v-model="params.reg_nama_rs_lainnya"
                        name="reg_nama_rs_lainnya" placeholder="Nama Rumah Sakit / Fasyankes" />
                </div>
            </div>
        </div>


        </template> -->

        <div class="form-group row">
            <div class="col-md-2">
                <label for="nama_pasien">Domisili</label>
            </div>
            <div class="col-md-4">
                <select class="form-control" type="text" name="reg_kota" placeholder="" required v-model="params.kota">
                    <option :value="item.id" :key="idx" v-for="(item,idx) in optionKota">{{item.nama}}
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-2">
                <label for="nama_pasien">Instansi Pengirim</label>
            </div>
            <div class="col-md-4">
                <input type="text" name="params.instansi_pengirim" v-model="params.instansi_pengirim" id="" class="form-control"
                    placeholder="Instansi Pengirim">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-12 text-left">
                <button class="btn btn-primary" style="width:200px;margin-top:20px" @click="doFilter"><i
                        class="fa fa-eye"></i> Filter</button>

            </div>
        </div>

    </Ibox>
</template>
<script>
    import axios from 'axios'
    let _this = null;
    export default {
        name: 'FilterInputHasil',
        props: ['oid'],
        data() {
            return {
                optFasyankes:[],
                params: {
                    nik: null,
                    nomor_sampel: null,
                    start_date: null,
                    end_date: null,
                    instansi_pengirim: null,
                    kota: null,
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
                let tp = tipe=="Dinkes"?"dinkes":"rumah_sakit";
                let resp = await axios.get('/v1/list-fasyankes-jabar?tipe='+tp)
                this.optFasyankes = resp.data;
                this.optFasyankes.push({
                    id:9999,
                    nama:'Fasyankes Lainnya'
                })
            },
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
        watch:{
            "params.reg_fasyankes_pengirim":function(newVal, oldVal){
                this.changeFasyankes(this.params.reg_fasyankes_pengirim)
            },
        }
    }
</script>