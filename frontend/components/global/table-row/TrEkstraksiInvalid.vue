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
            {{item.jenis_sampel_nama}}
        </td>
        <td>
            {{ item.waktu_sample_invalid | formatDateTime }}
        </td>
        <td>
            {{ item.pcr ? item.pcr.catatan_pemeriksaan : '-' }}
        </td>
        <td>
            {{ item.ekstraksi ? item.ekstraksi.catatan_pengiriman : '-' }}
        </td>
        <td width="20%">
            <nuxt-link tag="a" class="mb-1 text-nowrap btn btn-success btn-sm" :to="`/ekstraksi/detail/${item.id}`" title="Klik untuk melihat detail"><i class="uil-info-circle"></i></nuxt-link>
            <button
                type="button"
                class="mb-1 text-nowrap btn btn-warning btn-sm"
                title="Klik untuk menandai sampel sebagai sampel yang kurang"
                @click="promptKurang()"
                :disabled="loading"
                :class="{'btn-loading': loading}"
            >
                <i class="uil-flask-potion"></i> Sampel Kurang
            </button>
            <button
                type="button"
                class="mb-1 text-nowrap btn btn-info btn-sm"
                title="Klik untuk menandai sampel sebagai sampel yang perlu swab ulang"
                @click="promptSwabUlang()"
                :disabled="loading"
                :class="{'btn-loading': loading}"
                style="margin-top: 4px"
            >
                <i class="uil-raindrops-alt"></i> Swab Ulang
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
        promptKurang() {
            const swalWithBootstrapButtons = this.$swal.mixin({
                customClass: {
                confirmButton: 'mb-1 text-nowrap btn btn-success',
                cancelButton: 'mb-1 text-nowrap btn btn-danger'
                },
                buttonsStyling: false
            })
            swalWithBootstrapButtons.fire({
                title: 'Apakah Anda Yakin Mendandai Sampel sebagai sampel kurang?',
                text: "Sampel yang kurang akan diberitahukan kepada verifikator untuk melakukan pengambilan sampel ulang",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tandai Sampel Kurang',
                cancelButtonText: 'Batalkan',
                reverseButtons: true
            }).then(async (result) => {
                console.log(result)
                if (result.value) {
                    try {
                        this.loading = true
                        let resp = await axios.post("/v1/ekstraksi/set-kurang/" + this.item.id);
                        swalWithBootstrapButtons.fire(
                            'Selesai!',
                            'Sampel berhasil ditandai sebagai sampel kurang',
                            'success'
                        )
                        this.$bus.$emit('refresh-ajaxtable', 'ekstraksi-invalid')
                    } catch (err) {
                        console.log(err)
                        if (err.response && err.response.data.code == 422) {
                            swalWithBootstrapButtons.fire(
                                'Gagal',
                                err.response.data.message,
                                'error'
                            )
                        } else {
                            swalWithBootstrapButtons.fire(
                                'Gagal',
                                'Gagal menandai sampel kurang',
                                'error'
                            )
                        }
                    }
                    this.loading = false
                } else if (
                /* Read more about handling dismissals below */
                result.dismiss === this.$swal.DismissReason.cancel
                ) {
                }
            })
        },
        promptSwabUlang() {
            const swalWithBootstrapButtons = this.$swal.mixin({
                customClass: {
                confirmButton: 'mb-1 text-nowrap btn btn-success',
                cancelButton: 'mb-1 text-nowrap btn btn-danger'
                },
                buttonsStyling: false
            })
            swalWithBootstrapButtons.fire({
                title: 'Apakah Anda Yakin Mendandai Sampel perlu swab ulang?',
                text: "Sampel yang kurang akan diberitahukan kepada verifikator untuk melakukan swab ulang",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tandai Swab Ulang',
                cancelButtonText: 'Batalkan',
                reverseButtons: true
            }).then(async (result) => {
                console.log(result)
                if (result.value) {
                    try {
                        this.loading = true
                        let resp = await axios.post("/v1/ekstraksi/set-swab-ulang/" + this.item.id);
                        swalWithBootstrapButtons.fire(
                            'Selesai!',
                            'Sampel berhasil ditandai sebagai sampel yang perlu swab ulang',
                            'success'
                        )
                        this.$bus.$emit('refresh-ajaxtable', 'ekstraksi-invalid')
                    } catch (err) {
                        console.log(err)
                        if (err.response && err.response.data.code == 422) {
                            swalWithBootstrapButtons.fire(
                                'Gagal',
                                err.response.data.message,
                                'error'
                            )
                        } else {
                            swalWithBootstrapButtons.fire(
                                'Gagal',
                                'Gagal menandai sampel kurang',
                                'error'
                            )
                        }
                    }
                    this.loading = false
                } else if (
                /* Read more about handling dismissals below */
                result.dismiss === this.$swal.DismissReason.cancel
                ) {
                }
            })
        },
    }
}
</script>
