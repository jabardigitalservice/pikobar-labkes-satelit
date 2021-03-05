<template>
  <div class="wrapper wrapper-content" ref="body">
    <portal to="title-name"> Dinkes </portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link tag="button" to="/dinkes/tambah" class="btn btn-primary">
          <em class="fa fa-plus" /> Tambah Akun Dinkes
        </nuxt-link>
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
  </div>
</template>

<script>
  export default {
    middleware: ["auth", "checkrole"],
    meta: {
      allow_role_id: [1],
    },
    data() {
      return {
        loading: false,
        isLoading: false,
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
      }
    }
  }
</script>
