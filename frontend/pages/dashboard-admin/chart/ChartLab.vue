<template>
  <div>
    <div class="row ml-1 mb-2">
      <div class="row col-md-8">
        <select v-model="params.tipe">
          <option value="Weekly">7 Hari Terakhir</option>
          <option value="Monthly">1 Bulan Terakhir</option>
        </select>
        <date-picker placeholder="Tanggal Pemeriksaan" format="d MMMM yyyy" input-class="form-control" style="margin-left: 5px" v-model="params.tanggal_pemeriksaan" />
        <button class="btn btn-default" style="margin-left: 5px" @click="resetFilter" title="Reset Filter">
          <i class='fa fa-undo' />
        </button>
      </div>
      <div class="row col-md-4 flex-right p-0">
        <multiselect v-model="kota" :options="optionKota" track-by="nama" label="nama" placeholder="Pilih Kota" />
      </div>
    </div>
    <canvas :chart="chart" id="chartHasilPemeriksaanFasyankes" />
  </div>
</template>

<script>
  var chartFasyankes;

  export default {
    props: ['barId'],
    name: 'chartFasyankes',
    data() {
      return {
        params: {
          tipe: 'Monthly',
          tanggal_pemeriksaan: '',
          kota: '',
        },
        kota: [],
        optionKota: [],
        chart: {
          labels: [],
          datasets: {
            backgroundColor: '#2F80ED',
            barThickness: 20,
            data: []
          },
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
          let resp = await this.$axios.get(`v1/dashboard-admin/chart-register-by-fasyankes?tipe=${this.params.tipe}&date=${this.params.tanggal_pemeriksaan}&kota=${this.params.kota}`);
          console.log(resp.data.result)
          this.chart.datasets.data = resp.data.result.data
          this.chart.labels = resp.data.result.labels
        } catch (e) {
          this.chart.datasets.data = []
        }
        this.setChart(tipe);
      },
      setChart(tipe) {
        var ctx = document.getElementById("chartHasilPemeriksaanFasyankes").getContext("2d");
        let chartData = {
          labels: this.chart.labels,
          datasets: this.chart.datasets
        };

        chartFasyankes = new Chart(ctx, {
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
        this.params.tipe = 'Monthly';
        this.params.kota = '';
        this.params.tanggal_pemeriksaan = '';
        this.kota = '';
        this.$bus.$emit('refresh-chart-fasyankes', this.params)
      }
    },
    created() {
      this.getKota();
      setTimeout(() => {
        this.loadData('Weekly');
      }, 1000);
    },
    mounted() {
      this.$bus.$on('refresh-chart-fasyankes', (tipe) => {
        chartFasyankes.destroy();
        setTimeout(() => {
          this.loadData(tipe)
        }, 1000);
      })
    },
    watch: {
      "params.tipe": function (newVal, oldVal) {
        this.$bus.$emit('refresh-chart-fasyankes', this.params)
      },
      "params.tanggal_pemeriksaan": function (newVal, oldVal) {
        this.$bus.$emit('refresh-chart-fasyankes', this.params)
      },
      "kota": function (newVal, oldVal) {
        this.params.kota = '';
        if (this.kota) {
          this.params.kota = this.kota.id
        }
        this.$bus.$emit('refresh-chart-fasyankes', this.params)
      }
    }
  };
</script>