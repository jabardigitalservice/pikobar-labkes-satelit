<template>
  <div class="wrapper wrapper-content" ref="body">
    <portal to="title-name"> Dinkes </portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link to="/dinkes/tambah" class="btn btn-primary">
          <i class="uil-plus" /> Tambah Data
        </nuxt-link>
      </div>
    </portal>
    
    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Dinkes">
          <ajax-table
            url="/v1/users/dinkes"
            :oid="`dinkes`"
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
            :rowtemplate="'tr-dinkes'"
            :columns="{
              dinkes: 'DINKES',
              name: 'NAMA ADMIN',
              email: 'EMAIL',
              status: 'STATUS',
            }"
          />
        </Ibox>
      </div>
    </div>
  </div>
</template>

<script>
import Form from "vform";
import $ from "jquery";
const JQuery = $;
export default {
  middleware: ["auth", "checkrole"],
  meta: {
    allow_role_id: [1],
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
  },
  created() {
    this.getLabSatelit();
  }
};
</script>