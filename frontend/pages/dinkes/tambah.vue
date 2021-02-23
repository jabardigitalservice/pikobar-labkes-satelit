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
              Nama Admin<span style="color: red">*</span>
            </label>
            <div class="col-md-8">
              <input v-model="form.name" type="text" name="name" class="form-control" required
                :class="{ 'is-invalid': form.errors.has(`name`) }">
              <has-error :form="form" field="name" />
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-4 col-form-label">
              Username<span style="color: red">*</span>
            </label>
            <div class="col-md-8">
              <input v-model="form.username" type="text" name="username" class="form-control" required
                :class="{ 'is-invalid': form.errors.has(`username`) }">
              <has-error :form="form" field="username" />
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-4 col-form-label">
              Dinkes<span style="color: red">*</span>
            </label>
            <div class="col-md-8">
              <select v-model="form.kota_id " class="form-control" name="kota_id " required
                :class="{ 'is-invalid': form.errors.has(`kota_id `) }">
                <option :value="item.id" :key="idx" v-for="(item, idx) in optionKota">
                  {{ item.nama }}
                </option>
              </select>
              <has-error :form="form" field="kota_id " />
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
          <div class="form-group row">
            <label class="col-md-4 col-form-label">
              Email<span style="color: red">*</span>
            </label>
            <div class="col-md-8">
              <input class="form-control" name="email" placeholder="Email" type="text" tabindex="1" required autofocus
                v-model="form.email" :class="{ 'is-invalid': form.errors.has(`email`) }" />
              <has-error :form="form" field="email" />
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
      </div>
    </Ibox>
  </div>
</template>

<script>
  import Form from "vform"
  import {
    getRole
  } from "~/utils"

  export default {
    middleware: "auth",
    data() {
      return {
        loading: false,
        isLoading: false,
        optionKota: [],
        roleAdmin: getRole('Admin Dinkes'),
        form: new Form({
          name: null,
          username: null,
          email: null,
          kota_id: null,
          role_id: getRole('Admin Dinkes', 'id'),
        }),
      }
    },
    methods: {
      initForm() {
        this.isLoading = false
        this.form.name = null
        this.form.username = null
        this.form.email = null
        this.form.kota_id = null
        this.form.role_id = 8
      },
      async getListDinkes() {
        const resp = await this.$axios.get("/v1/list-kota-jabar")
        const {
          data
        } = resp || []
        this.optionKota = data
      },
      async submit() {
        this.isLoading = true;
        try {
          const response = await this.form.post("/v1/user/dinkes/invite");
          this.$toast.success(response.data.message, {
            icon: "paper-plane",
            iconPack: "fontawesome",
            text: "Undangan terkirim",
            duration: 5000,
          });
          this.initForm()
          this.$router.push("/dinkes")
        } catch (err) {
          this.isLoading = false;
          if (err.response && err.response.data.code == 422) {
            this.$nextTick(() => {
              this.form.errors.set(err.response.data.error);
            });
            this.$toast.error("Mohon cek kembali formulir Anda", {
              icon: "times",
              iconPack: "fontawesome",
              duration: 5000,
            });
          } else {
            this.$swal.fire(
              "Terjadi kesalahan",
              "Silakan hubungi Admin",
              "error"
            );
          }
        }
      },
    },
    created() {
      this.getListDinkes()
    }
  }
</script>