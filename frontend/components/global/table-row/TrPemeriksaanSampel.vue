<template>
    <tr>
        <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
        <td>
            {{item.sam_barcodenomor_sampel}}
        </td>
        <td>
            {{item.sam_jenis_sampel}}
        </td>
        <td>
            <p>Dikirim Pada : {{item.eks_tanggal_pengiriman_rna}} pukul {{item.eks_jam_pengiriman_rna}}</p>
        </td>
        <td width="28%">
            <nuxt-link tag="button" class="btn btn-primary btn-xs" :to="`/pemeriksaansampel/periksa/${item.sam_barcodenomor_sampel}`"><i class="uil-check"></i>Proses</nuxt-link>
            <nuxt-link :to="`/pemeriksaansampel/kembalikan/${item.pemeriksaansampel}`" class="btn btn-danger btn-xs" tag="button"><i class="uil-arrow-left"></i>Kembalikan ke Lab Ekstraksi</nuxt-link>
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
