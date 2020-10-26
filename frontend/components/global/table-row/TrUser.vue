<template>
    <tr>
        <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
        <td>
            {{item.username}}
        </td>
        <td>
            {{item.name}}
        </td>
        <td>{{item.email}}</td>
        <td>{{item.koordinator}}</td>
        <td>{{item.lab_satelit ? item.lab_satelit.nama : '-'}}</td>
        <td>{{item.lab_satelit ? item.lab_satelit.alamat : '-'}}</td>
        <td>{{item.status}}</td>
        <td>
            <nuxt-link :to="`/user/${item.id}`" class="btn btn-yellow btn-sm">
                <i class="fa fa-eye"></i>
                Lihat
            </nuxt-link>
            <nuxt-link :to="`/user/${item.id}/edit`" class="btn btn-primary btn-sm">
                <i class="fa fa-edit"></i>
                Ubah
            </nuxt-link>
            <button class="btn btn-danger btn-sm" @click="deleteData(item.id)"> <i class="fa fa-trash"></i> Hapus</button>
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
            try {
                let resp = await axios.delete('/v1/user/'+id);
                this.$toast.success('Berhasil menghapus data', {
                    icon: 'check',
                    iconPack: 'fontawesome',
                    duration: 5000
                })
                this.$bus.$emit('refresh-ajaxtable', 'user');
            }catch(e){
                this.$swal.fire("Terjadi kesalahan", "Silakan hubungi Admin", "error");
                console.log(e);
            }
        }
    }
}
</script>
