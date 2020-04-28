<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Verifikasi</portal>
    <!-- <portal to="title-action">
      <div class="title-action">
        <router-link to="/ekstraksi/kirim" class="btn btn-primary">
          <i class="fa fa-paper-plane"></i> Kirim Sampel
        </router-link>
      </div>
    </portal> -->

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Sampel Menunggu Verifikasi">
          <p class="sub-header">
            Berikut ini adalah daftar sampel dari hasil pemeriksaan</p>
          <ajax-table
            url="/v1/verifikasi/list"
            :oid="'ekstraksi'"
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
                :rowtemplate="'tr-verifikasi'"
                :columns="{
                      nomor_register: 'Nomor Register',
                      pasien_nama : 'Nama Pasien',
                      nomor_sampel : 'Nomor Sampel',
                      parameter_lab: 'Parameter Lab',
                      kesimpulan_pemeriksaan: 'Kesimpulan Pemeriksaan',
                    }"
          ></ajax-table>
        </Ibox>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Sampel Terverifikasi">
          <p class="sub-header">
            Berikut ini adalah daftar sampel dari hasil pemeriksaan</p>
          <ajax-table
            url="/v1/verifikasi/list-verified"
            :oid="'ekstraksi'"
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
                :rowtemplate="'tr-verifikasi'"
                :columns="{
                      nomor_register: 'Nomor Register',
                      pasien_nama : 'Nama Pasien',
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
      params1: null,
    };
  },
  head() {
    return { title: "Sampel Hasil Pemeriksaan" };
  }
};
</script>
