<template>
  <div class="wrapper wrapper-content" ref="body">
    <portal to="title-name"> Pengguna </portal>
    <portal to="title-action">
      <div class="title-action">
        <button
          type="button"
          class="btn btn-primary"
          data-toggle="modal"
          data-target="#invite-modal"
        >
          <i class="fa fa-paper-plane" /> Undang
        </button>
      </div>
    </portal>
    
    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Pengguna">
          <ajax-table
            url="/v1/user"
            :oid="`user`"
            :params="params"
            :config="{
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
            }"
            :rowtemplate="'tr-user'"
            :columns="{
              username: 'USERNAME',
              name: 'NAMA',
              email: 'EMAIL',
              koordinator: 'KOORDINATOR',
              lab: 'LAB',
              alamat_lab: 'ALAMAT LAB',
              status: 'STATUS',
            }"
          />
        </Ibox>
      </div>
    </div>

    <custom-modal modal_id="invite-modal" title="Undang Pengguna">
      <div slot="body">
        <div class="col-lg-12">
          <form
            id="scanbarcode row"
            action="/api/v1/user"
            method="post"
            @submit.prevent="submit"
            @keydown="form.onKeydown($event)"
          >
            <div class="form-group row">
              <label class="col-md-3 col-lg-3"
                >Lab <span style="color: red">*</span></label
              >
              <div class="col-md-8 col-lg-9">
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
                <has-error :form="form" field="lab_satelit_id" />
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-4 col-lg-3"
                >E-mail <span style="color: red">*</span></label
              >
              <div class="col-md-8 col-lg-9">
                <input
                  id="barcodesampel"
                  class="form-control"
                  name="email"
                  placeholder="Email"
                  type="text"
                  tabindex="1"
                  required
                  autofocus
                  v-model="form.email"
                />
              </div>
            </div>
            <div class="form-group row">
              <v-button :loading="form.busy" class="btn btn-md btn-primary btn-block">
                <i class="fa fa-paper-plane"></i> Kirim Undangan
              </v-button>
            </div>
          </form>
        </div>
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
        email: null,
        lab_satelit_id: null,
      },
      params: {
        username: null,
        name: null,
        email: null,
        status: null,
        lab: null,
      },
    };
  },
  head() {
    return {
      title: this.$t("home"),
    };
  },
  async asyncData({ route, store }) {
    let form = new Form({
      register_file: null,
    });
    return {
      form,
    };
  },
  methods: {
    async getLabSatelit() {
      const resp = await this.$axios.get("/lab-satelit");
      this.option_lab_satelit = resp.data.data;
      console.log(this.option_lab_satelit);
    },
    async submit() {
        try {
          const response = (await this.form.post("/v1/user/invite")).data;
          if (response.status == 200) {
            this.$toast.success(response.message, {
              icon: 'paper-plane',
              iconPack: 'fontawesome',
              text: 'Undangan terkirim',
              duration: 5000
            })
            JQuery('#addRegistrasiRujukan').modal('hide');
          }

          if (response.status == 400) {
            this.$toast.error(response.message, {
              icon: 'times',
              iconPack: 'fontawesome',
              duration: 5000
            })
          }
        } catch (e) {
          this.$toast.error('Terjadi kesalahan server', {
            icon: 'times',
            iconPack: 'fontawesome',
            duration: 5000
          })
          console.log(e)
        }
      },

    previewFile(file) {
      this.form.register_file = file;
    },
  },
  created() {
    this.getLabSatelit();
  }
};
</script>