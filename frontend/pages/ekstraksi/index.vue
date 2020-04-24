<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Penerimaan dan Ekstraksi Sampel</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link to="/sample/add" class="btn btn-primary">
          <i class="fa fa-plus"></i> Sampel Baru
        </router-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Penerimaan dan Ekstraksi RNA Sampel">
          <p class="sub-header">
            Berikut ini adalah daftar dari status penerimaan sampel yang dikirim, silahkan terima sampel dan lakukan ekstraksi RNA untuk dikirim ke laboratorium berikutnya</p>
           
           <ajax-table
            url="/ekstraksi/get-data"
            :oid="'ekstraksi-data'"
            :params="params1"
            :config="{
                    autoload: true,
                    has_number: true,
                    has_entry_page: true,
                    has_pagination: true,
                    has_action: true,
                    has_search_input: true,
                    custom_header: '',
                    default_sort: 'pen_nomor_ekstraksi',
                    custom_empty_page: true,
                    class: {
                        table: [],
                        wrapper: ['table-responsive'],
                    }
                    }"
                    :rowtemplate="'tr-data-ekstraksi'"
                    :columns="{
                      pen_nomor_ekstraksi: 'Urutan Ekstraksi',
                      pen_noreg: 'Identitas Pasien',
                      pen_id_sampel: 'Status Sampel'  
                    }"
          ></ajax-table>
        </Ibox>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Data Sampel yang telah dikirim">
          <p
            class="sub-header"
          >Berikut ini adalah daftar dari status registrasi yang sampelnya telah dipilih dan dikirim ke laboratorium tingkat 3</p>
          <ajax-table
            url="/ekstraksi/get-kirim"
            :oid="'ekstraksi-kirim'"
            :params="params1"
            :config="{
                    autoload: true,
                    has_number: true,
                    has_entry_page: true,
                    has_pagination: true,
                    has_action: true,
                    has_search_input: true,
                    custom_header: '',
                    default_sort: 'pen_nomor_ekstraksi',
                    custom_empty_page: true,
                    class: {
                        table: [],
                        wrapper: ['table-responsive'],
                    }
                    }"
                :rowtemplate="'tr-ekstraksi-kirim'"
                :columns="{
                      pen_nomor_ekstraksi: 'Keterangan Sampel',
                      pen_noreg : 'Identitas Pasien'
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
        sam_barcodenomor_sampel: null
      },
      params2: {
        sam_barcodenomor_sampel: null
      }
    };
  },
  head() {
    return { title: "Penerimaan Sampel" };
  }
};
</script>
