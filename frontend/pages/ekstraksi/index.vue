<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Sampel yang Telah Di-Ekstraksi</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link to="/ekstraksi/kirim" class="btn btn-primary">
          <i class="fa fa-paper-plane"></i> Kirim Sampel
        </router-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Sampel yang Telah Di-Ekstraksi">
          <p class="sub-header">
            Berikut ini adalah daftar dari sudah di-Ekstraksi</p>
          <ajax-table
            url="/v1/ekstraksi/get-data"
            :oid="'ekstraksi-dilakukan'"
            :params="params1"
            :config="{
                    autoload: true,
                    has_number: true,
                    has_entry_page: true,
                    has_pagination: true,
                    has_action: true,
                    has_search_input: true,
                    custom_header: '',
                    default_sort: 'waktu_extraction_sample_extracted',
                    custom_empty_page: true,
                    class: {
                        table: [],
                        wrapper: ['table-responsive'],
                    }
                    }"
                :rowtemplate="'tr-ekstraksi-dilakukan'"
                :columns="{
                      nomor_register: 'Nomor Register',
                      nomor_sampel : 'Nomor Sampel',
                      jenis_sampel : 'Jenis Sampel',
                      sampel_status: 'Status',
                      operator: 'Operator',
                      waktu_extraction_sample_extracted: 'Ekstraksi dilakukan pada',
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
        sampel_status: 'extraction_sample_extracted'
      },
    };
  },
  head() {
    return { title: "Sampel yang Telah Di-Ekstraksi" };
  }
};
</script>
