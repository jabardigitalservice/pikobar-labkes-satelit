<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Lihat Pengguna</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link :to="`/perujuk/${id}/edit`" class="btn btn-warning">
          <em class="fa fa-edit" /> Ubah
        </router-link>
        <a href="#" @click.prevent="$router.back()" class="btn btn-black">
          <em class="uil-arrow-left" />
          Kembali
        </a>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-10">
        <Ibox title="Informasi Pengguna">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Username
            </div>
            <div class="col-md-7 flex-text-center">
              {{username || ''}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Email
            </div>
            <div class="col-md-7 flex-text-center">
              {{email || ''}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Fasyankes Perujuk
            </div>
            <div class="col-md-7 flex-text-center">
              {{perujuk && perujuk.nama ? perujuk.nama : ''}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Domisili
            </div>
            <div class="col-md-7 flex-text-center">
              {{ perujuk && perujuk.kota && perujuk.kota.nama ? perujuk.kota.nama : ''  }}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Role
            </div>
            <div class="col-md-7 flex-text-center">
              {{ getRole('Admin Perujuk', 'value') }}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Status
            </div>
            <div class="col-md-7 flex-text-center">
              {{last_login_at ? last_login_at : 'inactive'}}
            </div>
          </div>
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
    head() {
      return {
        title: "Lihat Pengguna",
      }
    },
    async asyncData({ route }) {
      const resp = await axios.get(`/v1/user/${route.params.id}`)
      const {
        id,
        name,
        email,
        username,
        perujuk,
        role_id,
        last_login_at
      } = resp.data.data || {}
  
      return {
        id,
        name,
        email,
        username,
        perujuk,
        role_id,
        last_login_at,
        getRole
      }
    }
  }
</script>
