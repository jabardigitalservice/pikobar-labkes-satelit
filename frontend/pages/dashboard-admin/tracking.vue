<template>
  <div class="row">

    <div class="col-split-5 ">
      <div class="ibox">
        <div class="ibox-content" style="height: 135px;">
          <div class="text-center">
            <div class="font-weight-bold text-center text-blue">Total Sampel Masuk</div>
            <h2 v-if="!loading" class="font-weight-bold text-center">
              {{ data.total_masuk_sampel | formatCurrency}}</h2>
            <img v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
            <small v-if="!loading">Pcs</small>
          </div>
        </div>
      </div>
    </div>

    <div class="col-split-5 ">
      <div class="ibox">
        <div class="ibox-content" style="height: 135px;">
          <div class="text-center">
            <div class="font-weight-bold text-center text-blue">Total Sampel Diperiksa</div>
            <h2 v-if="!loading" class="font-weight-bold text-center">
              {{ data.total_sampel_diperiksa | formatCurrency}}</h2>
            <img v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
            <small v-if="!loading">Pcs</small>
          </div>
        </div>
      </div>
    </div>

    <div class="col-split-5 ">
      <div class="ibox">
        <div class="ibox-content" style="height: 135px;">
          <div class="text-center">
            <div class="font-weight-bold text-center text-blue">Rata-rata Waktu Pemeriksaan</div>
            <h2 v-if="!loading" class="font-weight-bold text-center">
              {{ data.rata_rata_waktu_pemeriksaan | formatCurrency}}</h2>
            <img v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
            <small v-if="!loading">Jam</small>
          </div>
        </div>
      </div>
    </div>

    <div class="col-split-5 ">
      <div class="ibox">
        <div class="ibox-content" style="height: 135px;">
          <div class="text-center">
            <div class="font-weight-bold text-center text-blue">Total Sampel Positif</div>
            <h2 v-if="!loading" class="font-weight-bold text-center">
              {{ data.total_sample_positif | formatCurrency}}</h2>
            <img v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
            <small v-if="!loading">Pcs</small>
          </div>
        </div>
      </div>
    </div>

    <div class="col-split-5 ">
      <div class="ibox">
        <div class="ibox-content" style="height: 135px;">
          <div class="text-center">
            <div class="font-weight-bold text-center text-blue">Total Sampel Negatif</div>
            <h2 v-if="!loading" class="font-weight-bold text-center">
              {{ data.total_sample_negatif | formatCurrency}}</h2>
            <img v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
            <small v-if="!loading">Pcs</small>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
  import axios from "axios";
  export default {
    name: "tracking-dashboard-admin",
    data() {
      return {
        loading: true,
        data: {
          total_masuk_sampel: "-",
          total_sampel_diperiksa: "-",
          rata_rata_waktu_pemeriksaan: "-",
          total_sample_positif: "-",
          total_sample_negatif: "-",
        }
      };
    },
    methods: {
      async loadData() {
        this.loading = true;
        try {
          let resp = await axios.get("/dinkes/dashboard");
          this.data = resp.data.data;
        } catch (e) {
          console.log(e);
        }
        this.loading = false;
      }
    },
    created() {
      this.loadData();
    }
  };
</script>

<style scoped>
  .tracking-row>div {
    position: relative;
  }

  .ibox-content {
    border-radius: 10px;
    margin-right: 10px;
  }

  @media (min-width: 768px) {
    .tracking-row>div:not(:last-child)>div:after {
      position: absolute;
      width: 1px;
      height: 100%;
      top: 0;
      right: 0;
      background: #E0E0E0;
      content: "";
    }
  }

  @media (max-width: 767px) {
    .tracking-row>div:nth-child(2n+1)>div:after {
      position: absolute;
      width: 1px;
      height: 100%;
      top: 0;
      right: 0;
      background: #E0E0E0;
      content: "";
    }
  }
</style>