<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Perbarui Data Perujuk</portal>
    <portal to="title-action">
      <div class="title-action">
        <a href="#" @click.prevent="$router.back()" class="btn btn-black">
          <em class="uil-arrow-left" />
          Kembali
        </a>
      </div>
    </portal>

    <Ibox title="Edit Akun Perujuk">
      <div class="row">
        <div class="col-lg-12">
          <div class="form-group row">
            <label class="col-md-4 col-form-label">
              Instansi Pengirim<span class="text-danger">*</span>
            </label>
            <div class="col-md-8">
              <input-option-instansi-pengirim :form="params" field="fasyankes_pengirim" />
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-4 col-form-label">
              Fasyankes Perujuk<span class="text-danger">*</span>
            </label>
            <div class="col-md-8">
              <multiselect v-model="fasyankes" :options="optFasyankes" track-by="nama" label="nama"
                :class="{ 'is-invalid': form.errors.has(`perujuk_id`) }" placeholder="Nama Dinkes/Puskesmas/Rumah Sakit"
                :disabled="!params.fasyankes_pengirim">
              </multiselect>
              <has-error :form="form" field="perujuk_id" />
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-4 col-form-label">
              Domisili
            </label>
            <div class="col-md-8">
              <input v-model="domisili" type="text" name="domisili" class="form-control" readonly />
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-4 col-form-label">
              Email<span class="text-danger">*</span>
            </label>
            <div class="col-md-8">
              <input class="form-control" name="email" type="email" v-model="email" readonly />
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-4 col-form-label">
              Username<span class="text-danger">*</span>
            </label>
            <div class="col-md-8">
              <input v-model="username" type="text" name="username" class="form-control" readonly>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-4 col-form-label">
              Role<span class="text-danger">*</span>
            </label>
            <div class="col-md-8">
              <select v-model="role_id" class="form-control" name="role_id" readonly>
                <option :value="roleAdmin.id">
                  {{ roleAdmin.value }}
                </option>
              </select>
            </div>
          </div>
        </div>

        <div class="dimmed" v-if="isLoading">
          <div class="loading-indicator">
            <img alt="loading" src="~/assets/img/loading.gif" width="48" height="48">
            Loading ...
          </div>
        </div>

        <div class="col-md-12 mt-2 flex-right">
          <button @click="submit()" :disabled="isDisabledSubmit" :class="{'btn-loading': loading}"
            class="btn btn-md btn-primary block" type="button">
            Submit
          </button>
        </div>
      </div>
    </Ibox>
  </div>
</template>

<script>
  import Form from "vform"
  import axios from "axios"
  import {
    getRole,
    humanize
  } from "~/utils"

  export default {
    middleware: "auth",
    head() {
      return {
        title: "Perbarui Pengguna",
      }
    },
    async asyncData({ route }) {
      const resp = await axios.get(`/v1/user/${route.params.id}`)
      const {
        id,
        email,
        username,
        perujuk,
        role_id
      } = resp.data.data || {}
  
      return {
        id,
        email,
        username,
        perujuk,
        role_id,
        form: new Form({
          perujuk_id: id || null,
        }),
        optFasyankes: [],
        fasyankes: perujuk || null,
        params: {
          fasyankes_pengirim: perujuk?.tipe ? perujuk.tipe : null
        },
        roleAdmin: getRole('Admin Perujuk'),
        domisili: perujuk?.kota?.nama ? perujuk.kota.nama : '',
        humanize,
        loading: false,
        isLoading: false,
        isDisabledSubmit: true,
        userid: route.params.id
      }
    },
    methods: {
      async changeFasyankes(tipe) {
        const resp = await this.$axios.get(`/v1/list-fasyankes-jabar?tipe=${tipe}`)
        const {
          data
        } = resp || []
        this.optFasyankes = data
      },
      async submit() {
        try {
          await this.form.put(`/v1/user/perujuk/${this.userid}`)
          this.$toast.success('Berhasil', {
            icon: "check",
            iconPack: "fontawesome",
            duration: 5000,
          })
          this.$router.push("/perujuk")
        } catch (err) {
          if (err.response && err.response.status === 422) {
            this.$nextTick(() => {
              this.form.errors.set(err.response.data.error)
            })
            this.$swal.fire(
              "Terjadi kesalahan",
              "Mohon cek kembali formulir Anda",
              "error"
            )
          } else {
            this.$swal.fire(
              "Terjadi kesalahan",
              "Silakan hubungi Admin",
              "error"
            )
          }
          return
        }
      },
    },
    created() {
      this.changeFasyankes(this.params.fasyankes_pengirim)
    },
    watch: {
      "params.fasyankes_pengirim"() {
        this.fasyankes = null
        this.domisili = null
        this.changeFasyankes(this.params.fasyankes_pengirim)
      },
      "fasyankes"() {
        this.form.perujuk_id = null
        if (this.fasyankes) {
          const {
            id,
            kota
          } = this.fasyankes
          this.form.perujuk_id = id || null
          this.domisili = kota?.nama ? kota.nama : null
        }
      },
      "form.perujuk_id"(newVal) {
        if (newVal !== this.id) {
          this.isDisabledSubmit = false
        } else {
          this.isDisabledSubmit = true
        }
      }
    }
  }
</script>