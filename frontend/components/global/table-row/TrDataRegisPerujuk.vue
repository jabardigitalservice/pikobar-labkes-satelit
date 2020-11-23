<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>
      <div class="badge badge-white" style="text-align:left; padding:10px">
        {{item.nomor_sampel}}
      </div>
    </td>
    <td>{{item.kode_kasus}}</td>
    <td nowrap>
      <div v-if="item.nama_pasien" style="text-transform: capitalize;"><b>{{ item.nama_pasien }}</b></div>
      <div v-if="item.nik" class="text-muted">{{ item.nik }}</div>
      <div class="text-muted">{{ usiaPasien || null }}</div>
    </td>
    <td>{{item.kota ? item.kota.nama : null}}</td>
    <td>{{item.sumber_pasien}}</td>
    <td>{{item.status}}</td>
    <td>
      <div class="text-red" v-if="(item.nik==null || item.nik=='') || (item.nama_pasien==null || item.nama_pasien=='')">
        <b>Data Belum Lengkap</b>
      </div>
      <div class="text-yellow"
        v-if="(item.nik!=null && item.nik!='') && (item.nama_pasien!=null && item.nama_pasien!='')">
        <b>Data Lengkap</b>
      </div>
    </td>
    <td v-if="config.has_action">
      <nuxt-link :to="`/registrasi/perujuk/detail/${item.id}`"
        class="mb-1 btn btn-yellow btn-sm" title="Klik untuk melihat detail">
        <i class="fa fa-eye" />
      </nuxt-link>
      <nuxt-link :to="`/registrasi/perujuk/update/${item.id}`"
        class="mb-1 btn btn-primary btn-sm" title="Klik untuk edit data">
        <i class="fa fa-edit" />
      </nuxt-link>
      <button class="mb-1 btn btn-danger btn-sm" @click="deleteData(item, usiaPasien)" title="Klik untuk hapus data">
        <i class="fa fa-trash" />
      </button>
    </td>
  </tr>
</template>
<script>
  import axios from "axios";
  import {
    getHumanAge,
    getAlertPopUp,
    momentFormatDate,
    momentFormatTime,
    getKeteranganData
  } from '~/utils';
  export default {
    props: ["item", "pagination", "rowparams", "index", "config"],
    data() {
      return {
        momentFormatDate,
        momentFormatTime
      };
    },
    methods: {
      async deleteData(item, usia) {
        const content = `
          <div class="row flex-content-center">
            ${this.$t("alert_confirm_delete_text")}
          </div>
          <div class="row col-lg-12 flex-content-center mt-4" style="font-size: 12px">
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Nomor Sampel
              </div>
              <div class="col-md-7 flex-left">
                ${item.nomor_sampel || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Kode Kasus
              </div>
              <div class="col-md-7 flex-left">
                ${item.kode_kasus || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Pasien
              </div>
              <div class="col-md-7">
                <div class="flex-left" style="text-transform: capitalize">${item.nama_pasien || '-'}</div>
                <div class="flex-left">${item.nik || ''}</div>
                <div class="flex-left">${usia}</div>
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Domisili
              </div>
              <div class="col-md-7 flex-left">
                ${item.kota || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Kategori
              </div>
              <div class="col-md-7 flex-left">
                ${item.sumber_pasien || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Kriteria
              </div>
              <div class="col-md-7 flex-left">
                ${item.status || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Keterangan
              </div>
              <div class="col-md-7 flex-left">
                ${getKeteranganData(item.nik, item.nama_pasien)}
              </div>
            </div>
          </div>
        `;
        let swal = this.$swal;
        let toast = this.$toast;
        let bus = this.$bus;
        const swalCustom = swal.mixin({
          customClass: {
            confirmButton: 'btn btn-danger btn-md ml-2',
            cancelButton: 'btn btn-clear btn-md'
          },
          buttonsStyling: false
        });
        const {
          value: isConfirm
        } = await swalCustom.fire(getAlertPopUp('delete', content));
        if (isConfirm) {
          try {
            await this.$axios.delete(`v1/register-perujuk/delete/${item.id}`);
            toast.success('Berhasil menghapus data', {
              icon: 'check',
              iconPack: 'fontawesome',
              duration: 5000
            })
            await bus.$emit('refresh-ajaxtable', 'registrasi-perujuk');
          } catch (e) {
            swal.fire("Terjadi kesalahan", "Silakan hubungi Admin", "error");
          }
        }
      },
      showDetail(item) {
        const payload = {
          id: item.nomor_register,
          data: item
        };
        this.$store.dispatch("register/saveData", payload);
        this.$router.push("/registrasi/mandiri/detail");
      }
    },
    computed: {
      usiaPasien() {
        if (this.item.usia_tahun && this.item.usia_bulan) {
          return `${this.item.usia_tahun} tahun ${this.item.usia_bulan} bulan`;
        }
        if (this.item.tanggal_lahir) {
          return getHumanAge(this.item.tanggal_lahir);
        }
        return "";
      }
    }
  };
</script>