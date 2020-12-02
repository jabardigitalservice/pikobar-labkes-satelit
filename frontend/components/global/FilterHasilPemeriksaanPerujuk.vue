<template>
  <Ibox title="Filter Data">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group row">
          <div class="col-md-4 flex-text-center">
            <div for="nama_pasien">Nama Pasien</div>
          </div>
          <div class="col-md-8">
            <input type="text" name="nama_pasien" v-model="params.nama_pasien" class="form-control"
              placeholder="Nama Pasien / NIK">
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 flex-text-center">
            <div>Tanggal Pemeriksaan</div>
          </div>
          <div class="col-md-8">
            <rangedate-picker @selected="onDateSelected" ref="rangedatepicker" />
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group row">
          <div class="col-md-4 flex-text-center">
            <div>Domisili</div>
          </div>
          <div class="col-md-8">
            <multiselect v-model="kota" :options="optionKota" track-by="nama" label="nama" placeholder="Pilih Domisili">
            </multiselect>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 flex-text-center">
            <div>Hasil Pemeriksaan</div>
          </div>
          <div class="col-md-8">
            <select class="form-control" type="text" name="reg_kota" placeholder=""
              v-model="params.kesimpulan_pemeriksaan">
              <option :value="item.id" :key="idx" v-for="(item,idx) in hasil_pemeriksaan">{{item.nama}}
              </option>
            </select>
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
  import {
    KesimpulanPemeriksaan,
  } from './../../assets/js/constant/enum';
  let _this = null;
  export default {
    name: 'FilterHasilPemeriksaanPerujuk',
    props: ['oid'],
    data() {
      return {
        hasil_pemeriksaan: KesimpulanPemeriksaan,
        params: {
          nama_pasien: null,
          nik: null,
          start_date: null,
          end_date: null,
          kota: null,
          kesimpulan_pemeriksaan: null,
        },
        kota: [],
        optionKota: []
      }
    },
    methods: {
      doFilter() {
        this.$bus.$emit('refresh-ajaxtable2', this.oid, this.params);
      },
      async getKota() {
        const resp = await this.$axios.get('/v1/list-kota-jabar');
        this.optionKota = resp.data;
      },
      resetForm() {
        this.params.nama_pasien = null;
        this.params.nik = null;
        this.params.start_date = null;
        this.params.end_date = null;
        this.params.kota = null;
        this.params.kesimpulan_pemeriksaan = null;
        this.kota = null;
        this.$refs.rangedatepicker.dateRange.start = null;
        this.$refs.rangedatepicker.dateRange.end = null;
        this.$bus.$emit('refresh-ajaxtable2', this.oid, this.params);
      },
      onDateSelected: function (daterange) {
        this.params.start_date = daterange.start || null;
        this.params.end_date = daterange.end || null;
      }
    },
    created() {
      _this = this;
      this.getKota();
    },
    watch: {
      "params.reg_fasyankes_pengirim": function (newVal, oldVal) {
        this.fasyankes = null;
        this.changeFasyankes(this.params.reg_fasyankes_pengirim)
      },
      "fasyankes": function (newVal, oldVal) {
        this.params.fasyankes_id = null
        if (this.fasyankes) {
          this.params.fasyankes_id = this.fasyankes.id || null;
        }
      },
      "kota": function (newVal, oldVal) {
        this.params.kota = null
        if (this.kota) {
          this.params.kota = this.kota.id
        }
      }
    }
  }
</script>