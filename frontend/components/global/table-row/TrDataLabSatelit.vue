<template>
    <tr>
        <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
        <td>
            {{ item.nama }}
        </td>
        <td>
            {{ item.alamat }}
        </td>
        <td>
            {{ item.longitude }}
        </td>
        <td>
            {{ item.latitude }}
        </td>
        <td>
            <nuxt-link :to="`lab-satelit/show/${item.id}`" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></nuxt-link>
            <nuxt-link :to="`lab-satelit/update/${item.id}`" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></nuxt-link>
            <a href="#" class="btn btn-danger btn-sm" @click="deleteData(item.id)"><i class="fa fa-trash"></i></a>
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
            await axios.delete('lab-satelit/'+id)
                .then((response)=>{
                     this.$swal.fire(
                        'Berhasil Menghapus Data',
                        'Data Lab Satelit Berhasil dihapus',
                        'success'
                    );
                    this.$bus.$emit('refresh-ajaxtable', 'lab-satelit')
                })
                .catch((error)=>{
                     this.$swal.fire(
                            'Terjadi Kesalahan',
                            'Gagal menghapus data, silakan coba kembali',
                            'error'
                        );
                    console.log('error Hapus lab satelit : ',error)
                })
        }
    }
}
</script>
