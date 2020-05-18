<template>
    <tr>
        <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
        <td>
            {{item.nomor_sampel}}
        </td>
        <td>
            {{item.pasien.nama_lengkap}}
        </td>
        <td>
            {{item.pasien.nik}}
        </td>
        <td>
            {{item.register.instansi_pengirim}}
        </td>
        <td>
            {{ item.waktu_sample_taken | formatDateTime }}
        </td>
        <td width="20%">
            <nuxt-link tag="a" class="btn btn-info btn-sm" :to="`/pcr/input/${item.id}`" title="Klik untuk mengisi hasil analisis">
                <i class="uil-flask"></i> Proses
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
