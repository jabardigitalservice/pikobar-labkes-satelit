<template>
    <tr>
        <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
        <td>
            Sampel yang dikirim : <span class="badge badge-primary">Sampel # {{item.pen_nomor_ekstraksi}}</span>
        </td>
        <td>
            <p class="badge badge-danger" v-if="item.pen_noreg==null">Identitas Pasien Belum Dimasukan Register</p>
        </td>
        <td width="20%">
            <button class="btn btn-success" title="Klik untuk melihat detail"><i class="fa fa-info"></i></button>
            <nuxt-link :to="`/ekstraksi/edit/${item.pen_nomor_ekstraksi}`" class="btn btn-success btn-sm" tag="button"><i class="fa fa-edit"></i></nuxt-link>
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
