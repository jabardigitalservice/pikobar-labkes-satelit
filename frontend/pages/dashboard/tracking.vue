<template>
  <div class="row">
    <div class="col-lg-12">

      <Ibox title="Tracking Progress">
        <div class="row tracking-row">
          <div class="col-md-2 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Registrasi</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.status.register | formatCurrency}}
              </h2>
              <img v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Orang</small>
            </div>
          </div>
          <div class="col-md-2 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Sampel</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.status.sampel_masuk | formatCurrency}}
              </h2>
              <img v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Pcs</small>
            </div>
          </div>
          <div class="col-md-2 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Positif</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.status.positif | formatCurrency}}
              </h2>
              <img v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Orang</small>
            </div>
          </div>
          <div class="col-md-2 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Negatif</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.status.negatif | formatCurrency}}
              </h2>
              <img v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Orang</small>
            </div>
          </div>
          <div class="col-md-2 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Inkonklusif</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.status.inkonklusif | formatCurrency}}
              </h2>
              <img v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Orang</small>
            </div>
          </div>
          <div class="col-md-2 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Invalid</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.status.invalid | formatCurrency}}
              </h2>
              <img v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Orang</small>
            </div>
          </div>
        </div>
      </Ibox>

      <Ibox title="Pasien Diperiksa">
        <div class="row tracking-row">
          <div class="col-split-5 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">OTG</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.status.register_otg | formatCurrency}}
              </h2>
              <img v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Pcs</small>
            </div>
          </div>
          <div class="col-split-5 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">ODP</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.status.register_odp | formatCurrency}}
              </h2>
              <img v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Orang</small>
            </div>
          </div>
          <div class="col-split-5 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">PDP</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.status.register_pdp | formatCurrency}}
              </h2>
              <img v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Orang</small>
            </div>
          </div>
          <div class="col-split-5 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Positif</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.status.register_positif | formatCurrency}}
              </h2>
              <img v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Orang</small>
            </div>
          </div>
          <div class="col-split-5 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Tanpa Status</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.status.register_tanpa_status | formatCurrency}}
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
    name: "ekstraksi",
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
          let resp = await this.$axios.get("/v1/dashboard/tracking");
          this.data = resp.data;
        } catch (e) {
          this.data.status.register = "-";
          this.data.status.sampel_masuk = "-";
          this.data.status.positif = "-";
          this.data.status.negatif = "-";
          this.data.status.inkonklusif = "-";
          this.data.status.register_otg = "-";
          this.data.status.register_odp = "-";
          this.data.status.register_pdp = "-";
          this.data.status.register_positif = "-";
          this.data.status.register_tanpa_status = "-";
          this.data.status.register_instansi_pengirim = "-";
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