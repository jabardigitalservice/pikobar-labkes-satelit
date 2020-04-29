<template>
  <Ibox :title="title">
    <div class="form-group" v-if="!disableInput">
      <label>Scan Barcode Nomor Sampel</label>
      <div class="input-group">
        <input
          type="text"
          class="form-control"
          @keyup.enter.prevent="addSample()"
          v-model="input_nomor_sampel"
        />
        <span class="input-group-append">
          <button type="button" class="btn btn-primary" @click.prevent="addSample()">
            <i class="fa fa-plus"></i> Tambah
          </button>
        </span>
      </div>
    </div>
    <p>Jumlah Sampel: {{ value.length }}</p>
    <p v-for="(item,idx) in value" :key="idx">
      <span
        class="label"
        :class="{
                'label-info': item.valid,
                'label-danger': !item.valid,
              }"
        style="font-size: 1.1em"
      >Sampel #{{item.nomor_sampel}}</span>
      &nbsp;
      <span
        class="label label-danger pull-right"
        style="font-size: 1.1em; cursor: pointer"
        @click="removeSample(idx)"
      >
        <i class="fa fa-times"></i>
      </span>
      <br v-if="!item.valid" />
      <span
        class="label label-warning"
        style="font-size: 1.1em; cursor: pointer"
        @click="removeSample(idx)"
        v-if="!item.valid"
        v-html="item.error"
      ></span>
    </p>
  </Ibox>
</template>

<script>
import axios from "axios";

export default {
  props: ["value", "sampelStatus", "title", "disableInput"],
  data() {
    return {
      input_nomor_sampel: ""
    };
  },
  methods: {
    async checkIsSampleValid(sample) {
      let resp = (
        await axios.get("v1/sampel/cek-nomor-sampel", {
          params: {
            nomor_sampel: sample.nomor_sampel,
            sampel_status: this.sampelStatus
          }
        })
      ).data;
      sample.valid = resp.valid;
      sample.error = resp.error;
    },
    addSample() {
      this.input_nomor_sampel = this.input_nomor_sampel.replace(/[^\d]/g, "");
      if (this.input_nomor_sampel == "") return;
      if (this.value.find(s => s.nomor_sampel == this.input_nomor_sampel)) {
        this.$toast.error(
          "Nomor sampel " + this.input_nomor_sampel + " telah dimasukkan",
          {
            icon: "times",
            iconPack: "fontawesome",
            duration: 5000
          }
        );
        return;
      }
      let sample = {
        nomor_sampel: this.input_nomor_sampel,
        valid: true,
        error: ""
      };
      this.value.unshift(sample);
      this.checkIsSampleValid(sample);
      this.input_nomor_sampel = "";
    },
    removeSample(index) {
      this.value.splice(index, 1);
    },
    isFormValid() {
      if (this.input_nomor_sampel != "") {
        this.$toast.error("Ada sampel yang belum di-submit", {
          duration: 5000
        });
        return false;
      }
      if (this.value.length < 1) {
        this.$toast.error("Jumlah sampel minimal satu", {
          duration: 5000
        });
        return false;
      }
      for (var i = 0; i < this.value.length; i++) {
        if (!this.value[i].valid) {
          this.$toast.error(
            "Ada sampel yang tidak valid. Mohon hapus terlebih dahulu",
            {
              icon: "times",
              iconPack: "fontawesome",
              duration: 5000
            }
          );
          return false;
        }
      }
      return true;
    }
  }
};
</script>

<style>
</style>