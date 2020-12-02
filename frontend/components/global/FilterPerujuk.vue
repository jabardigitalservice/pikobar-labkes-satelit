<template>
  <Ibox title="Filter Data">
    <div class="row">

      <div class="col-md-6">
        <div class="form-group row">
          <div class="col-md-4 flex-text-center">
            <div for="nama_pasien">Nama Pasien / NIK</div>
          </div>
          <div class="col-md-8">
            <input type="text" name="nama_pasien" v-model="params.nama_pasien" class="form-control"
              placeholder="Nama Pasien / NIK">
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 flex-text-center">
            <div>Tanggal Input</div>
          </div>
          <div class="col-md-8 input-group">
            <date-picker placeholder="Tanggal Input" format="d MMMM yyyy" input-class="form-control" v-model="params.tanggal" />
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group row">
          <div class="col-md-4 flex-text-center">
            <div>Nomor Sampel</div>
          </div>
          <div class="col-md-8 input-group">
            <input type="text" class="form-control" placeholder="Nomor Sampel" v-model="params.nomor_sampel">
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 flex-text-center">
            <div>Kategori</div>
          </div>
          <div class="col-md-8">
            <input type="text" name="params.kategori" v-model="params.kategori" class="form-control"
              placeholder="Kategori">
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 mt-2 flex-right">
        <apply-clear-filter-button :doFilter="doFilter" :clearFilter="resetForm" />
      </div>
    </div>

  </Ibox>
</template>

<script>
  let _this = null;
  export default {
    name: 'FilterPerujuk',
    props: ['oid'],
    data() {
      return {
        params: {
          nama_pasien: null,
          kategori: null,
          nik: null,
          tanggal: null,
          nomor_sampel: null,
        },
      }
    },
    methods: {
      doFilter() {
        this.$bus.$emit('refresh-ajaxtable2', this.oid, this.params);
      },
      resetForm() {
        this.params.nama_pasien = null;
        this.params.kategori = null;
        this.params.nik = null;
        this.params.tanggal = null;
        this.params.nomor_sampel = null;
        this.$bus.$emit('refresh-ajaxtable2', this.oid, this.params);
      },
    },
    created() {
      _this = this;
    },
  }
</script>