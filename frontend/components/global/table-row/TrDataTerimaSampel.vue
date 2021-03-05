<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>
      <input
        type="checkbox"
        name="list-sampel"
        v-bind:id="item.id"
        v-bind:value="item.nomor_sampel"
        v-model="selected"
        @click="sampelOnChangeSelect"
      >
    </td>
    <td nowrap>
      <div v-if="item.nama_pasien" style="text-transform: capitalize;"><b>{{ item.nama_pasien }}</b></div>
      <div v-if="item.nik" class="text-muted">{{ item.nik }}</div>
      <div class="text-muted">{{ usiaPasien || null }}</div>
    </td>
    <td>{{item.kota ? item.kota.nama : null}}</td>
    <td>{{item.sumber_pasien}}</td>
    <td nowrap>
      <div><b>{{ item.created_at ? momentFormatDate(item.created_at) : null }}</b></div>
      <div class="text-muted">
        {{ item.created_at ? 'pukul ' + momentFormatTime(item.created_at) : null }}</div>
    </td>
    <td>
      <div class="badge badge-white" style="text-align:left; padding:10px">
        {{item.nomor_sampel}}
      </div>
    </td>
    <td>{{item.perujuk ? item.perujuk.nama : null}}</td>
    <td v-if="config.has_action">
      <button class="mb-1 btn btn-primary btn-sm" @click="terimaData(item, usiaPasien)" title="Terima Sampel">
        <i class="fa fa-check" />
      </button>
      <button class="mb-1 btn btn-danger btn-sm" @click="deleteData(item, usiaPasien)" title="Tolak Sampel">
        <i class="fa fa-times" />
      </button>
    </td>
  </tr>
</template>
<script>
  import axios from "axios";
  import Form from "vform";
  import {
    getHumanAge,
    getAlertPopUp,
    momentFormatDate,
    momentFormatTime,
    getKeteranganData
  } from '~/utils';
  export default {
    props: ["item", "pagination", "rowparams", "index", "config"],
    data() {
      let datas = this.$store.state.registrasi_perujuk.selectedSampels;
      return {
        checked: false,
        selected: [],
        dataArr: datas,
        momentFormatDate,
        momentFormatTime,
        form: new Form({
          id: [this.item.id]
        }),
      };
    },
    computed: {
      selectedSampels: {
        set(val) {
          this.$store.state.selectedSampels = val
        },
        get() {
          return this.$store.state.selectedSampels
        }
      }
    },
    methods: {
      async terimaData(item, usia) {
        const content = `
          <div class="row flex-content-center">
            ${this.$t("alert_confirm_terima_text")}
          </div>
          <div class="row col-lg-12 flex-content-center mt-4" style="font-size: 12px">
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Nomor Sampel
              </div>
              <div class="col-md-7 flex-left">
                ${item.nomor_sampel || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Kode Kasus
              </div>
              <div class="col-md-7 flex-left">
                ${item.kode_kasus || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Pasien
              </div>
              <div class="col-md-7">
                <div class="flex-left" style="text-transform: capitalize">${item.nama_pasien || '-'}</div>
                <div class="flex-left">${item.nik || ''}</div>
                <div class="flex-left">${usia}</div>
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Perujuk Sampel
              </div>
              <div class="col-md-7 flex-left">
                ${item.perujuk ? item.perujuk.nama : '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Domisili
              </div>
              <div class="col-md-7 flex-left">
                ${item.kota ? item.kota.nama : '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Kategori
              </div>
              <div class="col-md-7 flex-left">
                ${item.sumber_pasien || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Kriteria
              </div>
              <div class="col-md-7 flex-left">
                ${item.status || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Keterangan
              </div>
              <div class="col-md-7 flex-left">
                ${getKeteranganData(item.nik, item.nama_pasien)}
              </div>
            </div>
          </div>
        `;
        let swal = this.$swal;
        let toast = this.$toast;
        let bus = this.$bus;
        const swalCustom = swal.mixin({
          customClass: {
            confirmButton: 'btn btn-primary btn-md ml-2',
            cancelButton: 'btn btn-clear btn-md'
          },
          buttonsStyling: false
        });
        const {
          value: isConfirm
        } = await swalCustom.fire(getAlertPopUp('terima', content));
        if (isConfirm) {
          try {
            const response = await this.form.post("/v1/register-perujuk/bulk");
            this.$toast.success(response.message, {
              icon: 'check',
              iconPack: 'fontawesome',
              duration: 5000
            })
            this.$store.commit('registrasi_perujuk/clear');
            this.$bus.$emit('refresh-ajaxtable', 'registrasi-perujuk');
          } catch (e) {
            swal.fire("Terjadi kesalahan", "Silakan hubungi Admin", "error");
          }
        }
      },
      async deleteData(item, usia) {
        const content = `
          <div class="row flex-content-center">
            ${this.$t("alert_confirm_delete_text")}
          </div>
          <div class="row col-lg-12 flex-content-center mt-4" style="font-size: 12px">
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Nomor Sampel
              </div>
              <div class="col-md-7 flex-left">
                ${item.nomor_sampel || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Kode Kasus
              </div>
              <div class="col-md-7 flex-left">
                ${item.kode_kasus || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Pasien
              </div>
              <div class="col-md-7">
                <div class="flex-left" style="text-transform: capitalize">${item.nama_pasien || '-'}</div>
                <div class="flex-left">${item.nik || ''}</div>
                <div class="flex-left">${usia}</div>
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Perujuk Sampel
              </div>
              <div class="col-md-7 flex-left">
                ${item.perujuk ? item.perujuk.nama : '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Domisili
              </div>
              <div class="col-md-7 flex-left">
                ${item.kota ? item.kota.nama : '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Kategori
              </div>
              <div class="col-md-7 flex-left">
                ${item.sumber_pasien || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Kriteria
              </div>
              <div class="col-md-7 flex-left">
                ${item.status || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Keterangan
              </div>
              <div class="col-md-7 flex-left">
                ${getKeteranganData(item.nik, item.nama_pasien)}
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
            await this.$axios.delete(`v1/register-perujuk/delete/${item.id}`);
            toast.success('Berhasil menghapus data', {
              icon: 'check',
              iconPack: 'fontawesome',
              duration: 5000
            })
            await bus.$emit('refresh-ajaxtable', 'registrasi-perujuk');
          } catch (e) {
            swal.fire("Terjadi kesalahan", "Silakan hubungi Admin", "error");
          }
        }
      },
      showDetail(item) {
        const payload = {
          id: item.nomor_register,
          data: item
        };
        this.$store.dispatch("register/saveData", payload);
        this.$router.push("/registrasi/mandiri/detail");
      },
      sampelOnChangeSelect(e) {
        const newDomchecked = e.target.checked
        const el = e ? e.currentTarget : null
        const regId = el ? el.getAttribute("id") : null
        const noSampel = el ? el.getAttribute("value") : null
        this.checked = newDomchecked
        this.checked ? this.$store.commit("registrasi_perujuk/add", {id: regId, name: noSampel})
          : this.$store.commit("registrasi_perujuk/remove", {id: regId, name: noSampel})
      }
    },
    computed: {
      usiaPasien() {
        if (this.item.usia_tahun && this.item.usia_bulan) {
          return `${this.item.usia_tahun} tahun ${this.item.usia_bulan} bulan`;
        }
        if (this.item.tanggal_lahir) {
          return getHumanAge(this.item.tanggal_lahir);
        }
        return "";
      }
    },
    watch: {
      'selected': function () {
        const sampel = document.getElementById(this.item.id).value
        const findinArr = this.dataArr.length > 0 ? this.dataArr.find((el) => el.name === sampel) : null
        findinArr ? document.getElementById(this.item.id).checked = true
          : document.getElementById(this.item.id).checked = false
      }
    }
  }
</script>