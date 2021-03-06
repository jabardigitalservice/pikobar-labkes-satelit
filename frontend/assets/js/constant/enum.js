export const KesimpulanPemeriksaan = [{
  id: 'positif',
  nama: 'POSITIF'
}, {
  id: 'negatif',
  nama: 'NEGATIF'
}, {
  id: 'inkonklusif',
  nama: 'INKONKLUSIF'
}, {
  id: 'invalid',
  nama: 'INVALID'
}];

export const oidHasChecked = ['registrasi-perujuk', 'verifikasi-admin', 'registrasi-sampel'];

export const metodeEkstraksi = ['Manual', 'Otomatis'];

export const optionsKitEkstraksi = ['Geneaid', 'Qiagen', 'Invitrogen', 'Roche', 'Addbio'];

export const optionsAlatEkstraksi = ['Kingfisher', 'Genolution'];

export const optionRoles = [
  {
    id: 1,
    value: 'Super Admin'
  },
  {
    id: 2,
    value: 'Admin Dinkes'
  },
  {
    id: 8,
    value: 'Admin Satelit'
  },
  {
    id: 9,
    value: 'Admin Perujuk'
  }
];

export const pasienStatus = [{
  'value': 1,
  'text': 'Kontak Erat'
}, {
  'value': 2,
  'text': 'Suspek'
}, {
  'value': 3,
  'text': 'Probable'
}, {
  'value': 4,
  'text': 'Konfirmasi'
}, {
  'value': 5,
  'text': 'Tanpa Kriteria'
}];

export const optionsPenerimaSampel = ['Adit', 'Ariza', 'Fahmy', 'Figur', 'Firman'];

export const colAdminSatelit = {
  waktu_pcr_sample_analyzed: 'TANGGAL PEMERIKSAAN',
  nomor_sampel: 'NO SAMPEL',
  pasien_nama: 'NAMA PASIEN',
  kota_domilisi: 'DOMISILI',
  instansi_pengirim: 'INSTANSI',
  parameter_lab: 'PARAMETER LAB',
  status: 'Kriteria',
  sumber_pasien: 'KATEGORI',
  kesimpulan_pemeriksaan: 'KESIMPULAN PEMERIKSAAN',
  catatan: 'KETERANGAN',
}

export const colSuperAdmin = {
  checkbox_sampel_id: '#',
  waktu_pcr_sample_analyzed: 'TANGGAL PEMERIKSAAN',
  nomor_sampel: 'NO SAMPEL',
  pasien_nama: 'NAMA PASIEN',
  kota_domilisi: 'DOMISILI',
  instansi_pengirim: 'INSTANSI',
  lab: 'LAB PEMERIKSA',
  sumber_pasien: 'KATEGORI',
  kesimpulan_pemeriksaan: 'KESIMPULAN PEMERIKSAAN',
}

export const optionInstansiPengirim = [
  'dinkes',
  'puskesmas',
  'rumah_sakit'
];
