<template>
    <tr>
        <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
        <td>
            {{item.nomor_sampel}}
        </td>
        <td>
            <span v-if="item.pasien">{{item.pasien.nama_lengkap}}</span>
            <span class="nik" v-if="item.pasien">NIK. {{item.pasien.nik}}</span>
            <span class="usia" v-if="item.pasien">{{ usiaPasien }}</span>
        </td>
        
        <td>
            {{item.waktu_sample_valid | formatDate}}
        </td>
        <td width="20%">
            <nuxt-link tag="a" class="btn btn-success btn-sm" :to="`/validasi/detail/${item.id}`" title="Klik untuk melihat detail"><i class="uil-info-circle"></i></nuxt-link>
            <button
                @click="downloadPDF()"
                class="btn btn-sm btn-primary"
                type="button"
            >
                <i class="fa fa-file-excel"></i>
                {{ 'Print' }}
            </button>
            <!-- <nuxt-link :to="`/validasi/edit/${item.id}`" class="btn btn-warning btn-sm" tag="a"><i class="fa fa-edit"></i></nuxt-link> -->
        </td>
    </tr>
</template>
<script>

import axios from "axios";

export default {
    props  : ['item', 'pagination', 'rowparams', 'index'],
    data() {
        return {
            loading: false
        }
    },
    methods: {
        async downloadPDF(){
            
            try {
                this.loading = true;

                axios({
                        url: process.env.apiUrl + "/v1/validasi/export-pdf/" + this.item.id,
                        // params: this.form,
                        method: 'GET',
                        responseType: 'blob',
                    }).then((response) => {
                        const blob = new Blob([response.data], {type: response.data.type});
                        const url = window.URL.createObjectURL(blob);
                        const link = document.createElement('a');
                        link.href = url;
                        const contentDisposition = response.headers['content-disposition'];

                        const fileNameHeader = "x-suggested-filename";
                        const suggestedFileName = response.headers[fileNameHeader];
                        const effectiveFileName = (suggestedFileName === undefined
                                    ? "surat-hasil-pemeriksaan-" + this.item.nomor_sampel
                                    : suggestedFileName);                            

                        let fileName = effectiveFileName + '.pdf';

                        if (contentDisposition) {
                            const fileNameMatch = contentDisposition.match(/filename=(.+)/);
                            if (fileNameMatch.length === 2)
                                fileName = fileNameMatch[1];
                        }
                        link.setAttribute('download', fileName);
                        document.body.appendChild(link);
                        link.click();
                        link.remove();
                        window.URL.revokeObjectURL(url);
                        this.isLoadingExp = false;
                    });

                // const response = await this.form.post("/v1/verifikasi/export-excel");

                // this.$toast.success(response.data.message, {
                //   icon: "check",
                //   iconPack: "fontawesome",
                //   duration: 5000
                // });

                // this.$router.back()

            } catch (err) {
                if (err.response && err.response.data.code == 422) {
                    this.$nextTick(() => {
                        this.form.errors.set(err.response.data.error);
                    });
                    this.$toast.error("Mohon cek kembali formulir Anda", {
                        icon: "times",
                        iconPack: "fontawesome",
                        duration: 5000
                    });
                } else {
                    console.log(err);
                    
                    this.$swal.fire(
                        "Terjadi kesalahan",
                        "Silakan hubungi Admin",
                        "error"
                    );
                }
            }
            this.loading = false;
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
