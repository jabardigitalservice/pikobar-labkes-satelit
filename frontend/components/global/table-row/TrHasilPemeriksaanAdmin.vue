<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>
      <input
        type="checkbox"
        name="list-sampel"
        v-bind:id="item.sampel_id"
        v-bind:value="item.nomor_sampel"
        v-model="selected"
        @click="sampelOnChangeSelect"
      >
    </td>
    <td nowrap>
      <div><b>{{item.waktu_pcr_sample_analyzed | formatDate}}</b></div>
      <div class="text-muted">{{ item.jam_input_hasil ? 'pukul ' + item.jam_input_hasil : null }}</div>
    </td>
    <td>
      <div class="badge badge-white" style="text-align:left; padding:10px">
        {{item.nomor_sampel}}
      </div>
    </td>
    <td nowrap>
      <div v-if="item.nama_lengkap" style="text-transform: capitalize;"><b>{{ item.nama_lengkap }}</b></div>
      <div v-if="item.nik" class="text-muted">{{ item.nik }}</div>
      <div class="text-muted">{{ usiaPasien || null }}</div>
    </td>
    <td>
      {{item.nama_kota}}
    </td>
    <td>
      {{ item.instansi_pengirim_nama }}
    </td>
    <td>
      {{ item.lab_satelit_nama }}
    </td>
    <td style="text-transform: capitalize;">
      {{item.sumber_pasien }}
    </td>
    <td style="text-transform: capitalize;">
      {{item.kesimpulan_pemeriksaan}}
    </td>
    <td width="20%">
      <nuxt-link tag="a" class="mb-1 btn btn-yellow btn-sm" :to="`/hasil-pemeriksaan/detail/${item.sampel_id}`"
        title="Klik untuk melihat detail">
        <i class="uil-info-circle" />
      </nuxt-link>
    </td>
  </tr>
</template>
<script>
  import Form from "vform";
  import {
    pasienStatus
  } from '~/assets/js/constant/enum';
  export default {
    props: ["item", "pagination", "rowparams", "index"],
    components: {},
    data() {
      let loading = false
      let datas = this.$store.state.hasil_pemeriksaan.selectedSampels;
      let form = new Form({
        sampel_id: this.item.id
      });
      return {
        checked: false,
        selected: [],
        dataArr: datas,
        loading,
        form,
        pasienStatus
      };
    },
    computed: {
      selectedSampels: {
        set(val) {
          this.$store.state.selectedSampels = val
        },
        get() {
          return this.$store.state.selectedSampels
        }
      },
      usiaPasien() {
        if (this.item.tanggal_lahir) {
          let tglLahir = new Date(this.item.tanggal_lahir);
          let today_date = new Date();
          let today_year = today_date.getFullYear();
          let today_month = today_date.getMonth();
          let today_day = today_date.getDate();

          var age = today_date.getFullYear() - tglLahir.getFullYear();
          var m = today_date.getMonth() - tglLahir.getMonth();
          if (m < 0 || (m === 0 && today_date.getDate() < tglLahir.getDate())) {
            age--;
          }
          return `Usia: ${age} tahun`;
        }
        if (this.item.usia_tahun) {
          return `Usia: ${this.item.usia_tahun} tahun`;
        }
        return 'Usia: -'
      }
    },
    methods: {
      sampelOnChangeSelect(e) {
        const newDomchecked = e.target.checked
        const el = e ? e.currentTarget : null
        const regId = el ? el.getAttribute("id") : null
        const noSampel = el ? el.getAttribute("value") : null
        this.checked = newDomchecked;
        this.checked ? this.$store.commit("hasil_pemeriksaan/add", {id: regId, name: noSampel})
          : this.$store.commit("hasil_pemeriksaan/remove", {id: regId, name: noSampel})
      }
    },
    watch: {
      'selected': function () {
        const sampel = document.getElementById(this.item.sampel_id).value
        const findinArr = this.dataArr.length > 0 ? this.dataArr.find((el) => el.name === sampel) : null
        findinArr ? document.getElementById(this.item.sampel_id).checked = true
          : document.getElementById(this.item.sampel_id).checked = false
      }
    }
  }
</script>

<style scoped>
  .nik {
    display: block;
    color: rgb(140, 143, 135);
  }
</style>