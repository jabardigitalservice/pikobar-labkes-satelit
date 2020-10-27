<template>
  <div class="loginColumns animated fadeInDown">
    <div class="row">
      <div class="col-md-12 text-center mb-3">
        <div class="title-welcome">Undangan Berpartisipasi</div>
        <div class="title-welcome">Sistem Informasi Lab Jejaring</div>
        <div class="title-welcome">COVID-19 Jawa Barat</div>
      </div>
      <div class="col-lg-8 m-auto">
        <card>
          <form @submit.prevent="register" @keydown="form.onKeydown($event)">
            <!-- Email -->
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">{{
                $t("email")
              }}</label>
              <div class="col-md-7">
                <label
                  :class="{ 'is-invalid': form.errors.has('email') }"
                  type="email"
                  name="email"
                  class="form-control"
                  readonly
                >
                  {{ this.form.email }}
                </label>
                <has-error :form="form" field="email" />
              </div>
            </div>

            <!-- Koordinator -->
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">{{
                $t("coordinator")
              }}</label>
              <div class="col-md-7">
                <input
                  v-model="form.koordinator"
                  :class="{ 'is-invalid': form.errors.has('koodinator') }"
                  type="text"
                  name="koordinator"
                  class="form-control"
                />
                <has-error :form="form" field="koordinator" />
              </div>
            </div>

            <!-- Name -->
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">{{
                $t("name")
              }}</label>
              <div class="col-md-7">
                <input
                  v-model="form.name"
                  :class="{ 'is-invalid': form.errors.has('name') }"
                  type="text"
                  name="name"
                  class="form-control"
                />
                <has-error :form="form" field="name" />
              </div>
            </div>

            <!-- Username -->
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">{{
                $t("username")
              }}</label>
              <div class="col-md-7">
                <input
                  v-model="form.username"
                  :class="{ 'is-invalid': form.errors.has('username') }"
                  type="text"
                  name="username"
                  class="form-control"
                />
                <has-error :form="form" field="username" />
              </div>
            </div>

            <!-- Password -->
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">{{
                $t("password")
              }}</label>
              <div class="col-md-7">
                <input
                  v-model="form.password"
                  :class="{ 'is-invalid': form.errors.has('password') }"
                  type="password"
                  name="password"
                  class="form-control"
                />
                <has-error :form="form" field="password" />
              </div>
            </div>

            <!-- Password Confirmation -->
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">{{
                $t("confirm_password")
              }}</label>
              <div class="col-md-7">
                <input
                  v-model="form.password_confirmation"
                  :class="{
                    'is-invalid': form.errors.has('password_confirmation'),
                  }"
                  type="password"
                  name="password_confirmation"
                  class="form-control"
                />
                <has-error :form="form" field="password_confirmation" />
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-7 offset-md-3 d-flex">
                <!-- Submit Button -->
                <v-button :loading="form.busy">
                  {{ $t("register") }}
                </v-button>
              </div>
            </div>
          </form>
        </card>
      </div>
      <div class="col-lg-12 text-center">
          <div class="footer-logo-desc mt-5">Developed by</div>
          <div>
            <img
              alt="image"
              class="img-footer-logo"
              src="~/assets/img/logo/pemprov-jabar.png"
            />
            <img
              alt="image"
              class="img-footer-logo"
              src="~/assets/img/logo/jds.png"
            />
          </div>
        </div>
      </div>
  </div>
</template>

<script>
import Form from "vform";

export default {
  layout: "login",
  head() {
    return { title: this.$t("register") };
  },
  data: () => ({
    form: new Form({
      username: "",
      name: "",
      email: "",
      koordinator: "",
      password: "",
      password_confirmation: "",
      token: "",
      lab_satelit_id: "",
    }),
  }),

  methods: {
    async register() {
      // Register the user.
      try {
        this.loading = true;
        const response = await this.form.post("/v1/user/register");
        this.$toast.success(response.data.message, {
          icon: "check",
          iconPack: "fontawesome",
          duration: 5000,
        });
        this.$router.push({ name: "home" });
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
      }
      this.loading = false;
    },
    async getTokenInfo() {
      await this.$axios
        .get("/v1/user/register/" + this.$route.params.token)
        .then((response) => {
          let data = response.data;
          if (response.status == 200) {
            this.form.email = data.email;
            this.form.lab_satelit_id = data.lab_satelit_id;
          }
        });
    },
  },
  created() {
    this.getTokenInfo();
    this.form.token = this.$route.params.token;
  },
};
</script>

<style scoped>
.ibox-content {
  padding: 35px 30px 20px 30px;
}

@media all and (max-width: 670px) {
  .title-welcome {
    font-size: 24px;
  }

  .ibox-content {
    padding: 15px 20px 20px 20px;
  }

  .loginColumns {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px 20px 20px 20px;
  }

  .title-footer {
    position: relative;
    bottom: 10px;
    padding-top: 15px;
  }
}
</style>