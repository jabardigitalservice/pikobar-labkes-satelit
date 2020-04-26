<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Penerimaan Sampel RNA</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link to="/pcr/terima" class="btn btn-primary">
          <i class="uil-flask"></i> Terima Sampel RNA
        </router-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Penerimaan Sampel RNA">
          <p
            class="sub-header"
          >Berikut ini adalah daftar dari status penerimaan sampel yang dikirim, silahkan terima sampel dan lakukan analisa RT-PCR</p>
          <ajax-table
            url="/v1/pcr/get-data"
            :oid="'pcr-penerimaan'"
            :params="params1"
            :config="{
                    autoload: true,
                    has_number: true,
                    has_entry_page: true,
                    has_pagination: true,
                    has_action: true,
                    has_search_input: true,
                    custom_header: '',
                    default_sort: 'waktu_extraction_sample_sent',
                    custom_empty_page: true,
                    class: {
                        table: [],
                        wrapper: ['table-responsive'],
                    }
                    }"
            :rowtemplate="'tr-pcr-penerimaan'"
            :columns="{
                      nomor_register: 'Nomor Register',
                      nomor_sampel : 'Nomor Sampel',
                      jenis_sampel : 'Jenis Sampel',
                      waktu_extraction_sample_sent:'Waktu Pengiriman',
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
        register_status: "extraction_sample_sent"
      }
    };
  },
  head() {
    return { title: "Penerimaan Sampel RNA" };
  }
};
</script>
