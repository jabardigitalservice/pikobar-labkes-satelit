<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td nowrap>
      <span>{{item.waktu_pcr_sample_analyzed | formatDate}}</span>
      <span>{{ item.jam_input_hasil }}</span>
    </td>

    <td>{{item.nomor_sampel}}</td>
    <td nowrap>
      <span>{{item.nama_lengkap}}</span>
      <span class="nik">NIK. {{item.nik}}</span>
      <span class="usia">{{ usiaPasien }}</span>
    </td>
    <td>
      <span>{{item.nama_kota}}</span>
    </td>
    <td>
      <span>{{ item.instansi_pengirim_nama }}</span>
    </td>
    <td nowrap>
      <div v-for="item in JSON.parse(item.hasil_deteksi)" :key="item.target_gen">
        {{ item.target_gen }} : {{ item.ct_value ? item.ct_value : '-' }}
      </div>
    </td>
    <td style="text-transform: capitalize;">
      {{item.status ? item.status.toUpperCase() : null}}
    </td>
    <td style="text-transform: capitalize;">
      {{item.sumber_pasien}}
    </td>
    <td style="text-transform: capitalize;">
      {{item.kesimpulan_pemeriksaan}}
      <span v-if="item.kesimpulan_pemeriksaan == 'positif' && item.status != 'positif'">Baru</span>
      <span v-else-if="item.kesimpulan_pemeriksaan == 'positif' && item.status == 'positif'">Lama</span>
    </td>
    <td>{{item.catatan_pemeriksaan}}</td>
    <td width="20%">
      <nuxt-link tag="a" class="mb-1 text-nowrap btn btn-success btn-sm"
        :to="`/hasil-pemeriksaan/detail/${item.sampel_id}`" title="Klik untuk melihat detail">
        <i class="uil-info-circle"></i>
      </nuxt-link>
      <nuxt-link :to="`/hasil-pemeriksaan/edit/${item.sampel_id}`" class="mb-1 text-nowrap btn btn-warning btn-sm"
        tag="a">
        <i class="fa fa-edit"></i>
      </nuxt-link>
    </td>
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