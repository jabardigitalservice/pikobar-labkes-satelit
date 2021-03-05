<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>
      {{ item.perujuk && item.perujuk.nama ? item.perujuk.nama : '-' }}
    </td>
    <td>
      {{ item.email || '' }}
    </td>
    <td>
      {{ item.kota_perujuk && item.kota_perujuk.nama ? item.kota_perujuk.nama : '' }}
    </td>
    <td>
      {{item.last_login_at ? 'active' : 'inactive'}}
    </td>
    <td>
      {{item.last_login_at || '-'}}
    </td>
    <td>
      <nuxt-link :to="`/perujuk/${item.id}`" class="mb-1 btn btn-yellow btn-sm" title="Klik untuk melihat detail">
        <em class="fa fa-eye" />
      </nuxt-link>
      <nuxt-link :to="`/perujuk/${item.id}/edit`" class="mb-1 btn btn-primary btn-sm"
        title="Klik untuk mengubah data">
        <em class="fa fa-edit" />
      </nuxt-link>
      <button v-if="!item.has_data_perujuk" class="mb-1 btn btn-danger btn-sm" @click="deleteData(item)"
        title="Klik untuk hapus data">
        <em class="fa fa-trash" />
      </button>
    </td>
  </tr>
</template>

<script>
  import {
    getAlertPopUp
  } from '~/utils'
  export default {
    props: ['item', 'pagination', 'rowparams', 'index'],
    methods: {
      async deleteData(item) {
        const content = `
        <div class="row flex-content-center">
          ${this.$t("alert_confirm_delete_text")}
        </div>
        <div class="row col-lg-12 flex-content-center mt-4" style="font-size: 12px">
          <div class="form-group row col-md-10">
            <div class="col-md-5 text-blue flex-left">
              Username
            </div>
            <div class="col-md-7 flex-left">
              ${item.username || '-'}
            </div>
          </div>
          <div class="form-group row col-md-10">
            <div class="col-md-5 text-blue flex-left">
              Email
            </div>
            <div class="col-md-7 flex-left">
              ${item.email || '-'}
            </div>
          </div>
          <div class="form-group row col-md-10">
            <div class="col-md-5 text-blue flex-left">
              Fasyankes Perujuk
            </div>
            <div class="col-md-7 flex-left">
              ${item.perujuk && item.perujuk.nama ? item.perujuk.nama : '-'}
            </div>
          </div>
          <div class="form-group row col-md-10">
            <div class="col-md-5 text-blue flex-left">
              Status
            </div>
            <div class="col-md-7 flex-left">
              ${item.last_login_at || item.status}
            </div>
          </div>
        </div>
      `;
        let swal = this.$swal;
        let toast = this.$toast;
        let bus = this.$bus;
        const swalCustom = swal.mixin({
          customClass: {
            confirmButton: 'btn btn-danger btn-md ml-2',
            cancelButton: 'btn btn-clear btn-md'
          },
          buttonsStyling: false
        });
        const {
          value: isConfirm
        } = await swalCustom.fire(getAlertPopUp('delete', content));
        if (isConfirm) {
          try {
            await this.$axios.delete(`/v1/user/${item.id}`);
            toast.success('Berhasil menghapus data', {
              icon: 'check',
              iconPack: 'fontawesome',
              duration: 5000
            })
            bus.$emit('refresh-ajaxtable', 'master-perujuk');
          } catch {
            swal.fire("Terjadi kesalahan", "Silakan hubungi Admin", "error");
          }
        }
      },
    }
  }
</script>