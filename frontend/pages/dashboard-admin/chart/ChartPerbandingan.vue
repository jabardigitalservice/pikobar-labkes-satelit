<template>
  <div>
    <div class="input-group mb-2">
      <div class="col-md-6 p-0 mr-4">
        <multiselect v-model="kota" :options="optionKota" track-by="nama" label="nama" placeholder="Pilih Kota" />
      </div>
      <div class="col-md-5 p-0">
      <select class="form-control" v-model="params.perbandingan">
        <option value="Daily">7 Hari Terakhir</option>
        <option value="Monthly">1 Bulan Terakhir</option>
      </select>
      </div>
    </div>
    <canvas :chart="chart" id="chartPerbandingan" />
  </div>
</template>

<script>
  var chartPerbandingan;
  export default {
    props: ['barId'],
    name: 'chartPerbandingan',
    data() {
      return {
        params: {
          kota: null,
          perbandingan: 'Daily',
        },
        kota: [],
        optionKota: [],
        chart: {
          labels: [
            "Mon",
            "Tue",
            "Wed",
            "Thu",
            "Fri",
            "Sat",
            "Sun"
          ],
          datasets: [{
            label: 'Negatif',
            backgroundColor: '#2F80ED',
            borderColor: '',
            data: [4, 6, 2, 6]
          }, {
            label: 'Positif',
            backgroundColor: '#F2C94C',
            borderColor: '',
            data: [1, 0, 3, 2]
          }, {
            label: 'Invalid',
            backgroundColor: '#EA4343',
            borderColor: '',
            data: [1, 1, 0, 2]
          }],
          type: "bar",
          options: {
            responsive: true,
            scales: {
              xAxes: [{
                stacked: true,
                gridLines: {
                  display: false,
                },
              }],
              yAxes: [{
                stacked: true,
              }]
            }
          },
          options1: {
            responsive: true,
            scales: {
              xAxes: [{
                stacked: true,
                gridLines: {
                  display: false,
                },
                ticks: {
                  autoSkip: false,
                  maxRotation: 90,
                  minRotation: 90
                }
              }],
              yAxes: [{
                stacked: true,
              }]
            }
          }
        }
      }
    },
    methods: {
      async loadData(tipe) {
        // TODO: fetch data
        this.setChart(tipe);
      },
      setChart(tipe) {
        var ctx = document.getElementById("chartPerbandingan").getContext("2d");
        let chartData = {
          labels: this.chart.labels,
          datasets: this.chart.datasets
        };

        chartPerbandingan = new Chart(ctx, {
          type: this.chart.type,
          data: chartData,
          options: tipe === 'Daily' ? this.chart.options : this.chart.options1
        });
      },
      async getKota() {
        const resp = await this.$axios.get('/v1/list-kota-jabar');
        this.optionKota = resp.data;
      },
    },
    created() {
      this.getKota();
      setTimeout(() => {
        this.loadData('Daily');
      }, 1000);
    },
    mounted() {
      this.$bus.$on('refresh-chart-perbandingan', (tipe) => {
        chartPerbandingan.destroy();
        setTimeout(() => {
          this.loadData(tipe)
        }, 1000);
      })
    },
    watch: {
      "kota": function (newVal, oldVal) {
        this.params.kota = null
        if (this.kota) {
          this.params.kota = this.kota.id
        }
      },
      "params.perbandingan": function (newVal, oldVal) {
        this.$bus.$emit('refresh-chart-perbandingan', this.params.perbandingan)
      },
    }
  };
</script>