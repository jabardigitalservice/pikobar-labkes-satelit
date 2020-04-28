<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>{{item.nomor_register}}</td>
    <td>{{item.nomor_sampel}}</td>
    <td>{{item.jenis_sampel}}</td>
    <td>{{item.lab_pcr_nama}}</td>
    <td>{{ item.waktu_extraction_sample_reextract | formatDateTime }}</td>
    <td>{{ item.pcr ? item.pcr.catatan_pemeriksaan : '-' }}</td>
    <td width="20%">
      <nuxt-link
        tag="a"
        class="btn btn-success btn-sm"
        :to="`/ekstraksi/detail/${item.id}`"
        title="Klik untuk melihat detail"
      >
        <i class="uil-info-circle"></i>
      </nuxt-link>
      <button
        type="button"
        class="btn btn-info btn-sm"
        title="Klik untuk menandai sampel untuk diproses"
        @click="promptProses()"
        :disabled="loading"
        :class="{'btn-loading': loading}"
      >
        <i class="uil-flask"></i> Proses
      </button>
      <button
        type="button"
        class="btn btn-warning btn-sm"
        title="Klik untuk menandai sampel sebagai invalid"
        @click="promptInvalid()"
        :disabled="loading"
        :class="{'btn-loading': loading}"
      >
        <i class="uil-flask-potion"></i> Invalid
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
    promptInvalid() {
      const swalWithBootstrapButtons = this.$swal.mixin({
        customClass: {
          confirmButton: 'btn btn-success',
          cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
      })
      swalWithBootstrapButtons.fire({
        title: 'Apakah Anda Yakin Mendandai Sampel Menjadi Invalid?',
        text: "Setelah sampel menjadi invalid, data tidak dapat dikembalikan. Masukkan keterangan invalid:",
        type: 'warning',
        input: 'text',
        showCancelButton: true,
        confirmButtonText: 'Ya, Tandai Sampel Invalid',
        cancelButtonText: 'Batalkan',
        reverseButtons: true
      }).then(async (result) => {
        console.log(result)
        if (result.value == '') {
          swalWithBootstrapButtons.fire(
            'Gagal',
            'Alasan tidak boleh kosong',
            'error'
          )
        } else if (result.value) {
          try {
            this.loading = true
            let resp = await axios.post("/v1/ekstraksi/set-invalid/" + this.item.id, {alasan: result.value});
            swalWithBootstrapButtons.fire(
              'Selesai!',
              'Sampel berhasil ditandai sebagai invalid',
              'success'
            )
            this.$bus.$emit('refresh-ajaxtable', 'ekstraksi-dikembalikan')
          } catch (err) {
            if (err.response && err.response.data.code == 422) {
              swalWithBootstrapButtons.fire(
                'Gagal',
                err.response.data.message,
                'error'
              )
            } else {
              swalWithBootstrapButtons.fire(
                'Gagal',
                'Gagal menandai sampel menjadi invalid',
                'error'
              )
            }
          }
          this.loading = false
        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === this.$swal.DismissReason.cancel
        ) {
        }
      })
    },
    promptProses() {
      const swalWithBootstrapButtons = this.$swal.mixin({
        customClass: {
          confirmButton: 'btn btn-success',
          cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
      })
      swalWithBootstrapButtons.fire({
        title: 'Apakah Anda Yakin Mendandai Sampel untuk diproses?',
        text: "Setelah sampel menjadi diproses, data tidak dapat dikembalikan. Masukkan keterangan proses:",
        type: 'info',
        input: 'text',
        showCancelButton: true,
        confirmButtonText: 'Ya, Tandai Sampel Proses',
        cancelButtonText: 'Batalkan',
        reverseButtons: true
      }).then(async (result) => {
        console.log(result)
        if (result.value == '') {
          swalWithBootstrapButtons.fire(
            'Gagal',
            'Alasan tidak boleh kosong',
            'error'
          )
        } else if (result.value) {
          try {
            this.loading = true
            let resp = await axios.post("/v1/ekstraksi/set-proses/" + this.item.id, {alasan: result.value});
            swalWithBootstrapButtons.fire(
              'Selesai!',
              'Sampel berhasil ditandai sebagai sampel yang diproses',
              'success'
            )
            this.$bus.$emit('refresh-ajaxtable', 'ekstraksi-dikembalikan')
          } catch (err) {
            if (err.response && err.response.data.code == 422) {
              swalWithBootstrapButtons.fire(
                'Gagal',
                err.response.data.message,
                'error'
              )
            } else {
              swalWithBootstrapButtons.fire(
                'Gagal',
                'Gagal menandai sampel menjadi sampel yang diproses',
                'error'
              )
            }
          }
          this.loading = false
        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === this.$swal.DismissReason.cancel
        ) {
        }
      })
    }
  }
}
</script>
