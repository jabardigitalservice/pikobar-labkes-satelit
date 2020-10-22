<template>
  <div>
    <div class="input-group mb-2 flex-right">
      <select v-model="params.perbandingan">
        <option value="Weekly">7 Hari Terakhir</option>
        <option value="Monthly">1 Bulan Terakhir</option>
      </select>
    </div>
    <canvas :chart="chart" id="chartKota" />
  </div>
</template>

<script>
  var chartKota;

  export default {
    props: ['barId'],
    name: 'chartKota',
    data() {
      return {
        params: {
          perbandingan: 'Weekly',
        },
        chart: {
          labels: [
            "Kota Bandung",
            "Kab. Bandung",
            "Kota Cimahi",
          ],
          datasets: [{
            label: 'Negatif',
            backgroundColor: '#2F80ED',
            borderColor: '',
            data: [40, 60, 20, 60]
          }, {
            label: 'Positif',
            backgroundColor: '#F2C94C',
            borderColor: '',
            data: [10, 0, 30, 20]
          }, {
            label: 'Inkonklusif',
            backgroundColor: '#109858',
            borderColor: '',
            data: [10, 10, 0, 20]
          }, {
            label: 'Invalid',
            backgroundColor: '#EA4343',
            borderColor: '',
            data: [10, 10, 0, 20]
          }],
          type: "horizontalBar",
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
                barPercentage: 0.5,
                categoryPercentage: 0.5,
                barThickness: 20
              }]
            },
            legend: {
              display: false,
            }
          },
        }
      }
    },
    methods: {
      async loadData(tipe) {
        // TODO: fetch data
        this.setChart(tipe);
      },
      setChart(tipe) {
        var ctx = document.getElementById("chartKota").getContext("2d");
        let chartData = {
          labels: this.chart.labels,
          datasets: this.chart.datasets
        };

        chartKota = new Chart(ctx, {
          type: this.chart.type,
          data: chartData,
          options: this.chart.options
        });
      }
    },
    created() {
      setTimeout(() => {
        this.loadData('Weekly');
      }, 1000);
    },
    mounted() {
      this.$bus.$on('refresh-chart-perbandingan', (tipe) => {
        chartKota.destroy();
        setTimeout(() => {
          this.loadData(tipe)
        }, 1000);
      })
    }
  };
</script>