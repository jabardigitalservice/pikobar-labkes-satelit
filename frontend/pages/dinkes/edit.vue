<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Perbarui Data Dinkes</portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link to="/dinkes" class="btn btn-black">
          <i class="uil-arrow-left" /> Kembali
        </nuxt-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Informasi Akun Dinkes">
          <form @submit.prevent="submit" @keydown="form.onKeydown($event)">
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">
                Akun Dinkes
              </label>
              <div class="col-md-7">
                <label class="form-control" readonly>
                  {{ this.form.name }}
                </label>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">{{
                $t("email")
              }}</label>
              <div class="col-md-7">
                <label class="form-control" readonly>
                  {{ this.form.email }}
                </label>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">
                Username
              </label>
              <div class="col-md-7">
                <label class="form-control" readonly>
                  {{ this.form.username }}
                </label>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">
                Dinkes
              </label>
              <div class="col-md-7">
                <select v-model="form.kota_id" class="form-control" name="kota_id">
                  <option :value="item.id" :key="idx" v-for="(item, idx) in optionKota">
                    {{ item.nama }}
                  </option>
                </select>
                <has-error :form="form" field="password" />
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">
                Role
              </label>
              <div class="col-md-7">
                <label class="form-control" readonly>
                  {{ this.roleAdmin.value }}
                </label>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-7 offset-md-3 d-flex">
                <button @click="submit()" :loading="form.busy" class="btn btn-md btn-primary" type="button">
                  Submit
                </button>
              </div>
            </div>
          </form>
        </Ibox>
      </div>
    </div>
  </div>
</template>

<script>
  import Form from "vform";
  import axios from "axios";
  import {
    getRole
  } from "~/utils"

  export default {
    middleware: "auth",
    async asyncData({
      route
    }) {
      let resp = await axios.get("/v1/user/" + route.params.id);
      let data = resp.data.data;

      return {
        form: new Form({
          username: data.username,
          name: data.name,
          email: data.email,
          kota_id: data.kota_id,
          id: data.id,
          role_id: getRole('Admin Dinkes', 'id')
        }),
        optionKota: null,
        roleAdmin: getRole('Admin Dinkes')
      }
    },
    methods: {
      async getListDinkes() {
        const resp = await this.$axios.get("/v1/list-kota-jabar")
        const {
          data
        } = resp || []
        this.optionKota = data
      },
      async submit() {
        try {
          const response = await this.form.put(
            "/v1/user/dinkes/" +
            this.form.id
          );
          this.$toast.success('Berhasil', {
            icon: "check",
            iconPack: "fontawesome",
            duration: 5000,
          });
          this.$router.push("/dinkes");
        } catch (err) {
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
          return;
        }
      },
    },
    created() {
      this.getListDinkes();
    },
    head() {
      return {
        title: "Perbarui Pengguna",
      };
    },
  };
</script>