<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>
      <div class="badge badge-white" style="text-align:left; padding:5px">
        {{item.nomor_sampel}}
      </div>
    </td>
    <td>
      {{item.nama_lengkap}}
    </td>
    <td>
      {{item.nik}}
    </td>
    <td>
      {{item.instansi_pengirim_nama}}
    </td>
    <td nowrap>
      <div>
        <b>{{ item.waktu_sample_taken ? momentFormatDate(item.waktu_sample_taken) : null }}</b>
      </div>
      <div class="text-muted">
        {{ item.waktu_sample_taken ? 'pukul ' + momentFormatTime(item.waktu_sample_taken) : null }}
      </div>
    </td>
    <td width="20%">
      <nuxt-link tag="a" class="btn btn-primary btn-sm mb-1" :to="`/pcr/input/${item.sampel_id}`"
        title="Klik untuk mengisi hasil analisis">
        <i class="uil-flask"></i> Proses
      </nuxt-link>
      <a href="#" class="btn btn-clear btn-sm mb-1" @click="deleteData(item.sampel_id)"
        title="Klik untuk menandai sampel invalid">
        <i class="uil-flask-potion" /> Invalid
      </a>
    </td>
  </tr>
</template>

<script>
  import {
    momentFormatDate,
    momentFormatTime
  } from '~/utils';
  export default {
    props: ['item', 'pagination', 'rowparams', 'index'],
    data() {
      return {
        momentFormatDate,
        momentFormatTime
      }
    },
    methods: {
      async deleteData(id) {
        this.$swal.fire({
          title: 'Apakah Anda Yakin ?',
          text: "untuk mengubah status menjadi invalid",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya',
          cancelButtonText: 'Tidak',
        }).then((result) => {
          if (result.value) {
            this.$axios.post("v1/pcr/input-invalid/" + id)
              .then((response) => {
                this.$swal.fire(
                  'Berhasil mengubah Data',
                  'Data Berhasil menjadi invalid',
                  'success'
                );
                this.$bus.$emit('refresh-ajaxtable', 'pcr-analisis')
              })
              .catch((error) => {
                this.$swal.fire(
                  'Terjadi Kesalahan',
                  'Gagal mengubah data, silakan coba kembali',
                  'error'
                );
              })
          }
        })
      },
    }
  }
</script>