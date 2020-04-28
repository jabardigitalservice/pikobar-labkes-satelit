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
            {{item.pcr ? item.pcr.kesimpulan_pemeriksaan : '-'}}
        </td>
        <td>
            {{ item.waktu_pcr_sample_analyzed | formatDateTime }}
        </td>
        <td>
            {{item.status ? item.status.deskripsi : '-'}}
        </td>
        <td width="20%">
            <nuxt-link tag="a" class="btn btn-success btn-sm" :to="`/pcr/detail/${item.id}`" title="Klik untuk melihat detail"><i class="uil-info-circle"></i></nuxt-link>
            <nuxt-link tag="a" class="btn btn-info btn-sm" :to="`/pcr/input/${item.id}`" title="Klik untuk mengisi hasil analisis" v-if="item.register_status != 'sample_valid'">
                <i class="uil-edit"></i> Revisi
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
