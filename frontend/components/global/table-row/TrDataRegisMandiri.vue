<template>
    <tr>
        <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
        <td>{{item.nomor_register}}</td>
        <td>
            <p><b>NIK Pasien : </b>{{ item.nik }}</p>
            <p><b>Nama Pasien : </b>{{ item.nama_lengkap }}</p>
            <p><b>Usia Pasien : </b>{{ item.usia }}</p>
        </td>
        <td>
            {{item.nama_kota}}
        </td>
        <td>
            {{item.sumber_pasien}}
        </td>
        <td>
            <span class="badge badge-success mr-2" style="text-align:left;margin-bottom:10px" v-for="s in item.samples" :key="s"># {{s.nomor_sampel}} <br>
            Status : {{s.sampel_status}}
            </span>
        </td>
        <td>
            {{item.tgl_input}}
        </td>
        <td v-if="config.has_action">
            <nuxt-link :to="`/registrasi/mandiri/detail/${item.register_id}/${item.pasien_id}`" class="btn btn-success btn-sm"><i class="fa fa-eye"></i></nuxt-link>
            <nuxt-link :to="`/registrasi/mandiri/update/${item.register_id}/${item.pasien_id}`" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></nuxt-link>
            <a href="#" class="btn btn-danger btn-sm" @click="deleteData(item.register_id, item.pasien_id)"><i class="fa fa-trash"></i></a>
        </td>
    </tr>
</template>
<script>
import axios from 'axios'
export default {
    props  : ['item', 'pagination', 'rowparams', 'index','config'],
    data() {
        return {
        }
    },
    methods: {
        async deleteData(id, pasien){
            await axios.delete('v1/register/mandiri/'+id+'/'+pasien)
                .then((response)=>{
                    this.$toast.success(response.data.message, {
                        icon: 'check',
                        iconPack: 'fontawesome',
                        duration: 5000
                    })

                    this.$bus.$emit('refresh-ajaxtable', 'registrasi-mandiri')
                })
                .catch((error)=>{
                    this.$swal.fire(
                        'Terjadi Kesalahan',
                        'Gagal menghapus data, silakan hubungi admin',
                        'error'
                    );
                })
            // await axios.delete('pengguna/'+id)
            //     .then((response)=>{
            //          this.$swal.fire(
            //             'Berhasil Menghapus Data',
            //             'Data Pengguna Berhasil dihapus',
            //             'success'
            //         );
            //         this.$bus.$emit('refresh-ajaxtable', 'master-user')
            //     })
            //     .catch((error)=>{
            //          this.$swal.fire(
            //                 'Terjadi Kesalahan',
            //                 'Gagal menghapus data, silakan coba kembali',
            //                 'error'
            //             );
            //         console.log('error Hapus pengguna : ',error)
            //     })
        },
        showDetail(item) {
            const payload = {
                id:item.nomor_register,
                data:item
            }
            this.$store.dispatch('register/saveData',payload);
            this.$router.push('/registrasi/mandiri/detail')
            // console.log(this.$store.getters['register/data'])
        }
    }
}
</script>
