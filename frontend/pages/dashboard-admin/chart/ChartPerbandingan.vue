<template>
  <div>
    <div class="input-group mb-2">
      <div class="col-md-6 p-0 mr-4">
        <multiselect v-model="kota" :options="optionKota" track-by="nama" label="nama" placeholder="Pilih Kota" />
      </div>
      <div class="col-md-5 p-0">
      <select class="form-control" v-model="params.tipe">
        <option value="Weekly">7 Hari Terakhir</option>
        <option value="Monthly">1 Bulan Terakhir</option>
      </select>
      </div>
    </div>
    <canvas :chart="chart" id="chartPerbandinganData" />
  </div>
</template>

<script>
  var chartPerbandingan;
  import axios from 'axios'

  export default {
    props: ['barId'],
    name: 'chartPerbandingan',
    data() {
      return {
        params: {
          kota: '',
          tipe: 'Weekly',
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
            data: []
          }, {
            label: 'Positif',
            backgroundColor: '#F2C94C',
            borderColor: '',
            data: []
          }, {
            label: 'Inkonklusif',
            backgroundColor: '#EA4343',
            borderColor: '',
            data: []
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
        try {
          let resp = await axios.get(`v1/dashboard-admin/chart-trendline?kota=${this.params.kota}&tipe=${this.params.tipe}`);
          this.chart.datasets[0].data = resp.data.result.negatif
          this.chart.datasets[1].data = resp.data.result.positif
          this.chart.datasets[2].data = resp.data.result.lainnya
          this.chart.labels = resp.data.result.labels
        } catch (e) {
          this.chart.datasets[0].data = 0
          this.chart.datasets[1].data = 0
          this.chart.datasets[2].data = 0
        }
        this.setChart(tipe);
      },
      setChart(tipe) {
        var ctx = document.getElementById("chartPerbandinganData").getContext("2d");
        let chartData = {
          labels: this.chart.labels,
          datasets: this.chart.datasets
        };

        chartPerbandingan = new Chart(ctx, {
          type: this.chart.type,
          data: chartData,
          options: tipe === 'Weekly' ? this.chart.options : this.chart.options1
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
        this.loadData('Weekly');
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
        this.params.kota = '';
        if (this.kota) {
          this.params.kota = this.kota.id
        }
        this.$bus.$emit('refresh-chart-perbandingan', this.params)
      },
      "params.tipe": function (newVal, oldVal) {
        this.$bus.$emit('refresh-chart-perbandingan', this.params)
      },
    }
  };
</script>