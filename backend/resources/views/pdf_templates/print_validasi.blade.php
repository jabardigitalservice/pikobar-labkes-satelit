<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Validasi</title>
</head>
<body marginwidth="0" marginheight="0">

    <div style="margin-bottom: 20px; margin-top: 20px;">
        <img src="{{ $kop_surat }}" width="100%" alt="" srcset="">
    </div>

    <h3>Validasi Pemeriksaan PCR</h3>

    <table>
        <tbody>
            <tr>
                <td width="47%">
                  <b>Nomor Registrasi</b>
                </td>
                <td width="10%">:</td>
                <td width="60%">
                  <span>{{$sampel['nomor_register']}}</span>
                </td>
            </tr>
            <tr>
                <td width="30%">
                  <b>Pasien</b>
                </td>
                <td width="10%">:</td>
                <td width="60%">
                    @if ($pasien)
                        <span>{{$pasien['nama_depan']}} {{$pasien['nama_belakang']}}</span>
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
                        <span>{{$pasien['no_ktp'] }}</span>
                    @endif
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Tanggal Lahir Pasien</b>
                </td>
                <td width="10%">:</td>
                <td width="60%">
                    @if ($pasien)
                        <span>{{$pasien['tanggal_lahir'] }}</span>
                    @endif
                </td>
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
                  <b>Lab Penerima</b>
                </td>
                <td width="10%">:</td>
                <td width="60%">
                  <span>{{$sampel['lab_pcr_nama']}}</span>
                </td>
              </tr>
        </tbody>
    </table>

    @if ($pemeriksaan_sampel && count($pemeriksaan_sampel) > 0)
        @foreach ($pemeriksaan_sampel as $key=> $hasil)
            
            <table style="margin-top: 3%">
                <tbody>
                    <tr>
                        <td colspan="3"><b>HASIL PEMERIKSAAN SAMPEL ({{ $key+1 }})</b></td>
                    </tr>
                    <tr>
                        <td width="47%">
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
    @endif
    
</body>
</html>