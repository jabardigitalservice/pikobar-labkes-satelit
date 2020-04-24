<template>
    <tr>
        <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
        <td>
            <b>Sampel yang dikembalikan</b>
            <p class="badge badge-danger">Sampel #{{item.pen_nomor_ekstraksi}}</p>
            
            </td>
        <td>
            <p class="badge badge-danger" v-if="item.pen_noreg==null">Identitas Pasien Belum Dimasukan Register</p>
        </td>
        <td width="20%">
            <nuxt-link :to="`/ekstraksi/pilihulang/${item.pen_nomor_ekstraksi}`" class="btn btn-primary btn-sm" tag="button"><i class="uil-info-circle"></i> Ubah lalu kirim kembali</nuxt-link>
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
