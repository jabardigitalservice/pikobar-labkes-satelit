<template>
    <tr>
        <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
        <td>Sampel #{{ item.sam_barcodenomor_sampel }}</td>
        <td>
            <a href="#" class="btn btn-warning btn-sm">
                <i class="fa fa-edit"></i>
                Ubah Keterangan Sampel
            </a>
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
