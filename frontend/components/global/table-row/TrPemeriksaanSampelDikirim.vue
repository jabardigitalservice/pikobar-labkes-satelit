<template>
    <tr>
        <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
        <td width="15%">
            <p class="badge badge-primary">#{{item.sam_barcodenomor_sampel}}</p>
        </td>
        <td>
            {{item.sam_jenis_sampel}}
        </td>
        <td>
            <p>Dikirim Pada : {{item.eks_tanggal_pengiriman_rna}} pukul {{item.eks_jam_pengiriman_rna}}</p>
        </td>
        <td width="28%">
            <nuxt-link :to="`pemeriksaansampel/detail/${item.pem_id}`" class="btn btn-xs btn-info"><i class="uil-eye"></i> Detail Pemeriksaan</nuxt-link>
            <nuxt-link :to="`pemeriksaansampel/edit/${item.pem_id}`" class="btn btn-xs btn-warning"><i class="uil-edit"></i> Ubah Pemeriksaan</nuxt-link>
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
    },
    filters:{
        // transformJenisSampel(value) {
        //     switch(value){
        //         case 1:

        //     }
        //     return value;
        // }
    }
}
</script>
