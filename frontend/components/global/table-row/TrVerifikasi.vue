<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>{{item.nomor_register}}</td>
    <td>
      <span v-if="item.pasien">{{item.pasien.nama_depan}}</span>
      <span class="nik" v-if="item.pasien">NIK. {{item.pasien.no_ktp}}</span>
      <span class="usia" v-if="item.pasien">{{ usiaPasien }}</span>
    </td>
    <td>
        <span v-if="item.pasien">{{item.pasien.kota.nama}}</span>
    </td>
    <td>{{item.nomor_sampel}}</td>
    <td>
      <ol>
        <li
          v-for="item in item.pemeriksaanSampel.hasil_deteksi"
          :key="item.target_gen"
        >{{ item.target_gen }} : {{ item.ct_value }}</li>
      </ol>
    </td>
    <td>{{item.pemeriksaanSampel.kesimpulan_pemeriksaan}}</td>
    <td width="20%">
      <nuxt-link
        tag="a"
        class="btn btn-success btn-sm"
        :to="`/verifikasi/detail/${item.id}`"
        title="Klik untuk melihat detail"
      >
        <i class="uil-info-circle"></i>
      </nuxt-link>
      <nuxt-link :to="`/verifikasi/edit/${item.id}`" class="btn btn-warning btn-sm" tag="a">
        <i class="fa fa-edit"></i>
      </nuxt-link>

      <button type="button" class="btn btn-primary btn-sm" 
        @click="verifikasiSampel()" 
        :disabled="loading"
        :class="{'btn-loading': loading}"
        >   
            Verifikasi
        </button>
    </td>
    <!-- <CustomModal :modal_id="`modalVerifikasi${item.id}`" v-if="isModalShow"
        :title="`Verifikasi`"
    >
    </CustomModal>-->
  </tr>
</template>
<script>
import Form from "vform";
import axios from "axios";

export default {
  props: ["item", "pagination", "rowparams", "index"],
  components: {},
  data() {

    let loading = false

    let form = new Form({
        sampel_id: this.item.id
    });

    return {
        loading,
        form
    };
  },
  methods: {
    showModalVerifikasi(sampelId) {},
    verifikasiSampel(){

        const swalWithBootstrapButtons = this.$swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
            title: 'Apakah Anda Yakin untuk Verifikasi Sampel ini?',
            text: "Setelah sampel menjadi verifikasi, data tidak dapat dikembalikan.",
            type: 'warning',
            // input: 'text',
            showCancelButton: true,
            confirmButtonText: 'Ya, Tandai Sampel Terverifikasi',
            cancelButtonText: 'Batalkan',
            reverseButtons: true
        }).then(async (result) => {
            console.log(result)
            if (result.value == '') {
                swalWithBootstrapButtons.fire(
                    'Gagal',
                    'Terjadi kesalahan. Hubungi Admin!',
                    'error'
                )
            } else if (result.value) {
                try {
                    this.loading = true
                    let resp = await await this.form.post("/v1/verifikasi/verifikasi-single-sampel/" + this.item.id);
                    swalWithBootstrapButtons.fire(
                        'Selesai!',
                        'Sampel berhasil ditandai sebagai invalid',
                        'success'
                    )
                    this.$bus.$emit('refresh-ajaxtable', 'verifikasi')
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

    }
  },
  computed: {
    usiaPasien() {
      let tglLahir = new Date(this.item.pasien.tanggal_lahir);
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
  }
};
</script>

<style scoped>
.nik {
  display: block;
  color: rgb(140, 143, 135);
}
</style>
