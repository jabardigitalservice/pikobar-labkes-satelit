<template>
    <tr>
        <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
        <td>
            {{item.nomor_register}}
        </td>
        <td>
            {{item.nomor_sampel}}
        </td>
        <td>
            {{item.jenis_sampel}}
        </td>
        <td>
            {{ item.waktu_sample_invalid | formatDateTime }}
        </td>
        <td>
            {{ item.pcr ? item.pcr.catatan_pemeriksaan : '-' }}
        </td>
        <td>
            {{ item.ekstraksi ? item.ekstraksi.catatan_pengiriman : '-' }}
        </td>
        <td width="20%">
            <nuxt-link tag="a" class="btn btn-success btn-sm" :to="`/ekstraksi/detail/${item.id}`" title="Klik untuk melihat detail"><i class="uil-info-circle"></i></nuxt-link>
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
