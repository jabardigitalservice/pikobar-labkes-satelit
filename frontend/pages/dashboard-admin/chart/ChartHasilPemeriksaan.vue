<template>
  <div>
    <div class="row mb-2">
      <div style="flex: 0 0 85%" class="ml-1">
        <date-picker placeholder="Tanggal Pemeriksaan" format="d MMMM yyyy" input-class="form-control" />
      </div>
      <div style="flex: 0 0 10%">
        <button class="btn btn-default" style="margin-left: 5px"><i class='fa fa-undo' /></button>
      </div>
    </div>
    <div class="row mb-2">
      <div class="form-group ml-1">
        <select class="form-control" v-model="params.kota">
          <option value="bandung">Kota Bandung</option>
        </select>
      </div>
    </div>
    <chartjs-pie :bind="true" :datasets="datasets" :labels="labels" :option="option" />
  </div>
</template>

<script>
  import axios from 'axios'
  export default {
    data() {
      return {
        params: {
          kota: '',
          tanggal_pemeriksaan: ''
        },
        datasets: [{
          data: [1, 6, 2],
          backgroundColor: ["#F2C94C", "#2F80ED", "#109858", '#EA4343'],
          // hoverBackgroundColor: ["#d1e3f7", "#fbd2cd", "#fef5c9"]
        }],
        labels: ['Positif', 'Negatif', 'Inkonklusif'],
        option: {
          legend: {
            display: true,
            position: 'bottom',
          }
        }
      };
    },
    methods: {
      async loadData() {
        this.loading = true;
        try {
          let resp = await axios.get(`v1/dashboard-admin/chart-hasil-pemeriksaan?kota=${this.params.kota}&tanggal_pemeriksaan=${this.params.tanggal_pemeriksaan}`);
          this.datasets[0].data[0] = resp.data.result.positif;
          this.datasets[0].data[1] = resp.data.result.negatif;
          this.datasets[0].data[2] = resp.data.result.lainnya;
        } catch (e) {
          this.datasets[0].data[0] = 0;
          this.datasets[0].data[1] = 0;
          this.datasets[0].data[2] = 0;
        }
        this.loading = false;
      },
    },
    created() {
      this.loadData();
    }
  };
</script>