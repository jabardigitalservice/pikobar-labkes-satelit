<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>
      <input
        type="checkbox"
        name="list-sampel"
        v-if="item.sampel_status == 'sample_taken' && !item.register_perujuk_id"
        v-bind:value="item.register_id"
        v-bind:id="'selected-sampel-' + item.register_id"
        v-bind:nomor_sampel="item.nomor_sampel"
        v-model="selected"
        @click="sampelOnChangeSelect"
      />
    </td>
    <td>
      <div class="badge badge-white" style="text-align:left; padding:10px">
        {{item.nomor_sampel}}
      </div>
    </td>
    <td nowrap>
      <div v-if="item.nama_lengkap" style="text-transform: capitalize;"><b>{{ item.nama_lengkap }}</b></div>
      <div v-if="item.nik" class="text-muted">{{ item.nik }}</div>
      <div class="text-muted">{{ usiaPasien || null }}</div>
    </td>
    <td>{{item.nama_kota}}</td>
    <td>{{item.nama_rs}}</td>
    <td>{{item.sumber_pasien}}</td>
    <td>{{item.status ? pasienStatus.find(x => x.value == item.status).text : null }}</td>
    <td nowrap>
      <div><b>{{ item.waktu_sample_taken ? momentFormatDate(item.waktu_sample_taken) : null }}</b></div>
      <div class="text-muted">
        {{ item.waktu_sample_taken ? 'pukul ' + momentFormatTime(item.waktu_sample_taken) : null }}</div>
    </td>
    <td>
      <div class="text-red"
        v-if="(item.nik==null || item.nik=='') || (item.nama_lengkap==null || item.nama_lengkap=='')">
        <b>Data Belum Lengkap</b>
      </div>
      <div class="text-yellow"
        v-if="(item.nik!=null && item.nik!='') && (item.nama_lengkap!=null && item.nama_lengkap!='')">
        <b>Data Lengkap</b>
      </div>
    </td>
    <td v-if="config.has_action">
      <nuxt-link :to="`/registrasi/sampel/detail/${item.register_id}/${item.pasien_id}`"
        class="mb-1 btn btn-yellow btn-sm" title="Klik untuk melihat detail">
        <i class="fa fa-eye" />
      </nuxt-link>
      <nuxt-link :to="`/registrasi/sampel/update/${item.register_id}/${item.pasien_id}`"
        class="mb-1 btn btn-primary btn-sm" title="Klik untuk edit data" v-if="item.sampel_status == 'sample_taken' && !item.register_perujuk_id">
        <i class="fa fa-edit" />
      </nuxt-link>
      <button class="mb-1 btn btn-danger btn-sm" @click="deleteData(item, usiaPasien)" title="Klik untuk hapus data" v-if="item.sampel_status == 'sample_taken' && !item.register_perujuk_id">
        <i class="fa fa-trash" />
      </button>
    </td>
  </tr>
</template>
<script>
  import {
    pasienStatus
  } from '~/assets/js/constant/enum';
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
      let datas = this.$store.state.registrasi_sampel.selectedSampels
      return {
        pasienStatus,
        momentFormatDate,
        momentFormatTime,
        checked: false,
        selected: [],
        dataArr: datas,
      };
    },
    watch: {
      'selected': function () {
        const sampel = document.getElementById("selected-sampel-" + this.item.register_id).value
        const findinArr = this.dataArr.length > 0 ? this.dataArr.find((el) => el === sampel) : null
        findinArr ? document.getElementById("selected-sampel-" + this.item.register_id).checked = true
          : document.getElementById("selected-sampel-" + this.item.register_id).checked = false
      },
    },
    methods: {
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
                Pasien
              </div>
              <div class="col-md-7">
                <div class="flex-left" style="text-transform: capitalize">${item.nama_lengkap || '-'}</div>
                <div class="flex-left">${item.nik || ''}</div>
                <div class="flex-left">${usia}</div>
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Domisili
              </div>
              <div class="col-md-7 flex-left">
                ${item.nama_kota || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Instansi
              </div>
              <div class="col-md-7 flex-left">
                ${item.nama_rs || '-'}
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
                ${item.status ? pasienStatus.find(x => x.value == item.status).text : '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Tanggal Input
              </div>
              <div class="col-md-7">
                <div class=" flex-left">
                  ${momentFormatDate(item.waktu_sample_taken)}, ${momentFormatTime(item.waktu_sample_taken)}
                </div>
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Keterangan
              </div>
              <div class="col-md-7 flex-left">
                ${getKeteranganData(item.nik, item.nama_lengkap)}
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
            await this.$axios.delete(`v1/register/sampel/${item.register_id}/${item.pasien_id}`);
            toast.success('Berhasil menghapus data', {
              icon: 'check',
              iconPack: 'fontawesome',
              duration: 5000
            })
            await bus.$emit('refresh-ajaxtable', 'registrasi-sampel');
          } catch (err) {
            swal.fire("Terjadi kesalahan", "Silakan hubungi Admin", "error");
          }
        }
      },
      sampelOnChangeSelect(e) {
        const newDomchecked = e.target.checked
        const el = e ? e.currentTarget : null
        const nomorSampel = el ? el.getAttribute("value") : null
        this.checked = newDomchecked
        this.checked ? this.$store.commit("registrasi_sampel/add", nomorSampel)
          : this.$store.commit("registrasi_sampel/remove", nomorSampel)
      },
      showDetail(item) {
        const payload = {
          id: item.nomor_register,
          data: item
        };
        this.$store.dispatch("register/saveData", payload);
        this.$router.push("/registrasi/mandiri/detail");
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
    }
  };
</script>