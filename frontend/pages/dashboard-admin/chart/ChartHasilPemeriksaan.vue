<template>
  <div>
    <div class="row mb-2">
      <div style="flex: 0 0 85%" class="ml-1">
        <date-picker placeholder="Tanggal Pemeriksaan" format="d MMMM yyyy" input-class="form-control" v-model="params.tanggal_pemeriksaan" />
      </div>
      <div style="flex: 0 0 10%">
        <button class="btn btn-default" style="margin-left: 5px" @click="resetFilter" title="Reset Filter">
          <i class='fa fa-undo' />
        </button>
      </div>
    </div>
    <div class="row mb-2">
      <div style="flex: 0 0 85%" class="ml-1">
        <multiselect v-model="kota" :options="optionKota" track-by="nama" label="nama" placeholder="Pilih Kota" />
      </div>
    </div>
    <canvas :chart="chart" id="chartHasilPemeriksaan" />
  </div>
</template>

<script>
  var chartHasil;
  import {
    momentFormatDateDefault,
  } from '~/utils';
  export default {
    props: ['pieId'],
    name: 'chartHasil',
    data() {
      return {
        params: {
          kota: '',
          tanggal_pemeriksaan: ''
        },
        kota: [],
        optionKota: [],
        chart: {
          labels: ['Positif', 'Negatif', 'Inkonklusif'],
          datasets: [{
            data: [],
            backgroundColor: ["#F2C94C", "#2F80ED", "#EA4343"],
            hoverBackgroundColor: ["#fadd84", "#5ea3ff", "#fbd2cd"]
          }],
          type: "pie",
          options: {
            legend: {
              display: true,
              position: 'bottom',
            }
          }
        }
      };
    },
    methods: {
      async loadData() {
        this.loading = true;
        try {
          let tanggal_pemeriksaan = this.params.tanggal_pemeriksaan ? momentFormatDateDefault(this.params.tanggal_pemeriksaan) : this.params.tanggal_pemeriksaan;
          let resp = await this.$axios.get(`v1/dashboard-admin/chart-hasil-pemeriksaan?kota=${this.params.kota}&tanggal_pemeriksaan=${tanggal_pemeriksaan}`);
          this.chart.datasets[0].data[0] = resp.data.result.positif;
          this.chart.datasets[0].data[1] = resp.data.result.negatif;
          this.chart.datasets[0].data[2] = resp.data.result.lainnya;
        } catch (e) {
          this.chart.datasets[0].data[0] = 0;
          this.chart.datasets[0].data[1] = 0;
          this.chart.datasets[0].data[2] = 0;
        }
        this.setChart();
      },
      setChart() {
        var ctx = document.getElementById("chartHasilPemeriksaan").getContext("2d");
        let chartData = {
          labels: this.chart.labels,
          datasets: this.chart.datasets
        };
        chartHasil = new Chart(ctx, {
          type: this.chart.type,
          data: chartData,
          options: this.chart.options
        });
      },
      async getKota() {
        const resp = await this.$axios.get('/v1/list-kota-jabar');
        this.optionKota = resp.data;
      },
      resetFilter() {
        this.params.kota = '';
        this.params.tanggal_pemeriksaan = '';
        this.kota = null;
        this.$bus.$emit('refresh-chart-hasil-pemeriksaan', this.params)
      }
    },
    created() {
      this.getKota();
      setTimeout(() => {
        this.loadData();
      }, 1000);
    },
    mounted() {
      this.$bus.$on('refresh-chart-hasil-pemeriksaan', () => {
        chartHasil.destroy();
        setTimeout(() => {
          this.loadData()
        }, 1000);
      })
    },
    watch: {
      "params.tanggal_pemeriksaan": function (newVal, oldVal) {
        this.$bus.$emit('refresh-chart-hasil-pemeriksaan', this.params)
      },
      "kota": function (newVal, oldVal) {
        this.params.kota = '';
        if (this.kota) {
          this.params.kota = this.kota.id
        }
        this.$bus.$emit('refresh-chart-hasil-pemeriksaan', this.params)
      }
    }
  };
</script>