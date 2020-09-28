<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td nowrap>
      <div v-if="item.nama_lengkap" style="text-transform: capitalize;"><b>{{ item.nama_lengkap }}</b></div>
      <div v-if="item.nik" class="text-muted">{{ item.nik }}</div>
      <div class="text-muted">{{ usiaPasien || null }}</div>
    </td>
    <td>{{item.nama_kota}}</td>
    <td>{{item.instansi_pengirim_nama}}</td>
    <td>
      <div class="badge badge-white" style="text-align:left; padding:10px">
        {{item.nomor_sampel}}
      </div>
    </td>
    <td>{{item.sumber_pasien}}</td>
    <td>{{item.status ? pasienStatus.find(x => x.value == item.status).text : null }}</td>
    <td nowrap>
      <div><b>{{ item.waktu_sample_taken ? momentFormatDate(item.waktu_sample_taken) : null }}</b></div>
      <div class="text-muted">
        {{ item.waktu_sample_taken ? 'pukul ' + momentFormatTime(item.waktu_sample_taken) : null }}</div>
    </td>
    <td>
      <div class="text-red"
        v-if="(item.nik==null || item.nik=='') || (item.nama_lengkap==null || item.nama_lengkap=='')">
        <b>Data Belum Lengkap</b>
      </div>
      <div class="text-yellow"
        v-if="(item.nik!=null && item.nik!='') && (item.nama_lengkap!=null && item.nama_lengkap!='')">
        <b>Data Lengkap</b>
      </div>
    </td>
    <td v-if="config.has_action">
      <nuxt-link :to="`/registrasi/sampel/detail/${item.register_id}/${item.pasien_id}`"
        class="mb-1 btn btn-yellow btn-sm" title="Klik untuk melihat detail">
        <i class="fa fa-eye" />
      </nuxt-link>
      <nuxt-link :to="`/registrasi/sampel/update/${item.register_id}/${item.pasien_id}`"
        class="mb-1 btn btn-primary btn-sm" title="Klik untuk edit data">
        <i class="fa fa-edit" />
      </nuxt-link>
      <a href="#" class="mb-1 btn btn-danger btn-sm" @click="deleteData(item.register_id, item.pasien_id)">
        <i class="fa fa-trash" title="Klik untuk hapus data" />
      </a>
    </td>
  </tr>
</template>
<script>
  import axios from "axios";
  import {
    pasienStatus
  } from '~/assets/js/constant/enum';
  import {
    getHumanAge,
    getAlertDelete,
    momentFormatDate,
    momentFormatTime
  } from '~/utils';
  export default {
    props: ["item", "pagination", "rowparams", "index", "config"],
    data() {
      return {
        pasienStatus,
        momentFormatDate,
        momentFormatTime
      };
    },
    methods: {
      async deleteData(id, pasien) {
        let swal = this.$swal;
        let toast = this.$toast;
        let bus = this.$bus;
        const {
          value: isConfirm
        } = await swal.fire(getAlertDelete());
        if (isConfirm) {
          try {
            await this.$axios.delete(`v1/register/sampel/${id}/${pasien}`);
            toast.success('Berhasil menghapus data', {
              icon: 'check',
              iconPack: 'fontawesome',
              duration: 5000
            })
            await bus.$emit('refresh-ajaxtable', 'registrasi-sampel');
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