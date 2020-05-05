<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Penerimaan Sampel untuk Ekstraksi</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link to="/ekstraksi/terima" class="btn btn-primary">
          <i class="uil-flask"></i> Lakukan Ekstraksi
        </router-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Data Sampel yang telah dikirim Admin Sampel">
          <p
            class="sub-header"
          >Berikut ini adalah daftar sampel yang telah dikirim Admin Sampel untuk diterima di Lab Ekstraksi dan dilakukan ekstraksi</p>
          <ajax-table
            url="/v1/ekstraksi/get-data"
            :oid="'ekstraksi-penerimaan'"
            :params="params1"
            :config="{
                    autoload: true,
                    has_number: true,
                    has_entry_page: true,
                    has_pagination: true,
                    has_action: true,
                    has_search_input: true,
                    custom_header: '',
                    default_sort: 'waktu_sample_taken',
                    custom_empty_page: true,
                    class: {
                        table: [],
                        wrapper: ['table-responsive'],
                    }
                    }"
                :rowtemplate="'tr-ekstraksi-penerimaan'"
                :columns="{
                      nomor_register: 'Nomor Register',
                      nomor_sampel : 'Nomor Sampel',
                      jenis_sampel_nama : 'Jenis Sampel',
                      waktu_sample_taken: 'Sampel diambil pada',
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
        sampel_status: 'sample_taken'
      },
    };
  },
  head() {
    return { title: "Penerimaan Sampel untuk Ekstraksi" };
  }
};
</script>
