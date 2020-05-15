<template>
    <tr>
        <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
        <td>
            <input 
                type="checkbox" class="form-control checkbox-pcr-penerimaan" 
                v-bind:value="item.nomor_sampel" 
                v-bind:id="'selected-sampel-'+item.id" 
                v-model="checked" 
                v-on:change="sampelOnChangeSelect(item.nomor_sampel)"
            >
        </td>
        <td>
            {{item.nomor_register}}
        </td>
        <td>
            {{item.nomor_sampel}}
        </td>
        <td>
            {{item.jenis_sampel_nama}}
        </td>
        <td>
            {{ item.waktu_extraction_sample_sent | formatDateTime }}
        </td>
        <td width="20%">
            <nuxt-link tag="a" class="btn btn-success btn-sm" :to="`/pcr/detail/${item.id}`" title="Klik untuk melihat detail"><i class="uil-info-circle"></i></nuxt-link>
        </td>
    </tr>
</template>
<script>
export default {
    props  : ['item', 'pagination', 'rowparams', 'index'],
    data() {
        return {
            checked: false
        }
    },
    methods: {
        sampelOnChangeSelect(nomorSampel){
            if (this.checked) {                
                this.$store.commit('pcr_penerimaan/add', nomorSampel)                    
            }

            if (!this.checked) {
                this.$store.commit('pcr_penerimaan/remove', nomorSampel)
            }
        }
    }
    
}
</script>
<style scoped>
    .checkbox-pcr-penerimaan{
        transform: scale(0.6);
    }
</style>
