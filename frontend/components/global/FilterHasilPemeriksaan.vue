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
              placeholder="Nama Pasien">
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
            <div>Kategori</div>
          </div>
          <div class="col-md-8">
            <input type="text" name="params.sumber_pasien" v-model="params.sumber_pasien" class="form-control"
              placeholder="Kategori">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group row">
          <div class="col-md-4 flex-text-center">
            <div>Instansi Pengirim</div>
          </div>
          <div class="col-md-8">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="reg_fasyankes_pengirim" value="rumah_sakit"
                v-model="params.reg_fasyankes_pengirim">
              <label class="form-check-label" for="fasyanrs">Rumah Sakit</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="reg_fasyankes_pengirim" value="dinkes"
                v-model="params.reg_fasyankes_pengirim">
              <label class="form-check-label" for="fasyandinkes">Dinkes</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="reg_fasyankes_pengirim" value="puskesmas"
                v-model="params.reg_fasyankes_pengirim">
              <label class="form-check-label" for="fasyandinkes">Puskesmas</label>
            </div>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 flex-text-center">
            <div>Nama Fasyankes</div>
          </div>
          <div class="col-md-8">
            <multiselect v-model="fasyankes" :options="optFasyankes" track-by="nama" label="nama"
              placeholder="Nama Rumah Sakit / Dinkes / Puskesmas">
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
        <div class="form-group row" v-show="oid === 'verifikasi'">
          <div class="col-md-4 flex-text-center">
            <div>Kriteria</div>
          </div>
          <div class="col-md-8">
            <select class="form-control" type="text" name="reg_kota" placeholder="" v-model="params.status">
              <option :value="item.value" :key="idx" v-for="(item,idx) in status">{{item.text}}
              </option>
            </select>
          </div>
        </div>
        <div class="form-group row" v-show="oid === 'verifikasi-admin'">
          <div class="col-md-4 flex-text-center">
            <div>Lab Pemeriksa</div>
          </div>
          <div class="col-md-8">
              <select v-model="params.lab_satelit_id" class="form-control" name="lab_satelit_id">
                <option :value="item.id" :key="idx" v-for="(item, idx) in option_lab_satelit">
                  {{ item.nama }}
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
    pasienStatus
  } from './../../assets/js/constant/enum';
  let _this = null;
  export default {
    name: 'FilterHasilPemeriksaan',
    props: ['oid'],
    data() {
      return {
        hasil_pemeriksaan: KesimpulanPemeriksaan,
        status: pasienStatus,
        optFasyankes: [],
        fasyankes: [],
        option_lab_satelit: null,
        params: {
          nama_pasien: null,
          reg_fasyankes_pengirim: null,
          fasyankes_id: null,
          start_date: null,
          end_date: null,
          kota: null,
          kesimpulan_pemeriksaan: null,
          sumber_pasien: null,
          status: null,
          lab_satelit_id: null,
        },
        kota: [],
        optionKota: []
      }
    },
    methods: {
      doFilter() {
        this.$bus.$emit('refresh-ajaxtable2', this.oid, this.params);
      },
      async getLabSatelit() {
        const resp = await this.$axios.get("/lab-satelit");
        this.option_lab_satelit = resp.data.data;
      },
      async getKota() {
        const resp = await this.$axios.get('/v1/list-kota-jabar');
        this.optionKota = resp.data;
      },
      async changeFasyankes(tipe) {
        let resp = await this.$axios.get(`/v1/list-fasyankes-jabar?tipe=${tipe}`)
        this.optFasyankes = resp.data;
      },
      resetForm() {
        this.fasyankes = null;
        this.params.reg_fasyankes_pengirim = null;
        this.params.fasyankes_id = null;
        this.params.nama_pasien = null;
        this.params.start_date = null;
        this.params.end_date = null;
        this.params.kota = null;
        this.params.kesimpulan_pemeriksaan = null;
        this.params.sumber_pasien = null;
        this.params.status = null;
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
      this.getLabSatelit();
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