<template>
  <div class="wrapper wrapper-content" ref="body">
    <portal to="title-name"> Dinkes </portal>
    <portal to="title-action">
      <div class="title-action">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#invite-dinkes-modal">
          <i class="fa fa-envelope-o" /> Undang
        </button>
        <!-- <nuxt-link to="/pengguna/tambah" class="btn btn-primary">
          <i class="uil-plus"></i> Tambah Dinkes
        </nuxt-link> -->
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Dinkes">
          <ajax-table url="/v1/users/dinkes" :oid="`master-dinkes`" :params="params" :config="{
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

    <custom-modal modal_id="invite-dinkes-modal" title="Undang">
      <div slot="body">
        <div class="col-lg-12">

          <!-- Name -->
          <div class="form-group row">
            <label class="col-md-4 col-form-label">Admin Dinkes</label>
            <div class="col-md-8">
              <input v-model="form.name" type="text" name="name" class="form-control">
            </div>
          </div>

          <!-- Username -->
          <div class="form-group row">
            <label class="col-md-4 col-form-label">Username</label>
            <div class="col-md-8">
              <input v-model="form.username" type="text" name="username" class="form-control">
            </div>
          </div>

          <!-- Lab -->
          <div class="form-group row">
            <label class="col-md-4 col-form-label">Lab</label>
            <div class="col-md-8">
              <select v-model="form.lab_satelit_id" class="form-control" name="lab_satelit_id" required>
                <option :value="item.id" :key="idx" v-for="(item, idx) in option_lab_satelit">
                  {{ item.nama }}
                </option>
              </select>
            </div>
          </div>

          <!-- Email -->
          <div class="form-group row">
            <label class="col-md-4 col-form-label">Email</label>
            <div class="col-md-8">
              <input class="form-control" name="email" placeholder="Email" type="text" tabindex="1" required autofocus
                v-model="form.email" />
            </div>
          </div>
        </div>

        <button @click="submit()" :disabled="loading" :class="{'btn-loading': loading}"
          class="btn btn-md btn-primary block m-b pull-right" type="button">
          <i class="fa fa-envelope-o mr-1" /> Kirim Undangan
        </button>
      </div>
    </custom-modal>

  </div>
</template>

<script>
  import Form from "vform";
  import $ from "jquery";
  import CustomModal from "~/components/CustomModal";
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
        dataError: [],
        option_lab_satelit: null,
        form: {
          name: null,
          username: null,
          email: null,
          lab_satelit_id: null,
        },
        params: {
          dinkes: null,
          name: null,
          email: null,
          status: null,
        },
      };
    },
    head() {
      return {
        title: this.$t("home"),
      };
    },
    methods: {
      async getLabSatelit() {
        const resp = await this.$axios.get("/lab-satelit");
        this.option_lab_satelit = resp.data.data;
      },
      async submit() {
        try {
          console.log('test', this.form)
          const response = await this.form.post("/v1/dinkes/invite");
          console.log('response', response)
          this.$toast.success(response.data.message, {
            icon: "paper-plane",
            iconPack: "fontawesome",
            text: "Undangan terkirim",
            duration: 5000,
          });
          JQuery("#invite-dinkes-modal").modal("hide");
          this.form.email = null;
          this.form.lab_satelit_id = null;
          this.$bus.$emit("refresh-ajaxtable", "master-dinkes");
        } catch (err) {
          console.log('error')
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
      this.getLabSatelit();
    }
  };
</script>