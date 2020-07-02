<template>
    <tr>
        <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
        <td>
            {{item.nomor_sampel}}
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
        <td>
            {{ item.waktu_sample_taken | formatDate }}
        </td>
        <td width="20%">
            <nuxt-link tag="a" class="btn btn-info btn-sm" :to="`/pcr/input/${item.sampel_id}`"
                title="Klik untuk mengisi hasil analisis">
                <i class="uil-flask"></i> Proses
            </nuxt-link>
            <a href="#" class="mb-1 btn btn-danger btn-sm" @click="deleteData(item.sampel_id)"><i
                    class="fa fa-close"></i></a>
        </td>
    </tr>
</template>
<script>
    import axios from 'axios'
    export default {
        props: ['item', 'pagination', 'rowparams', 'index'],
        data() {
            return {}
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
                        axios.post("v1/pcr/input-invalid/" + id)
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