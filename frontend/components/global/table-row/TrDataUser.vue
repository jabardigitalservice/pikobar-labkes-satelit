<template>
    <tr>
        <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
        <td>
            {{ item.name }}
            <br>
            {{ item.validator ? ('Validator: ' + item.validator.nama) : '' }}
        </td>
        <td>
            {{ item.username }}
            <br>
            {{ item.validator ? ('NIP: ' + item.validator.nip) : '' }}
        </td>
        <td>{{ item.email }}</td>
        <td>
            {{ item.roles ? item.roles.roles_name : '-' }}
            <br>
            {{ item.lab_pcr ? ('Lab PCR: ' + item.lab_pcr.nama) : '' }}
        </td>
        <td>
            <nuxt-link :to="`pengguna/detail/${item.id}`" class="mb-1 btn btn-info btn-sm">Detail</nuxt-link>
            <nuxt-link :to="`pengguna/update/${item.id}`" class="mb-1 btn btn-primary btn-sm">Update</nuxt-link>
            <a href="#" class="mb-1 btn btn-danger btn-sm" @click="deleteData(item.id)">Delete</a>
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
         deleteData(id){
            this.$swal.fire({
                title: 'Apakah Anda Yakin ?',
                text: "untuk menghapus Akun",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
            }).then((result) => {
                if (result.value) {
                     axios.delete('pengguna/'+id)
                    .then((response)=>{
                        this.$swal.fire(
                            'Berhasil Menghapus Data',
                            'Data Pengguna Berhasil dihapus',
                            'success'
                        );
                        this.$bus.$emit('refresh-ajaxtable', 'master-user')
                    })
                    .catch((error)=>{
                        this.$swal.fire(
                                'Terjadi Kesalahan',
                                'Gagal menghapus data, silakan coba kembali',
                                'error'
                            );
                        console.log('error Hapus pengguna : ',error)
                    })
                }
            })

            
        }
    }
}
</script>
