<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>
      {{ item.username }}
    </td>
    <td>
      {{ item.name }}
    </td>
    <td>{{ item.email }}</td>
    <td>{{ item.koordinator }}</td>
    <td>{{ item.lab || "-" }}</td>
    <td>{{ item.alamat_lab || "-" }}</td>
    <td>{{ item.last_login_at || item.status }}</td>
    <td>
      <nuxt-link :to="`/user/${item.id}`" class="btn btn-yellow btn-sm">
        <i class="fa fa-eye"></i>
        Lihat
      </nuxt-link>
      <nuxt-link :to="`/user/${item.id}/edit`" class="btn btn-primary btn-sm">
        <i class="fa fa-edit"></i>
        Ubah
      </nuxt-link>
      <button
        v-if="!item.has_data"
        class="btn btn-danger btn-sm"
        @click="deleteData(item.id)"
      >
        <i class="fa fa-trash"></i> Hapus
      </button>
      <button
        :class="`btn ${userStatus ? 'btn-warning' : 'btn-success'} btn-sm`"
        @click="statusToggle(item.id, userStatus)"
      >
        <i class="fa fa-power-off"></i>
        {{ userStatus ? "Non Aktifkan" : "Aktifkan" }}
      </button>
    </td>
  </tr>
</template>
<script>
import axios from "axios";
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
    async deleteData(id, status) {
      this.$swal
        .fire({
          title: "Apakah Anda Yakin ?",
          text: "untuk menghapus Akun",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Ya",
          cancelButtonText: "Tidak",
        })
        .then((result) => {
          if (result.value) {
            axios
              .delete("/v1/user/" + id)
              .then((response) => {
                this.$swal.fire(
                  "Berhasil Menghapus Data",
                  "Data Pengguna Berhasil dihapus",
                  "success"
                );
                this.$bus.$emit("refresh-ajaxtable", "master-user");
              })
              .catch((error) => {
                this.$swal.fire(
                  "Terjadi Kesalahan",
                  "Gagal menghapus data, silakan coba kembali",
                  "error"
                );
              });
          }
        });
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
            axios
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
