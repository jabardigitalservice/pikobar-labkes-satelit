<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Validasi</title>

    <style type="text/css">
        #tabel-ct-scan {
            border: 1px solid black;
            border-collapse: collapse;
            text-align: center;
        }
        table#tabel-ct-scan th {
            background-color: darkseagreen;
        }
    </style>

</head>
<body marginwidth="0" marginheight="0">

    <div style="margin-bottom: 20px; margin-top: 20px;">
        <img src="{{ $kop_surat }}" width="100%" alt="" srcset="">
    </div>

    <center><b>HASIL PEMERIKSAANTES PRO AKTIF COVID-19</b></center>
    {{-- <center><b>No./Lap.COV/IV/2020</b></center> --}}

    <table style="margin-top: 2%">
        <tbody>
            <tr>
                <td width="30%">
                  <b>Nomor Registrasi</b>
                </td>
                <td width="10%">:</td>
                <td width="60%">
                  <span><b>{{$sampel['nomor_register']}}</b></span>
                </td>
            </tr>
            <tr>
                <td width="30%">
                  <b>Nama Pasien</b>
                </td>
                <td width="10%">:</td>
                <td width="60%">
                    @if ($pasien)
                        <span><b>{{$pasien['nama_lengkap']}}</b></span>
                    @endif
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Nomor Induk Kependudukan</b>
                </td>
                <td width="10%">:</td>
                <td width="60%">
                    @if ($pasien)
                        <span>{{$pasien['nik'] }}</span>
                    @endif
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Jenis Kelamin</b>
                </td>
                <td width="10%">:</td>
                <td width="60%">
                    @if ($pasien && $pasien['jenis_kelamin'] == 'L')
                        <span>Laki-laki</span>
                    @endif
                    @if ($pasien && $pasien['jenis_kelamin'] == 'P')
                        <span>Perempuan</span>
                    @endif
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Tanggal Lahir Pasien / Umur</b>
                </td>
                <td width="10%">:</td>
                <td width="60%">
                    @if ($pasien)
                        <span>{{ $tanggal_lahir_pasien }}, {{ $umur_pasien }} tahun</span>
                    @endif
                </td>
              </tr>

              @if ($last_pemeriksaan_sampel)
                <tr>
                    <td colspan="3" style="padding-top: 20px"></td>
                </tr>
                <tr>
                    <td width="30%">
                    <b>Nomor Sampel</b>
                    </td>
                    <td width="10%">:</td>
                    <td width="60%">
                    <span>{{$sampel['nomor_sampel']}}</span>
                    </td>
                </tr>
                <tr>
                    <td width="30%">
                    <b>Tanggal Penerimaan Sampel</b>
                    </td>
                    <td width="10%">:</td>
                    <td width="60%">
                        {{ $last_pemeriksaan_sampel['tanggal_penerimaan_sampel'] }}
                    </td>
                </tr>
                <tr>
                    <td width="30%">
                    <b>Metode Pemeriksaan</b>
                    </td>
                    <td width="10%">:</td>
                    <td width="60%">
                        rRT-PCR-{{ $last_pemeriksaan_sampel['nama_kit_pemeriksaan'] }}
                    </td>
                </tr>
                <tr>
                    <td width="30%">
                    <b>Jenis Sampel</b>
                    </td>
                    <td width="10%">:</td>
                    <td width="60%">
                        {{ $sampel['jenis_sampel_nama'] }}
                    </td>
                </tr>

                <tr>
                    <td colspan="3" style="padding-top: 20px"></td>
                </tr>

                <tr>
                    <td width="30%">
                        <b>Hasil Pemeriksaan</b>
                    </td>
                    <td width="10%">:</td>
                    <td width="60%">
                        <span><b>{{$last_pemeriksaan_sampel['kesimpulan_pemeriksaan']}}</b></span>
                    </td>
                </tr>

              @endif

              {{-- <tr>
                <td width="30%">
                  <b>Lab Penerima</b>
                </td>
                <td width="10%">:</td>
                <td width="60%">
                  <span>{{$sampel['lab_pcr_nama']}}</span>
                </td>
              </tr> --}}
        </tbody>
    </table>

    @if ($last_pemeriksaan_sampel)
        
        <table id="tabel-ct-scan" style="width:70%; margin-top: 1%">
            <thead>
                <tr>
                    <th width="10%"><b>No.</b></th>
                    <th width="30%"><b>Target Gen</b></th>
                    <th width="30%"><b>CT Value</b></th>
                </tr>
            </thead>
            <tbody>

                {{-- @if ($last_pemeriksaan_sampel['hasil_deteksi'] && count($last_pemeriksaan_sampel['hasil_deteksi']) > 0)
                    @foreach ($last_pemeriksaan_sampel['hasil_deteksi'] as $key=> $item)
                        <tr>
                            <td>{{ ($key+1) }}</td>
                            <td>{{$item['target_gen']}}</td>
                            <td>{{$item['ct_value']}}</td>
                        </tr>
                    @endforeach
                @endif --}}

                @if ($last_pemeriksaan_sampel['hasil_deteksi_terkecil'] && count($last_pemeriksaan_sampel['hasil_deteksi_terkecil']) > 0)
                    <tr>
                        <td>1</td>
                        <td>{{$last_pemeriksaan_sampel['hasil_deteksi_terkecil']['target_gen']}}</td>
                        <td>{{$last_pemeriksaan_sampel['hasil_deteksi_terkecil']['ct_value']}}</td>
                    </tr>
                @endif

                @if (!$last_pemeriksaan_sampel['hasil_deteksi'] || count($last_pemeriksaan_sampel['hasil_deteksi']) < 1)
                    <tr>
                        <td colspan="3"><i>Tidak ada hasil CT Scan</i></td>
                    </tr>
                @endif

            </tbody>
        </table>
    @endif

    

    {{-- @if ($pemeriksaan_sampel && count($pemeriksaan_sampel) > 0)
        @foreach ($pemeriksaan_sampel as $key=> $hasil)
            
            <table style="margin-top: 3%">
                <tbody>
                    <tr>
                        <td colspan="3"><b>HASIL PEMERIKSAAN ({{ $key+1 }})</b></td>
                    </tr>
                    <tr>
                        <td width="30%">
                          <b>Tanggal Penerimaan Sampel</b>
                        </td>
                        <td width="10%">:</td>
                        <td width="60%">
                            {{ $hasil['tanggal_penerimaan_sampel'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                          <b>Petugas Penerimaan Sampel RNA</b>
                        </td>
                        <td width="10%">:</td>
                        <td>{{ $hasil['petugas_penerimaan_sampel_rna'] }}</td>
                    </tr>
                    <tr>
                        <td>
                          <b>Operator Realtime PCR</b>
                        </td>
                        <td width="10%">:</td>
                        <td>{{ $hasil['operator_realtime_pcr'] }}</td>
                    </tr>
                    <tr>
                        <td>
                          <b>Tanggal Mulai Pemeriksaan</b>
                        </td>
                        <td width="10%">:</td>
                        <td>{{ $hasil['tanggal_mulai_pemeriksaan'] }}</td>
                    </tr>
                    <tr>
                        <td>
                          <b>Jam Mulai Pemeriksaan</b>
                        </td>
                        <td width="10%">:</td>
                        <td>{{ $hasil['jam_mulai_pemeriksaan'] }}</td>
                    </tr>
                    <tr>
                        <td>
                            <b>Jam Selesai Pemeriksaan</b>
                        </td>
                        <td width="10%">:</td>
                        <td>{{ $hasil['jam_selesai_pemeriksaan'] }}</td>
                    </tr>
                    <tr>
                        <td>
                            <b>Metode Pemeriksaan</b>
                        </td>
                        <td width="10%">:</td>
                        <td>{{ $hasil['metode_pemeriksaan'] }}</td>
                    </tr>
                    <tr>
                        <td>
                            <b>Nama Kit Pemeriksaan</b>
                        </td>
                        <td width="10%">:</td>
                        <td>{{ $hasil['nama_kit_pemeriksaan'] }}</td>
                    </tr>
                    <tr>
                        <td>
                            <b>Tanggal Input Hasil</b>
                        </td>
                        <td width="10%">:</td>
                        <td>{{ $hasil['tanggal_input_hasil'] }}</td>
                    </tr>
                    <tr>
                        <td>
                            <b>Jam Input Hasil</b>
                        </td>
                        <td width="10%">:</td>
                        <td>{{ $hasil['jam_input_hasil'] }}</td>
                    </tr>
                    <tr>
                        <td>
                            <b>Kesimpulan Pemeriksaan</b>
                        </td>
                        <td width="10%">:</td>
                        <td>{{ $hasil['kesimpulan_pemeriksaan'] }}</td>
                    </tr>
                    <tr>
                        <td>
                            <b>Hasil CT Scan</b>
                        </td>
                        <td width="10%">:</td>
                        <td>
                            <table border="0.5">
                                <thead>
                                    <tr>
                                        <td><b>Target Gen</b></td>
                                        <td><b>CT Value</b></td>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if ($hasil['hasil_deteksi'] && count($hasil['hasil_deteksi']) > 0)
                                        @foreach ($hasil['hasil_deteksi'] as $item)
                                            <tr>
                                                <td>{{$item['target_gen']}}</td>
                                                <td>{{$item['ct_value']}}</td>
                                            </tr>
                                        @endforeach
                                    @endif

                                    @if (!$hasil['hasil_deteksi'] || count($hasil['hasil_deteksi']) < 1)
                                        <tr>
                                            <td colspan="2">Tidak ada hasil CT Scan</td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>
                        </td>
                    </tr>

                </tbody>
            </table>

        @endforeach
    @endif --}}


    @if ($validator)
        <table width="97%" style="margin-top: 12%">
            <tbody>
                <tr>
                    <td width="30%"></td>
                    <td width="25%"></td>
                    <td align="center">
                        Bandung, {{ $tanggal_validasi ?? ' -' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="padding-top: 65px;"></td>
                </tr>
                <tr>
                    <td width="30%"></td>
                    <td width="25%"></td>
                    <td align="center">
                        {{ $validator ? $validator->nama : ' -' }}
                    </td>
                </tr>
                <tr>
                    <td width="30%"></td>
                    <td width="25%"></td>
                    <td align="center">
                        NIP. {{ $validator ? $validator->nip : ' -' }}
                    </td>
                </tr>
            </tbody>
        </table>
    @endif
    
</body>
</html>