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
            <div>Nama Rumah Sakit/Dinkes</div>
          </div>
          <div class="col-md-8">
            <input type="text" name="params.instansi_pengirim" v-model="params.instansi_pengirim" id=""
              class="form-control" placeholder="Nama Rumah Sakit/Dinkes">
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
        params: {
          start_date: null,
          end_date: null,
          instansi_pengirim: null,
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
        let tp = tipe == "Dinkes" ? "dinkes" : "rumah_sakit";
        let resp = await this.$axios.get(`/v1/list-fasyankes-jabar?tipe=${tp}`)
        this.optFasyankes = resp.data;
      },
      resetForm() {
        this.params.instansi_pengirim = null;
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
        this.changeFasyankes(this.params.reg_fasyankes_pengirim)
      },
    }
  }
</script>