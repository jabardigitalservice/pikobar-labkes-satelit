<template>
    <tr>
        <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
        <td>
            <p class="badge badge-xs badge-primary">Sampel #{{ item.nomor_sampel }}</p>
        </td>
        <td>
            {{item.nomor_register}}
        </td>
        <td>{{item.jenis_sampel_nama}}</td>
        <td>{{ item.waktu_sample_taken | formatDateTime }}</td>
        <td>{{item.petugas_pengambilan_sampel}}</td>
        <td>
            <nuxt-link :to="`sample/edit/${item.nomor_sampel}`" class="btn btn-warning btn-sm">
                <i class="fa fa-edit"></i>
                Ubah Keterangan Sampel
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
