<template>
    <tr>
        <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
        <td>
            <p><b>Nomor Registrasi : </b>{{ item.noreg }}</p>
            <p><b>NIK Pasien : </b>{{ item.nik }}</p>
            <p><b>Nama Pasien : </b>{{ item.nama }}</p>
        </td>
        <td>
            <p><b>Dinkes Pengirim : </b>{{ item.dinkes_pengirim }}</p>
            <p><b>Fasyankes : </b>{{ item.fasyankes }}</p>
            <p><b>Dokter Penanggung Jawab : </b>{{ item.dokter_pj }}</p>
        </td>
        <td>{{ item.created_at }}</td>
        <td>
            <button class="btn btn-success btn-sm" @click="showDetail(item)">Detail</button>
            <nuxt-link :to="`registrasi-mandiri/update/${item.id}`" class="btn btn-primary btn-sm">Update</nuxt-link>
            <a href="#" class="btn btn-danger btn-sm" @click="deleteData(item.id)">Delete</a>
        </td>
    </tr>
</template>
<script>
import axios from 'axios'
export default {
    props  : ['item', 'pagination', 'rowparams', 'index'],
    data() {
        return {
        }
    },
    methods: {
        async deleteData(id){
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
                id:item.noreg,
                data:item
            }
            this.$store.dispatch('register/saveData',payload);
            this.$router.push('/registrasi/mandiri/detail')
            // console.log(this.$store.getters['register/data'])
        }
    }
}
</script>
