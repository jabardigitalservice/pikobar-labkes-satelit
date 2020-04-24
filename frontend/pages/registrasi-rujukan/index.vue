<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Register dari Rujukan</portal>
    <portal to="title-action">
      <div class="title-action">
       
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Register Pasien Rujukan">
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
                <button type="submit" class="mt-2 btn btn-md btn-primary">Tambahkan Informasi Register</button>
              </div>
            </center>
          </form>
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
