<template>
  <div>
    <div class="row mb-2">
      <div style="flex: 0 0 85%" class="ml-1">
        <date-picker placeholder="Tanggal Pemeriksaan" format="d MMMM yyyy" input-class="form-control" />
      </div>
      <div style="flex: 0 0 10%">
        <button class="btn btn-default" style="margin-left: 5px" @click="resetFilter">
          <i class='fa fa-undo' />
        </button>
      </div>
    </div>
    <div class="row mb-2">
      <div style="flex: 0 0 85%" class="ml-1">
        <multiselect v-model="kota" :options="optionKota" track-by="nama" label="nama" placeholder="Pilih Kota" />
      </div>
    </div>
    <canvas :chart="chart" id="chartHasil" />
  </div>
</template>

<script>
  var chartHasil;
  export default {
    props: ['pieId'],
    name: 'chartHasil',
    data() {
      return {
        params: {
          kota: null,
          tanggal_pemeriksaan: ''
        },
        kota: [],
        optionKota: [],
        chart: {
          labels: ['Positif', 'Negatif', 'Inkonklusif', 'Invalid'],
          datasets: [{
            data: [1, 6, 2, 3],
            backgroundColor: ["#F2C94C", "#2F80ED", "#109858", '#EA4343'],
            hoverBackgroundColor: ["#fadd84", "#5ea3ff", "#1efc94", "#fbd2cd"]
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
      async loadData(tipe) {
        // TODO: fetch data
        this.setChart(tipe);
      },
      setChart(tipe) {
        var ctx = document.getElementById("chartHasil").getContext("2d");
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
        this.params.kota = null;
        this.params.tanggal_pemeriksaan = null;
        this.kota = null;
        this.$bus.$emit('refresh-chart-hasil-pemeriksaan', this.params)
      },
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
      "kota": function (newVal, oldVal) {
        this.params.kota = null
        if (this.kota) {
          this.params.kota = this.kota.id
        }
      }
    }
  };
</script>