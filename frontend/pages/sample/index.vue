<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Penerimaan Sampel</portal>
    <portal to="title-action">
      <div class="title-action">
        <a href class="btn btn-primary">
          <i class="fa fa-plus"></i> Sampel Baru
        </a>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Penerimaan atau Pengambilan Sampel">
          <p
            class="sub-header"
          >Scan / masukan nomor barcode salah satu sampel untuk register pasien rujukan</p>
          <form id="scanbarcode row" action method="post">
            <center>
              <div class="form-group">
                <input
                  id="barcodesampel"
                  class="form-control col-md-3"
                  name
                  placeholder="Scan..."
                  type="text"
                  tabindex="1"
                  required
                  autofocus
                />
                <br />
                <button type="submit" class="mt-2 btn btn-md btn-primary">Tambahkan Informasi Sampel</button>
              </div>
            </center>
          </form>
        </Ibox>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Sampel dari Register">
          <p
            class="sub-header"
          >Berikut ini adalah daftar dari registrasi yang belum ada status penerimaan atau pengambilan sampel, Silahkan pilih dan lakukan Ambil atau Terima Sampel Pasien</p>
          <ajax-table
            url="/sample/get-data"
            :oid="'sample-register'"
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
            :rowtemplate="'tr-sample-register'"
            :columns="{
                      sam_barcodenomor_sampel: 'Sampel',
                    }"
          ></ajax-table>
        </Ibox>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Status yang telah dikirim">
          <p class="sub-header">Berikut adalah status yang telah dikirimkan ke Lab Ekstraksi</p>
          <ajax-table
            url="/sample/get-data"
            :oid="'sample-sent'"
            :params="params2"
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
            :rowtemplate="'tr-sample-register'"
            :columns="{
                      sam_barcodenomor_sampel: 'Sampel',
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
