<template>
  <div>
    <div class="row">
      <div class="col-md-5 p-0">
        <Ibox title="Hasil Pemeriksaan">
          <ChartHasilPemeriksaan :pieId="'HasilPemeriksaan'" />
        </Ibox>
      </div>
      <div class="col-md-7" style="padding-right: 0">
        <Ibox title="Trendline Hasil Pemeriksaan">
          <ChartPerbandingan :barId="'HasilPemeriksaan'" />
        </Ibox>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 p-0">
        <Ibox title="Hasil Pemeriksaan Terbanyak">
          <ChartKota :barId="'HasilPemeriksaanKota'" />
        </Ibox>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 p-0">
        <Ibox title="Lab Pengirim">
          <ChartLab :barId="'LabPengirim'" />
        </Ibox>
      </div>
    </div>
  </div>
</template>

<script>
  import ChartHasilPemeriksaan from './ChartHasilPemeriksaan'
  import ChartPerbandingan from './ChartPerbandingan'
  import ChartKota from './ChartKota'
  import ChartLab from './ChartLab'

  export default {
    name: "charts",
    components: {
      ChartHasilPemeriksaan,
      ChartPerbandingan,
      ChartKota,
      ChartLab
    },
    data() {
      return {
        loading: true,
        data: {
          status: {},
          labs: [],

        },
      };
    },
  }
</script>