<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Pelacakan Sampel</portal>
    <portal to="title-action">
      <div class="title-action"></div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Sampel">
          <p
            class="sub-header"
          >Scan / masukan nomor barcode salah satu sampel dilacak</p>
          <form
            id="scanbarcode row"
            action
            method="post"
            @submit.prevent="submit"
            @keydown="form.onKeydown($event)"
          >
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
                  v-model="form.nomor_sampel"
                />
                <br />
                <v-button :loading="form.busy" class="mt-2 btn btn-md btn-primary">
                  <i class="fa fa-plus"></i> Tambahkan Informasi Register
                </v-button>
              </div>
            </center>
          </form>
        </Ibox>
      </div>
    </div>
  </div>
</template>

<script>
import Form from "vform";


export default {
    middleware: "auth",
    data() {

        return {
            form: new Form({
                nomor_sampel:null
            })
        }
    }
}
</script>