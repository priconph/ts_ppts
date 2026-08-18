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

  @section('title', 'Dashboard')

  @section('content_page')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li> -->
              <li class="breadcrumb-item active">Dashboard</li>
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
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              
              <!-- Start Page Content -->
              <div class="card-body">
                <div class="row">
                  
                  <div class="col-12">
                    <h1>Welcome to TS - Production Product Traceability System</h1> 
                  </div>
                  <div class="col-12">
                    <h5>For concerns/issues, please contact ISS at local numbers 205, 206, or 208. </h5> 
                  </div>
                  <div class="col-12">
                    <h5>Or you may send us e-mail at servicerequest@pricon.ph. Thank you! </h5> 
                  </div>
                  <!-- <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                      <div class="inner">
                        <h3 id="h3TotalNoOfMachines">0</h3>
                        <p>Visual Inspection</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-microscope"></i>
                      </div>
                      <a href="{{ route('prod_runcard_new') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                      </a>
                    </div>
                  </div>
                  <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                      <div class="inner">
                        <h3 id="h3TotalNoOfMachines">0</h3>
                        <p>Defect Escalation</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                      </div>
                      <a href="{{ route('defect_escalation') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                      </a>
                    </div>
                  </div>
                  <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                      <div class="inner">
                        <h3 id="h3TotalNoOfMachines">0</h3>
                        <p>OQC Lot Application</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-microscope"></i>
                      </div>
                      <a href="{{ route('oqclotapplication_new') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                      </a>
                    </div>
                  </div>
                  <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                      <div class="inner">
                        <h3 id="h3TotalNoOfMachines">0</h3>
                        <p>Overall Inspection</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-microscope"></i>
                      </div>
                      <a href="{{ route('overallinspection') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                      </a>
                    </div>
                  </div>
                  <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                      <div class="inner">
                        <h3 id="h3TotalNoOfMachines">0</h3>
                        <p>OQC Inspection Result</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-microscope"></i>
                      </div>
                      <a href="{{ route('oqcvir_pts') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                      </a>
                    </div>
                  </div>
                  <div class="col-lg-9 col-6">
                  </div>
                  <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                      <div class="inner">
                        <h3 id="h3TotalNoOfMachines">0</h3>
                        <p>Packing Confirmation</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-box-open"></i>
                      </div>
                      <a href="{{ route('packing_pts') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                      </a>
                    </div>
                  </div>
                  <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                      <div class="inner">
                        <h3 id="h3TotalNoOfMachines">0</h3>
                        <p>Preliminary Packing</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-box-open"></i>
                      </div>
                      <a href="{{ route('packinginspection_pts') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                      </a>
                    </div>
                  </div>
                  <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                      <div class="inner">
                        <h3 id="h3TotalNoOfMachines">0</h3>
                        <p>Supervisor Validation</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-box-open"></i>
                      </div>
                      <a href="{{ route('supervisorvalidation_pts') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                      </a>
                    </div>
                  </div>
                  <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                      <div class="inner">
                        <h3 id="h3TotalNoOfMachines">0</h3>
                        <p>Final QC Packing Inspection</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-box-open"></i>
                      </div>
                      <a href="{{ route('finalpackinginspection_pts') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                      </a>
                    </div>
                  </div> -->
                  
                 
                </div>
                <!-- /.row -->

              </div>
              <!-- !-- End Page Content -->
            </div>
            <!-- /.card -->
          </div>
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
    $(document).ready(function () {
      bsCustomFileInput.init();
      CountUserByStatForDashboard(1);
    });
  </script>
  @endsection
@endauth