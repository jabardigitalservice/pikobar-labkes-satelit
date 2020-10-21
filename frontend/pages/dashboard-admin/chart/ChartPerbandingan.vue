<template>
  <div>
    <div class="input-group mb-2">
      <select class="form-control ml-1" v-model="params.perbandingan">
        <option value="Daily">7 Hari Terakhir</option>
        <option value="Monthly">1 Bulan Terakhir</option>
      </select>
      <select class="form-control ml-2" v-model="params.kota">
        <option value="bandung">Kota Bandung</option>
      </select>
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
          kota: 'bandung',
          perbandingan: 'Daily',
        },
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
      }
    },
    created() {
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
    }
  };
</script>