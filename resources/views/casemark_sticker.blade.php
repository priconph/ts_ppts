<!-- @php $layout = 'layouts.super_user_layout'; @endphp -->
@auth
  @php
    if(Auth::user()->user_level_id == 1){
      $layout = 'layouts.super_user_layout';
    }
    else if(Auth::user()->user_level_id == 2){
      $layout = 'layouts.admin_layout';
    }
    else if(Auth::user()->user_level_id == 4){
      $layout = 'layouts.fvi_layout';
    }
    else if(Auth::user()->user_level_id == 5){
      $layout = 'layouts.oqc_layout';
    }
    else if(Auth::user()->user_level_id == 6){
      $layout = 'layouts.packing_layout';
    }
    else if(Auth::user()->user_level_id == 7){
      $layout = 'layouts.clerk_layout';
    }
  @endphp
@endauth

@auth
  @extends($layout)

@section('title', 'Casemark Printing')

@section('content_page')

<style type="text/css">
    .hidden_scanner_input{
      position: absolute;
      opacity: 0;
    }
    textarea{
      resize: none;
    }
    /*#mdl_edit_material_details>div{*/
      /*width: 2000px!important;*/
      /*min-width: 1400px!important;*/
    /*}*/

    .modal-xl-custom{
      width: 95%!important;
      min-width: 90%!important;
    }
  </style>

  <!-- Content Header (Page header) -->
  <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>F3 Casemark Printing</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Casemark</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>


  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- left column -->

            <!-- Start Page Content -->
                <!-- <iframe src="http://rapid/pats_ts_casemark/" height="650" width="1700"></iframe> -->
                <iframe style="border: none;" src="http://rapid/pats_ts_casemark_f3/" no-border height="850" width="1900"></iframe>
            <!-- !-- End Page Content -->

      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->


@endsection

@section('js_content')
<script type="text/javascript">
  $(document).ready(function(){
    setTimeout(function() {
      $('.nav-link').find('.fa-bars').closest('a').click();
    }, 100);
  });
</script>
@endsection
@endauth
