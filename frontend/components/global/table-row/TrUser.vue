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
        <td>{{ item.lab_satelit ? item.lab_satelit.name : '-'}}</td>
        <td>{{item.status}}</td>
        <td>
            <nuxt-link :to="`v1/user/${item.id}`" class="btn btn-warning btn-sm">
                <i class="fa fa-edit"></i>
                Lihat
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
                let resp = await axios.get('/sample/delete/'+id);
                this.$toast.success('Berhasil menghapus data', {
                    icon: 'check',
                    iconPack: 'fontawesome',
                    duration: 5000
                })
                this.$bus.$emit('refresh-ajaxtable', 'sample-sent');
            }catch(e){
                this.$swal.fire("Terjadi kesalahan", "Silakan hubungi Admin", "error");
                console.log(e);
            }
        }
    }
}
</script>
