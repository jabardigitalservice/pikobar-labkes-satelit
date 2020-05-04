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
            <div class="col-md-1">
                S.d
            </div>
            <div class="col-md-4">
                <date-picker placeholder="Tanggal Akhir Input" format="d MMMM yyyy" input-class="form-control"
                    :monday-first="true" v-model="params.end_date" />
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
export default {
    name: 'FilterRegistrasi',
    props:['oid'],
    data(){
        return {
            params: {
                jenis_registrasi: "mandiri",
                nama_pasien: null,
                nomor_register:null,
                nomor_sampel:null,
                start_date:null,
                end_date:null
            }
        }
    },
    methods:{
        doFilter(){
            this.$bus.$emit('refresh-ajaxtable',this.oid);
        }
    }
}
</script>