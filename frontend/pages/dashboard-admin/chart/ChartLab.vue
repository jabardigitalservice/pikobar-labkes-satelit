<template>
  <div>
    <div class="row ml-1 mb-2">
      <div class="row col-md-6">
        <select v-model="params.perbandingan">
          <option value="Daily">7 Hari Terakhir</option>
          <option value="Monthly">1 Bulan Terakhir</option>
        </select>
        <date-picker placeholder="Tanggal Pemeriksaan" format="d MMMM yyyy" input-class="form-control" style="margin-left: 5px" />
        <button class="btn btn-default" style="margin-left: 5px"><i class='fa fa-undo' /></button>
      </div>
      <div class="row col-md-6 flex-right p-0">
        <select v-model="params.kota">
          <option value="bandung">Kota Bandung</option>
        </select>
      </div>
    </div>
    <canvas :chart="chart" id="chartLab" />
  </div>
</template>

<script>
  var chartLab;

  export default {
    props: ['barId'],
    name: 'chartLab',
    data() {
      return {
        params: {
          perbandingan: 'Monthly',
          kota: 'bandung',
          tanggal_pemeriksaan: ''
        },
        chart: {
          labels: [
            "Labkes",
            "RSUP Hasan Sadikin",
            "Klinik IDI",
            "Puskesmas",
            "Etc.",
          ],
          datasets: [{
            backgroundColor: '#2F80ED',
            borderColor: '',
            data: [40, 60, 20, 60, 100]
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
        var ctx = document.getElementById("chartLab").getContext("2d");
        let chartData = {
          labels: this.chart.labels,
          datasets: this.chart.datasets
        };

        chartLab = new Chart(ctx, {
          type: this.chart.type,
          data: chartData,
          options: this.chart.options
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
        chartLab.destroy();
        setTimeout(() => {
          this.loadData(tipe)
        }, 1000);
      })
    }
  };
</script>