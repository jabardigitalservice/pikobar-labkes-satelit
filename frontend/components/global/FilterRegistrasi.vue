<template>
    <Ibox title="Filter Data">

        <div class="form-group row">
            <div class="col-md-2">
                <label for="nama_pasien">Nama Pasien</label>
            </div>
            <div class="col-md-4">
                <input type="text" name="nama_pasien" v-model="params.nama_pasien" id="" class="form-control"
                    placeholder="Ketikkan Nama Pasien">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-2">
                <label for="nama_pasien">Nomor Register</label>
            </div>
            <div class="col-md-4">
                <input type="text" name="nomor_register" v-model="params.nomor_register" id="" class="form-control"
                    placeholder="Nomor Register">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-2">
                <label for="nama_pasien">Nomor Sampel</label>
            </div>
            <div class="col-md-4">
                <input type="text" name="nomor_sampel" v-model="params.nomor_sampel" id="" class="form-control"
                    placeholder="Scan / Ketik No. Sampel">
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

        <div class="form-group row" v-if="oid=='registrasi-rujukan'">
            <div class="col-md-2">
                <label for="nama_pasien">Sumber Sampel</label>
            </div>
            <div class="col-md-4">
                 <select class="form-control" id="rsfasyankes" v-model="params.sumber_sampel">
                     <option value="">Semua Fasyankes</option>
                    <option value="RSUP Dr. Hasan Sadikin">RSUP Dr. Hasan Sadikin</option>
                    <option value="RSP Dr. H.A. Rotinsulu">RSP Dr. H.A. Rotinsulu</option>
                    <option value="RSP Dr. Goenawan P"> RSP Dr. Goenawan P</option>
                    <option value="RSUD Dr. Slamet">RSUD Dr. Slamet</option>
                    <option value="RSUD R. Syamsudin, SH">RSUD R. Syamsudin, SH</option>
                    <option value="RSUD Indramayu">RSUD Indramayu</option>
                    <option value="RSUD Gunungjati">RSUD Gunungjati</option>
                    <option value="Rumkit Tk. ll Dustira">Rumkit Tk. ll Dustira</option>
                    <option value="RSUD Cibinong">RSUD Cibinong</option>
                    <option value="RSUD Ciawi">RSUD Ciawi</option>
                    <option value="RSUD Cibabat">RSUD Cibabat</option>
                    <option value="RSUD Kota Bogor">RSUD Kota Bogor</option>
                    <option value="RSUD Al Ihsan">RSUD Al Ihsan</option>
                    <option value="RS Bhayangkara Sartika Asih">RS Bhayangkara Sartika Asih</option>
                    <option value="RSUD dr. Soekardjo">RSUD dr. Soekardjo</option>
                    <option value="RSUD SMC Kab. Tasik">RSUD SMC Kab. Tasik</option>
                    <option value="RS Paru Prov. Jabar Sidawangi">RS Paru Prov. Jabar Sidawangi</option>
                    <option value="RSUD Bayu Asih">RSUD Bayu Asih</option>
                    <option value="RSUD Karawang">RSUD Karawang</option>
                    <option value="RSUD Sekarwangi">RSUD Sekarwangi</option>
                    <option value="RSUD Subang">RSUD Subang</option>
                    <option value="RSUD Waled">RSUD Waled</option>
                    <option value="RSUD Arjawinangun">RSUD Arjawinangun</option>
                    <option value="RSUD 45 Kuningan">RSUD 45 Kuningan</option>
                    <option value="RSUD Kab Bekasi">RSUD Kab Bekasi</option>
                    <option value="RSUD Sumedang">RSUD Sumedang</option>
                    <option value="RSUD Banjar">RSUD Banjar</option>
                    <option value="RSUD Ciamis">RSUD Ciamis</option>
                    <option value="RSUD Cideres">RSUD Cideres</option>
                    <option value="RSUD Majalaya">RSUD Majalaya</option>
                    <option value="RS Lanud dr. M. Salamun">RS Lanud dr. M. Salamun</option>
                    <option value="RSUD Kota Depok">RSUD Kota Depok</option>
                    <option value="RSUD Sayang">RSUD Sayang</option>
                    <option value="RSUD dr. Chasbullah A">RSUD dr. Chasbullah A</option>
                    <option value="Other">RS Lainnya, Sebutkan</option>
                </select>
            </div>
        </div>

        <div class="form-group row" v-if="params.sumber_sampel=='Other'">
            <div class="col-md-2">
                <label for="">Fasyankes Lainnya</label>
            </div>
            <div class="col-md-4">
                <input type="text" name="" id="" class="form-control" v-model="params.other_nama_rs">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-2">
                <label for="nama_pasien">Domisili</label>
            </div>
            <div class="col-md-4">
                <select class="form-control" type="text" name="reg_kota" placeholder="" required
                    v-model="params.kota">
                    <option :value="item.id" :key="idx" v-for="(item,idx) in optionKota">{{item.nama}}
                    </option>
                </select>
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
    name: 'FilterRegistrasi',
    props:['oid'],
    data(){
        return {
            params: {
                jenis_registrasi: null,
                nama_pasien: null,
                nomor_register:null,
                nomor_sampel:null,
                start_date:null,
                end_date:null,
                sumber_pasien:null,
                sumber_sampel:null,
                kota:null,
                other_nama_rs:null
            },
            optionKota:[]
        }
    },
    methods:{
        doFilter(){
            // this.$bus.$emit('refresh-ajaxtable')
            this.$bus.$emit('refresh-ajaxtable2',this.oid, this.params);
        },
        async getKota() {
            const resp = await axios.get('/v1/list-kota-jabar');
            this.optionKota = resp.data;
        },
    },
    mounted(){
        if(this.oid == 'registrasi-rujukan') {
            this.params.jenis_registrasi = 'rujukan';
        }else{
            this.params.jenis_registrasi = "mandiri";
        }
    },
    created(){
        // alert(this.oid);
        _this = this;
        // if(this.oid == 'registrasi-rujukan') {
        //     this.jenis_registrasi = 'rujukan';
        // }
        this.getKota();
    }
}
</script>