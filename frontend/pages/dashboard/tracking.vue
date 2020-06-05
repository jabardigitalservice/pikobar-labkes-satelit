<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Informasi
            <br class="d-sm-none"><br class="d-sm-none">
          </h5>
        </div>
        <div class="ibox-content">
          <div class="row tracking-row">
            <div class="col-md-3 col-6 mb-3 mb-md-0">
              <div class="text-center">
                <h5 class="font-weight-bold text-muted">Registrasi</h5>
                <h2 v-if="!loading" class="font-weight-bold" style="color: #32CD32;">{{ data.status.register | formatCurrency}}</h2>
                <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
                <small class="text-muted">Orang</small>
                <router-link to="/registrasi/rujukan" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link>
              </div>
              <!-- <h1 class="no-margins" v-if="!loading">{{ data.status.registration_incomplete | formatCurrency}}</h1>
              <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
              <div class="font-bold text-success">
                <small>Pelengkapan Registrasi</small>
                <router-link to="/registrasi/rujukan" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link>
              </div> -->
            </div>
            <div class="col-md-2 col-6 mb-3 mb-md-0">
              <div class="text-center">
                <h5 class="font-weight-bold text-muted">Sampel</h5>
                <h2 v-if="!loading" class="font-weight-bold" style="color: #32CD32;">{{ data.status.sampel_masuk | formatCurrency}}</h2>
                <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
                <small class="text-muted">Pcs</small>
                <router-link to="/sample" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link>
              </div>
              <!-- <h1
                class="no-margins"
                v-if="!loading"
              >{{ data.status.waiting_sample | formatCurrency}}</h1>
              <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
              <div class="font-bold text-warning">
                <small>Pengambilan Sampel</small>
                <router-link to="/sample" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link>
              </div> -->
            </div>
            <div class="col-md-2 col-6 mb-3 mb-md-0">
              <div class="text-center">
                <h5 class="font-weight-bold text-muted">Positif</h5>
                <h2 v-if="!loading" class="font-weight-bold" style="color: #32CD32;">{{ data.status.positif | formatCurrency}}</h2>
                <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
                <small class="text-muted">Orang</small>
                <router-link to="/registrasi/rujukan" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link>
              </div>
              <!-- <h1
                class="no-margins"
                v-if="!loading"
              >{{ data.status.extraction | formatCurrency}}</h1>
              <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
              <div class="font-bold text-danger">
                <small>Ekstraksi</small>
                <router-link to="/ekstraksi" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link>
              </div> -->
            </div>
            <div class="col-md-2 col-6 mb-3 mb-md-0">
              <div class="text-center">
                <h5 class="font-weight-bold text-muted">Negatif</h5>
                <h2 v-if="!loading" class="font-weight-bold" style="color: #32CD32;">{{ data.status.negatif | formatCurrency}}</h2>
                <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
                <small class="text-muted">Orang</small>
                  <router-link to="/" tag="span">
                    <i class="fa fa-level-up"></i>
                  </router-link>
              </div>
              <!-- <h1 class="no-margins" v-if="!loading">{{ data.status.pcr | formatCurrency}}</h1>
              <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
              <div class="font-bold text-navy">
                <small>rRT-PCR</small>
                <router-link to="/" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link>
              </div> -->
            </div>
            <div class="col-md-2 col-6 mb-3 mb-md-0">
              <div class="text-center">
                <h5 class="font-weight-bold text-muted">Inkonklusif</h5>
                <h2 v-if="!loading" class="font-weight-bold" style="color: #32CD32;">{{ data.status.inkonklusif | formatCurrency}}</h2>
                <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
                <small class="text-muted">Orang</small>
                <router-link to="/" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link>
              </div>
              <!-- <h1 class="no-margins" v-if="!loading">{{ data.status.verification | formatCurrency}}</h1>
              <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
              <div class="font-bold text-info">
                <small>Verifikasi</small>
                <router-link to="/" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link>
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
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
        let resp = await axios.get("/v1/dashboard/tracking");
        this.data = resp.data;
      } catch (e) {
        this.data.status.register = "-";
        this.data.status.sampel_masuk = "-";
        this.data.status.positif = "-";
        this.data.status.negatif = "-";
        this.data.status.inkonklusif = "-";
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
    .tracking-row > div{
      position: relative;
    }
  @media (min-width: 768px) {
    .tracking-row > div:not(:last-child) > div:after{
      position: absolute;
      width: 1px;
      height: 100%;
      top:0;
      right: 0;
      background: #E0E0E0;
      content: "";
    }
  }
  @media (max-width: 767px) {
    .tracking-row > div:nth-child(2n+1) > div:after{
      position: absolute;
      width: 1px;
      height: 100%;
      top:0;
      right: 0;
      background: #E0E0E0;
      content: "";
    }
  }
</style>