<template>
  <div class="loginColumns animated fadeInDown">
    <div class="row" v-if="!status">
      <div class="col-md-12 text-center mb-3">
        {{ $t("checking") }}
      </div>
    </div>
    <div class="row" v-if="status">
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
              <label class="col-md-3 col-form-label text-md-right"
                >{{ $t("email") }}<span style="color: red">*</span></label
              >
              <div class="col-md-7">
                <label
                  type="email"
                  name="email"
                  class="form-control"
                  readonly
                >
                  {{ this.email }}
                </label>
              </div>
            </div>

            <!-- Name -->
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">
                Admin Dinkes
              <span style="color: red">*</span></label
              >
              <div class="col-md-7">
                <label
                  type="email"
                  name="email"
                  class="form-control"
                  readonly
                >
                  {{ this.admin_dinkes }}
                </label>
              </div>
            </div>

            <!-- Dinkes -->
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">
                Dinkes
              <span style="color: red">*</span></label
              >
              <div class="col-md-7">
                <label
                  type="email"
                  name="email"
                  class="form-control"
                  readonly
                >
                  {{ this.dinkes }}
                </label>
              </div>
            </div>

            <!-- Username -->
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right"
                >Username<span style="color: red">*</span></label
              >
              <div class="col-md-7">
                <label
                  type="email"
                  name="email"
                  class="form-control"
                  readonly
                >
                  {{ this.username }}
                </label>
              </div>
            </div>

            <!-- Password -->
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right"
                >{{ $t("password") }}<span style="color: red">*</span></label
              >
              <div class="col-md-7">
                <input
                  v-model="form.password"
                  :class="{ 'is-invalid': form.errors.has('password') }"
                  type="password"
                  name="password"
                  class="form-control"
                  required
                />
                <has-error :form="form" field="password" />
              </div>
            </div>

            <!-- Password Confirmation -->
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right"
                >{{ $t("confirm_password")
                }}<span style="color: red">*</span></label
              >
              <div class="col-md-7">
                <input
                  v-model="form.password_confirmation"
                  :class="{
                    'is-invalid': form.errors.has('password_confirmation'),
                  }"
                  type="password"
                  name="password_confirmation"
                  class="form-control"
                  required
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
import swal from "sweetalert2";

export default {
  layout: "login",
  middleware({ store, redirect}) {
    if (store.getters["auth/check"]) {
      swal.fire('Harus Logout terlebih dahulu');
      return redirect('/home')
    }
  },
  head() {
    return { title: this.$t("register") };
  },
  data: () => ({
    status: false,
    username: "",
    admin_dinkes: "",
    email: "",
    dinkes: "",
    form: new Form({
      password: "",
      password_confirmation: "",
      token: "",
    }),
  }),

  methods: {
    async register() {
      // Register the user.
      try {
        this.loading = true;
        const response = await this.form.post("/v1/user/dinkes/register");
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
            this.username = data.username;
            this.admin_dinkes = data.admin_dinkes;
            this.email = data.email;
            this.dinkes = data.dinkes;
            this.status = true
          }
        })
        .catch((err) => {
          if (err.response.status == 404){
            this.$router.push({ name: "home" });
            this.status = false
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