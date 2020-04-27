<template>
  <div class="row">
    <div class="col-lg-8">
      <div class="ibox">
        <div class="ibox-title">
          <div class="ibox-tools">
            <span class="label label-primary float-right">Total</span>
          </div>
          <h5>Sampel Ekstraksi</h5>
        </div>
        <div class="ibox-content">
          <div class="row">
            <div class="col-md-3">
              <h1 class="no-margins" v-if="!loading">{{ data.status.sample_taken | formatCurrency}}</h1>
              <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
              <div class="font-bold text-success">
                <small>Sampel Baru</small>
                <router-link to="/ekstraksi/penerimaan" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link>
              </div>
            </div>
            <div class="col-md-3">
              <h1
                class="no-margins"
                v-if="!loading"
              >{{ data.status.extraction_sample_extracted | formatCurrency}}</h1>
              <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
              <div class="font-bold text-warning">
                <small>Ekstraksi Selesai</small>
                <router-link to="/ekstraksi" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link>
              </div>
            </div>
            <div class="col-md-3">
              <h1
                class="no-margins"
                v-if="!loading"
              >{{ data.status.extraction_sample_reextract | formatCurrency}}</h1>
              <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
              <div class="font-bold text-danger">
                <small>Ekstraksi Dikembalikan</small>
                <router-link to="/ekstraksi/dikembalikan" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link>
              </div>
            </div>
            <div class="col-md-3">
              <h1 class="no-margins" v-if="!loading">{{ data.status.extraction_sent | formatCurrency}}</h1>
              <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
              <div class="font-bold text-navy">
                <small>Ekstraksi Dikirim</small>
                <router-link to="/ekstraksi/terkirim" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Tujuan Lab PCR</h5>
        </div>
        <div class="ibox-content">
          <ul class="list-group clear-list">
            <li class="list-group-item fist-item" v-for="(item,idx) in data.labs" :key="idx">
              <span class="float-right">{{ item.total }}</span>
              <span class="label label-success">{{ idx+1 }}</span> {{item.lab_pcr_nama}}
            </li>
          </ul>
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
        let resp = await axios.get("/v1/dashboard/ekstraksi");
        this.data = resp.data;
      } catch (e) {
        this.data.status.sample_taken = "-";
        this.data.status.extraction_sample_extracted = "-";
        this.data.status.extraction_sent = "-";
        this.data.status.extraction_sample_reextract = "-";
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