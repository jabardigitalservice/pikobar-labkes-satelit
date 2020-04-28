<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Sampel Invalid</portal>
    <portal to="title-action">
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Data sampel yang telah dikembalikan Lab Pemeriksaan Namun Invalid">
          <p class="sub-header">
            Berikut ini adalah daftar dari status sampel yang telah dikembalikan dari Lab Pemeriksaan namun invalid</p>
           
          <ajax-table
            url="/v1/ekstraksi/get-data"
            :oid="'ekstraksi-invalid'"
            :params="params1"
            :config="{
                    autoload: true,
                    has_number: true,
                    has_entry_page: true,
                    has_pagination: true,
                    has_action: true,
                    has_search_input: true,
                    custom_header: '',
                    default_sort: 'waktu_sample_invalid',
                    custom_empty_page: true,
                    class: {
                        table: [],
                        wrapper: ['table-responsive'],
                    }
                    }"
                :rowtemplate="'tr-ekstraksi-invalid'"
                :columns="{
                      nomor_register: 'Nomor Register',
                      nomor_sampel : 'Nomor Sampel',
                      jenis_sampel : 'Jenis Sampel',
                      waktu_sample_invalid: 'Ditandai invalid pada',
                      catatan_pemeriksaan : 'Keterangan Pemeriksaan',
                      catatan_pengiriman : 'Keterangan Invalid',
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
        sampel_status: 'sample_invalid'
      },
    };
  },
  head() {
    return { title: "Sampel Invalid" };
  }
};
</script>
