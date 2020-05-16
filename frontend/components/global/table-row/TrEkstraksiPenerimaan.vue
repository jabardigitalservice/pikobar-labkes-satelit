<template>
    <tr>
        <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
        <td>
            <input 
                type="checkbox" class="form-control checkbox-ekstraksi-penerimaan" 
                v-bind:value="item.nomor_sampel" 
                v-bind:id="'selected-sampel-'+item.id" 
                v-model="checked" 
                v-on:change="sampelOnChangeSelect(item.nomor_sampel)"
            >
        </td>
        <td>
            {{item.nomor_register}}
        </td>
        <td>
            {{item.nomor_sampel}}
        </td>
        <td>
            {{item.jenis_sampel_nama}}
        </td>
        <td>
            <!-- Kondisi Sampel == petugas_pengambilan_sampel -->
            {{item.kondisi_sampel}}
            <span v-if="!item.kondisi_sampel">-</span>
        </td>
        <td>
          {{ item.status.deskripsi }}
        </td>
        <td>
            {{ item.waktu_sample_taken | formatDateTime }}
        </td>
        <td width="20%">
            <nuxt-link tag="a" class="mb-1 btn btn-success btn-sm" :to="`/ekstraksi/detail/${item.id}`" title="Klik untuk melihat detail"><i class="uil-info-circle"></i></nuxt-link>
            <button
                type="button"
                class="mb-1 btn btn-warning btn-sm"
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
            checked: false,
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
            this.$bus.$emit('refresh-ajaxtable', 'ekstraksi-penerimaan')
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
    sampelOnChangeSelect(nomorSampel){
        if (this.checked) {                
            this.$store.commit('ekstraksi_penerimaan/add', nomorSampel)
            // console.log(this.$store.state.validasi.selectedSampels);
            
        }

        if (!this.checked) {
            this.$store.commit('ekstraksi_penerimaan/remove', nomorSampel)
        }
    }
    }
}
</script>

<style scoped>
    .checkbox-ekstraksi-penerimaan{
        transform: scale(1.7);
    }
</style>
