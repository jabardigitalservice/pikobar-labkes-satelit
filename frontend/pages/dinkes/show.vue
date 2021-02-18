<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Lihat Pengguna</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link :to="`/pengguna/${id}/edit`" class="btn btn-warning">
          <i class="fa fa-edit"></i> Ubah
        </router-link>
        <nuxt-link to="/dinkes" class="btn btn-black">
          <i class="uil-arrow-left" /> Kembali
        </nuxt-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-10">
        <Ibox title="Informasi Pengguna">
          <table class="table table-hover">
            <tbody>
              <tr>
                <th width="30%">Nama Akun Dinkes</th>
                <td>{{ name }}</td>
              </tr>
              <tr>
                <th>Email</th>
                <td>{{ email }}</td>
              </tr>
              <tr>
                <th>Username</th>
                <td>{{ username }}</td>
              </tr>
              <tr>
                <th>Dinkes</th>
                <td>{{ kota && kota.nama ? kota.nama : ''  }}</td>
              </tr>
              <tr>
                <th>Role</th>
                <td>{{ getRole('Admin Dinkes', 'value') }}</td>
              </tr>
            </tbody>
          </table>
        </Ibox>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  import {
    getRole
  } from "~/utils"

  export default {
    middleware: "auth",
    async asyncData({ route }) {
      const resp = await axios.get(`/v1/user/${route.params.id}`)
      const {
        id,
        name,
        email,
        username,
        kota,
        role_id
      } = resp.data.data || {}
  
      return {
        id,
        name,
        email,
        username,
        kota,
        role_id,
        getRole
      }
    },
    head() {
      return {
        title: "Lihat Pengguna",
      };
    }
  }
</script>

<style scoped>
  .table-sub-head {
    padding-top: 25px;
  }
</style>