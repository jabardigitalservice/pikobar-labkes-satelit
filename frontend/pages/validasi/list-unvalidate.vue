<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Menunggu Validasi</portal>

    <div class="row">
      <div class="col-lg-12">

        <Ibox title="Sampel Menunggu Verifikasi">
          <p class="sub-header">
            Berikut ini adalah daftar sampel dari hasil verifikasi sampel</p>

          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Filter Kesimpulan Pemeriksaan</label>
                <select class="form-control" v-model="params1.kesimpulan_pemeriksaan">
                  <option value="">Semua Hasil</option>
                  <option value="positif">Positif</option>
                  <option value="negatif">Negatif</option>
                  <option value="inkonklusif">Inkonklusif</option>

                </select>
              </div>
            </div>
          </div>
          <ajax-table
            url="/v1/validasi/list"
            :oid="'validasi'"
            :params="params1"
            :config="{
                    autoload: true,
                    has_number: true,
                    has_entry_page: true,
                    has_pagination: true,
                    has_action: true,
                    has_search_input: true,
                    custom_header: '',
                    default_sort: '',
                    custom_empty_page: true,
                    class: {
                        table: [],
                        wrapper: ['table-responsive'],
                    }
                    }"
                :rowtemplate="'tr-validasi'"
                :columns="{
                      nomor_register: 'Nomor Register',
                      pasien_nama : 'Nama Pasien',
                      kota_nama : 'Kota Domisili',
                      nomor_sampel : 'Nomor Sampel',
                      parameter_lab: 'Parameter Lab',
                      kesimpulan_pemeriksaan: 'Kesimpulan Pemeriksaan',
                    }"
          ></ajax-table>
        </Ibox>
      </div>
    </div>

  </div>
</template>
 
<script>
export default {
  middleware: "auth",
  data() {
    return {
      params1: {
        kesimpulan_pemeriksaan: ""
      },
    };
  },
  head() {
    return { title: "Sampel Hasil Pemeriksaan" };
  },
  watch: {
    'params1.kesimpulan_pemeriksaan': function(newVal, oldVal) {
      this.$bus.$emit('refresh-ajaxtable', 'validasi')
    },
  },
};
</script>
