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
              <label class="col-md-3 col-form-label text-md-right">{{
                $t("coordinator")
              }}</label>
              <div class="col-md-7">
                <label class="form-control" readonly>
                  {{ this.form.coordinator }}
                </label>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">
                Dinkes
              </label>
              <div class="col-md-7">
                <select v-model="form.lab_satelit_id" class="form-control" name="lab_satelit_id">
                  <option :value="item.id" :key="idx" v-for="(item, idx) in option_lab_satelit">
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
                <select v-model="form.role" class="form-control" name="role" required
                  :class="{ 'is-invalid': form.errors.has(`role`) }">
                  <option :value="item" :key="item" v-for="item in optionsRoleDinkes">{{ humanize(item) }}
                  </option>
                </select>
                <has-error :form="form" field="password" />
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-7 offset-md-3 d-flex">
                <v-button :loading="form.busy">
                  {{ $t("edit") }}
                </v-button>
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
    optionsRoleDinkes
  } from "~/assets/js/constant/enum"
  import {
    humanize
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
          koordinator: data.koordinator,
          lab_satelit_id: data.lab_satelit_id,
          id: data.id,
          role: data.role_id ? data.role_id === 1 ? 'super_admin' : 'admin' : '-'
        }),
      };
      return {
        data
      };
    },
    data: () => {
      return {
        option_lab_satelit: null,
        optionsRoleDinkes,
      };
    },
    methods: {
      humanize,
      async getLabSatelit() {
        const resp = await this.$axios.get("/lab-satelit");
        this.option_lab_satelit = resp.data.data;
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
          this.$router.push("/user");
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
      this.getLabSatelit();
    },
    head() {
      return {
        title: "Perbarui Pengguna",
      };
    },
  };
</script>