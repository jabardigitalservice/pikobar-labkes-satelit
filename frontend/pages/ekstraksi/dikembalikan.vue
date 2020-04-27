<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Pemeriksaan Sampel</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link to="/ekstraksi/kirim-ulang" class="btn btn-primary">
          <i class="fa fa-paper-plane"></i> Kirim Ulang Sampel
        </router-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Data sampel yang telah diekstraksi namun dikembalikan Lab Pemeriksaan">
          <p class="sub-header">
            Berikut ini adalah daftar dari status sampel yang dikembalikan dari Lab Pemeriksaan</p>
           
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
                    default_sort: 'waktu_extraction_sample_reextract',
                    custom_empty_page: true,
                    class: {
                        table: [],
                        wrapper: ['table-responsive'],
                    }
                    }"
                :rowtemplate="'tr-ekstraksi-dikembalikan'"
                :columns="{
                      nomor_register: 'Nomor Register',
                      nomor_sampel : 'Nomor Sampel',
                      jenis_sampel : 'Jenis Sampel',
                      lab_pcr_nama : 'Lab PCR',
                      waktu_extraction_sample_reextract: 'Permintaan re-ekstraksi pada',
                      catatan_pemeriksaan : 'Keterangan Pemeriksaan',
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
        sampel_status: 'extraction_sample_reextract'
      },
    };
  },
  head() {
    return { title: "Penerimaan Sampel" };
  }
};
</script>
