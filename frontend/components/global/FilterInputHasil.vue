<template>
  <Ibox title="Filter Data">
    <div class="row">
      <div class="col-md-10">
        <div class="form-group row">
          <div class="col-md-4 flex-text-center">
            <div>Tanggal Input</div>
          </div>
          <div class="col-md-8 input-group">
            <rangedate-picker @selected="onDateSelected" ref="rangedatepicker" />
          </div>
        </div>
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
    name: 'FilterInputHasil',
    props: ['oid'],
    data() {
      return {
        optFasyankes: [],
        fasyankes: [],
        params: {
          start_date: null,
          end_date: null,
          reg_fasyankes_pengirim: null,
          fasyankes_id: null,
        },
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
        let resp = await this.$axios.get(`/v1/list-fasyankes-jabar?tipe=${tipe}`)
        this.optFasyankes = resp.data;
      },
      resetForm() {
        this.fasyankes = null;
        this.params.reg_fasyankes_pengirim = null;
        this.params.fasyankes_id = null;
        this.params.start_date = null;
        this.params.end_date = null;
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
    }
  }
</script>