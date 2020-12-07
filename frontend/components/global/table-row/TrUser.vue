<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>
      {{ item.username }}
    </td>
    <td>{{ item.email }}</td>
    <td>{{ item.koordinator }}</td>
    <td>{{ item.lab_satelit ? item.lab_satelit.nama : "-" }}</td>
    <td>{{ item.lab_satelit ? item.lab_satelit.alamat : "-" }}</td>
    <td>{{ item.status }}</td>
    <td>{{ item.last_login_at }}</td>
    <td>
      <nuxt-link :to="`/user/${item.id}`" class="mb-1 btn btn-yellow btn-sm" title="Klik untuk melihat detail">
        <i class="fa fa-eye" />
      </nuxt-link>
      <nuxt-link :to="`/user/${item.id}/edit`" class="mb-1 btn btn-primary btn-sm" title="Klik untuk edit data">
        <i class="fa fa-edit" />
      </nuxt-link>
      <button v-if="!item.has_data" class="mb-1 btn btn-danger btn-sm" @click="deleteData(item, userStatus)"
        title="Klik untuk hapus data">
        <i class="fa fa-trash" />
      </button>
      <button :class="`mb-1 btn ${userStatus ? 'btn-warning' : 'btn-success'} btn-sm`"
        @click="statusToggle(item.id, userStatus)" :title="userStatus ? 'Non Aktifkan' : 'Aktifkan'">
        <i class="fa fa-power-off" />
      </button>
    </td>
  </tr>
</template>

<script>
  import {
    getAlertPopUp
  } from '~/utils'
  export default {
    props: ["item", "pagination", "rowparams", "index"],
    data() {
      return {};
    },
    computed: {
      userStatus: function () {
        return this.item.status == "active";
      },
    },
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
              Koordinator
            </div>
            <div class="col-md-7 flex-left">
              ${item.koordinator || '-'}
            </div>
          </div>
          <div class="form-group row col-md-10">
            <div class="col-md-5 text-blue flex-left">
              Lab
            </div>
            <div class="col-md-7 flex-left">
              ${item.lab_satelit ? item.lab_satelit.nama : '-'}
            </div>
          </div>
          <div class="form-group row col-md-10">
            <div class="col-md-5 text-blue flex-left">
              Alamat Lab
            </div>
            <div class="col-md-7 flex-left">
              ${item.lab_satelit ? item.lab_satelit.alamat : '-'}
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
            await bus.$emit('refresh-ajaxtable', 'master-user');
          } catch (e) {
            swal.fire("Terjadi kesalahan", "Silakan hubungi Admin", "error");
          }
        }
      },
      async statusToggle(id, status) {
        let text = status ? "Menonaktifkan" : "Mengaktifkan";
        this.$swal
          .fire({
            title: "Apakah Anda Yakin ?",
            text: "untuk " + text + " Akun",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
          })
          .then((result) => {
            if (result.value) {
              this.$axios
                .put("/v1/user/status-toggle/" + id)
                .then((response) => {
                  this.$swal.fire(
                    "Berhasil " + text + " Data",
                    "",
                    "success"
                  );
                  this.$bus.$emit("refresh-ajaxtable", "master-user");
                })
                .catch((err) => {
                  this.$swal.fire(
                    "Terjadi Kesalahan",
                    err.response.data.error,
                    "error"
                  );
                });
            }
          });
      },
    },
  };
</script>