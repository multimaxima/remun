@extends('mobile.layouts.content')

@section('style')
  <link rel="stylesheet" type="text/css" href="https://resource.multimaxima.com/rsud_genteng/apexcharts/dist/apexcharts.css">
@endsection

@section('bawah')
  <li><a href="{{ route('jasa_remun') }}"><i class="fa fa-heart"></i>Remunerasi</a></li>
  <li><a href="{{ route('informasi_software') }}"><i class="fa fa-info"></i>Informasi</a></li>
@endsection

@section('content')
<div class="page-content-wrapper">
  <div class="container-fluid">    
    <div>      
      <label style="font-size: 3vw; margin-top: 1vh;">Selamat {{ $salam }}        
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
      </label>
      <div class="hero-slides owl-carousel" style="margin-top: 1vh;">
        <div class="single-hero-slide" style="background-color: white; height: 55vh;">
          <div class="slide-content h-100 align-items-center" style="padding: 4vh 4vw;">
            <div id="chart"></div>          
          </div>
        </div>
          
        <div class="single-hero-slide" style="background-color: white; height: 55vh;">
          <div class="slide-content h-100 align-items-center" style="padding: 4vh 4vw;">
            <div id="chart1"></div>
          </div>
        </div>

        <div class="single-hero-slide" style="background-color: white; height: 55vh;">
          <div class="slide-content h-100 align-items-center" style="padding: 4vh 4vw;">            
            <table width="100%" class="table table-striped" style="font-size: 3vw;">
              <tr>
                <td colspan="2" style="font-size: 3.5vw; font-weight: bold; text-align: center; margin-bottom: 2vh;">
                  PASIEN PER RUANG<br>BULAN {{ strtoupper($bulan->bulan) }}
                </td>
              </tr>
              @foreach($j_pasien as $j_pas)
              <tr>
                <td>{{ $j_pas->ruang }}</td>
                <td style="text-align: right;">{{ number_format($j_pas->jml,0) }}</td>
              </tr>
              @endforeach
            </table>
          </div>
        </div>

        <div class="single-hero-slide" style="background-color: white; height: 55vh;">
          <div class="slide-content h-100 align-items-center" style="padding: 4vh 4vw;">            
            <table width="100%" class="table table-striped" style="font-size: 3vw;">
              <tr>
                <td colspan="2" style="font-size: 3.5vw; font-weight: bold; text-align: center; margin-bottom: 2vh;">
                  TINDAKAN PER DPJP<br>BULAN {{ strtoupper($bulan->bulan) }}
                </td>
              </tr>
              @foreach($dpjp as $dok)
              <tr>
                <td>{{ $dok->nama }}</td>
                <td style="text-align: right;">{{ number_format($dok->jml,0) }}</td>
              </tr>
              @endforeach
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="product-catagories-wrapper py-3" style="margin-top: -8vh;">
  <div class="container">
    <div class="product-catagory-wrap">
      <div class="row g-3">
        @if(Auth::user()->id_akses == 1)
        <div class="col-4">
          <div class="card catagory-card">
            <div class="card-body" style="padding-right: 0; padding-left: 0;">
              <a class="text-danger" href="#">
                <i class="fa fa-check-square-o" style="color: #a6f144;"></i>
                <span>Data Claim</span>
              </a>
            </div>
          </div>
        </div>
        
        <div class="col-4">
          <div class="card catagory-card">
            <div class="card-body" style="padding-right: 0; padding-left: 0;">
              <a class="text-danger" href="#">
                <i class="fa fa-bookmark-o" style="color: #cdb9b9;"></i>
                <span>Data Remun</span>
              </a>
            </div>
          </div>
        </div>        
        @endif

        @if(Auth::user()->id_akses == 2)
          @if(Auth::user()->id_ruang <> 5 && Auth::user()->id_ruang <> 30 && Auth::user()->id_ruang <> 31 && Auth::user()->id_ruang <> 33 && Auth::user()->id_ruang <> 29 && Auth::user()->id_ruang <> 46 && Auth::user()->id_ruang <> 47 && Auth::user()->id_ruang <> 62 && Auth::user()->id_ruang <> 72 && Auth::user()->id_ruang <> 52)
            <div class="col-4">
              <div class="card catagory-card">
                <div class="card-body" style="padding-right: 0; padding-left: 0;">
                  <a class="text-danger" href="{{ route('pasien_ruang') }}">
                    <i class="fa fa-check-square-o" style="color: #a6f144;"></i>
                    <span>Layanan</span>
                  </a>
                </div>
              </div>
            </div>
          
            <div class="col-4">
              <div class="card catagory-card">
                <div class="card-body" style="padding-right: 0; padding-left: 0;">
                  <a class="text-danger" href="{{ route('pasien_ruang_data') }}">
                    <i class="fa fa-bookmark-o" style="color: #cdb9b9;"></i>
                    <span>Data Layanan</span>
                  </a>
                </div>
              </div>
            </div>        
          @endif

          @if(Auth::user()->id_ruang == 5)
            <div class="col-4">
              <div class="card catagory-card">
                <div class="card-body" style="padding-right: 0; padding-left: 0;">
                  <a class="text-danger" href="{{ route('pasien_operasi') }}">
                    <i class="fa fa-check-square-o" style="color: #a6f144;"></i>
                    <span>Layanan</span>
                  </a>
                </div>
              </div>
            </div>
          
            <div class="col-4">
              <div class="card catagory-card">
                <div class="card-body" style="padding-right: 0; padding-left: 0;">
                  <a class="text-danger" href="{{ route('pasien_operasi_transaksi') }}">
                    <i class="fa fa-bookmark-o" style="color: #cdb9b9;"></i>
                    <span>Data Layanan</span>
                  </a>
                </div>
              </div>
            </div>        
          @endif
  
          @if(Auth::user()->id_ruang == 30)
            <div class="col-4">
              <div class="card catagory-card">
                <div class="card-body" style="padding-right: 0; padding-left: 0;">
                  <a class="text-danger" href="{{ route('pasien_apotik') }}">
                    <i class="fa fa-check-square-o" style="color: #a6f144;"></i>
                    <span>Layanan</span>
                  </a>
                </div>
              </div>
            </div>
          
            <div class="col-4">
              <div class="card catagory-card">
                <div class="card-body" style="padding-right: 0; padding-left: 0;">
                  <a class="text-danger" href="{{ route('pasien_apotik_transaksi') }}">
                    <i class="fa fa-bookmark-o" style="color: #cdb9b9;"></i>
                    <span>Data Layanan</span>
                  </a>
                </div>
              </div>
            </div>        
          @endif
  
          @if(Auth::user()->id_ruang == 31)
            <div class="col-4">
              <div class="card catagory-card">
                <div class="card-body" style="padding-right: 0; padding-left: 0;">
                  <a class="text-danger" href="{{ route('pasien_gizi') }}">
                    <i class="fa fa-check-square-o" style="color: #a6f144;"></i>
                    <span>Layanan</span>
                  </a>
                </div>
              </div>
            </div>
          
            <div class="col-4">
              <div class="card catagory-card">
                <div class="card-body" style="padding-right: 0; padding-left: 0;">
                  <a class="text-danger" href="{{ route('pasien_gizi_transaksi') }}">
                    <i class="fa fa-bookmark-o" style="color: #cdb9b9;"></i>
                    <span>Data Layanan</span>
                  </a>
                </div>
              </div>
            </div>        
          @endif
  
          @if(Auth::user()->id_ruang == 33)
            <div class="col-4">
              <div class="card catagory-card">
                <div class="card-body" style="padding-right: 0; padding-left: 0;">
                  <a class="text-danger" href="{{ route('pasien_upp') }}">
                    <i class="fa fa-check-square-o" style="color: #a6f144;"></i>
                    <span>Layanan</span>
                  </a>
                </div>
              </div>
            </div>
          
            <div class="col-4">
              <div class="card catagory-card">
                <div class="card-body" style="padding-right: 0; padding-left: 0;">
                  <a class="text-danger" href="{{ route('pasien_upp_data') }}">
                    <i class="fa fa-bookmark-o" style="color: #cdb9b9;"></i>
                    <span>Data Layanan</span>
                  </a>
                </div>
              </div>
            </div>                  
          @endif
  
          @if(Auth::user()->id_ruang == 29 || Auth::user()->id_ruang == 46 || Auth::user()->id_ruang == 47 || Auth::user()->id_ruang == 62 || Auth::user()->id_ruang == 72)
            <div class="col-4">
              <div class="card catagory-card">
                <div class="card-body" style="padding-right: 0; padding-left: 0;">
                  <a class="text-danger" href="{{ route('pasien_laborat') }}">
                    <i class="fa fa-check-square-o" style="color: #a6f144;"></i>
                    <span>Layanan</span>
                  </a>
                </div>
              </div>
            </div>
          
            <div class="col-4">
              <div class="card catagory-card">
                <div class="card-body" style="padding-right: 0; padding-left: 0;">
                  <a class="text-danger" href="{{ route('pasien_laborat_transaksi') }}">
                    <i class="fa fa-bookmark-o" style="color: #cdb9b9;"></i>
                    <span>Data Layanan</span>
                  </a>
                </div>
              </div>
            </div>                  
          @endif  
  
          @if(Auth::user()->id_ruang == 52)
            <div class="col-4">
              <div class="card catagory-card">
                <div class="card-body" style="padding-right: 0; padding-left: 0;">
                  <a class="text-danger" href="{{ route('pasien_laborat') }}">
                    <i class="fa fa-check-square-o" style="color: #a6f144;"></i>
                    <span>Layanan</span>
                  </a>
                </div>
              </div>
            </div>
          
            <div class="col-4">
              <div class="card catagory-card">
                <div class="card-body" style="padding-right: 0; padding-left: 0;">
                  <a class="text-danger" href="{{ route('pasien_jenasah_transaksi') }}">
                    <i class="fa fa-bookmark-o" style="color: #cdb9b9;"></i>
                    <span>Data Layanan</span>
                  </a>
                </div>
              </div>
            </div>
          @endif          
        @endif

        <div class="col-4">
          <div class="card catagory-card">
            <div class="card-body" style="padding-right: 0; padding-left: 0;">
              <a class="text-danger" href="{{ route('jasa_remun') }}">
                <i class="fa fa-address-book-o" style="color: #c29fc9;"></i>
                <span>Remunerasi</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script src="/apexcharts/dist/apexcharts.js"></script>

  <script type="text/javascript">
    var t_pas_um  = [];
    var pas_um  = [];
    var pas_jkn = [];
    var pas_jam = [];
    var pas_cov = [];
    var pas_spm = [];
    var pas_jas = [];
    
    @foreach($pas_bln as $pasbln)
      t_pas_um.push({!! json_encode($pasbln->bulan) !!});
      pas_um.push({{ $pasbln->umum }});
      pas_jkn.push({{ $pasbln->jkn }});
      pas_jam.push({{ $pasbln->jampersal }});
      pas_cov.push({{ $pasbln->covid }});
      pas_spm.push({{ $pasbln->spm }});
      pas_jas.push({{ $pasbln->jasa_raharja }});      
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
        name: "J.Persal",
        data: pas_jam
      },
      {
        name: "Covid",
        data: pas_cov
      },
      {
        name: "SPM",
        data: pas_spm
      },
      {
        name: "J.Raharja",
        data: pas_jas
      }
      ],
      chart: {
        height: 300,
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
            width: 325,
          },
          legend: {
            position: 'bottom'
          }
        }
      }]
    };

    var chart = new ApexCharts(document.querySelector("#chart1"), options);
    chart.render();    
  </script>
@endsection