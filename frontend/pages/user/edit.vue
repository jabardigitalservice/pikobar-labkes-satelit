<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Perbarui Pengguna</portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link to="/user" class="btn btn-black">
          <i class="uil-arrow-left" /> Kembali
        </nuxt-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Informasi Pengguna">
          <form @submit.prevent="submit" @keydown="form.onKeydown($event)">
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">{{
                $t("name")
              }}</label>
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
              <label class="col-md-3 col-form-label text-md-right">{{
                $t("username")
              }}</label>
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
                  {{ this.form.koordinator }}
                </label>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">{{
                $t("lab")
              }}</label>
              <div class="col-md-7">
                <select
                  v-model="form.lab_satelit_id"
                  class="form-control col-md-8 col-lg-6"
                  name="lab_satelit_id"
                >
                  <option
                    :value="item.id"
                    :key="idx"
                    v-for="(item, idx) in option_lab_satelit"
                  >
                    {{ item.nama }}
                  </option>
                </select>
                <has-error :form="form" field="password" />
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-7 offset-md-3 d-flex">
                <v-button :loading="form.busy">
                  {{ $t('common.save') }}
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

export default {
  middleware: "auth",
  async asyncData({ route }) {
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
      }),
    };
    return { data };
  },
  data: () => {
    return {
      option_lab_satelit: null,
    };
  },
  methods: {
    async getLabSatelit() {
      const resp = await this.$axios.get("/lab-satelit");
      this.option_lab_satelit = resp.data.data;
    },
    async submit() {
      try {
        const response = await this.form.put(
          "/v1/user/" +
            this.form.id 
        );
        console.log(response);
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