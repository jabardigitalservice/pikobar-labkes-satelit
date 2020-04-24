<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Penerimaan Sampel</portal>
    <portal to="title-action">
      <div class="title-action">
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Pemeriksaan Sampel">
          <p
            class="sub-header"
          >Berikut ini adalah daftar dari status penerimaan sampel yang dikirim, silahkan terima sampel dan lakukan ekstraksi RNA untuk dikirim ke laboratorium berikutnya</p>
        
        <ajax-table
            url="/pemeriksaansampel/get-data"
            :oid="'pemeriksaan-sample'"
            :params="params1"
            :config="{
                    autoload: true,
                    has_number: true,
                    has_entry_page: true,
                    has_pagination: true,
                    has_action: true,
                    has_search_input: true,
                    custom_header: '',
                    default_sort: 'sam_barcodenomor_sampel',
                    custom_empty_page: true,
                    class: {
                        table: [],
                        wrapper: ['table-responsive'],
                    }
                    }"
                :rowtemplate="'tr-pemeriksaan-sampel'"
                :columns="{
                      sam_barcodenomor_sampel: 'Nomor Sampel',
                      sam_jenis_sampel: 'Informasi Sampel',
                      eks_tanggal_pengiriman_rna:'Pengiriman'
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
          >Berikut ini adalah daftar dari status registrasi yang sampelnya telah dipilih dan dikirim ke verifikator</p>
          <ajax-table
            url="/pemeriksaansampel/get-dikirim"
            :oid="'pemeriksaan-sample-dikirim'"
            :params="params1"
            :config="{
                    autoload: true,
                    has_number: true,
                    has_entry_page: true,
                    has_pagination: true,
                    has_action: true,
                    has_search_input: true,
                    custom_header: '',
                    default_sort: 'sam_barcodenomor_sampel',
                    custom_empty_page: true,
                    class: {
                        table: [],
                        wrapper: ['table-responsive'],
                    }
                    }"
                :rowtemplate="'tr-pemeriksaan-sampel-dikirim'"
                :columns="{
                      sam_barcodenomor_sampel: 'Keterangan Status',
                      sam_jenis_sampel: 'Status Sampel',
                      eks_tanggal_pengiriman_rna:'Pengiriman'
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
