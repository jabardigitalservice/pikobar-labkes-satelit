<template>
  <Ibox title="Filter Data">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group row">
          <div class="col-md-4 flex-text-center">
            <div for="nama_pasien">Nama Pasien</div>
          </div>
          <div class="col-md-8">
            <input type="text" name="nama_pasien" v-model="params.nama_pasien" id="" class="form-control"
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
            <div>Nama Rumah Sakit/Dinkes</div>
          </div>
          <div class="col-md-8">
            <input type="text" name="params.instansi_pengirim" v-model="params.instansi_pengirim" id=""
              class="form-control" placeholder="Nama Rumah Sakit/Dinkes">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group row">
          <div class="col-md-4 flex-text-center">
            <div>Kategori</div>
          </div>
          <div class="col-md-8">
            <input type="text" name="params.sumber_pasien" v-model="params.sumber_pasien" id="" class="form-control"
              placeholder="Kategori">
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
        <div class="form-group row">
          <div class="col-md-4 flex-text-center">
            <div>Status</div>
          </div>
          <div class="col-md-8">
            <select class="form-control" type="text" name="reg_kota" placeholder="" v-model="params.status">
              <option :value="item.id" :key="idx" v-for="(item,idx) in status">{{item.nama}}
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
    pasienStatusOld
  } from './../../assets/js/constant/enum';
  let _this = null;
  export default {
    name: 'FilterHasilPemeriksaan',
    props: ['oid'],
    data() {
      return {
        hasil_pemeriksaan: KesimpulanPemeriksaan,
        status: pasienStatusOld,
        optFasyankes: [],
        params: {
          nama_pasien: null,
          instansi_pengirim: null,
          start_date: null,
          end_date: null,
          kota: null,
          kesimpulan_pemeriksaan: null,
          sumber_pasien: null,
          status: null
        },
        kota: {},
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
      async changeFasyankes(tipe) {
        let tp = tipe == "Dinkes" ? "dinkes" : "rumah_sakit";
        let resp = await this.$axios.get(`/v1/list-fasyankes-jabar?tipe=${tp}`)
        this.optFasyankes = resp.data;
        this.optFasyankes.push({
          id: 9999,
          nama: 'Fasyankes Lainnya'
        })
      },
      resetForm() {
        this.params.nama_pasien = null;
        this.params.instansi_pengirim = null;
        this.params.start_date = null;
        this.params.end_date = null;
        this.params.kota = null;
        this.params.kesimpulan_pemeriksaan = null;
        this.params.sumber_pasien = null;
        this.params.status = null;
        this.kota = null;
        this.$bus.$emit('refresh-ajaxtable2', this.oid, this.params);
      }
    },
    mounted() {},
    created() {
      _this = this;
      this.getKota();
    },
    watch: {
      "params.reg_fasyankes_pengirim": function (newVal, oldVal) {
        this.changeFasyankes(this.params.reg_fasyankes_pengirim)
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