<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>{{item.nomor_register}}</td>
    <td>{{item.nomor_sampel}}</td>
    <td>{{item.jenis_sampel_nama}}</td>
    <td>{{item.lab_pcr_nama}}</td>
    <td>{{ item.waktu_extraction_sample_sent | formatDateTime }}</td>
    <td>
      {{item.status ? item.status.deskripsi : '-'}}
      <template v-if="item.is_musnah_ekstraksi">
        <br />
        <span class="badge badge-info">Sudah Dimusnahkan</span>
      </template>
      <template v-if="!item.is_musnah_ekstraksi">
        <br />
        <span class="badge badge-warning">Belum Dimusnahkan</span>
      </template>
    </td>
    <td>{{item.pcr ? item.pcr.kesimpulan_pemeriksaan : '-'}}</td>
    <td width="20%">
      <nuxt-link
        tag="a"
        class="btn btn-success btn-sm"
        :to="`/ekstraksi/detail/${item.id}`"
        title="Klik untuk melihat detail"
      >
        <i class="uil-info-circle"></i>
      </nuxt-link>
      <nuxt-link :to="`/ekstraksi/edit/${item.id}`" class="btn btn-warning btn-sm" tag="a">
        <i class="fa fa-edit"></i>
      </nuxt-link>
      <button
        v-if="!item.is_musnah_ekstraksi"
        type="button"
        class="btn btn-sm"
        style="background-color: #9f23c8; color: #fff"
        title="Klik untuk menandai sampel telah dimunakan"
        @click="doMusnahkan()"
        :disabled="loading"
        :class="{'btn-loading': loading}"
      >
        <i class="uil-flask-potion"></i><i class="fa fa-times"></i>
      </button>
    </td>
  </tr>
</template>
<script>
import axios from "axios";
export default {
  props  : ['item', 'pagination', 'rowparams', 'index'],
  data() {
    return {
      loading: false,
    }
  },
  methods: {
    async doMusnahkan(){
      try {
        this.loading = true
        let resp = await axios.post("/v1/ekstraksi/musnahkan/" + this.item.id);
        this.$toast.success("Sampel berhasil ditandai telah dimusnahkan", {
            icon: 'check',
            iconPack: 'fontawesome',
            duration: 5000
        })
        this.$bus.$emit('refresh-ajaxtable', 'ekstraksi-kirim')
      } catch (err) {
        if (err.response && err.response.data.code == 422) {
            this.$toast.error(err.response.data.message, {
                icon: 'times',
                iconPack: 'fontawesome',
                duration: 5000
            })
          swalWithBootstrapButtons.fire(
            'Gagal',
            err.response.data.message,
            'error'
          )
        } else {
            this.$toast.error("Gagal menandai sampel dimusnahkan", {
                icon: 'times',
                iconPack: 'fontawesome',
                duration: 5000
            })
        }
      }
      this.loading = false
    }
  }
}
</script>
