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
            {{ item.waktu_pcr_sample_analyzed | formatDateTime }}
        </td>
        <td>
            {{item.status ? item.status.deskripsi : '-'}}
            <template v-if="item.is_musnah_pcr">
                <br />
                <span class="badge badge-info">Sudah Dimusnahkan</span>
            </template>
            <template v-if="!item.is_musnah_pcr">
                <br />
                <span class="badge badge-warning">Belum Dimusnahkan</span>
            </template>
        </td>
        <td>
            {{item.pcr ? item.pcr.kesimpulan_pemeriksaan : '-'}}
        </td>
        <td width="20%">
            <nuxt-link tag="a" class="btn btn-success btn-sm" :to="`/pcr/detail/${item.id}`" title="Klik untuk melihat detail"><i class="uil-info-circle"></i></nuxt-link>
            <nuxt-link tag="a" class="btn btn-info btn-sm" :to="`/pcr/input/${item.id}`" title="Klik untuk mengisi hasil analisis" v-if="item.sampel_status != 'sample_valid'">
                <i class="uil-edit"></i> Revisi
            </nuxt-link>
            <button
                v-if="!item.is_musnah_pcr"
                type="button"
                class="btn btn-sm"
                style="background-color: #9f23c8; color: #fff"
                title="Klik untuk menandai sampel telah dimunakan"
                @click="doMusnahkan()"
                :disabled="loading"
                :class="{'btn-loading': loading}"
            >
                <i class="uil-flask-potion"></i><i class="fa fa-times"></i>
            </button>
        </td>
    </tr>
</template>
<script>
import axios from "axios";
export default {
    props  : ['item', 'pagination', 'rowparams', 'index'],
    data() {
        return {
            loading: false,
        }
    },
    methods: {
        async doMusnahkan(){
            try {
                this.loading = true
                let resp = await axios.post("/v1/pcr/musnahkan/" + this.item.id);
                this.$toast.success("Sampel berhasil ditandai telah dimusnahkan", {
                    icon: 'check',
                    iconPack: 'fontawesome',
                    duration: 5000
                })
                this.$bus.$emit('refresh-ajaxtable', 'pcr-hasil-pemeriksaan')
            } catch (err) {
                if (err.response && err.response.data.code == 422) {
                    this.$toast.error(err.response.data.message, {
                        icon: 'times',
                        iconPack: 'fontawesome',
                        duration: 5000
                    })
                swalWithBootstrapButtons.fire(
                    'Gagal',
                    err.response.data.message,
                    'error'
                )
                } else {
                    this.$toast.error("Gagal menandai sampel dimusnahkan", {
                        icon: 'times',
                        iconPack: 'fontawesome',
                        duration: 5000
                    })
                }
            }
            this.loading = false
        }
    }
}
</script>
