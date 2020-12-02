<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>
      <div class="badge badge-white" style="text-align:left; padding:10px">
        {{item.nomor_sampel}}
      </div>
    </td>
    <td>
      <span>{{item.kode_kasus}}</span>
    </td>
    <td nowrap>
      <div v-if="item.nama_lengkap" style="text-transform: capitalize;"><b>{{ item.nama_lengkap }}</b></div>
      <div v-if="item.nik" class="text-muted">{{ item.nik }}</div>
      <div class="text-muted">{{ usiaPasien || null }}</div>
    </td>
    <td>
      <span>{{item.nama_kota}}</span>
    </td>
    <td nowrap>
      <span>{{item.waktu_pcr_sample_analyzed | formatDate}}</span>
      <span>{{ item.jam_input_hasil }}</span>
    </td>
    <td style="text-transform: capitalize;">
      {{item.kesimpulan_pemeriksaan}}
    </td>
    <td>{{item.catatan_pemeriksaan}}</td>
    <td width="20%">
      <nuxt-link tag="a" class="mb-1 btn btn-yellow btn-sm" :to="`/hasil-pemeriksaan-perujuk/detail/${item.sampel_id}`"
        title="Klik untuk melihat detail">
        <i class="uil-info-circle" />
      </nuxt-link>
    </td>
  </tr>
</template>
<script>
  import Form from "vform";
  import axios from "axios";
  import {
    pasienStatus
  } from '~/assets/js/constant/enum';
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
        form,
        pasienStatus
      };
    },
    methods: {
      showModalVerifikasi(sampelId) {},
      verifikasiSampel() {

        const swalWithBootstrapButtons = this.$swal.mixin({
          customClass: {
            confirmButton: 'mb-1 text-nowrap btn btn-success',
            cancelButton: 'mb-1 text-nowrap btn btn-danger'
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
              let resp = await this.form.post("/v1/verifikasi/verifikasi-single-sampel/" + this.item.id);
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
          ) {}
        })

      }
    },
    computed: {
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
    }
  };
</script>

<style scoped>
  .nik {
    display: block;
    color: rgb(140, 143, 135);
  }
</style>