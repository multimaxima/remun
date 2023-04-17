@extends('layouts.content')
@section('title','Beranda')

@section('style')
  <link rel="stylesheet" type="text/css" href="https://resource.multimaxima.com/rsud_genteng/apexcharts/dist/apexcharts.css">
@endsection

@section('content')
<div class="navbar">
  <div class="navbar-inner">    
    <div class="row-fluid">
      <div class="span12">
        <label style="font-size: 14px; margin-top: 10px;">
          Selamat {{ $salam }}
          @if(Auth::user()->id_kelamin == 1)
            Bapak
          @else
            Ibu
          @endif
          <b>
          @if(Auth::user()->gelar_depan)
            {{ Auth::user()->gelar_depan }}
          @endif

          @if(Auth::user()->gelar_belakang)
            {{ strtoupper(Auth::user()->nama) }}, {{ Auth::user()->gelar_belakang }}
          @else
            {{ strtoupper(Auth::user()->nama) }}
          @endif
          </b>
          @if(Auth::user()->cuti == 1)
          , mohon maaf saat ini Anda tidak dapat melakukan entri data karena status Anda sedang <i>cuti</i>.
          @endif
        </label>
      </div>
    </div>
  </div>
</div>

<div class="row-fluid">
  <div class="span8" style="background-color: white; padding-right: 15px;">
    <div id="chart"></div>
  </div>
  <div class="span4" style="background-color: white; padding-right: 15px;">
    <div id="chart3"></div>
  </div>
</div>
    
<div class="row-fluid" style="margin-top: 10px;">
  <div class="span3" style="background-color: white; height: 170px;">
    <div id="chart1"></div>
  </div>
  <div class="span3" style="background-color: white; height: 170px;">
    <div id="chart2"></div>
  </div>
  <div class="span3" style="background-color: white; height: 170px; padding: 10px;">
    <label style="font-size: 13px; font-weight: bold;">
      PASIEN PER RUANG {{ strtoupper($bulan->bulan) }}
    </label>
    <table width="100%" class="table table-striped" style="font-size: 11px;">
      @foreach($j_pasien as $j_pas)
      <tr>
        <td style="padding: 2px 5px;">{{ $j_pas->ruang }}</td>
        <td style="text-align: right; padding: 2px 5px;">{{ number_format($j_pas->jml,0) }}</td>
      </tr>
      @endforeach
    </table>
  </div>
  <div class="span3" style="background-color: white; height: 170px; padding: 10px;">
    <label style="font-size: 13px; font-weight: bold;">
      TINDAKAN PER DPJP {{ strtoupper($bulan->bulan) }}
    </label>
    <table width="100%" class="table table-striped" style="font-size: 11px;">
      @foreach($dpjp as $dok)
      <tr>
        <td style="padding: 2px 5px;">{{ $dok->nama }}</td>
        <td style="text-align: right; padding: 2px 5px;">{{ number_format($dok->jml,0) }}</td>
      </tr>
      @endforeach
    </table>
  </div>
</div>-->
@endsection


@section('script')
  <script src="https://resource.multimaxima.com/rsud_genteng/apexcharts/dist/apexcharts.js"></script>

  <!--
  <script type="text/javascript">
    window.onload=function() {
      if(window.screen.height < 900 || window.screen.width < 1280) {
        alert('PERHATIAN !!!\nUntuk mendapatkan tampilan terbaik sangat disarankan untuk menggunakan resolusi layar minimal 1280 x 1024.');
      }
    };
  </script>
  -->

  <script type="text/javascript">
    var t_pas_um  = [];
    var pas_um  = [];
    var pas_jkn = [];
    var pas_jam = [];
    var pas_cov = [];
    var pas_spm = [];
    var pas_jas = [];
    
    var t_passum = [];

    var p_passum = [];
    var p_pasjkn = [];
    var p_pasjam = [];
    var p_pascov = [];
    var p_passpm = [];
    var p_pasjas = [];

    @foreach($pas_bln as $pasbln)
      t_pas_um.push({!! json_encode($pasbln->bulan) !!});
      pas_um.push({{ $pasbln->umum }});
      pas_jkn.push({{ $pasbln->jkn }});
      pas_jam.push({{ $pasbln->jampersal }});
      pas_cov.push({{ $pasbln->covid }});
      pas_spm.push({{ $pasbln->spm }});
      pas_jas.push({{ $pasbln->jasa_raharja }});      
    @endforeach

    @foreach($pasien as $pp_pasien)
      t_passum.push({{ $pp_pasien->tahun }});
      p_passum.push({{ $pp_pasien->umum }});
      p_pasjkn.push({{ $pp_pasien->jkn }});
      p_pasjam.push({{ $pp_pasien->jampersal }});
      p_pascov.push({{ $pp_pasien->covid }});
      p_passpm.push({{ $pp_pasien->spm }});
      p_pasjas.push({{ $pp_pasien->jasa_raharja }});
    @endforeach    

    var options = {
      series: [{
        name: "Umum",
        data: pas_um
      },
      {
        name: "BPJS",
        data: pas_jkn
      },
      {
        name: "Jampersal",
        data: pas_jam
      },
      {
        name: "Covid-19",
        data: pas_cov
      },
      {
        name: "Jamkesmin (SPM)",
        data: pas_spm
      },
      {
        name: "Jasa Raharja",
        data: pas_jas
      }
      ],
      chart: {
        height: 250,
        type: 'line',
        zoom: {
          enabled: false
        }
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'straight'
      },
      grid: {
        row: {
          colors: ['#f3f3f3', 'transparent'],
          opacity: 0.5
        },
      },
      xaxis: {
        categories: t_pas_um,
      }
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();

    var options = {
      series: [{{ $c_pasien->umum }}, {{ $c_pasien->jkn }}, {{ $c_pasien->jampersal }}, {{ $c_pasien->covid }}, {{ $c_pasien->spm }}, {{ $c_pasien->jasa_raharja }}],
      chart: {
        type: 'pie',
      },
      labels: ['Umum', 'BPJS', 'Jampersal','Covid-19','SPM','J.Raharja'],
      responsive: [{
        breakpoint: 480,
        options: {
          chart: {
            width: 200,
          },
          legend: {
            position: 'bottom'
          }
        }
      }]
    };

    var chart = new ApexCharts(document.querySelector("#chart1"), options);
    chart.render();

    var options = {
      series: [{{ $jenis->rajal }}, {{ $jenis->ranap }}],
      chart: {
        type: 'donut',
      },
      labels: ['R. Jalan', 'R. Inap'],
      responsive: [{
        breakpoint: 480,
        options: {
          chart: {
            width: 200
          },
          legend: {
            position: 'bottom'
          }
        }
      }]
    };

    var chart = new ApexCharts(document.querySelector("#chart2"), options);
    chart.render();

    var options = {
      series: [{
        name: 'Umum',
        data: p_passum
      }, {
        name: 'BPJS',
        data: p_pasjkn
      }, {
        name: 'Jampersal',
        data: p_pasjam
      }, {
        name: 'Covid-19',
        data: p_pascov
      }, {
        name: 'Jamkesmin (SPM)',
        data: p_passpm
      }, {
        name: 'Jasa Raharja',
        data: p_pasjas
      }],
      chart: {
        type: 'bar',
        height: 250
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '55%',
          endingShape: 'rounded'
        },
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
      },
      xaxis: {
        categories: t_passum,
      },
      fill: {
        opacity: 1
      },        
    };

    var chart = new ApexCharts(document.querySelector("#chart3"), options);
    chart.render();
  </script>
@endsection