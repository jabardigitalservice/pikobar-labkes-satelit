<template>
  <div class="row">
    <div class="col-lg-8">
      <div class="ibox">
        <div class="ibox-title">
          <div class="ibox-tools">
            <span class="label label-primary float-right">Total</span>
          </div>
          <h5>Progres RT-PCR</h5>
        </div>
        <div class="ibox-content">
          <div class="row">
            <div class="col-md-3 col-6">
              <h1 class="no-margins" v-if="!loading">{{ data.status.extraction_sample_sent | formatCurrency}}</h1>
              <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
              <div class="font-bold text-success">
                <small>Antrian PCR</small>
                <router-link to="/pcr/list-rna" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <h1
                class="no-margins"
                v-if="!loading"
              >{{ data.status.pcr_sample_received | formatCurrency}}</h1>
              <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
              <div class="font-bold text-warning">
                <small>Sampel yang telah di-PCR</small>
                <router-link to="/pcr/list-pcr" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <h1
                class="no-margins"
                v-if="!loading"
              >{{ data.status.sample_valid | formatCurrency}}</h1>
              <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
              <div class="font-bold text-danger">
                <small>Sampel Valid</small>
                <router-link to="/pcr/list-hasil-pemeriksaan" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <h1 class="no-margins" v-if="!loading">{{ data.status.sample_inconclusive | formatCurrency}}</h1>
              <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
              <div class="font-bold text-navy">
                <small>Sampel Inkonklusif</small>
                <router-link to="/pcr/list-hasil-inkonklusif" tag="span">
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
  name: "pcr",
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
        let resp = await axios.get("/v1/dashboard/pcr");
        this.data = resp.data;
      } catch (e) {
        this.data.status.extraction_sample_sent = "-";
        this.data.status.pcr_sample_received = "-";
        this.data.status.sample_valid = "-";
        this.data.status.sample_inconclusive = "-";
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