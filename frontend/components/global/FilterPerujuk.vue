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
            <rangedate-picker @selected="onDateSelected" ref="rangedatepicker" />
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
            <input type="text" name="params.sumber_pasien" v-model="params.sumber_pasien" class="form-control"
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
          sumber_pasien: null,
          nik: null,
          start_date: null,
          end_date: null,
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
        this.params.sumber_pasien = null;
        this.params.nik = null;
        this.params.start_date = null;
        this.params.end_date = null;
        this.params.nomor_sampel = null;
        this.$refs.rangedatepicker.$data.dateRange = {};
        this.$bus.$emit('refresh-ajaxtable2', this.oid, this.params);
      },
      onDateSelected: function (daterange) {
        this.params.start_date = daterange.start || null;
        this.params.end_date = daterange.end || null;
      }
    },
    created() {
      _this = this;
    },
  }
</script>