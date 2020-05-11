<template>
    <tr>
        <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
        <td>
            <input 
                type="checkbox" class="form-control checkbox-unvalidate" 
                v-bind:value="item.id" 
                v-bind:id="'selected-sampel-'+item.id" 
                v-model="checked" 
                v-on:change="sampelOnChangeSelect(item.id)"
            >
        </td>
        <td>
            {{item.nomor_register}}
        </td>
        <td nowrap>
            <span v-if="item.pasien">{{item.pasien.nama_lengkap}}</span>
            <span class="nik" v-if="item.pasien">NIK. {{item.pasien.nik}}</span>
            <span class="usia" v-if="item.pasien">{{ usiaPasien }}</span>
        </td>
        <td>
            <span v-if="item.register">{{ item.register.sumber_pasien }}</span>
        </td>
        <td>
            <span v-if="item.pasien && item.pasien.kota">{{item.pasien.kota.nama}}</span>
        </td>
        <td>
            {{item.nomor_sampel}}
        </td>
        <td>
            <div
                v-for="item in item.pemeriksaanSampel.hasil_deteksi" 
                :key="item.target_gen"
            >
                - {{ item.target_gen }} : {{ item.ct_value }}
            </div>
        </td>
        <td>
            {{item.kondisi_sampel}}
        </td>
        <td>
            {{item.pemeriksaanSampel.kesimpulan_pemeriksaan}}
        </td>
        <td width="20%">
            <nuxt-link tag="a" class="btn btn-success btn-sm" :to="`/validasi/detail/${item.id}`" title="Klik untuk melihat detail"><i class="uil-info-circle"></i></nuxt-link>
            <nuxt-link :to="`/validasi/edit/${item.id}`" class="btn btn-warning btn-sm" tag="a"><i class="fa fa-edit"></i></nuxt-link>
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
        sampelOnChangeSelect(sampelId){
            if (this.checked) {                
                this.$store.commit('validasi/add', sampelId)
                // console.log(this.$store.state.validasi.selectedSampels);
                
            }

            if (!this.checked) {
                this.$store.commit('validasi/remove', sampelId)
            }
        }
    },
    computed: {

        usiaPasien() {
            let tglLahir = new Date(this.item.pasien.tanggal_lahir);
            let today_date = new Date();
            let today_year = today_date.getFullYear();
            let today_month = today_date.getMonth();
            let today_day = today_date.getDate();

            var age = today_date.getFullYear() - tglLahir.getFullYear();
            var m = today_date.getMonth() - tglLahir.getMonth();
            if (m < 0 || (m === 0 && today_date.getDate() < tglLahir.getDate())) 
            {
                age--;
            }

            return `Usia: ${age} tahun`
        }
    }
}
</script>

<style scoped>
    .nik {
        display: block;
        color: rgb(140, 143, 135);
    }
</style>
