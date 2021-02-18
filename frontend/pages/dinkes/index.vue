<template>
  <div class="wrapper wrapper-content" ref="body">
    <portal to="title-name"> Dinkes </portal>
    <portal to="title-action">
      <div class="title-action">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#invite-dinkes-modal">
          <i class="uil-plus" /> Tambah Akun
        </button>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Dinkes">
          <ajax-table url="/v1/users/dinkes/" :oid="`master-dinkes`" :params="params" :config="{
              autoload: true,
              has_number: true,
              has_entry_page: true,
              has_pagination: true,
              has_action: true,
              has_search_input: false,
              custom_header: '',
              default_sort: 'name',
              default_sort_dir: 'asc',
              custom_empty_page: true,
              class: {
                table: [],
                wrapper: ['table-responsive'],
              },
            }" :rowtemplate="'tr-dinkes'" :columns="{
              dinkes: 'DINKES',
              name: 'NAMA ADMIN',
              email: 'EMAIL',
              status: 'STATUS',
            }" />
        </Ibox>
      </div>
    </div>

    <custom-modal modal_id="invite-dinkes-modal" title="Tambah Akun Dinkes">
      <div slot="body">
        <div class="col-lg-12">

          <!-- Name -->
          <div class="form-group row">
            <label class="col-md-4 col-form-label">
              Admin Dinkes<span style="color: red">*</span>
            </label>
            <div class="col-md-8">
              <input v-model="form.name" type="text" name="name" class="form-control" required
                :class="{ 'is-invalid': form.errors.has(`name`) }">
              <has-error :form="form" field="name" />
            </div>
          </div>

          <!-- Username -->
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

          <!-- Lab -->
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

          <!-- Role -->
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

          <!-- Email -->
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
        </div>

        <div class="dimmed" v-if="isLoading">
          <div class="loading-indicator">
            <img src="~/assets/img/loading.gif" width="48" height="48">
            Loading ...
          </div>
        </div>
        <button @click="submit()" :disabled="loading" :class="{'btn-loading': loading}"
          class="btn btn-md btn-primary block m-b pull-right" type="button">
          Submit
        </button>
      </div>
    </custom-modal>

  </div>
</template>

<script>
  import Form from "vform";
  import $ from "jquery";
  import CustomModal from "~/components/CustomModal";
  import {
    getRole
  } from "~/utils"
  const JQuery = $;
  export default {
    middleware: ["auth", "checkrole"],
    meta: {
      allow_role_id: [1],
    },
    components: {
      CustomModal,
    },
    data() {
      return {
        loading: false,
        isLoading: false,
        dataError: [],
        optionKota: [],
        roleAdmin: getRole('Admin Dinkes'),
        form: new Form({
          name: null,
          username: null,
          email: null,
          kota_id : null,
          role_id: getRole('Admin Dinkes', 'id'),
        }),
        params: {
          dinkes: null,
          name: null,
          email: null,
          status: null,
          last_login_at: null,
        },
      };
    },
    head() {
      return {
        title: this.$t("home"),
      };
    },
    methods: {
      async getListDinkes() {
        const resp = await this.$axios.get("/v1/list-kota-jabar")
        const { data } = resp || []
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
          JQuery("#invite-dinkes-modal").modal("hide");
          this.isLoading = false;
          this.form.name = null;
          this.form.username = null;
          this.form.email = null;
          this.form.kota_id  = null;
          this.form.role_id = 8;
          this.$bus.$emit("refresh-ajaxtable", "master-dinkes");
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
  };
</script>

<style scoped>
  .dimmed {
    z-index: 10;
    display: block;
    position: absolute;
    height: 100%;
    top: 0;
    left: 0;
    right: 0;
    background: rgba(255, 255, 255, 0.5);
  }
</style>