<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox">
        <div class="ibox-title">
          <div class="ibox-tools">
            <span class="label label-warning float-right">Sampel Invalid: {{ data.status.invalid }}</span>
            <br class="d-sm-none">
            <span class="label label-primary float-right">Selesai: {{ data.status.done }}</span>
          </div>
          <h5>Tracking Progress
            <br class="d-sm-none"><br class="d-sm-none">
          </h5>
        </div>
        <div class="ibox-content">
          <div class="row tracking-row">
            <div class="col-md-2 col-6 mb-3 mb-md-0">
              <div class="text-center">
                <h5 class="font-weight-bold text-muted">Registrasi</h5>
                <h2 v-if="!loading" class="font-weight-bold">{{ data.status.registration_incomplete | formatCurrency}}</h2>
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
                <h2 v-if="!loading" class="font-weight-bold">{{ data.status.waiting_sample | formatCurrency}}</h2>
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
                <h5 class="font-weight-bold text-muted">Ekstraksi</h5>
                <h2 v-if="!loading" class="font-weight-bold">{{ data.status.extraction | formatCurrency}}</h2>
                <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
                <small class="text-muted">Pcs</small>
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
                <h5 class="font-weight-bold text-muted">rRT-PCR</h5>
                <h2 v-if="!loading" class="font-weight-bold">{{ data.status.pcr | formatCurrency}}</h2>
                <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
                <small class="text-muted">Pcs</small>
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
                <h5 class="font-weight-bold text-muted">Verifikasi</h5>
                <h2 v-if="!loading" class="font-weight-bold">{{ data.status.verification | formatCurrency}}</h2>
                <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
                <small class="text-muted">Sampel</small>
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
            <div class="col-md-2 col-6 mb-3 mb-md-0">
              <div class="text-center">
                <h5 class="font-weight-bold text-muted">Validasi</h5>
                <h2 v-if="!loading" class="font-weight-bold">{{ data.status.waiting_sample | formatCurrency}}</h2>
                <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
                <small class="text-muted">Sampel</small>
                <router-link to="/" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link>
              </div>
              <!-- <h1 class="no-margins" v-if="!loading">{{ data.status.validation | formatCurrency}}</h1>
              <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
              <div class="font-bold text-success">
                <small>Validasi</small>
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
        this.data.status.registration_incomplete = "-";
        this.data.status.waiting_sample = "-";
        this.data.status.extraction = "-";
        this.data.status.pcr = "-";
        this.data.status.verification = "-";
        this.data.status.validation = "-";
        this.data.status.done = "-";
        this.data.status.invalid = "-";
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