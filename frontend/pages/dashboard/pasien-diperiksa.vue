<template>
  <div class="row">
    <div class="col-lg-12">
      <Ibox title="Pasien Diperiksa">
        <div class="row tracking-row">
          <div class="col-split-5 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Kontrak Erat</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.kontrak_erat | formatCurrency}}
              </h2>
              <img v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Pcs</small>
            </div>
          </div>
          <div class="col-split-5 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Suspek</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.suspek | formatCurrency}}
              </h2>
              <img v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Orang</small>
            </div>
          </div>
          <div class="col-split-5 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Probable</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.probable | formatCurrency}}
              </h2>
              <img v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Orang</small>
            </div>
          </div>
          <div class="col-split-5 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Konfirmasi</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.konfirmasi | formatCurrency}}
              </h2>
              <img v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Orang</small>
            </div>
          </div>
          <div class="col-split-5 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Tanpa Kriteria</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.tanpa_kriteria | formatCurrency}}
              </h2>
              <img v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Orang</small>
            </div>
          </div>
        </div>
      </Ibox>

    </div>
  </div>
</template>

<script>
  export default {
    name: "pasien-diperiksa",
    data() {
      return {
        loading: true,
        data: {
          status: {},
          labs: [],
        }
      };
    },
    methods: {
      async loadData() {
        this.loading = true;
        try {
          let resp = await this.$axios.get("/v1/dashboard/pasien-diperiksa");
          this.data = resp.data.result;
        } catch (e) {
          this.data.kontrak_erat = "-";
          this.data.suspek = "-";
          this.data.probable = "-";
          this.data.konfirmasi = "-";
          this.data.tanpa_kriteria = "-";
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