<template>
  <div class="wrapper wrapper-content">
	<portal to="title-name">Pelacakan Sampel</portal>
	<portal to="title-action">
	  <div class="title-action"></div>
	</portal>

	<div class="row">
	  <div class="col-lg-12">
		<Ibox title="Pencarian">
		  <div class="row">
			<div class="col-md-3">
			  <div class="form-group">
				<label>Nomor Register</label>
				<input v-model="params1.nomor_register" class="form-control" />
			  </div>
			</div>
			<div class="col-md-3">
			  <div class="form-group">
				<label>Nomor Sampel</label>
				<input v-model="params1.nomor_sampel" class="form-control" />
			  </div>
			</div>
			<div class="col-md-3">
			  <div class="form-group">
				<label>Nomor Induk Kependudukan</label>
				<input v-model="params1.nik" class="form-control" />
			  </div>
			</div>
			<div class="col-md-3">
			  <div class="form-group">
				<label>Nama Pasien</label>
				<input v-model="params1.nama_pasien" class="form-control" />
			  </div>
			</div>
		  </div>
		  <div class="row">
			<div class="col-2">
			  <button id="btn-search" class="btn btn-primary" @click="onSearch()">
				<i class="fa fa-search"></i> Cari Sampel
			  </button>
			</div>
		  </div>
		</Ibox>
	  </div>
	</div>

	<div class="row">
	  <div class="col-lg-12">
		<Ibox title="Sampel Tersedia">
		  <ajax-table
			url="/v1/pelacakan-sampel/list"
			:oid="'pelacakan-sampel'"
			:params="params1"
			:config="{
						autoload: true,
						has_number: true,
						has_entry_page: false,
						has_pagination: false,
						has_action: true,
						has_search_input: false,
						custom_header: '',
						default_sort: '',
						custom_empty_page: true,
						class: {
							table: [],
							wrapper: ['table-responsive'],
						}
					}"
			:rowtemplate="'tr-pelacakan-sampel'"
			:columns="{
						nomor_register: 'Nomor Register',
						pasien_nama : 'Nama Pasien',
						sumber_pasien: 'Sumber Pasien',
						kota_nama : 'Kota Domisili',
						nomor_sampel : 'Nomor Sampel',
						kesimpulan_pemeriksaan: 'Kesimpulan Pemeriksaan',
						status_sampel: 'Status',
						validator: 'Validator'
					}"
		  ></ajax-table>
		</Ibox>
	  </div>
	</div>
  </div>
</template>

<script>
import axios from "axios";
import Form from "vform";

export default {
  middleware: "auth",
  data() {
	return {
	  params1: {
		nomor_register: "",
		nomor_sampel: "",
		nik: "",
		nama_pasien: ""
	  }
	};
  },
  head() {
	return { title: "Pelacakan Sampel" };
  },
  methods: {
	async onSearch() {
      this.$bus.$emit("refresh-ajaxtable", "pelacakan-sampel");
	}
  }
};
</script>