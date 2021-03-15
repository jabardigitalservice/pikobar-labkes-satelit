<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Registrasi Admin Perujuk
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <a href="#" @click.prevent="$router.back()" class="btn btn-black">
          <em class="uil-arrow-left" />
          Kembali
        </a>
      </div>
    </portal>

    <Ibox title="Registrasi Akun Perujuk">
      <div class="row">
        <div class="col-lg-12">
          <div class="form-group row">
            <label class="col-md-4 col-form-label">
              Instansi Pengirim<span style="color: red">*</span>
            </label>
            <div class="col-md-8">
              <input-option-instansi-pengirim :form="params" field="fasyankes_pengirim" />
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-4 col-form-label">
              Fasyankes Perujuk<span style="color: red">*</span>
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
              <input v-model="domisili" type="text" name="domisili" class="form-control" disabled />
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-4 col-form-label">
              Email<span style="color: red">*</span>
            </label>
            <div class="col-md-8">
              <input class="form-control" name="email" type="email" tabindex="1" required autofocus v-model="form.email"
                :class="{ 'is-invalid': form.errors.has(`email`) }" />
              <has-error :form="form" field="email" />
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-4 col-form-label">
              Username<span style="color: red">*</span>
            </label>
            <div class="col-md-8">
              <input v-model="form.username" type="text" name="username" class="form-control" required
                :class="{ 'is-invalid': form.errors.has(`username`) }" autocomplete="off">
              <has-error :form="form" field="username" />
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-4 col-form-label">
              Password<span style="color: red">*</span>
            </label>
            <div class="col-md-8">
              <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" autocomplete="off"
                :type="showPassword ? 'password' : 'text'" name="password" class="form-control" required />
              <span class="prefix-icon-password" :class="showPassword ? 'fa fa-eye' : 'fa fa-eye-slash'"
                @click="showPassword = !showPassword" />
              <has-error :form="form" field="password" />
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-4 col-form-label">
              Konfirmasi Password<span style="color: red">*</span>
            </label>
            <div class="col-md-8">
              <input v-model="form.password_confirmation" :class="{
                    'is-invalid': form.errors.has('password_confirmation'),
                  }" :type="showRePassword ? 'password' : 'text'" name="password_confirmation" class="form-control"
                required />
              <span class="prefix-icon-password" :class="showRePassword ? 'fa fa-eye' : 'fa fa-eye-slash'"
                @click="showRePassword = !showRePassword" />
              <has-error :form="form" field="password_confirmation" />
              <span v-show="form.password" :class="{
                'text-success': form.password && form.password === form.password_confirmation,
                'text-red': form.password && form.password !== form.password_confirmation,
                }" class="font-medium text-sm ml-2 mt-2" v-text=" form.password === form.password_confirmation && form.password
                  ? 'Passwords match' : 'Passwords do not match'
                "></span>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-4 col-form-label">
              Role<span style="color: red">*</span>
            </label>
            <div class="col-md-8">
              <select v-model="form.role_id" class="form-control" name="role_id" required
                :class="{ 'is-invalid': form.errors.has(`role_id`) }">
                <option :value="roleAdmin.id">
                  {{ roleAdmin.value }}
                </option>
              </select>
              <has-error :form="form" field="role_id" />
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
          <button @click="submit()" :disabled="loading" :class="{'btn-loading': loading}"
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
  import {
    optionInstansiPengirim
  } from '~/assets/js/constant/enum'
  import {
    getRole,
    humanize
  } from "~/utils"

  export default {
    middleware: "auth",
    data() {
      return {
        loading: false,
        isLoading: false,
        form: new Form({
          username: null,
          email: null,
          role_id: getRole('Admin Perujuk', 'id'),
          perujuk_id: null,
          password: null,
          password_confirmation: null
        }),
        optionInstansiPengirim,
        optFasyankes: [],
        fasyankes: {},
        params: {
          fasyankes_pengirim: null
        },
        domisili: null,
        showPassword: true,
        showRePassword: true,
        humanize,
        roleAdmin: getRole('Admin Perujuk'),
        fieldTypes: {
          password: 'text',
        }
      }
    },
    methods: {
      initForm() {
        this.isLoading = false
        this.params.fasyankes_pengirim = null
        this.fasyankes = null
        this.domisili = null
        this.form.username = null
        this.form.email = null
        this.form.password = null
        this.form.password_confirmation = null
        this.form.role_id = 8
      },
      async changeFasyankes(tipe) {
        const resp = await this.$axios.get(`/v1/list-fasyankes-jabar?tipe=${tipe}`)
        const {
          data
        } = resp || []
        this.optFasyankes = data
      },
      async submit() {
        if (this.form.password !== this.form.password_confirmation) {
          this.$swal.fire(
            "Terjadi kesalahan",
            "Password tidak sesuai",
            "error"
          )
        }
        this.isLoading = true
        try {
          const response = await this.form.post("/v1/user/perujuk")
          this.$toast.success(response.data.message, {
            icon: "paper-plane",
            iconPack: "fontawesome",
            text: "Undangan terkirim",
            duration: 5000,
          })
          this.initForm()
          this.$bus.$emit("refresh-ajaxtable", "master-perujuk")
        } catch (err) {
          this.isLoading = false
          if (err.response && err.response.data.code == 422) {
            this.$nextTick(() => {
              this.form.errors.set(err.response.data.error)
            })
            this.$toast.error("Mohon cek kembali formulir Anda", {
              icon: "times",
              iconPack: "fontawesome",
              duration: 5000,
            })
          } else {
            this.$swal.fire(
              "Terjadi kesalahan",
              "Silakan hubungi Admin",
              "error"
            )
          }
        }
      },
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
          this.domisili = kota && kota.nama ? kota.nama : null
        }
      }
    }
  }
</script>