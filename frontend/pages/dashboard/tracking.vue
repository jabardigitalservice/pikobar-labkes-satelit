<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox">
        <div class="ibox-title">
          <div class="ibox-tools">
            <span class="label label-warning float-right">Sampel Invalid: {{ data.status.invalid }}</span>
            <span class="label label-primary float-right">Selesai: {{ data.status.done }}</span>
          </div>
          <h5>Tracking Progress</h5>
        </div>
        <div class="ibox-content">
          <div class="row">
            <div class="col-md-2">
              <h1 class="no-margins" v-if="!loading">{{ data.status.registration_incomplete | formatCurrency}}</h1>
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
              </div>
            </div>
            <div class="col-md-2">
              <h1
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
              </div>
            </div>
            <div class="col-md-2">
              <h1
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
              </div>
            </div>
            <div class="col-md-2">
              <h1 class="no-margins" v-if="!loading">{{ data.status.pcr | formatCurrency}}</h1>
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
              </div>
            </div>
            <div class="col-md-2">
              <h1 class="no-margins" v-if="!loading">{{ data.status.verification | formatCurrency}}</h1>
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
              </div>
            </div>
            <div class="col-md-2">
              <h1 class="no-margins" v-if="!loading">{{ data.status.validation | formatCurrency}}</h1>
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
              </div>
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

<style>
</style>