<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Lihat Pengguna</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link
          :to="`/dinkes/${this.data.id}/edit`"
          class="btn btn-warning"
        >
          <i class="fa fa-edit"></i> Ubah
        </router-link>
        <nuxt-link to="/dinkes" class="btn btn-black">
          <i class="uil-arrow-left" /> Kembali
        </nuxt-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-6">
        <Ibox title="Informasi Pengguna">
          <table class="table table-hover">
            <tbody>
              <tr>
                <th width="30%">Nama</th>
                <td>{{ data.name }}</td>
              </tr>
              <tr>
                <th>Email</th>
                <td>{{ data.email }}</td>
              </tr>
              <tr>
                <th>Username</th>
                <td>{{ data.username }}</td>
              </tr>
              <tr>
                <th>Koordinator</th>
                <td>{{ data.koordinator }}</td>
              </tr>
            </tbody>
          </table>
        </Ibox>
      </div>
      <div class="col-lg-6">
        <Ibox title="Informasi Lab Satelit">
          <table class="table table-hover">
            <tbody>
              <tr>
                <th width="30%">Nama</th>
                <td>{{ data.lab_satelit ? data.lab_satelit.nama : "-" }}</td>
              </tr>
              <tr>
                <th>Alamat</th>
                <td>{{ data.lab_satelit ? data.lab_satelit.alamat : "-" }}</td>
              </tr>
              <tr>
                <th>Kode</th>
                <td>
                  {{ data.lab_satelit ? data.lab_satelit.kode_lab : "-" }}
                </td>
              </tr>
            </tbody>
          </table>
        </Ibox>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  middleware: "auth",
  async asyncData({ route }) {
    let resp = await axios.get("/v1/users/dinkes" + route.params.id);
    let data = resp.data.data;

    return { data };
  },
  computed: {},
  head() {
    return {
      title: "Lihat Pengguna",
    };
  },
};
</script>

<style scoped>
.table-sub-head {
  padding-top: 25px;
}
</style>