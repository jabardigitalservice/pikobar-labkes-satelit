<template>
  <div>
    <div class="input-group mb-2 flex-right">
      <select v-model="params.tipe">
        <option value="Weekly">7 Hari Terakhir</option>
        <option value="Monthly">1 Bulan Terakhir</option>
      </select>
    </div>
    <canvas :chart="chart" id="chartHasilPemeriksaanKota" />
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
          tipe: 'Weekly',
        },
        chart: {
          labels: [],
          datasets: [{
            label: 'Negatif',
            backgroundColor: '#2F80ED',
            barThickness: 20,
            data: []
          }, {
            label: 'Positif',
            backgroundColor: '#F2C94C',
            barThickness: 20,
            data: []
          }, {
            label: 'Inkonklusif',
            backgroundColor: '#EA4343',
            barThickness: 20,
            data: []
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
                stacked: true
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
        try {
          let resp = await this.$axios.get(`v1/dashboard-admin/chart-hasil-pemeriksaan-by-kota?tipe=${this.params.tipe}`);
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
        var ctx = document.getElementById("chartHasilPemeriksaanKota").getContext("2d");
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
      this.$bus.$on('refresh-chart-kota', (tipe) => {
        chartKota.destroy();
        setTimeout(() => {
          this.loadData(tipe)
        }, 1000);
      })
    },
    watch: {
      "params.tipe": function (newVal, oldVal) {
        this.$bus.$emit('refresh-chart-kota', this.params.tipe)
      },
    }
  };
</script>