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
  export default {
    data() {
      return {
        params: {
          kota: 'bandung',
          tanggal_pemeriksaan: ''
        },
        datasets: [{
          data: [1, 6, 2, 3],
          backgroundColor: ["#F2C94C", "#2F80ED", "#109858", '#EA4343'],
          // hoverBackgroundColor: ["#d1e3f7", "#fbd2cd", "#fef5c9"]
        }],
        labels: ['Positif', 'Negatif', 'Inkonklusif', 'Invalid'],
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
          // TODO: fetch data
        } catch (e) {
          console.log(e);
        }
        this.loading = false;
      },
    },
    created() {
      this.loadData();
    }
  };
</script>