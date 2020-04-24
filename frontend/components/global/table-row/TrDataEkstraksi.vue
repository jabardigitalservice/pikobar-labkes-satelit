<template>
    <tr>
        <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
        <td>{{item.pen_nomor_ekstraksi}}</td>
        <td>
            <p class="badge badge-danger" v-if="item.pen_noreg==null">Identitas Pasien Belum Dimasukan Register</p>
        </td>
        <td width="15%">
            <p><b>Jumlah Sampel : </b>{{item.pen_id_sampel?item.pen_id_sampel.split(',').length:0}}</p>
            <p class="badge badge-primary" v-for="item in item.pen_id_sampel.split(',')" :key="item">Sampel #{{item}}</p>
        </td>
        <td width="20%">
            <nuxt-link :to="`/ekstraksi/pilih/${item.pen_nomor_ekstraksi}`" class="btn btn-success btn-sm" tag="button">Pilih Sampel Untuk Dikirim</nuxt-link>
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
