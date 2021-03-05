<template>
  <div class="wrapper wrapper-content" ref="body">
    <portal to="title-name">Perujuk</portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link tag="button" to="/perujuk/tambah" class="btn btn-primary">
          <em class="fa fa-plus" /> Tambah Akun Perujuk
        </nuxt-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Perujuk">
          <ajax-table url="/v1/users/perujuk/" :oid="`master-perujuk`" :params="params" :config="{
              autoload: true,
              has_number: true,
              has_entry_page: true,
              has_pagination: true,
              has_action: true,
              has_search_input: false,
              custom_header: '',
              default_sort: '',
              default_sort_dir: 'asc',
              custom_empty_page: true,
              class: {
                table: [],
                wrapper: ['table-responsive'],
              },
            }" :rowtemplate="'tr-perujuk'" :columns="{
              name: 'FASYANKES PERUJUK',
              email: 'EMAIL',
              domisili: 'DOMISILI',
              status: 'STATUS',
              last_login_at: 'TERAKHIR LOGIN',
            }"
            :disableSort="['name', 'domisili', 'status', 'last_login_at']"
          />
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
    head() {
      return {
        title: this.$t("home"),
      }
    },
    data() {
      return {
        params: {
          perujuk: null,
          name: null,
          email: null,
          status: null,
          last_login_at: null,
        },
      }
    }
  }
</script>
