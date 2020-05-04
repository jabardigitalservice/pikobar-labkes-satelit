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
        </td>
    </tr>
</template>
<script>
export default {
    props  : ['item', 'pagination', 'rowparams', 'index'],
    data() {
        return {
        }
    },
    methods: {
    }
}
</script>
