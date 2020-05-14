<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Validasi</title>

    <style type="text/css">
        @font-face {
            font-family: 'arialRegular';
            src: url("{{ public_path('fonts/arial/ARIAL_REGULAR.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'arialBold';
            src: url("{{ public_path('fonts/arial/ARIAL_BOLD.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: bold;
        }
        @font-face {
            font-family: 'arialItalic';
            src: url("{{ public_path('fonts/arial/ARIAL_ITALIC.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: italic;
        }
        body{
            font-family: "arialRegular", "arialBold", "arialItalic";
            font-size: 10pt;
        }
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

    {{-- <center><b>HASIL PEMERIKSAANTES PRO AKTIF COVID-19</b></center> --}}
    {{-- <center><b>No./Lap.COV/IV/2020</b></center> --}}

    <table style="margin-top: 2%" width="100%">
        <tbody>
            <tr>
                <td width="20%">
                  <b>No Reg</b>
                </td>
                <td width="2%">:</td>
                <td width="28%">
                  <span><b>{{$sampel['nomor_register']}}</b></span>
                </td>

                <td style="min-width:500px"></td>
                <td></td>
                <td></td>
                <td></td>

            </tr>
            <tr>
                <td width="20%">
                    <b>Nama</b>
                </td>
                <td width="2%">:</td>
                <td width="28%">
                    @if ($pasien)
                        <span><b>{{$pasien['nama_lengkap']}}</b></span>
                    @endif
                </td>

                <td style="min-width:500px"></td>
                <td width=20%>
                    <b>Tanggal Periksa</b>
                </td>
                <td width="2%">:</td>
                <td width="28%">
                    @if ($tanggal_periksa)
                        {{ $tanggal_periksa }}
                    @endif
                </td>
            </tr>
              {{-- <tr>
                <td width="20%">
                  <b>Nomor Induk Kependudukan</b>
                </td>
                <td width="2%">:</td>
                <td width="28%">
                    @if ($pasien)
                        <span>{{$pasien['nik'] }}</span>
                    @endif
                </td>
              </tr> 
              <tr>
                <td width="20%">
                  <b>Tanggal Lahir Pasien / Umur</b>
                </td>
                <td width="2%">:</td>
                <td width="28%">
                    @if ($pasien)
                        <span>{{ $tanggal_lahir_pasien }}, {{ $umur_pasien }} tahun</span>
                    @endif
                </td>
              </tr>--}}
              <tr>
                <td width="20%">
                  <b>Umur</b>
                </td>
                <td width="2%">:</td>
                <td width="28%">
                    @if ($pasien)
                        <span>{{ $umur_pasien }}</span>
                    @endif
                </td>

                <td style="min-width:500px"></td>
                <td width=20%>
                    <b>Dokter Pengirim</b>
                </td>
                <td width="2%">:</td>
                <td width="28%">
                    @if ($register && $register['nama_dokter'])
                        {{ $register['nama_dokter'] }}
                    @else
                        {{ '-' }}
                    @endif
                </td>

              </tr>
              <tr>

                <td width="20%">
                  <b>Jenis Kelamin</b>
                </td>
                <td width="2%">:</td>
                <td width="28%">
                    @if ($pasien && $pasien['jenis_kelamin'] == 'L')
                        <span>Laki-laki</span>
                    @endif
                    @if ($pasien && $pasien['jenis_kelamin'] == 'P')
                        <span>Perempuan</span>
                    @endif
                </td>

                <td style="min-width:500px"></td>
                <td width=20%>
                    <b>Instansi</b>
                </td>
                <td width="2%">:</td>
                <td width="28%">

                    @if ($register && $register['jenis_registrasi'] === 'mandiri')
                        {{ 'Labkesda Jawa Barat' }}
                        {{-- {{ $sampel->labPCR ? $sampel->labPCR['nama'] : '-' }} --}}
                    @endif

                    @if ($register && $register['jenis_registrasi'] === 'rujukan' && $register->fasyankes)
                        {{ $register->fasyankes ? $register->fasyankes['nama'] : '' }}
                    @endif

                    @if ($register && $register['jenis_registrasi'] === 'rujukan' && !$register->fasyankes)
                        {{ $register['fasyankes_pengirim'] }}
                    @endif

                    {{-- @if ($register && $register['rs_kunjungan'])
                        {{ $register['rs_kunjungan'] }}
                    @else
                        {{ '-' }}
                    @endif --}}
                </td>

              </tr>
              <tr>
                <td width="20%">
                  <b>Alamat</b>
                </td>
                <td width="2%">:</td>
                <td width="28%">
                    @if ($pasien && $pasien['alamat_lengkap'])
                        <span>{{ $pasien['alamat_lengkap'] }}</span>
                    @endif
                </td>

                <td style="min-width:500px"></td>
                <td></td>
                <td></td>
                <td></td>

              </tr>

              {{-- @if ($last_pemeriksaan_sampel)
                <tr>
                    <td colspan="3" style="padding-top: 20px"></td>
                </tr>
                <tr>
                    <td width="20%">
                    <b>Nomor Sampel</b>
                    </td>
                    <td width="2%">:</td>
                    <td width="28%">
                    <span>{{$sampel['nomor_sampel']}}</span>
                    </td>
                </tr>
                <tr>
                    <td width="20%">
                    <b>Tanggal Penerimaan Sampel</b>
                    </td>
                    <td width="2%">:</td>
                    <td width="28%">
                        {{ $last_pemeriksaan_sampel['tanggal_penerimaan_sampel'] }}
                    </td>
                </tr>
                <tr>
                    <td width="20%">
                    <b>Metode Pemeriksaan</b>
                    </td>
                    <td width="2%">:</td>
                    <td width="28%">
                        rRT-PCR-{{ $last_pemeriksaan_sampel['nama_kit_pemeriksaan'] }}
                    </td>
                </tr>
                <tr>
                    <td width="20%">
                    <b>Jenis Sampel</b>
                    </td>
                    <td width="2%">:</td>
                    <td width="28%">
                        {{ $sampel['jenis_sampel_nama'] }}
                    </td>
                </tr>

                <tr>
                    <td colspan="3" style="padding-top: 20px"></td>
                </tr>

                <tr>
                    <td width="20%">
                        <b>Hasil Pemeriksaan</b>
                    </td>
                    <td width="2%">:</td>
                    <td width="60%">
                        <span><b>{{$last_pemeriksaan_sampel['kesimpulan_pemeriksaan']}}</b></span>
                    </td>
                </tr>

              @endif --}}

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
        
        <table id="tabel-ct-scan" style="width:100%; margin-top: 2%">
            <thead>
                <tr>
                    <th width="30%"><b>PEMERIKSAAN</b></th>
                    <th width="30%"><b>NOMOR SAMPEL</b></th>
                    <th width="30%"><b>CT VALUE</b></th>
                    <th width="30%"><b>NILAI RUJUKAN</b></th>
                    <th width="30%"><b>HASIL</b></th>
                    <th width="30%"><b>METODE</b></th>
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

                    <tr>
                        <td>Covid-19</td>
                        <td>{{$sampel['nomor_sampel']}}</td>
                        <td>
                            {{-- {{$last_pemeriksaan_sampel['hasil_deteksi_terkecil']['target_gen']}}  --}}
                            {{-- {{$last_pemeriksaan_sampel['hasil_deteksi_terkecil']['ct_value']}}  --}}
                            @if ($last_pemeriksaan_sampel['hasil_deteksi_terkecil'] && ($last_pemeriksaan_sampel['hasil_deteksi_terkecil']['target_gen'] !== 'IC'))
                                {{ round($last_pemeriksaan_sampel['hasil_deteksi_terkecil']['ct_value'], 2) }}
                            @else
                                {{  '-' }}
                            @endif
                            {{-- {{ number_format((float)$last_pemeriksaan_sampel['hasil_deteksi_terkecil']['ct_value'], 2, ',','.') }} --}}
                        </td>
                        <td>
                            <span>negatif CT >= 40</span>
                            <span>positif CT < 40</span>
                        </td>
                        <td>
                            @if ($last_pemeriksaan_sampel['kesimpulan_pemeriksaan'])
                                <b>{{ ucfirst($last_pemeriksaan_sampel['kesimpulan_pemeriksaan']) }}</b>
                            @endif
                        </td>
                        <td>
                            rRT-PCR-{{ $last_pemeriksaan_sampel['nama_kit_pemeriksaan'] }}
                        </td>
                    </tr>

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
                    <td>
                        Bandung, {{ $tanggal_validasi ?? ' -' }}
                    </td>
                </tr>
                <tr>
                    <td width="30%"></td>
                    <td width="25%"></td>
                    <td>PENANGGUNG JAWAB LAB COVID-19</td>
                </tr>
                <tr>
                    <td colspan="3" style="padding-top: 65px;"></td>
                </tr>
                <tr>
                    <td width="30%"></td>
                    <td width="25%"></td>
                    <td>
                        <span style="text-decoration: underline">{{ $validator ? $validator->nama : ' -' }}</span>
                    </td>
                </tr>
                <tr>
                    <td width="30%"></td>
                    <td width="25%"></td>
                    <td>
                        NIP. {{ $validator ? $validator->nip : ' -' }}
                    </td>
                </tr>
            </tbody>
        </table>
    @endif
    
</body>
</html>