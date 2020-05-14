<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>{{item.nomor_register}}</td>
    <td nowrap>
      <span v-if="item.pasien">{{item.pasien.nama_lengkap}}</span>
      <span class="nik" v-if="item.pasien">NIK. {{item.pasien.nik}}</span>
      <span class="usia" v-if="item.pasien">{{ usiaPasien }}</span>
    </td>
    <td>
        <span v-if="item.register">{{ item.register.sumber_pasien }}</span>
    </td>
    <td>
      <span v-if="item.pasien && item.pasien.kota">{{item.pasien.kota.nama}}</span>
    </td>
    <td>{{item.nomor_sampel}}</td>
    <td nowrap>
      <div
        v-for="item in item.pemeriksaanSampel.hasil_deteksi_parsed"
        :key="item.target_gen"
      >- {{ item.target_gen }} : 
        <span v-if="!!item.ct_value">{{ parseFloat(item.ct_value).toFixed(2).replace('.', ',') }}</span>
        <span v-if="item.ct_value == null">{{ '-' }}</span>
      </div>
    </td>
    <td>{{item.kondisi_sampel}}</td>
    <td style="text-transform: capitalize;">
      {{item.pemeriksaanSampel.kesimpulan_pemeriksaan}}
    </td>
    <td>
      <span v-if="item.sampel_status === 'sample_verified'">Verifikasi</span>
      <span v-if="item.sampel_status === 'sample_valid'">Valid</span>
      <span v-if="item.counter_print_hasil" class="counter-print">
        <i>Diprint {{ item.counter_print_hasil }} kali</i>
      </span>
    </td>
    <td>{{item.waktu_sample_valid | formatDate}}</td>
    <td width="20%">
      <nuxt-link
        tag="a"
        class="mb-1 text-nowrap btn btn-success btn-sm"
        :to="`/validasi/detail/${item.id}`"
        title="Klik untuk melihat detail"
      >
        <i class="uil-info-circle"></i>
      </nuxt-link>
      <button @click="downloadPDF()" class="btn btn-sm btn-primary" type="button" :disabled="loading == true">
        <!-- <i class="fa fa-file-excel"></i> -->
        <span v-if="loading == true"><i class="fa fa-spinner"></i></span>
        <span v-if="loading == false">{{'Print'}}</span>
      </button>
      <button
        @click="regeneratePDF()"
        class="mb-1 text-nowrap btn btn-sm btn-primary"
        type="button"
      >{{ 'Regenerate Print' }}</button>
      <!-- <nuxt-link :to="`/validasi/edit/${item.id}`" class="mb-1 text-nowrap btn btn-warning btn-sm" tag="a"><i class="fa fa-edit"></i></nuxt-link> -->
    </td>
  </tr>
</template>
<script>
import Form from "vform";
import axios from "axios";

export default {
  props: ["item", "pagination", "rowparams", "index"],
  data() {
    let form = new Form({
      sampel_id: this.item.id
    });

    return {
      loading: false,
      form
    };
  },
  methods: {
    async downloadPDF() {
      try {
        this.loading = true;

        axios({
          url: process.env.apiUrl + "/v1/validasi/export-pdf/" + this.item.id,
          // params: this.form,
          method: "GET",
          responseType: "blob"
        }).then(response => {
          const blob = new Blob([response.data], { type: response.data.type });
          const url = window.URL.createObjectURL(blob);

          const link = document.createElement("a");
          link.href = url;


          // link.download = "test.pdf";
          // link.click();
          setTimeout(function(){
            window.open(url);
          }, 500);


          // const contentDisposition = response.headers["content-disposition"];

          // const fileNameHeader = "x-suggested-filename";
          // const suggestedFileName = response.headers[fileNameHeader];
          // const effectiveFileName =
          //   suggestedFileName === undefined
          //     ? "surat-hasil-pemeriksaan-" + this.item.nomor_sampel
          //     : suggestedFileName;

          // let fileName = effectiveFileName + ".pdf";

          // if (contentDisposition) {
          //   const fileNameMatch = contentDisposition.match(/filename=(.+)/);
          //   if (fileNameMatch.length === 2) fileName = fileNameMatch[1];
          // }
          // link.setAttribute("download", fileName);
          // document.body.appendChild(link);
          // link.click();
          // link.remove();
          // window.URL.revokeObjectURL(url);

          this.$bus.$emit("refresh-ajaxtable", "validated");

          this.loading = false;
        });

        // const response = await this.form.post("/v1/verifikasi/export-excel");

        // this.$toast.success(response.data.message, {
        //   icon: "check",
        //   iconPack: "fontawesome",
        //   duration: 5000
        // });

        // this.$router.back()
      } catch (err) {
        if (err.response && err.response.data.code == 422) {
          this.$nextTick(() => {
            this.form.errors.set(err.response.data.error);
          });
          this.$toast.error("Mohon cek kembali formulir Anda", {
            icon: "times",
            iconPack: "fontawesome",
            duration: 5000
          });
        } else {
          console.log(err);

          this.$swal.fire(
            "Terjadi kesalahan",
            "Silakan hubungi Admin",
            "error"
          );
        }

        this.loading = false;
      }
    },

    regeneratePDF() {
      const swalWithBootstrapButtons = this.$swal.mixin({
        customClass: {
          confirmButton: "mb-1 text-nowrap btn btn-success",
          cancelButton: "mb-1 text-nowrap btn btn-danger"
        },
        buttonsStyling: false
      });

      swalWithBootstrapButtons
        .fire({
          title:
            "Apakah Anda Yakin untuk memuat ulang PDF hasil pemeriksaan ini?",
          text: "Setelah dimuat ulang, file PDF sebelumnya akan diganti.",
          type: "warning",
          // input: 'text',
          showCancelButton: true,
          confirmButtonText: "Ya, Muat Ulang Hasil Print PDF",
          cancelButtonText: "Batalkan",
          reverseButtons: true
        })
        .then(async result => {
          console.log(result);
          if (result.value == "") {
            swalWithBootstrapButtons.fire(
              "Gagal",
              "Terjadi kesalahan. Hubungi Admin!",
              "error"
            );
          } else if (result.value) {
            try {
              this.loading = true;
              let resp = await this.form.post(
                "/v1/validasi/regenerate-pdf/" + this.item.id
              );
              swalWithBootstrapButtons.fire(
                "Selesai!",
                "Hasil Print PDF berhasil dimuat ulang",
                "success"
              );
              // this.$bus.$emit('refresh-ajaxtable', 'verifikasi')
            } catch (err) {
              if (err.response && err.response.data.code == 422) {
                swalWithBootstrapButtons.fire(
                  "Gagal",
                  err.response.data.message,
                  "error"
                );
              } else {
                swalWithBootstrapButtons.fire(
                  "Gagal",
                  "Gagal memuat ulang hasil print",
                  "error"
                );
              }
            }

            this.loading = false;
          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === this.$swal.DismissReason.cancel
          ) {
          }
        });
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
.nik,
.counter-print {
  display: block;
  color: rgb(140, 143, 135);
}
</style>
