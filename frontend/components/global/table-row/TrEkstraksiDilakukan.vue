<template>
    <tr>
        <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
        <td>
            <input 
                type="checkbox" class="form-control checkbox-ekstraksi-dilakukan" 
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
          {{ item.status.deskripsi }}
        </td>
        <td>
            <span v-if="item.ekstraksi">{{ item.ekstraksi.operator_ekstraksi }}</span>
            <span v-if="item.ekstraksi && !item.ekstraksi.operator_ekstraksi">{{ '-' }}</span>
        </td>
        <td>
            {{ item.waktu_extraction_sample_extracted | formatDateTime }}
        </td>
        <td width="20%">
            <nuxt-link tag="a" class="btn btn-success btn-sm" :to="`/ekstraksi/detail/${item.id}`" title="Klik untuk melihat detail"><i class="uil-info-circle"></i></nuxt-link>
            <nuxt-link :to="`/ekstraksi/edit/${item.id}`" class="btn btn-warning btn-sm" tag="a"><i class="fa fa-edit"></i></nuxt-link>
        </td>
    </tr>
</template>
<script>
export default {
    props  : ['item', 'pagination', 'rowparams', 'index'],
    data() {
        return {
            checked: false,
        }
    },
    methods: {
        sampelOnChangeSelect(nomorSampel){
            if (this.checked) {                
                this.$store.commit('ekstraksi_dilakukan/add', nomorSampel)
                // console.log(this.$store.state.validasi.selectedSampels);
                
            }

            if (!this.checked) {
                this.$store.commit('ekstraksi_dilakukan/remove', nomorSampel)
            }
        }
    }
}
</script>
<style scoped>
    .checkbox-ekstraksi-dilakukan{
        transform: scale(1.7);
    }
</style>
