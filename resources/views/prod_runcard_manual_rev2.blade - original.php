@php $layout = 'layouts.super_user_layout'; @endphp
@auth
  @php
    if(Auth::user()->user_level_id == 1){
      $layout = 'layouts.super_user_layout';
    }
    else if(Auth::user()->user_level_id == 2){
      $layout = 'layouts.admin_layout';
    }
    else if(Auth::user()->user_level_id == 3){
      $layout = 'layouts.user_layout';
    }
  @endphp
@endauth

@auth
  @extends($layout)

  @section('title', 'FVI')

  @section('content_page')
  <style type="text/css">
    .hidden_scanner_input{
      position: fixed;
      /*bottom: 0;
      left: 0;*/
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
      width: 98%!important;
      min-width: 95%!important;
    }
  </style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Final Visual</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item">Final Visual</li>
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
              <div class="card-header">
                <h3 class="card-title">1. Search PO Number</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                <div class="row">
                  <!-- <div class="col-sm-3"> -->
                    <!-- <label>Scan PO Code</label> -->
                    <!-- <br> -->
                    <!-- <div class="input-group"> -->
                      <!-- <div class="input-group-prepend">
                        <span class="input-group-text btnSearchPoNo" data-toggle="modal"><i class="fa fa-search"></i></span>
                      </div>
                      <input type="text" class="form-control btnSearchPoNo" id="txt_search_po_number" placeholder="Scan PO Code" readonly="true"> -->
                      <!-- <button type="button" class="btn btn-success btnSearchPoNo" title="Scan PO Code"><i class="fa fa-qrcode"></i></button>
                    </div> -->
                  <!-- </div>
                  <div class="col-sm-1">
                  </div> -->

                 <div class="col-sm-3">
                    <label>PO Number</label>
                    <div class="input-group">
<!--                       <div class="input-group-prepend">
                        <button type="button" class="btn btn-primary btnSearchPoNo" title="Scan PO Code"><i class="fa fa-qrcode"></i></button>
                      </div>
 -->                      <input type="text" class="form-control" id="txt_po_number_lbl">
                    </div>
                  </div>

                  <div class="col-sm-3">
                    <label>Device Name</label>
                      <input type="text" class="form-control" id="txt_device_name_lbl" readonly="">
                  </div>
                  <div class="col-sm-2">
                    <label>Device Code</label>
                      <input type="text" class="form-control" id="txt_device_code_lbl" readonly="">
                  </div>
                  <div class="col-sm-1">
                    <label>PO Qty</label>
                      <input type="text" class="form-control" id="txt_po_qty_lbl" readonly="">
                  </div>
                  <div class="col-sm-2">
                    <!-- <button class="btn btn-primary btn-sm" id="btn_download"><i class="fa fa-file"></i> User Manual</button> -->
                    <!-- <button class="btn btn-primary btn-sm" id="btnPrintPO"><i class="fa fa-plus"></i> Print PO</button> -->
                    <!-- <button class="btn btn-primary btn-sm" id="btnReference" disabled><i class="fa fa-file-alt"></i> Reference Check</button> -->
                  </div>

                  <!-- A Drawing -->
                  <div class="col-sm-2" hidden>
                    <input type="text" class="form-control" id="txt_Adrawing" readonly="" placeholder="A Drawing">
                    <input type="text" class="form-control" id="txt_Adrawing_rev" readonly="">
                    <input type="text" class="form-control" id="txt_Adrawing_fkid_document" readonly="">
                  </div>
                  <!-- Orig A Drawing -->
                  <div class="col-sm-2" hidden>
                    <input type="text" class="form-control" id="txt_orig_Adrawing" readonly="" placeholder="Orig A Drawing">
                    <input type="text" class="form-control" id="txt_orig_Adrawing_rev" readonly="">
                    <input type="text" class="form-control" id="txt_orig_Adrawing_fkid_document" readonly="">
                  </div>
                  <!-- G Drawing -->
                  <div class="col-sm-2" hidden>
                    <input type="text" class="form-control" id="txt_Gdrawing" readonly="" placeholder="G Drawing">
                    <input type="text" class="form-control" id="txt_Gdrawing_rev" readonly="">
                    <input type="text" class="form-control" id="txt_Gdrawing_fkid_document" readonly="">
                  </div>
                  <!-- J/R/DJ/KS/DC/GJ -->
                  <div class="col-sm-2" hidden>
                    <input type="text" class="form-control" id="txt_JRDJKSDCGJDoc" readonly="" placeholder="J/R/DJ/KS/DC/GJ">
                    <input type="text" class="form-control" id="txt_JRDJKSDCGJDoc_rev" readonly="">
                    <input type="text" class="form-control" id="txt_JRDJKSDCGJDoc_fkid_document" readonly="">
                  </div>
                  <!-- GP MD -->
                  <div class="col-sm-2" hidden>
                    <input type="text" class="form-control" id="txt_GPMD" readonly="" placeholder="GP MD">
                    <input type="text" class="form-control" id="txt_GPMD_rev" readonly="">
                    <input type="text" class="form-control" id="txt_GPMD_fkid_document" readonly="">
                  </div>

<!--                   <div class="col-sm-3">
                    <label>A Drawing</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-primary btnSearchADrawing" title="Click to check A Drawing" disabled><i class="fa fa-file-alt"></i></button>
                      </div>
                      <input type="text" class="form-control" id="txt_Adrawing" readonly="" placeholder="A Drawing">
                      <div class="col-sm-4"><input type="text" class="form-control" id="txt_Adrawing_rev" readonly=""></div>
                      <input type="hidden" class="form-control" id="txt_Adrawing_fkid_document" readonly="">
                    </div>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-primary btnSearchOrigADrawing" title="Click to check Orig A Drawing" disabled><i class="fa fa-file-alt"></i></button>
                      </div>
                      <input type="text" class="form-control" id="txt_orig_Adrawing" readonly="" placeholder="Orig A Drawing">
                      <div class="col-sm-4"><input type="text" class="form-control" id="txt_orig_Adrawing_rev" readonly=""></div>
                      <input type="hidden" class="form-control" id="txt_orig_Adrawing_fkid_document" readonly="">
                    </div>
                  </div>
 --><!--                   <div class="col-sm-1">
                    <label>Revision</label>
                      <input type="text" class="form-control" id="txt_Adrawing_rev" readonly="">
                  </div>
 -->              
<!--  <div class="col-sm-3">
                    <label>G Drawing</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-primary btnSearchGDrawing" title="Click to check A Drawing" disabled><i class="fa fa-file-alt"></i></button>
                      </div>
                      <input type="text" class="form-control" id="txt_Gdrawing" readonly="" placeholder="G Drawing">
                      <div class="col-sm-4"><input type="text" class="form-control" id="txt_Gdrawing_rev" readonly=""></div>
                      <input type="hidden" class="form-control" id="txt_Gdrawing_fkid_document" readonly="">
                    </div>
                  </div>
 --><!--                   <div class="col-sm-1">
                    <label>Revision</label>
                      <input type="text" class="form-control" id="txt_Gdrawing_rev" readonly="">
                  </div>
 -->            <!-- Other drawing -->
<!--                   <div class="col-sm-3 row_container" >
                    <label>Other Document</label> 
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-primary btnSearchWIDoc" title="Click to check WI Doc" disabled><i class="fa fa-file-alt"></i></button>
                      </div>
 -->                      <!-- <input type="text" class="form-control" id="txt_WIDoc" placeholder="WI"> -->

<!--                       <input type="text" class="form-control class_txt_WIDoc" list="list_txt_WIDoc" id="txt_WIDoc" name="txt_WIDoc" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" placeholder="WI">
                      <datalist class="class_dl_WIDoc" id="list_txt_WIDoc"></datalist>
 
                      <div class="col-sm-4"><input type="text" class="form-control" id="txt_WIDoc_rev" readonly=""></div>
                      <input type="hidden" class="form-control" id="txt_WIDoc_fkid_document" readonly="">
                    </div>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-primary btnSearchOGM_VIG_IGDoc" title="Click to check OGM/VIG/IG Doc" disabled><i class="fa fa-file-alt"></i></button>
                      </div>
 -->                      <!-- <input type="text" class="form-control" id="txt_OGM_VIG_IGDoc" placeholder="OGM/VIG/IG"> -->
                      
<!--                       <input type="text" class="form-control class_txt_OGM_VIG_IGDoc" list="list_txt_OGM_VIG_IGDoc" id="txt_OGM_VIG_IGDoc" name="txt_OGM_VIG_IGDoc" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" placeholder="OGM/VIG/IG">
                      <datalist class="class_dl_OGM_VIG_IGDoc" id="list_txt_OGM_VIG_IGDoc"></datalist>

                      <div class="col-sm-4"><input type="text" class="form-control" id="txt_OGM_VIG_IGDoc_rev" readonly=""></div>
                      <input type="hidden" class="form-control" id="txt_OGM_VIG_IGDoc_fkid_document" readonly="">
                    </div>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-primary btnSearchPPDoc" title="Click to check PP Doc" disabled><i class="fa fa-file-alt"></i></button>
                      </div>
 -->                      <!-- <input type="text" class="form-control" id="txt_PPDoc" placeholder="PP"> -->

<!--                       <input type="text" class="form-control class_txt_PPDoc" list="list_txt_PPDoc" id="txt_PPDoc" name="txt_PPDoc" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" placeholder="PP">
                      <datalist class="class_dl_PPDoc" id="list_txt_PPDoc"></datalist>

                      <div class="col-sm-4"><input type="text" class="form-control" id="txt_PPDoc_rev" readonly=""></div>
                      <input type="hidden" class="form-control" id="txt_PPDoc_fkid_document" readonly="">
                    </div>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-primary btnSearchUDDoc" title="Click to check UD Doc" disabled><i class="fa fa-file-alt"></i></button>
                      </div>
 -->                      <!-- <input type="text" class="form-control" id="txt_UDDoc" placeholder="UD"> -->

<!--                       <input type="text" class="form-control class_txt_UDDoc" list="list_txt_UDDoc" id="txt_UDDoc" name="txt_UDDoc" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" placeholder="UD">
                      <datalist class="class_dl_UDDoc" id="list_txt_UDDoc"></datalist>

                      <div class="col-sm-4"><input type="text" class="form-control" id="txt_UDDoc_rev" readonly=""></div>
                      <input type="hidden" class="form-control" id="txt_UDDoc_fkid_document" readonly="">
                    </div>
                  </div>
 --><!--                   <div class="col-sm-1">
                    <label>Revision</label>
                      <input type="text" class="form-control" id="txt_Odrawing_rev" readonly="">
                  </div>
 -->              
<!--                   <div class="col-sm-3">
                    <label>Packing Reference</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-primary btnSearchPM" title="Click to check PM Doc" disabled><i class="fa fa-file-alt"></i></button>
                      </div>
 -->                      <!-- <input type="text" class="form-control" id="txt_PMDoc" placeholder="PM"> -->

<!--                       <input type="text" class="form-control class_txt_PMDoc" list="list_txt_PMDoc" id="txt_PMDoc" name="txt_PMDoc" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" placeholder="PM">
                      <datalist class="class_dl_PMDoc" id="list_txt_PMDoc"></datalist>

                      <div class="col-sm-4"><input type="text" class="form-control" id="txt_PMDoc_rev" readonly=""></div>
                      <input type="hidden" class="form-control" id="txt_PMDoc_fkid_document" readonly="">
                    </div>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-primary btnSearchJRDJKSDCGJ" title="Click to check J/R/DJ/KS/DC/GJ Doc" disabled><i class="fa fa-file-alt"></i></button>
                      </div>
                      <input type="text" class="form-control" id="txt_JRDJKSDCGJDoc" readonly="" placeholder="J/R/DJ/KS/DC/GJ">
                      <div class="col-sm-4"><input type="text" class="form-control" id="txt_JRDJKSDCGJDoc_rev" readonly=""></div>
                      <input type="hidden" class="form-control" id="txt_JRDJKSDCGJDoc_fkid_document" readonly="">
                    </div>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-primary btnSearchGPMD" title="Click to check Generated Packing MD" disabled><i class="fa fa-file-alt"></i></button>
                      </div>
                      <input type="text" class="form-control" id="txt_GPMD" readonly="" placeholder="GP MD">
                      <div class="col-sm-4"><input type="text" class="form-control" id="txt_GPMD_rev" readonly=""></div>
                      <input type="hidden" class="form-control" id="txt_GPMD_fkid_document" readonly="">
                    </div>
                  </div>
 -->
<!--                   <div class="col-12">
                    <div class="float-right">
                      <button class="btn btn-primary btn-sm" id="btnAddDocRef"><i class="fa fa-plus"></i> Save Document Reference</utton>
                    </div>
                  </div>
 -->
<!--                   <div class="col-sm-1">
                    <label>Revision</label>
                      <input type="text" class="form-control" id="txt_packing_ref_rev" readonly="">
                  </div>
 -->              
<!--                   <div class="col-sm-2">
                  </div>
                  <div class="col-sm-2">
                    <label>Orig A Drawing</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-primary btnSearchOrigADrawing" title="Click to check Orig A Drawing" disabled><i class="fa fa-file-alt"></i></button>
                      </div>
                      <input type="text" class="form-control" id="txt_orig_Adrawing" readonly="">
                      <div class="col-sm-3"><input type="text" class="form-control" id="txt_orig_Adrawing_rev" readonly=""></div>
                      <input type="hidden" class="form-control" id="txt_orig_Adrawing_fkid_document" readonly="">
                    </div>

                  </div>
 -->
    <!--          <div class="col-sm-1">
                    <label>Revision</label>
                      <input type="text" class="form-control" id="txt_orig_Adrawing_rev" readonly="">
                  </div>
 -->                 <!--  <div class="col-sm-1">
                      <button type="button" class="btn btn-sm btn-success" id="btn_print_drawing">Print Drawing</button>
                  </div> -->

                </div>

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


    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">2. Document Reference Check / Visual Inspection</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    
                    <!-- <div class="row">
                     <div class="col-sm-2">
                        <label>Total # of OK</label>
                          <input type="text" class="form-control" id="txt_total_no_of_ok" readonly="">
                      </div>
                      <div class="col-sm-2">
                        <label>Total # of NG</label>
                          <div class="input-group mb-3">
                            <input type="text" class="form-control" id="txt_total_no_of_ng" readonly="">
                          </div>
                      </div>
                    </div> -->

                    <div class="float-right">
                      <!-- <button class="btn btn-success btn-sm" id="btnOverallInspection" disabled="true"><i class="fa fa-check-circle"></i> Overall Inspection <span class="spanOICount"></span></button>  -->
                      <button class="btn btn-primary btn-sm" id="btnShowAddProdRuncard"><i class="fa fa-plus"></i> Add Visual Inspection</button>
                    </div>
                    <br><br>
                    <div class="table-responsive">
                      <table class="table table-sm table-bordered table-hover" id="tbl_prod_runcard" style="min-width: 900px;">
                        <thead>
                          <tr class="bg-light">
                            <th>Action</th>
                            <th>Status</th>
                            <th>Lot #</th>
                            <!-- <th>Pair #</th> -->
                            <th>Created At</th>
                            <th class="bg-info">Total Lot Qty</th>
                            <th class="bg-info">NG</th>
                            <th>Remarks</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <!-- !-- End Page Content -->

            </div>
            <!-- /.card -->
          </div>

          <!-- right column --> 
          <div class="col-md-5" hidden>
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Stations</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <div class="table-responsive">
                      <table class="table table-sm table-bordered table-hover" id="tbl_stationsx" style="min-width: 600px;">
                        <thead>
                          <tr class="bg-light">
                            <th></th>
                            <th></th>
                            <th>Station</th>
                            <th>Date</th>
                            <th>Operator</th>
                            <th>Input</th>
                            <th>Output</th>
                            <th>NG QTY</th>
                            <th>MOD</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

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


    <!-- Modal -->
    <div class="modal fade" id="modalRuncardDetails" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-xl modal-xl-custom modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fa fa-info-circle text-info"></i> Visual Inspection Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- <div style="float: center;">
              <h5>Runcard # <span id="spanRuncardNo">---</span></h5>
            </div> -->
            <div class="row">
              <div class="col-sm-4 border px-4">
                <form id="frm_edit_material_details" method="post">

                  <div class="row">
                    <div class="col pt-3">
                      <button type="button" id="btn_edit_material_details_primary" class="btn btn-sm btn-link float-right"><i class="fa fa-edit"></i> Edit</button>
                      <span class="badge badge-secondary">1.</span> Visual Inspection Details
                    </div>
                  </div>

                  <input type="hidden" name="employee_id" id="frm_edit_material_details_employee_id">

                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">PO #</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_po_number" name="txt_po_number" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">PO Qty</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_po_qty" readonly>
                      </div>
                    </div>
                  </div>
                  <!-- <br> -->
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Device Name</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_use_for_device" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Device Code</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_device_code" name="txt_device_code" readonly>
                      </div>
                    </div>
                  </div>
                  <!-- <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Lot Qty</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_lot_qty" name="txt_lot_qty" >
                      </div>
                    </div>
                  </div> -->

                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Lot No.</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_lot_no" name="txt_lot_no" placeholder="Auto generated" readonly="readonly" style="color: green; font-weight: bold; font-size: 15px;">
                      </div>
                    </div>
                  </div>

                  <!-- <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Runcard No.</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_runcard_no" name="txt_runcard_no" placeholder="Auto generated" readonly="readonly" style="color: green; font-weight: bold; font-size: 15px;">
                      </div>
                    </div>
                  </div> -->
                  <!-- <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Mold</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_mold" name="txt_mold" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">C/T Supplier</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_ct_supplier" name="txt_ct_supplier" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Die No.</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_die_no" name="txt_die_no" readonly>
                      </div>
                    </div>
                  </div> -->
                  <!-- <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Pair #</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_pair_no" name="txt_pair_no" readonly>
                      </div>
                    </div>
                  </div> -->
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                        </div>
                        <textarea class="form-control form-control-sm" id="txt_remarks" name="txt_remarks" rows="5" readonly></textarea>
                      </div>
                    </div>
                  </div>
                  <!-- <div class="row" style="display: none;">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Reel Lot No.</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_reel_lot_no" name="txt_reel_lot_no" placeholder="Auto generated" readonly="true">
                      </div>
                    </div>
                  </div>
                  <div class="row" style="display: none;">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Assessment No.</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_assessment_no" name="txt_assessment_no" readonly="true" placeholder="" readonly="true">
                      </div>
                    </div>
                  </div>
                  <div class="row" style="display: none;">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">A Drawing Number</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_a_drawing_no" name="txt_a_drawing_no" readonly="true">
                      </div>
                    </div>
                  </div>
                  <div class="row" style="display: none;">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Revision</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_a_drawing_rev" name="txt_a_drawing_rev" readonly="true">
                      </div>
                    </div>
                  </div>
                  <div class="row" style="display: none;">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">G Drawing Number</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_g_drawing_no" name="txt_g_drawing_no" readonly="true">
                      </div>
                    </div>
                  </div>
                  <div class="row" style="display: none;">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Revision</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_g_drawing_rev" name="txt_g_drawing_rev" readonly="true">
                      </div>
                    </div>
                  </div>
                  <div class="row" style="display: none;">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Other Docs Number</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_other_docs_no" name="txt_other_docs_no">
                      </div>
                    </div>
                  </div>
                  <div class="row" style="display: none;">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Revision</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_other_docs_rev" name="txt_other_docs_rev">
                      </div>
                    </div>
                  </div> -->
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100">Assembly Line</span>
                        </div>
                        <select class="form-control select2 select2bs4 sel-assembly-lines" id="sel_assembly_line" name="sel_assembly_line" disabled>
                          <option value=""> N/A </option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row row_container">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_a_drawing">
                            <i class="fa fa-file" title="View"></i>
                          </button>
                          <span class="input-group-text w-100">A Drawing</span>
                        </div>
                          <input type="text" class="form-control" id="txt_Adrawing_no" name="txt_Adrawing_no" readonly="">
                        <input type="text" value="N/A" class="form-control form-control-sm" id="a_revision" name="a_revision" readonly>
                       </div>
                    </div>
                  </div>
                  <div class="row row_container">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_orig_a_drawing">
                            <i class="fa fa-file" title="View"></i>
                          </button>
                          <span class="input-group-text w-100">Orig A Drawing</span>
                        </div>
                          <input type="text" class="form-control" id="txt_orig_Adrawing_no" name="txt_orig_Adrawing_no" readonly="">
                        <input type="text" value="N/A" class="form-control form-control-sm" id="orig_a_revision" name="a_revision" readonly>
                       </div>
                    </div>
                  </div>

                  <div class="row row_container">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_g_drawing">
                            <i class="fa fa-file" title="View"></i>
                          </button>
                          <span class="input-group-text w-100">G Drawing</span>
                        </div>
                          <input type="text" class="form-control" id="txt_Gdrawing_no" name="txt_Gdrawing_no" readonly="">
                        <input type="text" value="N/A" class="form-control form-control-sm" id="g_revision" name="g_revision" readonly>
                      </div>
                    </div>
                  </div>

                  <div class="row row_container">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_wi_d_document">
                            <i class="fa fa-file" title="View"></i>
                          </button>
                          <span class="input-group-text w-100">WI Document</span>
                        </div>
                        <input type="text" class="form-control class_txt_WIDoc" list="list_txt_WIDoc" id="txt_WIDoc" name="txt_WIDoc" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" placeholder="WI">
                        <datalist class="class_dl_WIDoc" id="list_txt_WIDoc"></datalist>
                        <input type="text" value="N/A" class="form-control form-control-sm" id="txt_WIDoc_rev" name="txt_WIDoc_rev" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row row_container">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_ogm_vig_ig_d_document">
                            <i class="fa fa-file" title="View"></i>
                          </button>
                          <span class="input-group-text w-100">OGM/VIG/IG Document</span>
                        </div>
                        <input type="text" class="form-control class_txt_OGM_VIG_IGDoc" list="list_txt_OGM_VIG_IGDoc" id="txt_OGM_VIG_IGDoc" name="txt_OGM_VIG_IGDoc" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" placeholder="OGM/VIG/IG">
                        <datalist class="class_dl_OGM_VIG_IGDoc" id="list_txt_OGM_VIG_IGDoc"></datalist>

                        <input type="text" value="N/A" class="form-control form-control-sm" id="txt_OGM_VIG_IGDoc_rev" name="txt_OGM_VIG_IGDoc_rev" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row row_container">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_pp_d_document">
                            <i class="fa fa-file" title="View"></i>
                          </button>
                          <span class="input-group-text w-100">PP Document</span>
                        </div>
                          <!-- <input type="text" class="form-control" id="txt_PPDoc_no" name="txt_PPDoc_no" > -->
                        <input type="text" class="form-control class_txt_PPDoc" list="list_txt_PPDoc" id="txt_PPDoc" name="txt_PPDoc" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" placeholder="PP">
                        <datalist class="class_dl_PPDoc" id="list_txt_PPDoc"></datalist>

                        <input type="text" value="N/A" class="form-control form-control-sm" id="txt_PPDoc_rev" name="txt_PPDoc_rev" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row row_container">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_ud_d_document">
                            <i class="fa fa-file" title="View"></i>
                          </button>
                          <span class="input-group-text w-100">UD Document</span>
                        </div>
                          <!-- <input type="text" class="form-control" id="txt_UDDoc_no" name="txt_UDDoc_no" > -->
                          <input type="text" class="form-control class_txt_UDDoc" list="list_txt_UDDoc" id="txt_UDDoc" name="txt_UDDoc" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" placeholder="UD">
                          <datalist class="class_dl_UDDoc" id="list_txt_UDDoc"></datalist>

                          <input type="text" value="N/A" class="form-control form-control-sm" id="txt_UDDoc_rev" name="txt_UDDoc_rev" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row row_container">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_pm_document">
                            <i class="fa fa-file" title="View"></i>
                          </button>
                          <span class="input-group-text w-100">PM</span>
                        </div>
                          <!-- <input type="text" class="form-control" id="txt_PMDoc_no" name="txt_PMDoc_no" > -->
                          <input type="text" class="form-control class_txt_PMDoc" list="list_txt_PMDoc" id="txt_PMDoc" name="txt_PMDoc" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" placeholder="PM">
                          <datalist class="class_dl_PMDoc" id="list_txt_PMDoc"></datalist>

                        <input type="text" value="N/A" class="form-control form-control-sm" id="txt_PMDoc_rev" name="txt_PMDoc_rev" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row row_container">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_j_r_dj_ks_dc_gj_document">
                            <i class="fa fa-file" title="View"></i>
                          </button>
                          <span class="input-group-text w-100">J/R/DJ/KS/DC/GJ</span>
                        </div>
                          <input type="text" class="form-control" id="txt_JRDJKSDCGJDoc_no" name="txt_JRDJKSDCGJDoc_no" readonly="">
                        <input type="text" value="N/A" class="form-control form-control-sm" id="txt_JRDJKSDCGJDoc_revision" name="txt_JRDJKSDCGJDoc_revision" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row row_container">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_gp_md_document">
                            <i class="fa fa-file" title="View"></i>
                          </button>
                          <span class="input-group-text w-100">GP MD</span>
                        </div>
                          <input type="text" class="form-control" id="txt_GPMDDoc_no" name="txt_GPMDDoc_no" readonly="">
                        <input type="text" value="N/A" class="form-control form-control-sm" id="txt_GPMDDoc_revision" name="txt_GPMDDoc_revision" readonly>
                      </div>
                    </div>
                  </div>
<!--                   <div class="row row_container">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100">Other Drawing</span>
                        </div>
                          <input type="text" class="form-control" id="txt_Odrawing_no" name="txt_Odrawing_no" readonly="">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Revision</span>
                        </div>
                        <input type="text" value="N/A" class="form-control form-control-sm" id="o_revision" name="o_revision" readonly>
                      </div>
                    </div>
                  </div>
 --><!--                   <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100">Other Drawing</span>
                        </div>
                        <select class="form-control select2 select2bs4 drawing_no" id="other_drawing" name="other_drawing" disabled>
                          <option value=""> N/A </option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Revision</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="other_revision" name="other_revision" readonly>
                      </div>
                    </div>
                  </div>
 -->
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Created At</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_created_at" name="txt_created_at" readonly="true" placeholder="Auto generated">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100">Application Date/Time</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_application_datetime" name="application_datetime" readonly="true" placeholder="Auto generated">
                      </div>
                    </div>
                  </div>
                  <!-- <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Comp. Under Runcard No. </span>
                        </div>
                        <select class="form-control select2 select2bs4" id="sel_comp_under_runcard_no" name="sel_comp_under_runcard_no" disabled>
                          <option value="0"> N/A </option>
                        </select>
                      </div>
                    </div>
                  </div> -->
                  <div class="row" style="display: none;">
                    <div class="col text-right">
                      @csrf
                      <input type="hidden" name="txt_material_details_emp_num" id="txt_material_details_emp_num">
                      <input type="hidden" name="txt_prod_runcard_id_query" id="txt_prod_runcard_id_query">
                      <input type="hidden" name="txt_prod_runcard_status" id="txt_prod_runcard_status">
                      <!-- <input type="hidden" name="txt_prod_runcard_has_emboss" id="txt_prod_runcard_has_emboss">
                      <input type="hidden" name="require_oqc_before_emboss" id="txt_prod_runcard_require_oqc_before_emboss"> -->
                      <input type="hidden" name="txt_wbs_kit_issuance_id_query" id="txt_wbs_kit_issuance_id_query">
                      <!-- <input type="hidden" name="txt_wbs_sakidashi_issuance_id_query" id="txt_wbs_sakidashi_issuance_id_query"> -->
                      <input type="hidden" name="txt_wbs_kit_issuance_device_code_query" id="txt_wbs_kit_issuance_device_code_query">
<!--                       <button type="button" class="btn btn-sm btn-success" id="btn_print_drawing">Print Drawing</button>
 -->                      <button type="button" class="btn btn-sm btn-success" id="btn_save_material_details_primary">Save</button>
                      <button type="button" class="btn btn-sm btn-secondary" id="btn_cancel_material_details_primary">Cancel</button>
                    </div>
                  </div>
                </form>
                <br>
              </div><!-- col -->

              <div class="col-sm-8">
                <div class="row">
                  <div class="col border py-3 px-4 border-left-0 border-bottom-0">
                    <!-- <button type="button" class="btn btn-sm btn-info float-right mb-1" id="btn_setup_stations" disabled="disabled" style="display: none;"><i class="fa fa-cog"></i> Set-up stations</button> -->
                    <!-- <div style="float: right;">
                      <select class="form-control form-control-sm" id="sel_runcard_type" name="sel_runcard_type">
                          <option value="0">For FVI</option>
                          <option value="1">For Emboss Sealing</option>
                        </select>
                    </div> -->
                    <!-- <div class="row align-items-center"> -->
                    <div style="float: left;">
                      <span class="badge badge-secondary">2.</span> Process Stations 
                    </div>


                      <!-- <div class="input-group input-group-sm mb-3 col-sm-4" style="float: right;">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Runcard Type</span>
                        </div>
                        <select class="form-control form-control-sm" id="sel_runcard_type" name="sel_runcard_type">
                          <option value="0">For FVI</option>
                          <option value="1">For Emboss Sealing</option>
                        </select>
                      </div> -->

                    <!-- </div> -->
                    <div style="float: right;">
                      <button class="btn btn-primary btn-sm" id="btnAddProcess" data-toggle="modal" ><i class="fa fa-plus" ></i> Add Runcard</button>
                    </div>

                    <!-- <div style="float: right;">
                      <label style="text-align: center;">MOD Legends</label><br>
                      <span class="badge badge-pill badge-primary">Material NG</span> 
                      <span class="badge badge-pill badge-danger">Production NG</span>
                    </div> -->

                    <div class="table-responsive">
                      <table class="table table-sm small table-bordered table-hover" id="tbl_prod_runcard_stations" style="width: 100%;">
                        <thead>
                          <tr class="bg-light">
                            <th></th>
                            <!-- <th>Step</th>
                            <th>Step</th> -->
                            <th>Runcard #</th>
                            <th>Process</th>
                            <th>Date Time</th>
                            <th>C/T Area</th>
                            <th>Terminal Area</th>
                            <!-- <th>Machine</th> -->
                            <th>Input</th>
                            <th>Output</th>
                            <th>NG QTY</th>
                            <th>MOD</th>
                            <th>Remarks</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                          <th colspan="5"></th>
                          <th><b>TOTAL</b></th>
                          <th id="thRuncardTotalInput"><b>0</b></th>
                          <th id="thRuncardTotalOutput"><b>0</b></th>
                          <th id="thRuncardTotalNG"><b>0</b></th>
                          <th colspan="2"></th>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col border py-3 px-4 border-left-0 border-bottom-0">
                    <!-- <button type="button" class="btn btn-sm btn-info float-right mb-1" id="btn_setup_stations" disabled="disabled" style="display: none;"><i class="fa fa-cog"></i> Set-up stations</button> -->
                    <!-- <div style="float: right;">
                      <select class="form-control form-control-sm" id="sel_runcard_type" name="sel_runcard_type">
                          <option value="0">For FVI</option>
                          <option value="1">For Emboss Sealing</option>
                        </select>
                    </div> -->
                    <!-- <div class="row align-items-center"> -->
                    <div style="float: left;">
                      <span class="badge badge-secondary">3.</span> Accessories 
                    </div>


                      <!-- <div class="input-group input-group-sm mb-3 col-sm-4" style="float: right;">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Runcard Type</span>
                        </div>
                        <select class="form-control form-control-sm" id="sel_runcard_type" name="sel_runcard_type">
                          <option value="0">For FVI</option>
                          <option value="1">For Emboss Sealing</option>
                        </select>
                      </div> -->

                    <!-- </div> -->
                    <div style="float: right;">
                      <button class="btn btn-success btn-sm" id="btnPrintAccessory" data-toggle="modal" style="margin-bottom: 5px;"><i class="fa fa-print" ></i> Print Accessory</button> 

                      <button class="btn btn-primary btn-sm" id="btnAddAccessory" data-toggle="modal" style="margin-bottom: 5px;"><i class="fa fa-plus" ></i> Add Accessory</button>
                    </div>

                    <!-- <div style="float: right;">
                      <label style="text-align: center;">MOD Legends</label><br>
                      <span class="badge badge-pill badge-primary">Material NG</span> 
                      <span class="badge badge-pill badge-danger">Production NG</span>
                    </div> -->

                    <div class="table-responsive">
                      <table class="table table-sm small table-bordered table-hover" id="tblAccessories" style="width: 100%;">
                        <thead>
                          <tr class="bg-light">
                            <th></th>
                            <th></th>
                            <th>Accessory Name</th>
                            <th>Quantity</th>
                            <th>Usage per Socket</th>
                            <th>Counted By / Date</th>
                            <th>Checked By / Date: Visual Optr.</th>
                            <th>Prod'n Supervisor / Date</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                
              </div><!-- col -->

            </div>
          </div>
          <div class="modal-footer">
            <!-- <button type="button" class="btn btn-sm btn-success" id="btn_approve_prod" disabled><i class="fa fa-check-circle"></i> Prod Approved</button>
            <button type="button" class="btn btn-sm btn-success" id="btn_approve_qc" disabled><i class="fa fa-check-circle"></i> QC Approved</button> -->
            <button type="button" class="btn btn-sm btn-success" id="btnPrintPO_process_station">Print PO</button>
            <button type="button" class="btn btn-sm btn-success" id="btnSubmitToOQCLotApp">Submit OQC Lot App</button>
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal -->
    <div class="modal fade" id="mdl_setup_stations" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="mdl_alert_title">Set-up stations</h5>
          </div>
          <div class="modal-body" id="mdl_alert_body">
            <div class="row">
              <div class="col">
                <div class="table-responsive">
                  <table class="table table-sm small table-bordered table-hover w-100" id="tbl_setup_stations" style="min-width: 400px;">
                    <thead>
                      <tr class="bg-light">
                        <th><input type="checkbox" id="chkCheckAllSetupStations"></th>
                        <th>Step</th>
                        <th>Station</th>
                        <th>Sub-station</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success" id="btn_save_setup_stations">Save</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal -->
    <div class="modal fade" id="mdl_edit_prod_runcard_station_details" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content modal-lg">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fas fa-object-group text-info"></i> Stations</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <!-- <br> -->
            <!-- <h5 class="modal-title h5OIHeaderTitle">Overall Inspection - <span>0</span> Selected Runcards</h5> -->
          </div>
          <div class="modal-body">
            <form id="frm_edit_prod_runcard_station_details">
              @csrf
              <!-- <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Step</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_step" name="step_num" placeholder="(Auto Generated)" readonly>
                  </div>
                </div>
              </div> -->
              <!-- <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Assembly Line</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_station" readonly>
                  </div>
                </div>
              </div> -->
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Process</span>
                    </div>
                    <input type="text" name="process_name" class="form-control form-control-sm" id="txt_edit_prod_runcard_process" readonly value="Final Visual">
                    <!-- <select class="form-control select2 select2bs4 selSubStation" id="txt_edit_prod_runcard_substation" name="sub_station_id">
                      <option value=""> N/A </option>
                    </select> -->
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Date</span>
                    </div>
                    <input type="date" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_date" name="date" readonly value="<?php echo date('Y-m-d'); ?>">
                  </div>
                </div>
              </div>

              <div class="row" style="display: block;">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">CT Area</span>
                    </div>
                    <select class="form-control select2 select2bs4 selectUser" id="sel_edit_prod_runcard_ct_area" name="ct_area">
                      <option value=""> N/A </option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row" style="display: block;">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Terminal Area</span>
                    </div>
                    <select class="form-control select2 select2bs4 selectUser" id="sel_edit_prod_runcard_terminal_area" name="terminal_area">
                      <option value=""> N/A </option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row" style="display: none;">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Operator</span>
                    </div>
                    <select class="form-control select2 select2bs4 selectUser" id="txt_edit_prod_runcard_operator" name="txt_edit_prod_runcard_operator[]" multiple="multiple">
                      <option value=""> N/A </option>
                    </select>
                    <!-- <div class="input-group-append">
                      <button class="btn btn-info" type="button" title="Scan code" id="btn_scan_add_runcard_operator_code"><i class="fa fa-qrcode"></i></button>
                    </div> -->
                  </div>
                </div>
              </div>

              <div class="row" style="display: none;">
                <div class="col">
                  <div class="table-responsive">
                    <div style="float: right;">
                      <button class="btn btn-info btn-xs" type="button" title="Scan code" id="btn_scan_add_runcard_operator_code" style="margin-bottom: 5px;"><i class="fa fa-qrcode"></i> Scan Operator</button><br>
                    </div>
                    <table class="table table-sm table-bordered" id="tblProdRunOperators">
                      <thead>
                        <tr>
                          <th style="width: 60%;">Operator</th>
                          <th style="width: 30%;">Area</th>
                          <!-- <th style="width: 20%;">C/T Area</th>
                          <th style="width: 20%;">Terminal Area</th> -->
                          <th style="width: 10%;">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td colspan="3" style="text-align: center;">No data available in table</td>
                        </tr>
                        <!-- <tr>
                          <td>
                            <select class="form-control select2 select2bs4 selectMOD" id="selEditProdRunMod" name="mod[]">
                              <option value="">N/A</option>
                            </select>
                          </td>
                          <td>
                            <input type="number" class="form-control txtEditProdRunStaMODQty" name="mod_qty[]">
                          </td>
                          <td></td>
                        </tr> -->
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div class="row" style="display: none;">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Certified Operator</span>
                    </div>
                    <select class="form-control select2 select2bs4 selectUser" id="txt_edit_prod_runcard_cert_operator" name="txt_edit_prod_runcard_cert_operator[]" multiple="multiple">
                      <option value=""> N/A </option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row" style="display: none;">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Certified Operator</span>
                    </div>
                    <select class="form-control select2 select2bs4 selectUser" id="txt_edit_prod_runcard_cert_operator_visible" name="txt_edit_prod_runcard_cert_operator[]" multiple="multiple" disabled="true">
                      <option value=""> N/A </option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row" style="display: none;">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Machine</span>
                    </div>
                    <!-- <input type="text" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_machine" name="txt_edit_prod_runcard_station_machine"> -->

                    <!-- <select class="form-control form-control-sm select2bs4 selectMachine" name="txt_edit_prod_runcard_station_machine" id="txt_edit_prod_runcard_station_machine" style="width: 100%;">
                    </select> -->

                    <select class="form-control select2 select2bs4 selectMachine" id="txt_edit_prod_runcard_station_machine" name="txt_edit_prod_runcard_station_machine[]" multiple="multiple">
                      <option value=""> N/A </option>
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-info" type="button" title="Scan code" id="btn_scan_machine_code"><i class="fa fa-qrcode"></i></button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row" style="display: none;">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Assigned Machine</span>
                    </div>

                    <select class="form-control select2 select2bs4 selectMachine" id="txt_edit_prod_runcard_station_assigned_machine" name="txt_edit_prod_runcard_station_assigned_machine" multiple="multiple" readonly="readonly">
                      <option value=""> N/A </option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row" style="display: none;">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Assigned Machine</span>
                    </div>

                    <select class="form-control select2 select2bs4 selectMachine" id="txt_edit_prod_runcard_station_assigned_machine_visible" name="txt_edit_prod_runcard_station_assigned_machine_visible" multiple="multiple" disabled="true">
                      <option value=""> N/A </option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Input</span>
                    </div>
                    <input type="number" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_input" name="qty_input" required min="0">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Output</span>
                    </div>
                    <input type="number" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_output" name="qty_output" required min="0">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">NG Qty</span>
                    </div>
                    <input type="number" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_ng" name="qty_ng" min="0" value="0" readonly="true" required>
                  </div>
                </div>
              </div> <!--ENTENG -->
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                    </div>
                    <textarea class="form-control form-control-sm" id="txt_fvi_remarks" name="remarks" rows="5"></textarea>
                  </div>
                </div>
              </div>
              <hr>
              <div class="table-responsive">
                <div style="float: left;">
                  <label>Total No. of NG: <span id="pRCStatTotNoOfNG" style="color: green;">0</span></label>
                </div>
                <div style="float: right;">
                  <button type="button" id="btnAddMODTable" class="btn btn-xs btn-info" title="Add MOD"><i class="fa fa-plus"></i> Add MOD</button>
                </div>
                <br><br>
                <table class="table table-sm" id="tblEditProdRunStaMOD">
                  <thead>
                    <tr>
                      <th style="width: 70%;">MOD</th>
                      <th style="width: 20%;">QTY</th>
                      <!-- <th style="width: 20%;">Type of NG</th> -->
                      <th style="width: 10%;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- <tr>
                      <td>
                        <select class="form-control select2 select2bs4 selectMOD" id="selEditProdRunMod" name="mod[]">
                          <option value="">N/A</option>
                        </select>
                      </td>
                      <td>
                        <input type="number" class="form-control txtEditProdRunStaMODQty" name="mod_qty[]">
                      </td>
                      <td></td>
                    </tr> -->
                  </tbody>
                </table>
              </div>

            </form>
          </div>
          <div class="modal-footer">
            <input type="hidden" id="txt_prod_runcard_station_id_query" name="txt_prod_runcard_station_id_query" form="frm_edit_prod_runcard_station_details">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" form="frm_edit_prod_runcard_station_details" id="btn_save_prod_runcard_station_stations" disabled="true">Save</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal -->
    <div class="modal fade" id="mdlSaveAccessory" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content modal-xl">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fas fa-puzzle-piece text-info"></i> Accessory Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <!-- <br> -->
            <!-- <h5 class="modal-title h5OIHeaderTitle">Overall Inspection - <span>0</span> Selected Runcards</h5> -->
          </div>
          <div class="modal-body">
            <form id="frmSaveAccessory">
              @csrf

              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Product Name</span>
                    </div>
                    <input type="text" name="prod_runcard_id" class="form-control form-control-sm" readonly style="display: none;">
                    <input type="text" name="accessory_id" class="form-control form-control-sm" readonly style="display: none;">
                    <input type="text" name="product_name" class="form-control form-control-sm" readonly>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Accessory Name</span>
                    </div>
                    <!-- <input type="text" name="issuance_id" class="form-control form-control-sm"> -->
                    <select class="form-control select2 select2bs4 selAccessoryName" name="issuance_id" id="selSaveAccessoryName">
                      <!-- <option value=""> N/A </option> -->
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Quantity</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" name="quantity" required >
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Usage per Socket</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" name="usage_per_socket" required readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Counted By</span>
                    </div>
                    <select class="form-control select2 select2bs4 selectUser" name="counted_by">
                      <option value=""> N/A </option>
                    </select>
                    <input type="date" class="form-control" name="counted_by_date" alue="<?php echo date('Y-m-d'); ?>">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Checked By</span>
                    </div>
                    <select class="form-control select2 select2bs4 selectUser" name="checked_by">
                      <option value=""> N/A </option>
                    </select>
                    <input type="date" class="form-control" name="checked_by_date">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Supervisor</span>
                    </div>
                    <select class="form-control select2 select2bs4 selectUser" name="prod_supervisor" disabled="true">
                      <option value=""> N/A </option>
                    </select>
                    <input type="date" class="form-control" name="prod_supervisor_date" disabled="true">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="basic-addon1"><input type="checkbox" class="" name="require_supervisor" value="1"></span>
                    </div>
                    
                  </div>
                </div>
              </div>

            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" form="frmSaveAccessory" id="btnSaveAccessory">Save</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal -->
    <div class="modal fade" id="mdlSaveMaterial" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content modal-lg">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fas fa-puzzle-piece text-info"></i> Add Material</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="frmSaveMaterial" method="post">
              @csrf
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Material</span>
                    </div>
                    <select class="form-control select2 select2bs4 selMaterial" name="material_id">
                      <option value=""> N/A </option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Lot No</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" name="lot_no" readonly="true">
                    <input type="text" class="form-control form-control-sm" name="type" readonly="true" style="display: none;">
                    <div class="input-group-append">
                      <button class="btn btn-info" type="button" title="Scan Mat'l Lot No" id="btnScanMaterialLotNo"><i class="fa fa-qrcode"></i></button>
                    </div>

                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Material Type</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" name="text_type" readonly="true">

                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" id="btnSaveMaterial">Save</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal -->
    <div class="modal fade" id="mdl_alert" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="mdl_alert_title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="mdl_alert_body">
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

     <!-- Modal -->
    <div class="modal fade" id="mdl_qrcode_scanner" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-0">
            <!-- <h5 class="modal-title" id="exampleModalLongTitle"></h5> -->
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pt-0">
            <div class="text-center text-secondary">
              Please scan your ID.
              <br>
              <br>
              <h1><i class="fa fa-qrcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txt_qrcode_scanner" class="hidden_scanner_input" autocomplete="off">
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <div class="modal fade" id="modal_qrcode_scanner_for_delete_accesory" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-0">
            <!-- <h5 class="modal-title" id="exampleModalLongTitle"></h5> -->
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pt-0">
            <div class="text-center text-secondary">
              Please scan your ID.
              <br>
              <br>
              <h1><i class="fa fa-barcode fa-lg"></i></h1>
            </div>
            <input type="text" id="modal_qrcode_scanner_for_delete_accesory_employee_id" class="hidden_scanner_input" autocomplete="off">
            <input type="hidden" id="modal_qrcode_scanner_for_delete_accesory_id">
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal -->
    <div class="modal fade" id="mdl_employee_number_scanner" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-0">
            <!-- <h5 class="modal-title" id="exampleModalLongTitle"></h5> -->
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pt-0">
            <div class="text-center text-secondary">
              Please scan your ID.
              <br><br>
              <!-- <h1><i class="fa fa-barcode fa-lg"></i></h1> -->
              <h1><i class="fa fa-qrcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txt_employee_number_scanner" class="hidden_scanner_input">
          </div>
  <!--         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div> -->
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal Scan PO -->
    <div class="modal fade" id="modalScanPOTransLotCode" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-0">
            <!-- <h5 class="modal-title" id="exampleModalLongTitle"></h5> -->
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pt-0">
            <div class="text-center text-secondary">
              <span id="scanPOTransLotBody">Please scan the PO code.</span>
              <br>
              <br>
              <h1><i id="scanPOTransLotIcon" class="fa fa-qrcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txtSearchPoTransLotNo" class="hidden_scanner_input" autocomplete="off">
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <div class="modal fade" id="modalNGSummary">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">NG Summary</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <label style="float: left; font-size: 20px; color: green;">PO #: <span id="spanNGSummaryPoNo">--</span></label>
            <br><br>
            <div class="table-responsive">
              <table class="table table-sm table-bordered table-hover dataTable no-footer" id="tblNGSummary" style="width: 100%;">
                <thead>
                  <tr>
                    <th>FVI ID.</th>
                    <th>FVI No.</th>
                    <th>Mode of Defect</th>
                    <th>Qty</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="submit" class="btn btn-default" data-dismiss="modal">Close</button>
            <!-- <button type="submit" id="btnChangeDeviceStat" class="btn btn-primary"><i id="iBtnChangeDeviceStatIcon" class="fa fa-check"></i> Yes</button> -->
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- Modal -->
    <div class="modal fade" id="mdl_setup_stations" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="mdl_alert_title">Set-up stations</h5>
          </div>
          <div class="modal-body" id="mdl_alert_body">
            <div class="row">
              <div class="col">
                <div class="table-responsive">
                  <table class="table table-sm small table-bordered table-hover w-100" id="tbl_setup_stations" style="min-width: 400px;">
                    <thead>
                      <tr class="bg-light">
                        <th></th>
                        <th>Step</th>
                        <th>Station</th>
                        <th>Sub-station</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success" id="btn_save_setup_stations">Save</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <div class="modal fade" id="modalGenRuncardToPrint">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"><i class="fa fa-qrcode"></i> Generate QR Code</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <center>
              <div class="row">
                <div class="col-sm-6">
                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                              ->size(150)->errorCorrection('H')
                              ->generate('0')) !!}" id="imgGenRuncardPoNoBarcode" style="max-width: 200px; min-width: 200px;">
                    <br>
                    <label id="lblRuncardPoNo">...</label> <br>
                </div>
                <div class="col-sm-6">
                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                              ->size(150)->errorCorrection('H')
                              ->generate('0')) !!}" id="imgGenRuncardBarcode" style="max-width: 200px; min-width: 200px;">
                    <br>
                    <label id="lblRuncardNo">...</label> <br>
                </div>
              </div>
            </center>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnPrintRuncardBarcode" class="btn btn-primary"><i id="iBtnPrintRuncardBarcodeIcon" class="fa fa-print"></i> Print</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <textarea id="txt_str" style="opacity: 0.01"></textarea>
  </div>

  <!-- Nessa -->
   <div class="modal fade" id="modal_PO_QRcode">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-qrcode"></i> Generate QR Code</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <center>
              <div class="row">
                <div class="col-sm-12">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->margin(5)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_barcode_PO" style="max-width: 200px;">
                  <br>
                  <label id="lbl_PO"></label> <br>
                  <label id="lbl_device_name"></label> <br>
                </div>

              </div>
            </center>
        </div>
        <div class="modal-footer">
            <button type="submit" id="btn_print_barcode" class="btn btn-primary btn-sm"><i class="fa fa-print fa-xs"></i> Print</button>
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

   <div class="modal fade" id="modal_Drawing_QRcode">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-qrcode"></i> Generate QR Code</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <center>
              <!-- A Drawing -->
                <div class="col-sm-6 drawing-adrawing">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_A_drawing" style="max-width: 200px;">
                  <br>
                  <label id="lbl_PO_adrawing"></label> <br>
                  <label id="lbl_device_name_adrawing"></label> <br>
                  <label id="lbl_adrawing_no"></label> <br>
                </div>

              <!-- G Drawing -->
                <div class="col-sm-6 drawing-gdrawing">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_G_drawing" style="max-width: 200px;">
                  <br>
                  <label id="lbl_PO_gdrawing"></label> <br>
                  <label id="lbl_device_name_gdrawing"></label> <br>
                  <label id="lbl_gdrawing_no"></label> <br>
                </div>

              <!-- other Drawing -->
                <div class="col-sm-6 drawing-odrawing">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_O_drawing" style="max-width: 200px;">
                  <br>
                  <label id="lbl_PO_odrawing"></label> <br>
                  <label id="lbl_device_name_odrawing"></label> <br>
                  <label id="lbl_odrawing_no"></label> <br>
                </div>

            </center>
        </div>
        <div class="modal-footer">
            <button type="submit" id="btn_print_qr" class="btn btn-primary btn-sm"><i class="fa fa-print fa-xs"></i> Print</button>
            <button type="button" id="btn_close_dqr"class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div class="modal fade" id="modalPrintLotQRCode">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-qrcode"></i> Generate QR Code</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

              <div class="row">
                <div class="col-sm-6">
                  <center>
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->margin(5)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_barcode_lot_po" style="max-width: 200px;">
                  <br>
                  <label id="lbl_lot_PO"></label> <br>
                  <label id="lbl_lot_device_name"></label> <br>
                </center>
                </div>

                <div class="col-sm-6">
                  <center>
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->margin(5)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_barcode_lot" style="max-width: 200px;">
                  <br>
                  <label id="lbl_lot_no"></label> <br>
                  <label id="lbl_lot_qty"></label> <br>
                </center>
                </div>

              </div>
        </div>
        <div class="modal-footer">
            <button type="submit" id="btn_print_lot_barcode" class="btn btn-primary btn-sm"><i class="fa fa-print fa-xs"></i> Print</button>
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>


  <div class="modal fade" id="modalScan_EmployeeID" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please scan your employee id.
          <br>
          <br>
          <h1><i class="fa fa-barcode fa-lg"></i></h1>
          </div>
          <input type="text" id="modalScan_EmployeeID_id" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_to_inform_user_for_pilot_run" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <center><h5 class="modal-title"></i>Do you have a Pilot Ran?</h5></center>
        </div>
        <!-- <div class="modal-body pt-0"> -->
          <!-- <div class="text-center text-secondary">
            <select class="form-control">
              <option value="1">Yes</option>
              <option value="0">No</option>
            </select>
          </div> 
        </div> -->
          <input type="hidden" id="modal_to_inform_user_for_pilot_run_runcard_id">
      <div class="modal-footer">
          <center> 
              <button type="button" id="modal_to_inform_user_for_pilot_run_btn_yes" class="btn btn-primary btn-sm">Yes</button>
              <button type="button" id="modal_to_inform_user_for_pilot_run_btn_no" class="btn btn-secondary btn-sm">No</button>
          </center>
      </div>
    </div>
  </div>

  <!-- /.content-wrapper -->

  <!-- /.content-wrapper -->

  <img id="img_pricon_logo" hidden src="{{ asset('public/images/pricon-logo.png?v=2') }}">

    <textarea id="txtAccPrintBody" style="display: none;">
      <!DOCTYPE html>
      <html>
      <head>
        <title></title>
        <style type="text/css">
          *{
            font-family: "arial narrow", sans-serif;
          }
          table, tr, td{
            border: 1px solid #111;
            border: none;
          }
          table{
            border-collapse: collapse;
            border-spacing: 0;
          }
          td,tr{
            height: 0px!important;
            padding: 0px!important;
            margin: 0px!important;
          }
          td{
            padding: 0px 8px!important;
          }
          .table-container{
            vertical-align: top;
            width: 600px;
            min-width: 600px;
            max-width: 600px;
            height: 300px;
            min-height: 300px;
            max-height: 300px;
            border: 1px solid #000;
          }
          .border-bottom{
            border-bottom: 1px solid #000;
          }
          .text-left{
            text-align: left;
          }
          .text-right{
            text-align: right;
          }
          .text-center{
            text-align: center;
          }
          .text-middle{
            vertical-align: middle;
          }
          .text-bottom{
            vertical-align: bottom;
          }
          .text-top{
            vertical-align: top;
          }
          .float-right{
            float: right;
          }
          .bold{
            font-weight: bold;
          }
          .h5{
            font-size: 1.1em;
          }
          /*----------------*/
          .table1{
            font-size: 16px;
            font-family: Calibri;
          }
          .logo-left{
            max-height: 10px;
            max-width: : 20px;
            margin-left: 1px;
          }
          /*----------------*/
          .table2{
            font-size: 1.1em;
          }
          .logo-center{
            max-height: 10px;
            max-width: : 20px;
            margin: 0;
            padding: 0;
            margin-top: 1px;
          }
          /*----------------*/
          .table3{
            font-size: 1.1em;
          }
          tr.border,.border td{
            border: 1px solid #111;
          }

        </style>

        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>

      </head>
      <body id="container">
        <br><br>
          <div class="row" style="margin-left: 10px; margin-right: 10px; margin-top: 10px; margin-bottom: 10px;">
      </textarea>

      <textarea id="txtAccPrintBody2" style="display: none;">
        <div class="col-sm-6">
          <div style="border: 1px solid black;">
            <table class="table1 text-middle" cellspacing="0" cellpadding="0" border="0">
              <tbody>
                <tr>
                  <td colspan="2" class="text-bottom">
                    <img class="logo-left" src="{{ asset('public/images/pricon-logo.png?v=2') }}" style="max-height: 25px; max-width: : 40px; margin-left: 1px; margin-top: 2px;">
                  </td>
                </tr>
                <tr>
                  <td colspan="6" class="bold text-center" style="margin-bottom: 5px;">Accessory Tag</td>
                </tr>
                <tr>
                  <td style="height: 1px; width: 16%"></td>
                  <td style="height: 1px; width: 16%"></td>
                  <td style="height: 1px; width: 16%"></td>
                  <td style="height: 1px; width: 16%"></td>
                  <td style="height: 1px; width: 16%"></td>
                  <td style="height: 1px; width: 16%"></td>
                </tr>
                <tr>
                  <td colspan="6">Product Name: <u class="bold" id="txtAccProdName">asdsadasd</u></td>
                </tr>
                <tr>
                  <td colspan="6">Accessory Name: <u class="bold" id="txtAccAccessoryName">asdasdasdsa</u></td>
                </tr>
                <tr>
                  <td colspan="3">Quantity: <u class="bold" id="txtAccQuantity">1</u></td>
                  <td colspan="3">Usage per Socket: <u class="bold" id="txtAccUsagePerSocket">2</u></td>
                </tr>
                <tr>
                  <td colspan="6"></td>
                </tr>
                <tr>
                  <td colspan="6">Counted by/Date: <u class="bold" id="txtAccCountedByDate">Hazel / 10/29/2020</u></td>
                </tr>
                <tr>
                  <td colspan="6">Checked by/Date: Visual Optr.: <u class="bold" id="txtAccCheckedByDate">Hazel / 10/29/2020</u></td>
                </tr>
                <tr>
                  <td colspan="1"></td>
                  <td colspan="5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; QC Inspector: <u class="bold" id="txtAccInspector">N/A</u></td>
                </tr>
              </tbody>
            </table>                
          </div>
          
        </div>
      </textarea>

      <textarea id="txtAccPrintBody3" style="display: none;">
          </div>
      </body>
      </html>

    </textarea>

  @endsection

  @section('js_content')
  
  <script src="{{ URL::asset('public/js/my_js/DrawingNo.js') }}"></script>
  <!-- <script src="{{ URL::asset('public/template/plugins/jquery-ui/jquery-ui.min.js') }}"></script> -->
  <script src="{{ URL::asset('public/template/plugins/qz-print-free_1.8.0_src/qz-print/js/deployJava.js') }}"></script>
  <script type="text/javascript">

    // $('#modal_to_inform_user_for_pilot_run').modal('show')

    let _token = "{{ csrf_token() }}";
    let dt_materials, dt_setup_stations, dt_prod_runcard_stations, dt_sakidashi, dt_ng_summary;
    let dt_prod_runcard, dtAccessories;
    // let currentPoNo = "450222038400010";
    // let currentPoNo = "450196479600010";
    let currentPoNo = "";
    let currentTransSlipNo = "";
    let currentCtrlNo = "";
    // let currentEmbossCtrlNo = "";
    let arrSelectedMaterial = [];
    let arrSelectedSakidashi = [];
    // let arrSelectedEmboss = [];
    let totalNoOfOk = 0;
    let totalNoOfNG = 0;
    let boxing = "";
    let assessment = "";
    let aDrawing = "";
    let aDrawingRev = "";
    let gDrawing = "";
    let gDrawingRev = "";
    let materialKitIssuanceLotNo = "";
    let sakidashiIssuanceLotNo = "";
    // let embossIssuanceLotNo = "";
    let materialKitTransferSlip = "";
    let sakidashiCtrlNo = "";
    // let embossCtrlNo = "";
    let hasProdMatSakList = false;
    let viewMatKitAction = 1;
    let viewMatKitActionLotNo = null;
    let viewSakidashiAction = 1;
    let viewSakidashiActionLotNo = null;
    // let viewEmbossAction = 1;
    // let viewEmbossActionLotNo = null;
    let saveMatKitLotIssuanceId = null;
    let saveMatKitLotItem = null;
    let saveMatKitLotItemDesc = null;

    let saveSakidashiLotIssuanceId = null;
    let saveSakidashiLotItem = null;
    let saveSakidashiLotItemDesc = null;

    let arrSelectedRuncards = [];

    let deleteRuncardStationId = 0;

    let arrPrintAccessories = [];

    //-Nessa
    let img_barcode_PO;
    let lbl_PO;
    let lbl_device_name;

    let img_A_drawing;
    let lbl_PO_adrawing;
    let lbl_device_name_adrawing;
    let lbl_adrawing_no;
    let lbl_a_revision;
    let lbl_adrawing;

    let img_G_drawing;
    let lbl_PO_gdrawing;
    let lbl_device_name_gdrawing;
    let lbl_gdrawing_no;
    let lbl_g_revision;
    let lbl_gdrawing;

    let img_O_drawing;
    let lbl_PO_odrawing;
    let lbl_device_name_odrawing;
    let lbl_odrawing_no;
    let lbl_o_revision;
    let lbl_odrawing;


    let img_barcode_lot_po;
    let img_barcode_lot;
    let lbl_lot_po;
    let lbl_lot;
    let lbl_lot_device_name; 
    let lbl_lot_qty;



    // let saveEmbossLotIssuanceId = null;
    // let saveEmbossLotItem = null;
    // let saveEmbossLotItemDesc = null;

    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

    //-----
    var stickers=new Array();//creates an array to store the sticker ZPL string codes
    var java_enabled = navigator.javaEnabled();
    if(java_enabled){
      deployQZ();//deploys the QZapplet for printing
      $('#txt_str').attr('data-java_enabled',1);
    }else{
      $('#txt_str').attr('data-java_enabled',0);
    }
    function proceed_to_print(){
       var data_str = $('#txt_str').val();
       stickers=data_str.split(":-:");//the data returned by this ajax would be the sticker ZPL codes. If the sticker is more than one, the stickers are separated by the delimeter ":-:"
       findPrinter();//calls the findprinter function passing the string "ZPL" - ppc
    }
    /**
    * Deploys different versions of the applet depending on Java version.
    * Useful for removing warning dialogs for Java 6.  This function is optional
    * however, if used, should replace the <applet> method.  Needed to address 
    * MANIFEST.MF TrustedLibrary=true discrepency between JRE6 and JRE7.
    */
    function deployQZ() {
      var attributes = {id: "qz", code:'qz.PrintApplet.class', 
      archive:"{{ URL::asset('public/template/plugins/qz-print-free_1.8.0_src/qz-print/dist/qz-print.jar') }}", width:1, height:1};
      var parameters = {jnlp_href: "{{ URL::asset('public/template/plugins/qz-print-free_1.8.0_src/qz-print/dist/qz-print_jnlp.jnlp') }}", 
      cache_option:'plugin', disable_logging:'false', 
      initial_focus:'false'};
      if (deployJava.versionCheck("1.7+") == true) {}
        else if (deployJava.versionCheck("1.6+") == true) {
          attributes['archive'] = "{{ URL::asset('public/template/plugins/qz-print-free_1.8.0_src/qz-print/dist/jre6/qz-print.jar') }}";
          parameters['jnlp_href'] = "{{ URL::asset('public/template/plugins/qz-print-free_1.8.0_src/qz-print/dist/jre6/qz-print_jnlp.jnlp') }}";
        }
        deployJava.runApplet(attributes, parameters, '1.5');
      }

    /**
    * Returns whether or not the applet is not ready to print.
    * Displays an alert if not ready.
    */
    function notReady() {
      // If applet is not loaded, display an error
      if (!isLoaded()) {
        return true;
      }
      // If a printer hasn't been selected, display a message.
      else if (!qz.getPrinter()) {
        $("#messenger").html('Please select a printer first by using the "Detect Printer" button.');
        return true;
      }
      return false;
    }
    /**
    * Returns is the applet is not loaded properly
    */
    function isLoaded() {
      if (!qz) {
        $("#messenger").html('Error:\n\n\tPrint plugin is NOT loaded!');
        return false;
      } else {
        try {
          if (!qz.isActive()) {
            $("#messenger").html('Error:\n\n\tPrint plugin is loaded but NOT active!');
            return false;
          }
        } catch (err) {
          $("#messenger").html('Error:\n\n\tPrint plugin is NOT loaded properly!');
          return false;
        }
      }
      return true;
    }
    /***************************************************************************
    * Prototype function for finding the closest match to a printer name.
    * Usage:
    *    qz.findPrinter('zebra');
    *    window['qzDoneFinding'] = function() { alert(qz.getPrinter()); };
    ***************************************************************************/
    function findPrinter() {
      if (isLoaded()) {
         var name = "ZDesigner ZT220-200dpi";//calls the findprinter function passing the string "ZPL" - ppc
         // var name = "ZDesigner ZT230-200dpi ZPL ZT220";//calls the findprinter function passing the string "ZPL" - packing


          // Searches for locally installed printer with specified name
          qz.findPrinter(name);
          
          // Automatically gets called when "qz.findPrinter()" is finished.
          window['qzDoneFinding'] = function() {
          var printer = qz.getPrinter();
          printZPL();//printing ZPL
         // Remove reference to this function
         window['qzDoneFinding'] = null;
        };
      }
    }
    function printZPL() {//function for printing

      if (notReady()) { return; }
      $("#messenger").html("printing...");
      for(var i=0;i<stickers.length;i++)
      {
        qz.append(stickers[i]);//sets which printer to print
        qz.print();//prints the stickers
     }
     $("#messenger").html("Done.");
    }

    function SaveAccessory(){
      // let url = globalLink.replace('link', 'save_accessory');
      // let login = globalLink.replace('link', 'login');

      $.ajax({
          url: 'save_accessory',
          method: 'post',
          data: $("#frmSaveAccessory").serialize(),
          dataType: 'json',
          beforeSend() {
              $("#btnSaveAccessory").prop('disabled', true);
              $("#btnSaveAccessory").html('Saving...');
              $(".input-error", $("#frmSaveAccessory")).text('');
              $(".form-control", $("#frmSaveAccessory")).removeClass('is-invalid');
              // cnfrmLoading.open();
          },
          success(data){
              $("#btnSaveAccessory").prop('disabled', false);
              $("#btnSaveAccessory").html('Save');
              // cnfrmLoading.close();
              // $("input[name='unit_no']", $("#frmSaveAccessory")).focus();

              if(data['auth'] == 1){
                  if(data['result'] == 1){
                      toastr.success('Record Saved!');
                      $("#frmSaveAccessory")[0].reset();
                      $("#mdlSaveAccessory").modal('hide');
                      $(".input-error", $("#frmSaveAccessory")).text('');
                      $(".form-control", $("#frmSaveAccessory")).removeClass('is-invalid');
                      dtAccessories.draw();
                  }
                  else{
                      toastr.error('Saving Failed!');
                      if(data['error'] != null){
                          // if(data['error']['unit_no'] != null){
                          //     $("input[name='unit_no']", $("#frmSaveAccessory")).addClass('is-invalid');
                          // }
                          // else{
                          //     $("input[name='unit_no']", $("#frmSaveAccessory")).removeClass('is-invalid');
                          // }
                      }
                  }
              }
              else{ // if session expired
                  // cnfrmAutoLogin.open();
                  toastr.error('Session Expired!');
              }

              check_btnSubmitToOQCLotApp_if_disabled()
          },
          error(xhr, data, status){
              // cnfrmLoading.close();
              $("#btnSaveAccessory").prop('disabled', false);
              $("#btnSaveAccessory").html('Save');
              toastr.error('Saving Failed!');
          }
      });
  }


    //-----

    $(document).ready(function () {
      //-----
      //-----
      //-----
      // GetMaterialKittingListByPoNo($(".selWBSMatKitItem"));

      $('#tbl_prod_runcard').on('click','.btnPrintRuncardC3Label',function(){
        $('#txt_str').val('');
        if( $('#txt_str').attr('data-java_enabled')==1 ){
          var data = {
            'txt_runcard_id'                : $(this).attr('runcard-id'),
          };
          $.ajax({
            'data'      : data,
            'type'      : 'get',
            'dataType'  : 'json',
            'url'       : 'print_c3_label',
            success     : function(data){
              var html = '';
              if($.trim(data)){
                $('#txt_str').val(data['lbl']);
                if( data['result_code']==1 ){
                  proceed_to_print();
                  toastr.success('Sent to printer.');
                 }
                else{
                  toastr.warning('No lot number to print.');
                }
              }
            }
          });
        }
        else{
          toastr.warning('Java is not loaded in Chrome. Please contact ISS local 205.');
        }
      });
      //-----
      //-----
      //-----

      bsCustomFileInput.init();
      //Initialize Select2 Elements
      $('.select2').select2();

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4',
      });

      GetProductionRuncards($("#sel_comp_under_runcard_no"), 1);
      GetUserList($(".selectUser"));
      GetCboMOD($(".selectMOD"));

      // List of Materials in select2
      GetMaterialKittingList($(".selWBSMatKitItem"));
      GetSakidashiList($(".selWBSSakIssuItem"));
      // GetEmbossList($(".selWBSEmbossIssuItem")); 

      GetCboMachine($(".selectMachine"));
      // $("#txt_search_po_number").focus();

      GetAssemblyLines($(".sel-assembly-lines"));

      $("#tblAccessories").on('click', '.btnCheckAccessory', function(){
        let accessoryId = $(this).attr('accessory-id');
        let accessoryName = $(this).attr('accessory-name');
        let quantity = $(this).attr('quantity');
        let usagePerSocket = $(this).attr('usage-per-socket');
        let counted_by = $(this).attr('counted-by');
        let counted_by_date = $(this).attr('counted-by-date');

        // alert(accessoryId);

        let accData = {
          accessoryId: accessoryId,
          accessoryName: accessoryName,
          quantity: quantity,
          usagePerSocket: usagePerSocket,
          counted_by: counted_by,
          counted_by_date: counted_by_date,
        };

        // console.log(accData);

        if($(this).prop('checked')){
          arrPrintAccessories.push(accData);
          // if(!arrPrintAccessories.includes(accessoryId)){
          //     arrPrintAccessories.push(accessoryId);
          // }
        }
        else{
          let index = arrPrintAccessories.indexOf(accessoryId);
          arrPrintAccessories.splice(index, 1);
        }

        console.log(arrPrintAccessories);

        // console.log(accessoryId);
        // console.log(accessoryName);
        // console.log(quantity);
        // console.log(usagePerSocket);
        // console.log(counted_by);
        // console.log(counted_by_date);
      });

      $("#tblAccessories").on('click', '.btnAccessoryActions', function(){
        $('#modal_qrcode_scanner_for_delete_accesory').modal('show')
        $('#modal_qrcode_scanner_for_delete_accesory_employee_id').val('')
        $('#modal_qrcode_scanner_for_delete_accesory_employee_id').focus()
        $('#modal_qrcode_scanner_for_delete_accesory_id').val( $(this).attr('accessory-id') )
      });

      $(document).on('keyup','#modal_qrcode_scanner_for_delete_accesory_employee_id',function(e){
        if( e.keyCode == 13 ){
          // alert( $('#modal_qrcode_scanner_for_delete_accesory_id').val() )

          $.ajax({
            'data'      : { employee_id: $("#modal_qrcode_scanner_for_delete_accesory_employee_id").val() },
            'type'      : 'get',
            'dataType'  : 'json',
            'url'       : 'check_employee_id',
            success     : function(data){
              if( data['result'] ){

                $.ajax({
                  'data'      : {
                    _token: '{{ csrf_token() }}',
                    id: $('#modal_qrcode_scanner_for_delete_accesory_id').val()
                  },
                  'type'      : 'post',
                  'dataType'  : 'json',
                  'url'       : 'delete_runcard_accessory',
                  success     : function(data){
                    if( data['result']==1 ){
                      dtAccessories.draw();
                      toastr.success('Accessory deleted successfully.');
                    }else{
                      toastr.error('Something went wrong.');
                    }

                    check_btnSubmitToOQCLotApp_if_disabled()
                    $('#modal_qrcode_scanner_for_delete_accesory').modal('hide')

                  }
                })

              }
              else
                toastr.error( 'Invalid Employee ID' )
            },
            completed     : function(data){

            },
            error     : function(data){

            },
          });

        }
      });

      $("select[name='issuance_id']", $("#frmSaveAccessory")).change(function(){
        let itemDesc = $('#selSaveAccessoryName option:selected').attr('item-desc');
        let issuedQty = parseFloat($('#selSaveAccessoryName option:selected').attr('usage')) * parseFloat($("#txt_po_qty").val());
        let usage = $('#selSaveAccessoryName option:selected').attr('usage');

        // $("input[name='issuance_id']", $("#frmSaveAccessory")).val(itemDesc);
        $("input[name='quantity']", $("#frmSaveAccessory")).val(usage);
        $("input[name='usage_per_socket']", $("#frmSaveAccessory")).val(issuedQty);
      });

      $("input[name='require_supervisor']", $("#frmSaveAccessory")).click(function(){
        if($(this).prop('checked')){
          $("select[name='prod_supervisor']", $("#frmSaveAccessory")).val(null).trigger('change').prop('disabled', false);
          $("input[name='prod_supervisor_date']", $("#frmSaveAccessory")).val('').prop('disabled', false);
        }
        else{
          $("select[name='prod_supervisor']", $("#frmSaveAccessory")).val(null).trigger('change').prop('disabled', true);
          $("input[name='prod_supervisor_date']", $("#frmSaveAccessory")).val('').prop('disabled', true);
        }
      });

      $("#btnSaveAccessory").click(function(){
        SaveAccessory();
      });

       $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
          var target = $(e.target).attr("href") // activated tab
          // alert(target);
          
          if(target == "#home"){
            $("#txt_search_material_transfer_slip").focus();
            $("#btnSaveSelectedMatSak").css({'display': 'block'});
            // $("#btnSaveSelectedEmboss").css({'display': 'none'});
          }
          else if(target == "#profile"){
            $("#txt_search_sakidashi_lot_no").focus();
            $("#btnSaveSelectedMatSak").css({'display': 'block'});
            // $("#btnSaveSelectedEmboss").css({'display': 'none'});
          }
          // else if(target == "#materialEmbossTab"){
          //   $("#txt_search_emboss_lot_no").focus();
          //   $("#btnSaveSelectedEmboss").css({'display': 'block'});
          //   $("#btnSaveSelectedMatSak").css({'display': 'none'});
          // }
        });

      // $('input').each(function(i, obj) {
      //   if (!this.hasAttribute("placeholder")) {
      //     if( $(this).prop('type') == 'number' ){
      //       $(this).prop('placeholder','0');
      //     }
      //     if( $(this).prop('type') == 'text' ){
      //       $(this).prop('placeholder','---');
      //     }
      //   }
      // });

      // $("#btnAddProcess").click(function(){
      //   $('#mdl_edit_prod_runcard_station_details').modal('show');
      //   $('#frm_edit_prod_runcard_station_details')[0].reset();
      // });

      $("#btnAddAccessory").click(function(){
        $('#mdlSaveAccessory').modal('show');
        let usage = $("input[name='quantity']", $("#frmSaveAccessory")).val();
        let issuedQty = $("input[name='usage_per_socket']", $("#frmSaveAccessory")).val();

        $('#frmSaveAccessory')[0].reset();
        $("input[name='prod_runcard_id']", $("#frmSaveAccessory")).val($("#txt_prod_runcard_id_query").val());
        $("select[name='issuance_id']", $("#frmSaveAccessory")).val(0).trigger('change');
        $("select[name='counted_by']", $("#frmSaveAccessory")).val(0).trigger('change');
        $("select[name='checked_by']", $("#frmSaveAccessory")).val(0).trigger('change');
        $("select[name='prod_supervisor']", $("#frmSaveAccessory")).val(null).trigger('change');

        $("input[name='product_name']", $("#frmSaveAccessory")).val($("#txt_use_for_device").val());

        $("input[name='quantity']", $("#frmSaveAccessory")).val('');
        $("input[name='usage_per_socket']", $("#frmSaveAccessory")).val('');

        // $("input[name='quantity']", $("#frmSaveAccessory")).val(usage);
        // $("input[name='usage_per_socket']", $("#frmSaveAccessory")).val(issuedQty);
      });

      $("#btnPrintAccessory").click(function(){

        var str = '';
        str += $('#txtAccPrintBody').val();

        if(arrPrintAccessories.length > 0){
          for(let index = 0; index < arrPrintAccessories.length; index++) {
            // $("#txtAccProdName").html($("#txt_use_for_device").val());
            // $("#txtAccAccessoryName").html(arrPrintAccessories[index].accessoryName);
            // $("#txtAccQuantity").html(arrPrintAccessories[index].quantity);
            // $("#txtAccUsagePerSocket").html(arrPrintAccessories[index].usagePerSocket);
            // $("#txtAccCountedByDate").html(arrPrintAccessories[index].counted_by);
            // $("#txtAccCheckedByDate").html(arrPrintAccessories[index].counted_by_date);

            // console.log(arrPrintAccessories[index].accessoryName);

            // str += $('#txtAccPrintBody2').val();

            str += '<div class="col-sm-6">'
              str += '<div style="border: 1px solid black;">'
                str += '<table class="table1 text-middle" cellspacing="0" cellpadding="0" border="0">'
                  str += '<tbody>'
                    str += '<tr>'
                      str += '<td colspan="2" class="text-bottom">'
                        str += '<img class="logo-left" src="' + $('#img_pricon_logo').attr('src') + '" style="max-height: 25px; max-width: : 40px; margin-left: 1px; margin-top: 2px;">'
                      str += '</td>';
                    str += '</tr>';
                    str += '<tr>';
                      str += '<td colspan="6" class="bold text-center" style="margin-bottom: 5px;">Accessory Tag</td>';
                    str += '</tr>';
                    str += '<tr>';
                      str += '<td style="height: 1px; width: 16%"></td>';
                      str += '<td style="height: 1px; width: 16%"></td>';
                      str += '<td style="height: 1px; width: 16%"></td>';
                      str += '<td style="height: 1px; width: 16%"></td>';
                      str += '<td style="height: 1px; width: 16%"></td>';
                      str += '<td style="height: 1px; width: 16%"></td>';
                    str += '</tr>';
                    str += '<tr>';
                      str += '<td colspan="6">Product Name: <u class="bold" id="txtAccProdName">' + $("#txt_use_for_device").val() + '</u></td>';
                    str += '</tr>';
                    str += '<tr>';
                      str += '<td colspan="6">Accessory Name: <u class="bold" id="txtAccAccessoryName">' + arrPrintAccessories[index].accessoryName + '</u></td>';
                    str += '</tr>';
                    str += '<tr>';
                      str += '<td colspan="3">Quantity: <u class="bold" id="txtAccQuantity">' + arrPrintAccessories[index].quantity + '</u></td>';
                      str += '<td colspan="3">Usage per Socket: <u class="bold" id="txtAccUsagePerSocket">' + arrPrintAccessories[index].usagePerSocket + '</u></td>';
                    str += '</tr>';
                    str += '<tr>';
                      str += '<td colspan="6"></td>';
                    str += '</tr>';
                    str += '<tr>';
                      str += '<td colspan="6">Counted by/Date: <u class="bold" id="txtAccCountedByDate">' + arrPrintAccessories[index].counted_by + '</u></td>';
                    str += '</tr>';
                    str += '<tr>';
                      str += '<td colspan="6">Checked by/Date: Visual Optr.: <u class="bold" id="txtAccCheckedByDate">' + arrPrintAccessories[index].counted_by_date + '</u></td>';
                    str += '</tr>';
                    str += '<tr>';
                      str += '<td colspan="1"></td>';
                      str += '<td colspan="5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; QC Inspector: <u class="bold" id="txtAccInspector">N/A</u></td>';
                    str += '</tr>';
                  str += '</tbody>';
                str += '</table>';
              str += '</div>';
              
            str += '</div>';

          }
        }
        else{
          toastr.warning('No selected accessory.');
        }
        str += $('#txtAccPrintBody3').val();
        // txtAccProdName
        // txtAccAccessoryName
        // txtAccQuantity
        // txtAccUsagePerSocket
        // txtAccCountedByDate
        // txtAccCheckedByDate

        popup = window.open();
        let content = '';
        content = str;
        popup.document.write(content);
        // popup.focus(); //required for IE
        // popup.print();
        // popup.close();

        setTimeout(function(){ 
          popup.focus(); //required for IE
          popup.print();
          popup.close();
        }, 1000);

        // var str = '';
        // str += $('#txt_html_sticker_container_1').val();
        // str += $('#txt_html_plastic_container_1').val();
        // // str += $(this).closest('tr').find('.html_plastic_item').val();

        // // str += '<tr>';
        // //   str += '<td></td>';
        // //   str += '<td colspan="2" class="border-bottom">' + '$this->ctr' + ' +  ' + '$box_accessory_name' + '</td>';
        // //   str += '<td></td>';
        // //   str += '<td class="border-bottom text-center bold">' + '$accessory_tag_items->qty' + '</td>';
        // //   str += '<td></td>';
        // // str += '</tr>';
        // // str += '<tr>';
        // //   str += '<td colspan="6">Product Name: <u class="bold">' + '$box_device_name' + '</u></td>';
        // // str += '</tr>';
        // // str += '<tr>';
        // //   str += '<td colspan="6">Accessory Name: <u class="bold">' + '$box_accessory_name' + '</u></td>';
        // // str += '</tr>';
        // // str += '<tr>';
        // //   str += '<td colspan="3">Quantity: <u class="bold">' + '$accessory_tag_items->qty' + '</u></td>';
        // //   str += '<td colspan="3">Usage per Socket: <u class="bold">' + '$accessory_tag_items->usage_qty' + '</u></td>';
        // // str += '</tr>';
        // // str += '<tr>';
        // //   str += '<td colspan="6"></td>';
        // // str += '</tr>';
        // // str += '<tr>';
        // //   str += '<td colspan="6">Counted by/Date: <u class="bold">' + '$accessory_tag_items->user_counted_by->name' + ' / ' +  '$accessory_tag_items->counted_at' + '</u></td>';
        // // str += '</tr>';


        // str += $('#txt_html_plastic_container_2').val();
        // str += $('#txt_html_sticker_container_2').val();

        // let content = '';

        // let content = '';
        //   content += '<html>';
        //   content += '<head>';
        //     content += '<title></title>';
        //     content += '<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">';
        //     content += '<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></ ' + 'script>';
        //     content += '<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></ ' + 'script>';
        //     content += '<style type="text/css">';
        //       content += '.divBorder{';
        //         content += 'border: 2px solid black;';
        //               content += 'min-width: 225px;';
        //               content += 'margin-top: 10px;';
        //       content += '}';
        //     content += '</style>';
        //   content += '</head>';
        //   content += '<body>';
        //     content += '<div class="container-fluid">';
        //       content += '<div class="row">';

        //         for(let index = 0; index < arrPrintAccessories.length; index++) {
        //           content += '<div class="col-sm-6">';
        //             content += '<div class="divBorder">';
        //               // content += '<center>';
        //                 content += '<table>';
        //                   content += '<tr>';
        //                     content += '<td>';
        //                       // content += '<center>';
        //                         content += '<img src="{{ asset("public/images/pricon-logo.png?v=2") }}"></img>';
        //                       // content += '</center>';
        //                     content += '</td>';
        //                     content += '<td>';
        //                       content += '<label style="text-align: left; font-weight: bold; font-family: Arial; font-size: 18px;">' + $('#txt_use_for_device').val() + '</label>';
        //                       content += '<br>';
        //                       content += '<label style="text-align: left; font-family: Arial Narrow; font-size: 18px;">' + $('#txt_use_for_device').val() + '</label>';
        //                     content += '</td>';
        //                   content += '</tr>';
        //                 content += '</table>';
        //               // content += '</center>';
        //             content += '</div>';
        //           content += '</div>';
        //         }

        //       content += '</div>';
        //     content += '</div>';
        //   content += '</body>';
        //   content += '</html>';

        // popup = window.open();
        // let str = '';
        // str = content;
        // document.write(str);
        // // popup.focus(); //required for IE
        // // popup.print();
        // // popup.close();

        // return;

        if(arrPrintAccessories.length > 0){

          // PrintAccessories(arrPrintAccessories);

          // popup = window.open();
          // let content = '';
          // content += '<html>';
          // content += '<head>';
          //   content += '<title></title>';
          //   content += '<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">';
          //   content += '<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></ ' + 'script>';
          //   content += '<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></ ' + 'script>';
          //   content += '<style type="text/css">';
          //     content += '.divBorder{';
          //       content += 'border: 2px solid black;';
          //             content += 'min-width: 225px;';
          //             content += 'margin-top: 10px;';
          //     content += '}';
          //   content += '</style>';
          // content += '</head>';
          // content += '<body>';
          //   content += '<div class="container-fluid">';
          //     content += '<div class="row">';

          //       for(let index = 0; index < arrPrintAccessories.length; index++) {
          //         content += '<div class="col-sm-4">';
          //           content += '<div class="divBorder">';
          //             // content += '<center>';
          //               content += '<table>';
          //                 content += '<tr>';
          //                   content += '<td>';
          //                     // content += '<center>';
          //                       content += '<img src="{{ asset("public/images/pricon-logo.png?v=2") }}"></img>';
          //                     // content += '</center>';
          //                   content += '</td>';
          //                   content += '<td>';
          //                     content += '<label style="text-align: left; font-weight: bold; font-family: Arial; font-size: 18px;">' + $('#txt_use_for_device').val() + '</label>';
          //                     content += '<br>';
          //                     content += '<label style="text-align: left; font-family: Arial Narrow; font-size: 18px;">' + $('#txt_use_for_device').val() + '</label>';
          //                   content += '</td>';
          //                 content += '</tr>';
          //               content += '</table>';
          //             // content += '</center>';
          //           content += '</div>';
          //         content += '</div>';
          //       }

          //     content += '</div>';
          //   content += '</div>';
          // content += '</body>';
          // content += '</html>';
          // // alert(content);
          // // popup = window.open();
          // popup.document.write(content);
          // // popup.focus(); //required for IE
          // // popup.print();
          // // popup.close();
        }
        else{
          toastr.warning('No selected accessory.');
        }

        // var str = '';
        // str += $('#txt_html_sticker_container_1').val();
        // str += $('#txt_html_plastic_container_1').val();
        // // str += $(this).closest('tr').find('.html_plastic_item').val();

        // // str += '<tr>';
        // //   str += '<td></td>';
        // //   str += '<td colspan="2" class="border-bottom">' + '$this->ctr' + ' +  ' + '$box_accessory_name' + '</td>';
        // //   str += '<td></td>';
        // //   str += '<td class="border-bottom text-center bold">' + '$accessory_tag_items->qty' + '</td>';
        // //   str += '<td></td>';
        // // str += '</tr>';
        // // str += '<tr>';
        // //   str += '<td colspan="6">Product Name: <u class="bold">' + '$box_device_name' + '</u></td>';
        // // str += '</tr>';
        // // str += '<tr>';
        // //   str += '<td colspan="6">Accessory Name: <u class="bold">' + '$box_accessory_name' + '</u></td>';
        // // str += '</tr>';
        // // str += '<tr>';
        // //   str += '<td colspan="3">Quantity: <u class="bold">' + '$accessory_tag_items->qty' + '</u></td>';
        // //   str += '<td colspan="3">Usage per Socket: <u class="bold">' + '$accessory_tag_items->usage_qty' + '</u></td>';
        // // str += '</tr>';
        // // str += '<tr>';
        // //   str += '<td colspan="6"></td>';
        // // str += '</tr>';
        // // str += '<tr>';
        // //   str += '<td colspan="6">Counted by/Date: <u class="bold">' + '$accessory_tag_items->user_counted_by->name' + ' / ' +  '$accessory_tag_items->counted_at' + '</u></td>';
        // // str += '</tr>';


        // str += $('#txt_html_plastic_container_2').val();
        // // str += $('#txt_html_sticker_container_2').val();

        // popup = window.open();
        // let content = '';
        // content = str;
        // popup.document.write(content);
        // popup.focus(); //required for IE
        // popup.print();
        // popup.close();
      });

        // function PrintAccessories(data){
        //     popup = window.open();
        //     // popup.document.write('<br><br><div style="border: 2px solid black; padding: 1px 1px; max-width: 100px;" class="rotated"><img src="' + imgResultUserQrCode + '" style="max-width: 100px;"><br><center><label style="text-align: center; font-weight: bold; font-family: Arial;">' + qrcode + '</label></center></div>');
        //     let content = '';
        //     content += '<html>';
        //     content += '<head>';
        //       content += '<title></title>';
        //       content += '<style type="text/css">';
        //         // content += '.rotated {';
        //         //   // content += 'transform: rotate(270deg); /* Equal to rotateZ(45deg) */';
        //         //   // content += 'border: 2px solid black;';
        //         //   content += 'width: 160px;';
        //         //   content += 'position: absolute;';
        //         //   content += 'left: 8.5px;';
        //         //   content += 'top: 12px;';
        //         // content += '}';

        //         content += 'td {';
        //           // content += 'transform: rotate(270deg); /* Equal to rotateZ(45deg) */';
        //           content += 'padding: 1px 1px;';
        //           content += 'margin: 1px 1px;';
        //           content += 'width: 120px;';
        //         content += '}';

        //         content += '.vl {';
        //           content += 'border-right: 1px dashed black;';
        //           content += 'height: 60px;';
        //           content += 'float: right;';
        //         content += '}';

        //         content += '.vl1 {';
        //           content += 'border-right: 1px dashed black;';
        //           content += 'height: 15px;';
        //           content += 'float: right;';
        //         content += '}';

        //         content += '.title {';
        //           content += 'font-size: 7px;';
        //           content += 'float: center;';
        //           content += 'font-weight: bold;';
        //           content += 'font-family: arial;';
        //         content += '}';

        //         content += '.tempcss{ margin:0 0 0 10px !important;  } .print_border{ border:1px solid #000; padding:0 20px; } @media print { div.break {page-break-after: always;} }';

        //       content += '</style>';
        //     content += '</head>';
        //     content += '<body>';
        //         content += '<center>';
        //         // content += '<div class="rotated">';
        //       for(let index = 0; index < data; index++){
        //         content += '<table style="width: 100%; margin: 1px 1px; padding: 1px 1px;">';
        //         content += '<tr>';
        //         content += '<td>';
        //         content += '<center>';
        //         content += '<img src="{{ asset("public/images/pricon-logo.png?v=2") }}"></img>';
        //         content += '</center>';
        //         content += '</td>';

        //         // let lotNoSrc = $("#txtSrcGenWHMatIssuIdBarcode").val();
        //         // if(data.length > 0){
        //         //     lotNoSrc = data[index]['raw_qrcode'];
        //         // }

        //         // content += '<td>';
        //         // content += '<center>';
        //         // content += '<img src="{{ asset("public/images/pricon-logo.png?v=2") }}"></img>';
        //         // content += '</center>';
        //         // content += '</td>';

        //         // content += '<td>';
        //         // content += '<center>';
        //         // content += '<img src="' + $("#txtSrcGenWHMatIssuIdBarcode2").val() + '" style="min-width: 50px; max-width: 50px;">';
        //         // content += '</center>';
        //         // content += '</td>';
        //         // content += '</tr>';

        //         // content += '<tr>';
        //         // content += '<td>';
        //         // content += '<center>';
        //         // content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">' + $("#txtPrintSubKitPONo").val() + '<br> '+ $("#txtPrintSubKitKitNo").val() + ' - ' + $("#txtPrintSubKitKitter").val() + '</label>';
        //         // content += '<br>';
        //         // content += '</center>';
        //         // content += '</td>';

        //         // let kitCounter = $("#txtPrintSubKitCounter").val();
        //         // if(data.length > 0){
        //         //     kitCounter = data[index]['sub_kit_desc'].split(' | ')[9];
        //         // }

        //         // content += '<td>';
        //         // content += '<center>';
        //         // content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">' + kitCounter + '<br>' + $("#txtPrintSubKitLotNo").val() + '<br>QTY ';
        //         //   if(data.length > 0){
        //         //       content += data[index]['sub_kit_qty'];
        //         //   }
        //         //   else{
        //         //     content += txtPrintSubKitSubKitQty;
        //         //   }
        //         // content += ' pc(s) ' + '</label>';
        //         // content += '<br>';
        //         // content += '</center>';
        //         // content += '</td>';

        //         // content += '<td>';
        //         // content += '<center>';
        //         // content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">' + $("#txtPrintSubKitItemCode").val() + '<br> '+ $("#txtPrintSubKitItemDesc").val() + '</label>';
        //         // content += '<br>';
        //         // content += '</center>';
        //         // content += '</td>';
        //         // content += '</tr>';

        //         // let date = new Date();


        //         // content += '<tr>';
        //         // content += '<td colspan="3" style="padding: 1px 1px;" cellspacing="0">';
        //         // content += '<center><label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px; margin 1px 1px;">';
        //         //   content += date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate() + ' ' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds();
        //         // // content += '<br><br><br><br>';
        //         // //   if(copies > 1){
        //         // //     if(index == (copies - 1)){
        //         // //         // content += '';
        //         // //     }
        //         // //     else{
        //         // //       content += '<br><br>';
        //         // //     }
        //         // //   }
        //         // content += '</label>';
        //         // content += '</center></td>';

        //         // content += '</tr>';

        //         content += '</table>';
        //       }
        //         // content += '<div class="break"></div>';   
        //         // content += '</div>';
        //         content += '</center>';
        //     content += '</body>';
        //     content += '</html>';
        //     popup.document.write(content);

        //     setTimeout(function(){ 
        //       popup.focus(); //required for IE
        //       popup.print();
        //       popup.close();
        //     }, 1000);
        // }

      $('select[name="setup_qualification"]', $("#frm_edit_material_details")).change(function(){
        if($(this).val() == 1){
          $('select[name="setup_qualification"]', $("#frm_edit_material_details")).prop('disabled', false);
          $('input[name="eng_qualification_name"]', $("#frm_edit_material_details")).val('');
          $('input[name="eng_qualification_id"]', $("#frm_edit_material_details")).val('');
          $('select[name="setup_qualified"]', $("#frm_edit_material_details")).prop('disabled', false);
          $('.btnSelEngQual', $("#frm_edit_material_details")).prop('disabled', false);
          $('.btnClearEngQual', $("#frm_edit_material_details")).prop('disabled', false);
        }
        else{
          $('.btnSelEngQual', $("#frm_edit_material_details")).prop('disabled', true);
          $('.btnClearEngQual', $("#frm_edit_material_details")).prop('disabled', true);
          $('select[name="setup_qualified"]', $("#frm_edit_material_details")).prop('disabled', true);
          $('select[name="setup_qualified"]', $("#frm_edit_material_details")).val('0');
        }
      });

      $('select[name="qc_qualification"]', $("#frm_edit_material_details")).change(function(){
        if($(this).val() == 1){
          $('select[name="qc_qualification"]', $("#frm_edit_material_details")).prop('disabled', false);
          $('input[name="qc_stamp_name"]', $("#frm_edit_material_details")).val('');
          $('input[name="qc_stamp_id"]', $("#frm_edit_material_details")).val('');
          $('select[name="qc_qualified"]', $("#frm_edit_material_details")).prop('disabled', false);
          $('.btnSelQCStamp', $("#frm_edit_material_details")).prop('disabled', false);
          $('.btnClearQCStamp', $("#frm_edit_material_details")).prop('disabled', false);
          $('input[name="qc_stamp_name"]', $("#frm_edit_material_details")).val('');
          $('input[name="qc_stamp_id"]', $("#frm_edit_material_details")).val('');
        }
        else{
          $('.btnSelQCStamp', $("#frm_edit_material_details")).prop('disabled', true);
          $('.btnClearQCStamp', $("#frm_edit_material_details")).prop('disabled', true);
        }
      });

      $("#btnPrintRuncardBarcode").click(function(){
        // PrintWHMatIssu();
          popup = window.open();
          // popup.document.write('<br><br><div style="border: 2px solid black; padding: 1px 1px; max-width: 100px;" class="rotated"><img src="' + imgResultUserQrCode + '" style="max-width: 100px;"><br><center><label style="text-align: center; font-weight: bold; font-family: Arial;">' + qrcode + '</label></center></div>');
          let content = '';
          content += '<html>';
          content += '<head>';
            content += '<title></title>';
            content += '<style type="text/css">';
              content += '.rotated {';
                // content += 'transform: rotate(270deg); /* Equal to rotateZ(45deg) */';
                // content += 'border: 2px solid black;';
                content += 'width: 160px;';
                content += 'position: absolute;';
                content += 'left: 8.5px;';
                content += 'top: 12px;';
              content += '}';

              content += 'td {';
                // content += 'transform: rotate(270deg); /* Equal to rotateZ(45deg) */';
                content += 'padding: 1px 1px;';
                content += 'margin: 1px 1px;';
                content += 'width: 70px;';
              content += '}';

              content += '.vl {';
                content += 'border-right: 1px dashed black;';
                content += 'height: 60px;';
                content += 'float: right;';
              content += '}';

              content += '.vl1 {';
                content += 'border-right: 1px dashed black;';
                content += 'height: 10px;';
                content += 'float: right;';
              content += '}';

              content += '.title {';
                content += 'font-size: 7px;';
                content += 'float: center;';
                content += 'font-weight: bold;';
                content += 'font-family: arial;';
              content += '}';

            content += '</style>';
          content += '</head>';
          content += '<body>';
            content += '<center>';
            content += '<div class="rotated">';
            content += '<table>';
            content += '<tr>';
            content += '<td class="vl">';
            content += '<center>';
            content += '<span class="title">PO #</span>';
            content += '<img src="' + $("#imgGenRuncardPoNoBarcode").attr('src') + '" style="min-width: 60px; max-width: 60px;">';
            content += '</center>';
            content += '</td>';

            content += '<td>';
            content += '<center>';
            content += '<span class="title">RUNCARD #</span>';
            content += '<img src="' + $("#imgGenRuncardBarcode").attr('src') + '" style="min-width: 60px; max-width: 60px;">';
            content += '</center>';
            content += '</td>';
            content += '</tr>';

            content += '<tr>';
            content += '<td class="vl1">';
            content += '<center>';
            content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 8px;">' + $("#lblRuncardPoNo").text() + '</label>';
            content += '</center>';
            content += '</td>';
            content += '<td>';
            content += '<center>';
            content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 8px;">' + $("#lblRuncardNo").text() + '</label>';
            content += '</center>';
            content += '</td>';
            content += '</tr>';
            content += '</table>';
            content += '</div>';
            content += '</center>';
          content += '</body>';
          content += '</html>';
          popup.document.write(content);
          popup.focus(); //required for IE
          popup.print();
          popup.close();
        });

      $("#tblEditProdRunStaMOD").on('keyup', '.txtEditProdRunStaMODQty', function(){
        // console.log('wew');
        let totalNoOfMOD = 0;

        if($(this).val() > $("#txt_edit_prod_runcard_station_ng").val()){
          // toastr.warning('NG Qty limitation reached!');
          $("#pRCStatTotNoOfNG").css({color: 'red'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', true);
        }
        else{
          $('#tblEditProdRunStaMOD .txtEditProdRunStaMODQty').each(function(i, obj) {
            if($(this).val() == null || $(this).val() == ""){
            }
            else{
              totalNoOfMOD += parseFloat($(this).val());
            }
          });

          if($("#txt_edit_prod_runcard_station_ng").val() != totalNoOfMOD){
            toastr.warning('MOD NG Qty not Tally!');
            $("#pRCStatTotNoOfNG").css({color: 'red'});
            $("#btn_save_prod_runcard_station_stations").prop('disabled', true);
          }
          else{
            $("#pRCStatTotNoOfNG").css({color: 'green'});
            $("#btn_save_prod_runcard_station_stations").prop('disabled', false);
          }
          $("#pRCStatTotNoOfNG").text(totalNoOfMOD);
        }

      });

      $("#tblEditProdRunStaMOD").on('change', '.txtEditProdRunStaMODQty', function(){
        // console.log('wew');
        let totalNoOfMOD = 0;

        $('#tblEditProdRunStaMOD .txtEditProdRunStaMODQty').each(function(i, obj) {
          if($(this).val() == null || $(this).val() == ""){
          }
          else{
            totalNoOfMOD += parseFloat($(this).val());
          }
        });
        if($("#txt_edit_prod_runcard_station_ng").val() != totalNoOfMOD){
          toastr.warning('MOD NG Qty not Tally!');
          $("#pRCStatTotNoOfNG").css({color: 'red'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', true);
        }
        else{
          $("#pRCStatTotNoOfNG").css({color: 'green'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', false);
        }
        $("#pRCStatTotNoOfNG").text(totalNoOfMOD);
      });

      $('textarea').each(function(i, obj) {
        if (!this.hasAttribute("placeholder")) {
          $(this).prop('placeholder','...');
        }
      });

      //---
      // currentPoNo = '450196479600010'; //remove this after
      dt_prod_runcard      = $('#tbl_prod_runcard').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "get_prod_runcard_by_po",
            data: function (param){
                param.po_number = currentPoNo;
            }
          },
          bAutoWidth: false,

          "columns":[
            // { "data" : "raw_checkbox", orderable:false, searchable:false },
            { "data" : "raw_action", orderable:false, searchable:false },
            { "data" : "raw_status", orderable:false, searchable:false },
            { "data" : "lot_no" },
            // { "data" : "pair_no" },
            { "data" : "created_at", orderable:true, searchable:false, visible: false, },
            { "data" : "raw_qty_output_sum" },
            { "data" : "raw_qty_ng_sum" },
            { "data" : "remarks" },
            // { "data" : "total_no_of_emboss_ng" },
            // { "data" : "prod_runcard_station_many_details.0.qty_ng" },

            // total_no_of_ng
            // {
            //     name: '',
            //     data: null,
            //     sortable: false,
            //     searchable: false,
            //     render: function (data) {
            //         var actions = '';
            //         actions += '<a href="/transaksi-masuk/tambah/:id"><span class="label label-primary">TAMBAH</span></a>';
            //         actions += '<a href="/transaksi-masuk/edit/:id"><span class="label label-warning">EDIT</span></a>';
            //         return actions.replace(/:id/g, data.lot_no);
            //     }
            // }
          ],
          "columnDefs": [
            {
              "targets": [5, 6],
              "data": null,
              "defaultContent": "--"
            },
          ],
          order: [[2, "desc"]],
          "rowCallback": function(row,data,index ){
            currentPoNo = data['po_no'];
            $('#txt_po_number_lbl').val( data['po_no'] );
            $('#txt_device_name_lbl').val( data['wbs_kitting']['device_name'] );
            $('#txt_po_qty_lbl').val( data['wbs_kitting']['po_qty'] );
            // $("#txt_search_po_number").val("");
          },
          "drawCallback": function(row,data,index ){
            GetMaterialKittingListByPoNo($(".selAccessoryName"), $('#txt_po_number_lbl').val());

            $(".chkSelProdRuncard").each(function(index){
                if(arrSelectedRuncards.includes($(this).attr('production-runcard-id'))){
                    $(this).attr('checked', 'checked');
                }
            });

            // dt_setup_stations.draw();
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last = null;

            // Temporary -> switch comment
            GetMaterialKitting();
            // GetSakidashiIssuance();
            // if(api.rows().count() <= 0){
            //   GetMaterialKitting();
            // }

            if(api.rows().count() > 0){
              // totalNoOfOk = 0;
              // totalNoOfNG = 0;
              // let data = api.data();
              // let recount_ok = data.recount_ok;
              // let recount_ng = data.recount_ng;

              api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                  let data = api.row(i).data();

                  // if(data['last_runcard_po_qty'] != null){
                  // totalNoOfOk += parseInt(data['raw_qty_output_sum']);
                  // }
                  // let recount_ok = data.recount_ok;
                  // let recount_ng = data.recount_ng;
                  // if(data.prod_runcard_station_details != null){
                  //   console.log(data.prod_runcard_station_details);
                  //   if(){

                  //   }
                  // }
                  // if(data.prod_runcard_station_many_details.length > 0 && data.prod_runcard_station_many_details[0]['qty_ng'] != null){
                  //   totalNoOfNG += parseInt(data.prod_runcard_station_many_details[0]['qty_ng']);
                  // }
                  // console.log(data);

                  // Temp comment
                  // if(data.prod_runcard_station_many_details.length > 0 && data.prod_runcard_station_many_details[0]['qty_output'] != null){
                  //   totalNoOfOk += parseInt(data.prod_runcard_station_many_details[0]['qty_output']);
                  // }

                  // console.log(data['prod_runcard_station_many_details'].length);

                  // if(data['prod_runcard_station_many_details'].length > 0){
                  //   // for(let index = 0; index < data['prod_runcard_station_many_details'].length; index++){
                  //   //   if(data['prod_runcard_station_many_details'][index].qty_output != null){

                  //   //     totalNoOfOk += parseInt(data['prod_runcard_station_many_details'][index].qty_output);
                  //   //   }
                  //   //   // console.log(data['prod_runcard_station_many_details'][index].qty_output);
                  //   // }

                  //   // for(let index = data['prod_runcard_station_many_details'].length - 1; index >= 0; index--){
                  //   //   // Get Not Emboss
                  //   //   if(data['prod_runcard_station_many_details'][index].qty_output != null){
                  //   //     if(data['prod_runcard_station_many_details'][index]['has_emboss'] == 1){
                  //   //       totalNoOfOk += parseInt(data['prod_runcard_station_many_details'][index].qty_output);
                  //   //       break;
                  //   //     }
                  //   //   }
                  //   // }

                  //   for(let index = data['prod_runcard_station_many_details'].length - 1; index >= 0; index--){
                  //       // Get Not Emboss
                  //       if(data['prod_runcard_station_many_details'][index]['has_emboss'] == 0){
                  //         if(data['prod_runcard_station_many_details'][index]['has_emboss'] == 0){
                  //           if(data['prod_runcard_station_many_details'][index]['qty_output'] != null){
                  //             totalNoOfOk += parseInt(data['prod_runcard_station_many_details'][index]['qty_output']);
                  //             // console.log(data['prod_runcard_station_many_details'][index]['qty_output']);
                  //             break;
                  //           }
                  //         }
                  //       }
                  //     }
                  // }

                  // if(data.length > 0){
                  //     console.log(data);
                  //   for(let index = 0; index < data.length; index++){
                  //     if(data[index]['prod_runcard_station_many_details'].length > 0){
                  //       for(let index2 = data[index]['prod_runcard_station_many_details'].length - 1; index2 >= 0; index2--){
                  //         // Get Not Emboss
                  //         if(data[index]['prod_runcard_station_many_details'][index2]['has_emboss'] == 0){
                  //           totalNoOfOk = data[index]['prod_runcard_station_many_details'][index2]['qty_output'];
                  //           console.log(data[index]['prod_runcard_station_many_details'][index2]['qty_output']);
                  //           break;
                  //         }
                  //       }
                  //     }
                  //     else{
                  //       totalNoOfOk = 0;
                  //     }

                  //   }
                  // }
                  // else{
                  //   totalNoOfOk = 0;
                  // }

                  // totalNoOfOk += parseInt(data.raw_qty_output_sum);
                  // totalNoOfNG += parseInt(data.raw_qty_ng_sum);
              });

              // console.log(totalNoOfOk);
              // console.log(totalNoOfNG);
              $("#btnShowNGSummary").prop('disabled', false);
              // $("#txt_total_no_of_ok").val(totalNoOfOk);
              // $("#txt_total_no_of_ng").val(totalNoOfNG);

              // $("#btn_edit_material_details_verification").removeAttr('disabled');
            }
            else{
              // $("#btn_edit_material_details_verification").prop('disabled', 'disabled');
              // totalNoOfOk = 0;
              // totalNoOfNG = 0;
              // $("#txt_total_no_of_ok").val("");
              // $("#txt_total_no_of_ng").val("");
              $("#btnShowNGSummary").prop('disabled', true);
            }
          },
          paging: false,
      });//end of DataTable

      dt_materials      = $('#tbl_materials').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "view_materials_by_runcard_id",
            data: function (param){
                param.runcard_id = $("#txt_prod_runcard_id_query").val();
            }
          },
          bAutoWidth: false,
          "columns":[
            { "data" : "material_info.name", orderable:false, searchable:false },
            { "data" : "lot_no", orderable:false, searchable:false },
            { "data" : "raw_type", orderable:false, searchable:false },
          ],
          paging: false,
          info: false,
          // searching: false,
          pageLength: -1,
          // order: [[2, "asc"]],
          "rowCallback": function(row,data,index ){
            // $('#txt_po_number_lbl').val( data['po'] );
            // // $('#txt_assessment_no').val( data['assessment'] );
            // // console.log(data['kit_issuance']['assessment']);
            // if($("#txt_prod_runcard_status").val() <= 2){
            //   // $("#txt_assessment_no").val(data['kit_issuance']['assessment']);
            //   assessment = data['kit_issuance']['assessment'];

            //   if(assessment != null){
            //     $("#txt_assessment_no").val(assessment);
            //   }
            // }
          },
          "drawCallback": function(row,data,index ){
            // $(".chkSelMatKitIssue").each(function(index){
            //     if(arrSelectedMaterial.includes($(this).attr('material-kit-issue-id'))){
            //       $(this).attr('checked', 'checked');
            //     }
            // });
            // // arrSelectedMaterial
            // arrSelectedMaterial = [];
            // $('#tbl_materials tbody .col_lot_id').each(function(){
            //   // if(!$(this).attr('disabled')){
            //   //   // console.log('disabled');
            //   //   arr_substations[ctr] = {
            //   //       'step' : $(this).closest('td').find('.col_station_step').val(),
            //   //       'station' : $(this).closest('td').find('.col_station_id').val(),
            //   //       'substation' : $(this).closest('td').find('.col_sub_station_id').val(),
            //   //     };
            //   // }
            //   // console.log($(this).val());
            //   arrSelectedMaterial.push($(this).val());
            // });
            // if(arrSelectedMaterial.length > 0 || arrSelectedSakidashi.length > 0){
            //   // $("#btnSaveSelectedMatSak").prop('disabled', false);
            //   $("#spanNoOfSelectedMatSak").text("(" + (parseInt(arrSelectedMaterial.length) + parseInt(arrSelectedSakidashi.length)) + ")");
            // }
            // else{
            //   // $("#btnSaveSelectedMatSak").prop('disabled', true);
            //   $("#spanNoOfSelectedMatSak").text("");
            // }

            // if(hasProdMatSakList){
            //   // $("#btnSaveSelectedMatSak").prop('disabled', true);
            // }
            // else{
            //   // $("#btnSaveSelectedMatSak").prop('disabled', false);
            // }
            // console.log(arrSelectedMaterial);
          },
      });//end of DataTable

      dt_sakidashi      = $('#tbl_sakidashi').DataTable({
          "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "view_manual_sakidashi_by_runcard",
            data: function (param){
                param.po_number = currentPoNo;
                param.runcard_status = $("#txt_prod_runcard_status").val();
                param.sakidashi_list = arrSelectedSakidashi;
                param.material_kit_list = arrSelectedMaterial;
                param.prod_runcard_id_query = $("#txt_prod_runcard_id_query").val();
                param.lot_no = sakidashiIssuanceLotNo;
                // param.control_no = sakidashiCtrlNo;
                param.has_mat_sak_list = hasProdMatSakList;
                param.action = viewSakidashiAction;
                param.lot_no = viewSakidashiActionLotNo;
            }
          },
          bAutoWidth: false,
          paging: true,
          "columns":[
            // { "data" : "action", orderable:false, searchable:false },
            { "data" : "btn_save_material", orderable:false, searchable:false, visible:true },
            { "data" : "status", orderable:false, searchable:false },
            { "data" : "issuance_no", orderable:true, searchable:true },
            { "data" : "tbl_wbs_sakidashi_issuance_item.item_desc", orderable:false, searchable:true },
            { "data" : "tbl_wbs_sakidashi_issuance_item.lot_no", orderable:false, searchable:true },
            { "data" : "device_code", orderable:false, searchable:true },
            { "data" : "tbl_wbs_sakidashi_issuance_item.required_qty", orderable:false, searchable:true },
            { "data" : "tbl_wbs_sakidashi_issuance_item.issued_qty", orderable:false, searchable:true },
            // { "data" : "lot_qty_to_complete" },
            // { "data" : "runcard_used_qty" },
          ],
          order: [[2, "asc"]],
          "rowCallback": function(row,data,index ){
            // Check for production done verification
            // if( $(row).html().toLowerCase().indexOf('parts prep. done verification')>0 ){
            //   $(row).addClass('table-success');
            // }
            // if( $(row).html().toLowerCase().indexOf('returned')>0 ){
            //   $(row).addClass('table-warning');
            // }
          },
          "drawCallback": function(row,data,index ){
            $(".chkSelSakidashiIssue").each(function(index){
                if(arrSelectedSakidashi.includes($(this).attr('sakidashi-issue-id'))){
                  $(this).attr('checked', 'checked');
                }
            });

            // arrSelectedSakidashi = [];
            // $('#tbl_sakidashi tbody .col_lot_id').each(function(){
            //   arrSelectedSakidashi.push($(this).val());
            // });

            // if(arrSelectedSakidashi.length > 0){
            //   // $("#btnSaveSelectedMatSak").prop('disabled', false);
            //   $("#spanNoOfSelectedMatSak").text("(" + (parseInt(arrSelectedMaterial.length) + parseInt(arrSelectedSakidashi.length)) + ")")
            // }
            // else{
            //   // $("#btnSaveSelectedMatSak").prop('disabled', true);
            //   // $("#spanNoOfSelectedMatSak").text("")
            // }
            // console.log(arrSelectedSakidashi);
          },
          // "columnDefs": [
          //   {
          //     "targets": [3, 4, 5, 6, 7],
          //     "data": null,
          //     "defaultContent": "--"
          //   },
          // ],
          paging: false,
          info: false,
          searching: true,
          pageLength: -1,
          order: [[2, "asc"]],
      });//end of DataTable

      dtAccessories      = $('#tblAccessories').DataTable({
          "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "view_accessories",
            data: function (param){
                param.prod_runcard_id = $("#txt_prod_runcard_id_query").val();
            }
          },
          bAutoWidth: false,
          "columns":[
            { "data" : "raw_checkbox", orderable:false, searchable:false },
            { "data" : "raw_action", orderable:false, searchable:false },
            { "data" : "item_desc", orderable:false, searchable:false },
            { "data" : "quantity", orderable:false, searchable:false },
            { "data" : "usage_per_socket", orderable:false, searchable:false },
            { "data" : "raw_counted_by_info", orderable:false, searchable:false },
            { "data" : "raw_checked_by_info", orderable:false, searchable:false },
            { "data" : "raw_prod_supervisor_info", orderable:false, searchable:false },
          ],
          paging: false,
          info: false,
          searching: false,
          pageLength: -1,
          // order: [[2, "asc"]],
          "rowCallback": function(row,data,index ){

          },
          "drawCallback": function(row,data,index ){

          },
      });//end of DataTable

      // dt_emboss      = $('#tbl_emboss').DataTable({
      //     "processing" : true,
      //     "serverSide" : true,
      //     "ajax" : {
      //       url: "view_manual_emboss_by_runcard",
      //       data: function (param){
      //           // param.po_number = currentPoNo;
      //           // param.runcard_status = $("#txt_prod_runcard_status").val();
      //           // param.emboss_kit_list = arrSelectedEmboss;
      //           // param.prod_runcard_id_query = $("#txt_prod_runcard_id_query").val();
      //           // param.lot_no = embossIssuanceLotNo;
      //           // // param.control_no = sakidashiCtrlNo;
      //           // param.has_mat_sak_list = hasProdMatSakList;
      //           // param.require_oqc_before_emboss = $("#txt_prod_runcard_require_oqc_before_emboss").val();

      //           param.po_number = currentPoNo;
      //           param.runcard_status = $("#txt_prod_runcard_status").val();
      //           param.emboss_kit_list = arrSelectedEmboss;
      //           param.prod_runcard_id_query = $("#txt_prod_runcard_id_query").val();
      //           param.lot_no = embossIssuanceLotNo;
      //           // param.control_no = sakidashiCtrlNo;
      //           param.has_mat_sak_list = hasProdMatSakList;
      //           param.action = viewEmbossAction;
      //           param.lot_no = viewEmbossActionLotNo;
      //       }
      //     },
      //     bAutoWidth: false,
      //     paging: true,
      //     "columns":[
      //       // { "data" : "action", orderable:false, searchable:false },
      //       { "data" : "btn_save_material", orderable:false, searchable:false },
      //       { "data" : "status", orderable:false, searchable:false },
      //       { "data" : "issuance_no", orderable:true, searchable:true },
      //       { "data" : "tbl_wbs_sakidashi_issuance_item.item_desc", orderable:false, searchable:true },
      //       { "data" : "tbl_wbs_sakidashi_issuance_item.lot_no", orderable:false, searchable:true },
      //       { "data" : "device_code", orderable:false, searchable:true },
      //       { "data" : "tbl_wbs_sakidashi_issuance_item.required_qty", orderable:false, searchable:true },
      //       { "data" : "tbl_wbs_sakidashi_issuance_item.issued_qty", orderable:false, searchable:true },
      //       // { "data" : "lot_qty_to_complete" },
      //       // { "data" : "runcard_used_qty" },
      //     ],
      //     order: [[2, "asc"]],
      //     "rowCallback": function(row,data,index ){
      //       // Check for production done verification
      //       // if( $(row).html().toLowerCase().indexOf('parts prep. done verification')>0 ){
      //       //   $(row).addClass('table-success');
      //       // }
      //       // if( $(row).html().toLowerCase().indexOf('returned')>0 ){
      //       //   $(row).addClass('table-warning');
      //       // }
      //     },
      //     "drawCallback": function(row,data,index ){
      //       $(".chkSelEmbossIssue").each(function(index){
      //           if(arrSelectedEmboss.includes($(this).attr('emboss-issue-id'))){
      //             $(this).attr('checked', 'checked');
      //           }
      //       });

      //       if(arrSelectedEmboss.length > 0){
      //         // $("#btnSaveSelectedMatSak").prop('disabled', false);
      //         $("#spanNoOfSelectedEmboss").text("(" + arrSelectedEmboss.length + ")")
      //       }
      //       else{
      //         // $("#btnSaveSelectedMatSak").prop('disabled', true);
      //         $("#spanNoOfSelectedEmboss").text("")
      //       }

      //       // arrSelectedSakidashi = [];
      //       // $('#tbl_sakidashi tbody .col_lot_id').each(function(){
      //       //   arrSelectedSakidashi.push($(this).val());
      //       // });

      //       // if(arrSelectedSakidashi.length > 0){
      //       //   // $("#btnSaveSelectedMatSak").prop('disabled', false);
      //       //   $("#spanNoOfSelectedMatSak").text("(" + (parseInt(arrSelectedMaterial.length) + parseInt(arrSelectedSakidashi.length)) + ")")
      //       // }
      //       // else{
      //       //   // $("#btnSaveSelectedMatSak").prop('disabled', true);
      //       //   // $("#spanNoOfSelectedMatSak").text("")
      //       // }
      //       // console.log(arrSelectedSakidashi);
      //     },
      //     // "columnDefs": [
      //     //   {
      //     //     "targets": [3, 4, 5, 6, 7],
      //     //     "data": null,
      //     //     "defaultContent": "--"
      //     //   },
      //     // ],
      //     paging: false,
      //     info: false,
      //     searching: true,
      //     pageLength: -1,
      //     order: [[2, "asc"]],
      // });//end of DataTable

      let dtOutputStatus = 0;
      let groupStations = 2;
      dt_prod_runcard_stations = $('#tbl_prod_runcard_stations').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "view_prod_runcard_stations",
            data: function (param){
                param.prod_runcard_id_query          = $("#txt_prod_runcard_id_query").val();
                param.prod_runcard_status          = $("#txt_prod_runcard_status").val();
                // param.has_emboss          = $("#sel_runcard_type").val();
                // param.prod_runcard_no          = $("#txt_runcard_no").val();
                param.prod_lot_no          = $("#txt_lot_no").val();
                // param.require_oqc_before_emboss          = $("#txt_prod_runcard_require_oqc_before_emboss").val();
            }
          },
          
          "columns":[
            { "data" : "raw_action", orderable:false, searchable:false, visible: true },
            // { "data" : "step_num", orderable:true, visible: false, },
            // { "data" : "step_num", orderable:false, searchable:true },
            { "data" : "runcard_no", orderable:false, searchable:true },
            { "data" : "process_name", orderable:false, searchable:true },
            { "data" : "updated_at", orderable:false, searchable:true },
            { "data" : "ct_area_info.name", orderable:false, searchable:true },
            { "data" : "terminal_area_info.name", orderable:false, searchable:true },
            // {
            //     name: 'prod_runcard_station_operator_details',
            //     data: 'prod_runcard_station_operator_details',
            //     sortable: false,
            //     searchable: false,
            //     render: function (data) {
            //       // console.log(data);
            //         var result = '';
            //         if(data.length > 0){
            //           for(let index = 0; index < data.length; index++){
            //             result += '<span class="badge badge-pill badge-secondary">' + data[index]['operator_info']['name'] + '</span>';

            //             if(index <= parseInt(data.length) - 2){
            //               result += '<br>';
            //             }
            //           }
            //         }
            //         else{
            //           result = null;
            //         }
            //         return result;
            //     }
            // },
            // {
            //     name: 'prod_runcard_station_machine_details',
            //     data: 'prod_runcard_station_machine_details',
            //     sortable: false,
            //     searchable: false,
            //     render: function (data) {
            //       // console.log(data);
            //         var result = '';
            //         if(data.length > 0){
            //           for(let index = 0; index < data.length; index++){
            //             result += '<span class="badge badge-pill badge-secondary">' + data[index]['machine_info']['name'] + '</span>';

            //             if(index <= parseInt(data.length) - 2){
            //               result += '<br>';
            //             }
            //           }
            //         }
            //         else{
            //           result = null;
            //         }
            //         return result;
            //     }
            // },
            { "data" : "qty_input", orderable:false, searchable:true },
            { "data" : "qty_output", orderable:false, searchable:true },
            { "data" : "qty_ng", orderable:false, searchable:true },
            {
                name: 'prod_runcard_station_mod_details',
                data: 'prod_runcard_station_mod_details',
                sortable: false,
                searchable: false,
                render: function (data) {
                  // console.log(data);
                    var result = '';
                    if(data.length > 0){
                      for(let index = 0; index < data.length; index++){
                        result += '<span class="badge badge-pill badge-danger">' + data[index]['mod_qty'] + ' - ' + data[index]['mod_details'].name + '</span>';

                        if(index <= parseInt(data.length) - 2){
                          result += '<br>';
                        }
                      }
                    }
                    else{
                      result = null;
                    }
                    return result;
                }
            },
            { "data" : "remarks", orderable:false, searchable:true },

          ],
          order: [[1, "asc"]],
          paging: false,
          info: false,
          searching: false,
          pageLength: -1,
          "columnDefs": [ 
                // { "visible": false, "targets": groupStations },
                // { "visible": false, "targets": groupStations + 1 },
                {
                  "targets": [3, 4, 5, 6, 7, 8, 9],
                  "data": null,
                  "defaultContent": "--"
                },
                // {
                //   "targets": [12],
                //   "data": null,
                //   "defaultContent": "N/A"
                // },
           ],
           "drawCallback": function ( settings ) {
              // var api = this.api();
              // var rows = api.rows( {page:'current'} ).nodes();
              // var last = null;

              // if(api.rows().count() > 0){
              //   // $("#btn_edit_material_details_verification").prop('disabled', false);
              // }
              // else{
              //   // $("#btn_edit_material_details_verification").prop('disabled', true);
              // }

              // OutputDataCounter(api);

              // api.column(groupStations, {page:'current'} ).data().each( function ( group, i ) {
              //     if ( last !== group ) {

              //       let data = api.row(i).data();
              //       let station_name = data.station.name;
              //       let groupRowClass = 'bg-info';

              //       // if($("#sel_runcard_type").val() == 1){
              //       //   groupRowClass = 'bg-warning';                          
              //       // }

              //         $(rows).eq( i ).before(
              //             '<tr class="group ' + groupRowClass + '"><td colspan="11" style="text-align:center;"><b>' + station_name + '</b></td></tr>'
              //         );

              //         last = group;
              //     }
              // });

              // let lastProdStationStat = false;

              // console.log($("#tbl_prod_runcard_stations tr:last").find('.btn_edit_prod_runcard_station').attr('disabled'));

              var api = this.api();
              var rows = api.rows( {page:'current'} ).nodes();
              var last = null;

              // Temporary -> switch comment
              // GetMaterialKitting();
              // GetSakidashiIssuance();
              // if(api.rows().count() <= 0){
              //   GetMaterialKitting();
              // }

              let totalNoRuncardOfOk = 0;
              let totalNoRuncardOfInput = 0;
              let totalNoRuncardOfNG = 0;

              if(api.rows().count() > 0){
                // totalNoRuncardOfOk = 0;
                // totalNoRuncardOfNG = 0;
                // totalNoRuncardOfNG = 0;
                // let data = api.data();
                // let recount_ok = data.recount_ok;
                // let recount_ng = data.recount_ng;

                api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                    let data = api.row(i).data();

                    // if(data['last_runcard_po_qty'] != null){
                      totalNoRuncardOfOk += parseInt(data['qty_output']);
                      totalNoRuncardOfInput += parseInt(data['qty_input']);
                      totalNoRuncardOfNG += parseInt(data['qty_ng']);
                    // }

                    // totalNoOfNG += data.total_no_of_ng;

                    // console.log(data);
                });

              }

              $("#thRuncardTotalInput").html('<b>' + totalNoRuncardOfInput + '</b>');
              $("#thRuncardTotalOutput").html('<b>' + totalNoRuncardOfOk + '</b>');
              $("#thRuncardTotalNG").html('<b>' + totalNoRuncardOfNG + '</b>');
            }
      });//end of DataTable

      //----------
      dt_setup_stations      = $('#tbl_setup_stations').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "select_prod_runcard_setup_stations",
            data: function (param){
                // param.po_number              = $("#txt_search_po_number").val();
                param.device_code                  = $("#txt_device_code_lbl").val();
                param.prod_runcard_id_query          = $("#txt_prod_runcard_id_query").val();
            }
          },
          
          "columns":[
            { "data" : "raw_action", orderable:false, searchable:false },
            // { "data" : "raw_status", orderable:false, searchable:false },
            { "data" : "step" },
            { "data" : "station_sub_station.station.name" },
            { "data" : "station_sub_station.sub_station.name" },
            // { "data" : "qty_input" },
            // { "data" : "raw_operator" },
            // { "data" : "raw_input" },
            // { "data" : "raw_output" },
            // { "data" : "raw_ng_qty" },
            // { "data" : "raw_mod" },
          ],
          order: [[1, "asc"]],
          paging: false,
          info: false,
          searching: false,
      });//end of DataTable

      // $("#sel_runcard_type").change(function(){
      //   dt_prod_runcard_stations.draw();
        // if($(this).val() == 0){
        //   $('#myTab a[href="#home"]').tab('show');
        //   $('#myTab a[href="#profile"]').tab('show');
        //   $('#myTab a[href="#materialEmbossTab"]').tab('hide'); 
        // }
        // else{
        //   $('#myTab a[href="#home"]').tab('hide');
        //   $('#myTab a[href="#profile"]').tab('hide');
        //   $('#myTab a[href="#materialEmbossTab"]').tab('show'); 
        // }
      // });


      let groupMODRuncardNo = 0;
      dt_ng_summary = $('#tblNGSummary').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "view_ng_summary",
            data: function (param){
                param.po_no          = currentPoNo;
            }
          },
          
          "columns":[
            { "data" : "production_runcard_id", orderable:false, searchable:false },
            { "data" : "production_runcard_details.runcard_no", orderable:false, searchable:true },
            { "data" : "mod_details.name" , orderable:true, searchable:false},
            { "data" : "mod_qty" , orderable:true, searchable:false},
          ],
          order: [[groupMODRuncardNo, "desc"]],
          paging: true,
          info: true,
          searching: true,
          // pageLength: -1,
          "columnDefs": [ 
                { "visible": false, "targets": groupMODRuncardNo },
                { "visible": false, "targets": 1 },
                // { "visible": false, "targets": groupMODRuncardNo + 1 },
                {
                  "targets": [0, 1, 2, 3],
                  "data": null,
                  "defaultContent": "--"
                },
           ],
           order: [[0]],
           "drawCallback": function ( settings ) {
              var api = this.api();
              var rows = api.rows( {page:'current'} ).nodes();
              var last = null;

              api.column(groupMODRuncardNo, {page:'current'} ).data().each( function ( group, i ) {
                  if ( last !== group ) {

                    let data = api.row(i).data();
                    let production_runcard_id = '';
                    let production_runcard_no = '';

                    if(data.production_runcard_id != null){
                      production_runcard_id = data.production_runcard_id;
                    }
                    if(data.production_runcard_details != null){
                       production_runcard_no = data.production_runcard_details.runcard_no;
                    }
                    // let station_name = data.station.name;

                      $(rows).eq( i ).before(
                          '<tr class="group bg-info"><td colspan="2" style="text-align:center;" class="trNGSummaryRuncardNo">Runcard # : <b>' + production_runcard_no + '</b></td></tr>'
                      );

                      last = group;
                  }
              });
            }
      });//end of DataTable

      // $(document).on('keyup','#txt_search_po_number',function(e){
      //   if( e.keyCode == 13 ){
      //     currentPoNo = $(this).val();
      //     dt_prod_runcard.draw();
      //     // dt_sakidashi.draw();
      //     // dt_materials.draw();
      //     $('#tbl_materials tbody tr').removeClass('table-active');
      //     $('#txt_po_number_lbl').val('');
      //     $('#txt_device_name_lbl').val('');
      //     $('#txt_device_code_lbl').val('');
      //     $('#txt_po_qty_lbl').val('');
      //     $('#txt_device_name').val('');
      //     $(this).val('');
      //     $(this).focus();
      //   }
      // });

      // $("#tblNGSummary tbody").on('click', '.trNGSummaryRuncardNo', function(){
      //   dt_ng_summary.order([[1, 'desc']]).draw();
      // });

    $(document).on('click', '.chkSelProdRuncard', function(){
          let prodRuncardId = $(this).attr('production-runcard-id');

          if($(this).prop('checked')){
              // Checked
              if(!arrSelectedRuncards.includes(prodRuncardId)){
                  arrSelectedRuncards.push(prodRuncardId);
              }
          }
          else{  
              // Unchecked
              let index = arrSelectedRuncards.indexOf(prodRuncardId);
              arrSelectedRuncards.splice(index, 1);
          }
          $("#lblIntNoOfSendIntBatch").text(arrSelectedRuncards.length);
          if(arrSelectedRuncards.length <= 0){
              $("#btnOverallInspection").prop('disabled', true);
              $(".spanOICount").html('');

          }
          else{
              $("#btnOverallInspection").prop('disabled', false);
              $(".spanOICount").html('(' + arrSelectedRuncards.length + ')');

          }
      });

    $('#btnOverallInspection').click(function(){
      if(arrSelectedRuncards.length > 0){
        $("#mdl_edit_prod_runcard_station_details").modal('show');
        $("#txt_prod_runcard_id_query").val('');
      }
      else{
        toastr.warning('No Selected Runcard!');
      }
    });

      $(document).on('click', '.btnPrintRuncardNo', function(){
        let runcardId = $(this).attr('runcard-id');
        let runcardNo = $(this).attr('runcard-no');
        GetRuncardNoToPrint(runcardId, runcardNo);
      });

      $(document).on('keyup','#txt_search_material_lot_no',function(e){
        if( e.keyCode == 13 ){
          materialKitIssuanceLotNo = $(this).val();
          $("#txt_material_lot_no_lbl").val(materialKitIssuanceLotNo);
          dt_materials.draw();
          $(this).val('');
          $(this).focus();
        }
      });

      $(document).on('keyup','#txt_search_material_transfer_slip',function(e){
        if( e.keyCode == 13 ){
          materialKitTransferSlip = $(this).val().trim();
          $("#txt_material_transfer_slip_lbl").val(materialKitTransferSlip);
          dt_materials.draw();
          $(this).val('');
          $(this).focus();
        }
      });

      $(document).on('keyup','#txt_search_sakidashi_lot_no',function(e){
        if( e.keyCode == 13 ){
          sakidashiIssuanceLotNo = $(this).val().trim();
          $("#txt_sakidashi_lot_no_lbl").val(sakidashiIssuanceLotNo);
          dt_sakidashi.draw();
          $(this).val('');
          $(this).focus();
        }
      });

      // $(document).on('keyup','#txt_search_emboss_lot_no',function(e){
      //   if( e.keyCode == 13 ){
      //     embossIssuanceLotNo = $(this).val().trim();
      //     $("#txt_emboss_lot_no_lbl").val(embossIssuanceLotNo);
      //     // dt_emboss.draw();
      //     $(this).val('');
      //     $(this).focus();
      //   }
      // });

      $(document).on('keyup','#txt_search_sakidashi_ctrl_no',function(e){
        if( e.keyCode == 13 ){
          sakidashiCtrlNo = $(this).val();
          $("#txt_sakidashi_ctrl_no_lbl").val(sakidashiCtrlNo);
          dt_sakidashi.draw();
          $(this).val('');
          $(this).focus();
        }
      });

      $(document).on('keypress',function(e){
        if( ($("#mdl_employee_number_scanner").data('bs.modal') || {})._isShown ){
          $('#txt_employee_number_scanner').focus();
          if( e.keyCode == 13 && $('#txt_employee_number_scanner').val() !='' && ($('#txt_employee_number_scanner').val().length >= 4) ){
            $('#mdl_employee_number_scanner').modal('hide');
            var formid = $("#mdl_employee_number_scanner").attr('data-formid');

            if ( ( formid ).indexOf('#') > -1){
              $( formid ).submit();
            }
            else{
              switch( formid ){
                case 'save_setup_stations':
                save_setup_stations();
                break;
                case 'save_approve_qc':
                save_approve_qc();
                break;
                case 'save_approve_prod':
                save_approve_prod();
                break;
                case 'SaveProdMaterialList':
                  if(arrSelectedMaterial.length > 0 || arrSelectedSakidashi > 0){
                    SaveProductMaterialList(arrSelectedMaterial, arrSelectedSakidashi, "{{ csrf_token() }}", $("#txt_employee_number_scanner").val(), $("#txt_prod_runcard_id_query").val());
                  }
                  else{
                    toastr.warning('No Materials to be saved.');
                  }
                break;
                case 'submit_to_oqc_lot_app':
                submit_to_oqc_lot_app();
                break;
                case 'save_material':
                save_material();
                break;

                case 'select_eng_qual':
                  $.ajax({
                    url: "get_user_by_employee_no",
                    method: "get",
                    data: {
                        employee_no: $("#txt_employee_number_scanner").val()
                    },
                    dataType: "json",
                    beforeSend: function(){
                        
                    },
                    success: function(data){
                        if(data['data'] != null){
                            if(data['data']['position'] == 9){
                              $('input[name="eng_qualification_name"]', $("#frm_edit_material_details")).val(data['data']['name']);
                              $('input[name="eng_qualification_id"]', $("#frm_edit_material_details")).val(data['data']['id']);
                            }
                            else{
                              $('input[name="eng_qualification_name"]', $("#frm_edit_material_details")).val('');
                              $('input[name="eng_qualification_id"]', $("#frm_edit_material_details")).val('');
                              toastr.warning('Engineer not found!');
                            }
                        }
                        else{
                            $('input[name="eng_qualification_name"]', $("#frm_edit_material_details")).val('');
                            $('input[name="eng_qualification_id"]', $("#frm_edit_material_details")).val('');
                            toastr.error('User not found!');
                        }
                    },
                    error: function(data, xhr, status){
                        toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
                    }
                });
                break;

                case 'select_qc_qual':
                  $.ajax({
                    url: "get_user_by_employee_no",
                    method: "get",
                    data: {
                        employee_no: $("#txt_employee_number_scanner").val()
                    },
                    dataType: "json",
                    beforeSend: function(){
                        
                    },
                    success: function(data){
                        if(data['data'] != null){
                            if(data['data']['position'] == 2 || data['data']['position'] == 10){
                              $('input[name="qc_stamp_name"]', $("#frm_edit_material_details")).val(data['data']['name']);
                              $('input[name="qc_stamp_id"]', $("#frm_edit_material_details")).val(data['data']['id']);
                            }
                            else{
                              $('input[name="qc_stamp_name"]', $("#frm_edit_material_details")).val('');
                              $('input[name="qc_stamp_id"]', $("#frm_edit_material_details")).val('');
                              toastr.warning('QC not found!');
                            }
                        }
                        else{
                            $('input[name="qc_stamp_name"]', $("#frm_edit_material_details")).val('');
                            $('input[name="qc_stamp_id"]', $("#frm_edit_material_details")).val('');
                            toastr.error('User not found!');
                        }
                    },
                    error: function(data, xhr, status){
                        toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
                    }
                  });
                break;

                // case 'SaveProdEmbossMaterialList':
                //   if(arrSelectedEmboss.length > 0){
                //     SaveProductEmbossMaterialList(arrSelectedEmboss, "{{ csrf_token() }}", $("#txt_employee_number_scanner").val(), $("#txt_prod_runcard_id_query").val());
                //   }
                //   else{
                //     toastr.warning('No Materials to be saved.');
                //   }
                // break;
                default:
                break;
              }
            }
          }
        }
        else if( ($("#mdl_qrcode_scanner").data('bs.modal') || {})._isShown ){
          $('#txt_qrcode_scanner').focus();
          if( e.keyCode == 13 ){
            $('#mdl_qrcode_scanner').modal('hide');
            // alert($("#txt_qrcode_scanner").val());
            var formid = $("#mdl_qrcode_scanner").attr('data-formid');

            if ( ( formid ).indexOf('#') > -1){
              $( formid ).submit();
            }
            else{
              switch( formid ){
                case 'fn_scan_machine_code':
                  var val = $('#txt_qrcode_scanner').val();

                  // let scannedMachineId = $('#txt_edit_prod_runcard_station_machine').find("option[data-code='" + val + "']").val();
                  // let assignedMachine = $("#txt_edit_prod_runcard_station_assigned_machine").val();

                  // if(assignedMachine == scannedMachineId){
                  //   $('#txt_edit_prod_runcard_station_machine option[data-code="'+val+'"]').prop('selected', true).trigger('change');
                  // }
                  // else{
                  //   $('#txt_edit_prod_runcard_station_machine').val("").trigger('change');
                  //   toastr.warning('Invalid Machine!');
                  // }

                  // Replaced
                  // if ($('#txt_edit_prod_runcard_station_machine').find("option[data-code='" + val + "']").length) {
                  //   let scannedMachineId = $('#txt_edit_prod_runcard_station_machine').find("option[data-code='" + val + "']").val();
                  //   let assignedMachine = $("#txt_edit_prod_runcard_station_assigned_machine").val();

                  //   if(assignedMachine == scannedMachineId){
                  //     $('#txt_edit_prod_runcard_station_machine option[data-code="'+val+'"]').prop('selected', true).trigger('change');
                  //   }
                  //   else{
                  //     $('#txt_edit_prod_runcard_station_machine').val("").trigger('change');
                  //     toastr.warning('Wrong Machine!');
                  //   }
                  // }
                  // else{
                  //   $('#txt_edit_prod_runcard_station_machine').val("").trigger('change');
                  //   toastr.warning('Invalid Machine!');
                  // }
                  // $('#txt_qrcode_scanner').val("");

                  // with this
                  var val = $('#txt_qrcode_scanner').val();

                  if ($('#txt_edit_prod_runcard_station_machine').find("option[data-code='" + val + "']").length) {
                    let scannedUserId = $('#txt_edit_prod_runcard_station_machine').find("option[data-code='" + val + "']").val();

                    let assignedMachine = $("#txt_edit_prod_runcard_station_assigned_machine").val();

                    // if(assignedMachine.includes(scannedUserId)){
                      // toastr.warning('Certified!');
                      $('#txt_edit_prod_runcard_station_machine option[data-code="'+val+'"]').prop('selected', true).trigger('change');
                    // }
                    // else{
                    //   toastr.warning('Not Assigned!');
                    // }

                  }
                  else{
                    toastr.warning('Invalid Machine!');
                  }
                  $('#txt_qrcode_scanner').val("");
                break;

                case 'fn_scan_runcard_operator_code':
                  var val = $('#txt_qrcode_scanner').val();

                  console.log($('#txt_edit_prod_runcard_operator').find("option[data-code='" + val + "']"));

                  if ($('#txt_edit_prod_runcard_operator').find("option[data-code='" + val + "']")) {
                    let scannedUserId = $('#txt_edit_prod_runcard_operator').find("option[data-code='" + val + "']").val();
                    let scannedUserName = $('#txt_edit_prod_runcard_operator').find("option[data-code='" + val + "']").attr('user-fullname');

                    let addedProdRunOperators = $('#tblProdRunOperators tr:last').find('td:eq(0)').html();


                    if(scannedUserName != undefined){
                      if(addedProdRunOperators == 'No data available in table'){
                        // alert('No record');
                        $('#tblProdRunOperators tbody').html('');
                      }

                      let htmlResult = '<tr>';
                      htmlResult += '<td>';
                      htmlResult += '<input type="text" name="operators_id[]" class="form-control-sm" value="' + scannedUserId + '" style="display: none;">';
                      htmlResult +=  scannedUserName;
                      htmlResult +=  '</td>';
                      // htmlResult +=  '<td><input type="checkbox" name="chk_ct_area[]" class="form-control-sm chkCTArea" value="' + scannedUserId + '"> <input type="text" name="ct_area[]" class="form-control-sm txtCTArea" value="0" style="display: none;"></td>';
                      // htmlResult +=  '<td><input type="checkbox" name="chk_terminal_area[]" class="form-control-sm chkTerminalArea" value="' + scannedUserId + '"> <input type="text" name="terminal_area[]" class="form-control-sm txtTerminalArea" value="0" style="display: none;"></td>';
                      htmlResult +=  '<td><select type="text" name="area[]" class="form-control-sm selArea" style="width: 100%;"><option value="1" selected="true">C/T Area</option><option value="2">Terminal Area</option></select></td>';
                      htmlResult +=  '<td><button type="button" style="width:30px; margin-top: 3px;" title="Delete" class="btnDeleteOperator btn btn-danger btn-sm py-0"><i class="fa fa-times"></i></button></td>';
                      htmlResult += '</tr>';

                      $('#tblProdRunOperators tbody').append(htmlResult);

                      let rowCount = $('#tblProdRunOperators tbody tr').length;
                      // alert(scannedUserName + ' = ' + rowCount);

                      let certifiedOperators = $("#txt_edit_prod_runcard_cert_operator").val();

                      // if(certifiedOperators.includes(scannedUserId)){
                        // toastr.warning('Certified!');
                        $('#txt_edit_prod_runcard_operator option[data-code="'+val+'"]').prop('selected', true).trigger('change');
                      // }
                      // else{
                      //   toastr.warning('Not Certified!');
                      // }
                    }
                    else{
                      toastr.warning('Invalid Operator!')
                    }

                  }
                  else{
                    toastr.warning('Invalid User!');
                  }
                  $('#txt_qrcode_scanner').val("");
                break;

                case 'fn_scan_delete_process':
                  var val = $('#txt_qrcode_scanner').val();
                  
                  // deleteRuncardStationId

                  DeleteRuncardStation(val, deleteRuncardStationId);

                  $('#txt_qrcode_scanner').val("");
                break;

                case 'fn_scan_material_kitting':
                  var val = $('#txt_qrcode_scanner').val();
                break;

                case 'fn_scan_sakidashi':
                  var val = $('#txt_qrcode_scanner').val();
                break;

                case 'btnScanMaterialLotNo':
                  var lotNo = $('#txt_qrcode_scanner').val();

                  if(lotNo != ''){
                    lotNo = lotNo.split(' | ')[0];
                  }

                  // $("input[name='lot_no']", $("#frmSaveMaterial")).val(val); 
                  // Material Checking Function Here
                  CheckMaterialLotNo(lotNo);
                  // alert('wew');
                break;

                // case 'fn_scan_emboss':
                //   var val = $('#txt_qrcode_scanner').val();
                // break;
                default:
                break;
              }
            }            
          }//key
        }
        // SCAN PO, LOT, TRANSFER SLIP CODE
        if( ($("#modalScanPOTransLotCode").data('bs.modal') || {})._isShown ){
          $('#txtSearchPoTransLotNo').focus();
          if( e.keyCode == 13 && $('#txtSearchPoTransLotNo').val() !='' && ($('#txtSearchPoTransLotNo').val().length >= 4) ){
            // $('#modalScanPOTransLotCode').modal('hide');
            $("#scanPOTransLotIcon").removeClass('fa-spinner fa-pulse');
            $("#scanPOTransLotIcon").addClass('fa-qrcode');

            var formid = $("#modalScanPOTransLotCode").attr('data-formid');

            if ( ( formid ).indexOf('#') > -1){
              $( formid ).submit();
            }
            else{
              let scannedValue = "";
              switch( formid ){
                case 'search_po_number':
                  
                  // toastr.warning('PO # not found!'); // Remove this
                  // return; // Remove this
                  arrSelectedRuncards = [];
                  $("#btnOverallInspection").prop('disabled', true);
                  $(".spanOICount").html('');
                  currentPoNo = $("#txtSearchPoTransLotNo").val();
                  dt_prod_runcard.draw();
                  $('#tbl_materials tbody tr').removeClass('table-active');
                  $('#txt_po_number_lbl').val('');
                  $('#txt_device_name_lbl').val('');
                  $('#txt_device_code_lbl').val('');
                  $('#txt_po_qty_lbl').val('');
                  $('#txt_device_name').val('');

                  $('#txt_orig_Adrawing').val('');
                  $('#txt_orig_Adrawing_rev').val('');
                  $('#txt_Adrawing').val('');
                  $('#txt_Adrawing_rev').val('');
                  $('#txt_Gdrawing').val('');
                  $('#txt_Gdrawing_rev').val('');
                  $('#txt_JRDJKSDCGJDoc').val('');
                  $('#txt_JRDJKSDCGJDoc_rev').val('');
                  $('#txt_GPMD').val('');
                  $('#txt_GPMD_rev').val('');


                  // $('#txt_Odrawing').val('');
                  // $('#txt_Odrawing_rev').val('');

                  $(this).val('');
                  $(this).focus();
                  $("#modalScanPOTransLotCode").modal('hide');
                break;

                case 'scan_mat_kit':
                  scannedValue = $("#txtSearchPoTransLotNo").val();
                  // alert(scannedValue);
                  $(this).focus();
                  viewMatKitAction = 1;
                  viewMatKitActionLotNo = null;
                  ScanProdRuncardMaterialKiting(scannedValue);
                  $("#txtScannedMatKitLot").val(scannedValue.trim());
                break;

                case 'scan_sakidashi':
                  scannedValue = $("#txtSearchPoTransLotNo").val();
                  // alert(scannedValue);
                  $(this).focus();
                  ScanProdRuncardSakidashiIssuance(scannedValue);
                  $("#txtScannedSakidashiLot").val(scannedValue.trim());
                break;

                // case 'scan_emboss':
                //   scannedValue = $("#txtSearchPoTransLotNo").val();
                //   // alert(scannedValue);
                //   $(this).focus();
                //   ScanProdRuncardEmboss(scannedValue);
                //   $("#txtScannedEmbossLot").val(scannedValue.trim());
                // break;

                case 'scan_employee_no':
                  scannedValue = $("#txtSearchPoTransLotNo").val();
                  // alert(scannedValue);
                  $(this).focus();
                  ScanProdRuncardEmployee(scannedValue);
                  $("#txtScannedEmployeeNo").val(scannedValue.trim());
                break;

                case 'search_mat_kit':
                  scannedValue = $("#txtSearchPoTransLotNo").val();
                  // alert(scannedValue);
                  $(this).focus();
                  $("#txtSearchedMatKitLot").val('');
                  viewMatKitAction = 2;
                  viewMatKitActionLotNo = scannedValue;
                  $("#txtSearchedMatKitLot").val(scannedValue.trim());
                  dt_materials.draw();
                  $("#modalScanPOTransLotCode").modal('hide');
                break;

                case 'search_sakidashi':
                  scannedValue = $("#txtSearchPoTransLotNo").val();
                  // alert(scannedValue);
                  $(this).focus();
                  $("#txtSearchedSakidashiLotNo").val('');
                  viewSakidashiAction = 2;
                  viewSakidashiActionLotNo = scannedValue;
                  $("#txtSearchedSakidashiLot").val(scannedValue.trim());
                  dt_sakidashi.draw();
                  $("#modalScanPOTransLotCode").modal('hide');
                break;

                // case 'search_emboss':
                //   scannedValue = $("#txtSearchPoTransLotNo").val();
                //   // alert(scannedValue);
                //   $(this).focus();
                //   $("#txtSearchedEmbossLotNo").val('');
                //   viewEmbossAction = 2;
                //   viewEmbossActionLotNo = scannedValue;
                //   $("#txtSearchedEmbossLot").val(scannedValue.trim());
                //   // dt_emboss.draw();
                //   $("#modalScanPOTransLotCode").modal('hide');
                // break;

                case 'save_mat_kit':
                  scannedValue = $("#txtSearchPoTransLotNo").val();
                  SaveSakidashiLotIssuanceId(saveMatKitLotIssuanceId, saveMatKitLotItem, saveMatKitLotItemDesc, scannedValue);
                  saveMatKitLotIssuanceId = null;
                  saveMatKitLotItem = null;
                  saveMatKitLotItemDesc = null;
                break;

                case 'save_sakidashi_lot_issuance':
                  if($("#txt_prod_runcard_id_query").val() == null || $("#txt_prod_runcard_id_query").val() == ''){
                    toastr.error('No Runcard Details.');
                  }
                  else{
                    scannedValue = $("#txtSearchPoTransLotNo").val();
                    SaveSakidashiLotIssuanceId(saveSakidashiLotIssuanceId, saveSakidashiLotItem, saveSakidashiLotItemDesc, scannedValue);
                    saveSakidashiLotIssuanceId = null;
                    saveSakidashiLotItem = null;
                    saveSakidashiLotItemDesc = null;
                  }
                break;

                // case 'save_emboss_lot_issuance':
                //   if($("#txt_prod_runcard_id_query").val() == null || $("#txt_prod_runcard_id_query").val() == ''){
                //     toastr.error('No Runcard Details.');
                //   }
                //   else{
                //     scannedValue = $("#txtSearchPoTransLotNo").val();
                //     SaveEmbossLotIssuanceId(saveEmbossLotIssuanceId, saveEmbossLotItem, saveEmbossLotItemDesc, scannedValue);
                //     saveEmbossLotIssuanceId = null;
                //     saveEmbossLotItem = null;
                //     saveEmbossLotItemDesc = null;
                //   }
                // break;

                // case 'scan_whs_slip_no': 
                //   scannedValue = $("#txtSearchPoTransLotNo").val();
                //   $("#txtScannedWHSSlipNo").val(scannedValue);
                // break;

                default:
                break;
              }
            }
          }
        }
      });

      $('#txt_po_number_lbl').keypress(function(e){
        if(e.keyCode == 13){
          arrSelectedRuncards = [];
          $("#btnOverallInspection").prop('disabled', true);
          $("#btnReference").prop('disabled', false);
          $(".spanOICount").html('');
          currentPoNo = $(this).val();
          dt_prod_runcard.draw();
          $('#tbl_materials tbody tr').removeClass('table-active');
          // $('#txt_po_number_lbl').val('');
          $('#txt_device_name_lbl').val('');
          $('#txt_device_code_lbl').val('');
          $('#txt_po_qty_lbl').val('');
          $('#txt_device_name').val('');

          $('#txt_orig_Adrawing').val('');
          $('#txt_orig_Adrawing_rev').val('');
          $('#txt_Adrawing').val('');
          $('#txt_Adrawing_rev').val('');
          $('#txt_Gdrawing').val('');
          $('#txt_Gdrawing_rev').val('');
          $('#txt_JRDJKSDCGJDoc').val('');
          $('#txt_JRDJKSDCGJDoc_rev').val('');
          $('#txt_GPMD').val('');
          $('#txt_GPMD_rev').val('');


          // $('#txt_Odrawing').val('');
          // $('#txt_Odrawing_rev').val('');

          $(this).val('');
          $(this).focus();
        }
      });

      $(document).on('click','#tbl_prod_runcard tr',function(e){
        $(this).closest('tbody').find('tr').removeClass('table-active');
        $(this).closest('tr').addClass('table-active');
      });

      $("#tbl_sakidashi").on('click', '.chkSelSakidashiIssue', function(e){
        let sakidashiId = $(this).attr('sakidashi-issue-id');
        if($("#txt_prod_runcard_id_query").val() == "" || $("#txt_prod_runcard_id_query").val() == null){
          toastr.warning('Fill-out Material Details First');
          $(this).prop('checked', false);
        }
        else{
          if($(this).prop('checked')){
              // Checked
              if(!arrSelectedSakidashi.includes(sakidashiId)){
                arrSelectedSakidashi.push(sakidashiId);
              }
          }
          else{  
              // Unchecked
              let index = arrSelectedSakidashi.indexOf(sakidashiId);
              arrSelectedSakidashi.splice(index, 1);
          }

          let noOfSelected = parseInt(arrSelectedSakidashi.length) + parseInt(arrSelectedMaterial.length);
          if(noOfSelected > 0){
            $("#spanNoOfSelectedMatSak").text('(' + noOfSelected + ')');
            if($("#txt_prod_runcard_id_query").val() == "" || $("#txt_prod_runcard_id_query").val() == null){
            
            }
            else{
              $("#btnSaveSelectedMatSak").prop('disabled', false);
            }
          }
          else{
            $("#spanNoOfSelectedMatSak").text(''); 
            $("#btnSaveSelectedMatSak").prop('disabled', true);
          }
        }
        $("#txt_search_sakidashi_lot_no").focus();
      });

      // $("#tbl_emboss").on('click', '.chkSelEmbossIssue', function(e){
      //   let embossId = $(this).attr('emboss-issue-id');
      //   if($("#txt_prod_runcard_id_query").val() == "" || $("#txt_prod_runcard_id_query").val() == null){
      //     toastr.warning('Fill-out Material Details First');
      //     $(this).prop('checked', false);
      //   }
      //   else{
      //     if($(this).prop('checked')){
      //         // Checked
      //         if(!arrSelectedEmboss.includes(embossId)){
      //           arrSelectedEmboss.push(embossId);
      //         }
      //     }
      //     else{  
      //         // Unchecked
      //         let index = arrSelectedEmboss.indexOf(embossId);
      //         arrSelectedEmboss.splice(index, 1);
      //     }

      //     let noOfSelected = parseInt(arrSelectedEmboss.length);
      //     if(noOfSelected > 0){
      //       // For button of adding material 
      //       $("#spanNoOfSelectedEmboss").text('(' + noOfSelected + ')');
      //       if($("#txt_prod_runcard_id_query").val() == "" || $("#txt_prod_runcard_id_query").val() == null){
            
      //       }
      //       else{
      //         $("#btnSaveSelectedEmboss").prop('disabled', false);
      //       }
      //     }
      //     else{
      //       // For button of adding material 
      //       $("#spanNoOfSelectedEmboss").text(''); 
      //       $("#btnSaveSelectedEmboss").prop('disabled', true);
      //     }
      //   }
      //   $("#txt_search_emboss_lot_no").focus();
      // });

      $("#tblProdRunOperators").on('click', '.chkCTArea', function(e){
        if($(this).prop('checked')){
          $(this).parent().closest('tr').find('.txtCTArea').val(1);
        }
        else{
          $(this).parent().closest('tr').find('.txtCTArea').val(0);
        }
      });

      $("#tblProdRunOperators").on('click', '.chkTerminalArea', function(e){
        if($(this).prop('checked')){
          $(this).parent().closest('tr').find('.txtTerminalArea').val(1);
        }
        else{
          $(this).parent().closest('tr').find('.txtTerminalArea').val(0);
        }
      });

      $("#tblProdRunOperators").on('click', '.btnDeleteOperator', function(e){
        $(this).parent().closest('tr').remove();
        let rowCount = $('#tblProdRunOperators tbody tr').length;
        if(rowCount <= 0){
          $('#tblProdRunOperators tbody').html('<tr><td colspan="3" style="text-align: center;">No data available in table</td></tr>');
        }
      });

      $("#tbl_materials").on('click', '.chkSelMatKitIssue', function(e){
        let matKitIssue = $(this).attr('material-kit-issue-id');
        if($("#txt_prod_runcard_id_query").val() == "" || $("#txt_prod_runcard_id_query").val() == null){
          toastr.warning('Fill-out Material Details First');
          $(this).prop('checked', false);
        }
        else{
          if($(this).prop('checked')){
              // Checked
              if(!arrSelectedMaterial.includes(matKitIssue)){
                arrSelectedMaterial.push(matKitIssue);
              }
          }
          else{
              // Unchecked
              let index = arrSelectedMaterial.indexOf(matKitIssue);
              arrSelectedMaterial.splice(index, 1);
          }

          let noOfSelected = parseInt(arrSelectedSakidashi.length) + parseInt(arrSelectedMaterial.length);
          if(noOfSelected > 0){
            $("#spanNoOfSelectedMatSak").text('(' + noOfSelected + ')');
            if($("#txt_prod_runcard_id_query").val() == "" || $("#txt_prod_runcard_id_query").val() == null){
            
            }
            else{
              // $("#btnSaveSelectedMatSak").prop('disabled', false);
            }
          }
          else{
            $("#spanNoOfSelectedMatSak").text('');
            // $("#btnSaveSelectedMatSak").prop('disabled', true);
          }
        }
      });

      $("#btnShowAddProdRuncard").click(function(){
        if($("#txt_po_number_lbl").val() == "" || $("#txt_po_number_lbl").val() == null){
          toastr.warning('PO not found!');
        }
        else{

          checked_draw_count_reset()

          $.ajax({
            url: "get_first_lot_data_by_po_no",
            method: "get",
            data: {
                po_no: $("#txt_po_number_lbl").val()
            },
            dataType: "json",
            success: function(data){
              if( data['data'].length > 0 ){
                $("#txt_WIDoc").val( data['data'][0]['wi_d'] )
                $("#txt_WIDoc_no").val($("#txt_WIDoc").val());
                $("#txt_WIDoc_no").attr('readonly', true)
                $("#txt_WIDoc").attr('readonly', true)
                $("#txt_WIDoc_rev").val( data['data'][0]['wi_d_revision'] )
                $("#WIDoc_revision").val($("#txt_WIDoc_rev").val());

                $("#txt_OGM_VIG_IGDoc").val( data['data'][0]['ogm_vig_ig_d'] )
                $("#txt_OGMVIGIGDoc_no").val($("#txt_OGM_VIG_IGDoc").val());
                $("#txt_OGMVIGIGDoc_no").attr('readonly', true)
                $("#txt_OGM_VIG_IGDoc").attr('readonly', true)
                $("#txt_OGM_VIG_IGDoc_rev").val( data['data'][0]['ogm_vig_ig_d_revision'] )
                $("#txt_OGMVIGIGDoc_revision").val($("#txt_OGM_VIG_IGDoc_rev").val());

                $("#txt_PPDoc").val( data['data'][0]['pp_d'] )
                $("#txt_PPDoc_no").val($("#txt_PPDoc").val());
                $("#txt_PPDoc_no").attr('readonly', true)
                $("#txt_PPDoc").attr('readonly', true)
                $("#txt_PPDoc_rev").val( data['data'][0]['pp_d_revision'] )
                $("#txt_PPDoc_revision").val($("#txt_PPDoc_rev").val());

                $("#txt_UDDoc").val( data['data'][0]['ud_d'] )
                $("#txt_UDDoc_no").val($("#txt_UDDoc").val());
                $("#txt_UDDoc_no").attr('readonly', true)
                $("#txt_UDDoc").attr('readonly', true)
                $("#txt_UDDoc_rev").val( data['data'][0]['ud_d_revision'] )
                $("#txt_UDDoc_revision").val($("#txt_UDDoc_rev").val());

                $("#txt_PMDoc").val( data['data'][0]['pm'] )
                $("#txt_PMDoc_no").val($("#txt_PMDoc").val());
                $("#txt_PMDoc_no").attr('readonly', true)
                $("#txt_PMDoc").attr('readonly', true)
                $("#txt_PMDoc_rev").val( data['data'][0]['pm_revision'] )
                $("#txt_PMDoc_revision").val($("#txt_PMDoc_rev").val());

                // $("#txt_show_modal_to_inform").val(1);
              }else{
                $("#txt_WIDoc_no").val($("#txt_WIDoc").val());
                $("#txt_WIDoc_no").attr('readonly', false)
                $("#txt_WIDoc").attr('readonly', false)
                $("#WIDoc_revision").val($("#txt_WIDoc_rev").val());

                $("#txt_OGMVIGIGDoc_no").val($("#txt_OGM_VIG_IGDoc").val());
                $("#txt_OGMVIGIGDoc_no").attr('readonly', false)
                $("#txt_OGM_VIG_IGDoc").attr('readonly', false)
                $("#txt_OGMVIGIGDoc_revision").val($("#txt_OGM_VIG_IGDoc_rev").val());

                $("#txt_PPDoc_no").val($("#txt_PPDoc").val());
                $("#txt_PPDoc_no").attr('readonly', false)
                $("#txt_PPDoc").attr('readonly', false)
                $("#txt_PPDoc_revision").val($("#txt_PPDoc_rev").val());

                $("#txt_UDDoc_no").val($("#txt_UDDoc").val());
                $("#txt_UDDoc_no").attr('readonly', false)
                $("#txt_UDDoc").attr('readonly', false)
                $("#txt_UDDoc_revision").val($("#txt_UDDoc_rev").val());

                $("#txt_PMDoc_no").val($("#txt_PMDoc").val());
                $("#txt_PMDoc_no").attr('readonly', false)
                $("#txt_PMDoc").attr('readonly', false)
                $("#txt_PMDoc_revision").val($("#txt_PMDoc_rev").val());

                // $("#txt_show_modal_to_inform").val(0);
              }
            }
          })

          $("#btnAddMaterial").prop('disabled', true);
          $("#btnAddProcess").prop('disabled', true);
          $("#btnAddAccessory").prop('disabled', true);
          $("#btnSubmitToOQCLotApp").prop('disabled', true);
          $("#btn_edit_material_details_primary").prop('disabled', false);
          viewMatKitAction = 1;
          viewMatKitActionLotNo = null;
          viewSakidashiAction = 1; 
          viewSakidashiActionLotNo = null;
          // viewEmbossAction = 1;
          // viewEmbossActionLotNo = null;
          $("#txtScannedMatKitLot").val('')
          $("#txtScannedSakidashiLot").val('')
          // $("#txtScannedEmbossLot").val('')
          $("#txtScannedEmployeeNo").val('')
          $("#txtSearchedMatKitLot").val('');
          $("#txtSearchedSakidashiLotNo").val('');
          // $("#txtSearchedEmbossLotNo").val('');
          dt_materials.draw();
          dt_sakidashi.draw();
          // dt_emboss.draw();
          readonly_material_details_primary(true);
          readonly_material_details_secondary(true);
          reset_material_details_primary();
          reset_material_details_secondary();
          HandleButtons(true);
          // $("#btn_edit_material_details_primary").prop('disabled', false);
          // $("#btn_setup_stations").prop('disabled', true);
          $("#txt_lot_qty").val(boxing);
          $("#txt_assessment_no").val(assessment);
          $("#txt_a_drawing_no").val(aDrawing);
          $("#txt_a_drawing_rev").val(aDrawingRev);
          $("#txt_g_drawing_no").val(gDrawing);
          $("#txt_g_drawing_rev").val(gDrawingRev);
          $("#modalRuncardDetails").modal('show');
          arrSelectedMaterial = [];
          arrSelectedSakidashi = [];
          // arrSelectedEmboss = [];
          materialKitIssuanceLotNo = "";
          sakidashiIssuanceLotNo = "";
          // materialKitTransferSlip = $("#txt_material_transfer_slip_lbl").val();
          sakidashiCtrlNo = "";
          $("#txt_material_lot_no_lbl").val("");
          $("#txt_sakidashi_lot_no_lbl").val("");
          // dt_materials.draw();
          GetMaterialKitting();
          // GetSakidashiIssuance();

          GetDrawingNo($(".drawing_no"));
          
          dt_materials.draw();
          dt_sakidashi.draw();
          dt_prod_runcard_stations.draw();
          $("#txt_prod_runcard_id_query").val('');

          $("#txt_po_number").val($("#txt_po_number_lbl").val());
          $("#txt_po_qty").val($("#txt_po_qty_lbl").val());
          $("#txt_use_for_device").val($("#txt_device_name_lbl").val());
          $("#txt_device_code").val($("#txt_device_code_lbl").val());

          $("#txt_Adrawing_no").val($("#txt_Adrawing").val());
          $("#a_revision").val($("#txt_Adrawing_rev").val());
          $("#txt_orig_Adrawing_no").val($("#txt_orig_Adrawing").val());
          $("#orig_a_revision").val($("#txt_orig_Adrawing_rev").val());
          $("#txt_Gdrawing_no").val($("#txt_Gdrawing").val());
          $("#g_revision").val($("#txt_Gdrawing_rev").val());

          $("#txt_JRDJKSDCGJDoc_no").val($("#txt_JRDJKSDCGJDoc").val());
          $("#txt_JRDJKSDCGJDoc_revision").val($("#txt_JRDJKSDCGJDoc_rev").val());
          $("#txt_GPMDDoc_no").val($("#txt_GPMD").val());
          $("#txt_GPMDDoc_revision").val($("#txt_GPMD_rev").val());

          // $("#txt_Odrawing_no").val($("#txt_Odrawing").val());
          // $("#o_revision").val($("#txt_Odrawing_rev").val());

          let noOfSelected = parseInt(arrSelectedSakidashi.length) + parseInt(arrSelectedMaterial.length);
          if(noOfSelected > 0){
            $("#spanNoOfSelectedMatSak").text('(' + noOfSelected + ')');
            // $("#btnSaveSelectedMatSak").prop('disabled', false);
          }
          else{
            $("#spanNoOfSelectedMatSak").text(''); 
            // $("#btnSaveSelectedMatSak").prop('disabled', true);
          }

          // if(parseInt($("#txt_total_no_of_ok").val()) >= parseInt($("#txt_po_qty_lbl").val())){
          //   toastr.warning('PO Quantity already reached!');
          // }
          // $("#btn_edit_material_details_primary").prop('disabled', false);
        }

        check_print_po_if_show()
        check_btnSubmitToOQCLotApp_if_disabled()

      });


      $(".txt_a_drawing").on("keyup", function(e){
        var parent        = $(this).closest(".row_container");
        var data = {
          "action"          : "get_adrawing_no",
          "device_name"     : $("#txt_device_name_lbl").val()
        }
        data = $.param(data);
        $.ajax({
            type      : "get",
            dataType  : "json",
            data      : data,
            url       : "get_adrawing_no",
          success       : function(data){

            var list    = "";
           
            if ($.trim(data['doc'])){
             list+="<option value='N/A' data-revision='N/A' >N/A</option>";
              for(var ctr=0;ctr<data['doc'].length;ctr++){
                list+="<option value='"+data['doc'][ctr]['doc_no']+"' data-revision='"+data['doc'][ctr]['rev_no']+"'>"+data['doc'][ctr]['doc_no']+' '+data['doc'][ctr]['doc_title']+' '+data['doc'][ctr]['rev_no']+"</option>";
              }        
            }

             console.log(data['doc'])

            $(parent).find(".dl_a_drawing").html(list);
          }
        });
      });

      $('#a_drawing_no').change(function () {
  
        if($('#a_drawing_no').val() != 0) {
          var thiss = $(this);
          $('#dl_a_drawing_no option').each(function(index) {
              var id = $(this).val();
              var name = $(this).text();
              var revision = $(this).attr('data-revision');

              if($(thiss).val()==id){
              $('#a_revision').val(revision);
              }
          });

        }else{
            $('#a_revision').val('N/A');
        }
      });

      $(".txt_g_drawing").on("keyup", function(e){
        var parent        = $(this).closest(".row_container");
        var data = {
          "action"          : "get_gdrawing_no",
          "device_name"     : $("#txt_device_name_lbl").val()
        }
        data = $.param(data);
        $.ajax({
            type      : "get",
            dataType  : "json",
            data      : data,
            url       : "get_gdrawing_no",
          success       : function(data){

            var list    = "";
           
            if ($.trim(data['doc'])){
             list+="<option value='N/A' data-revision='N/A' >N/A</option>";
              for(var ctr=0;ctr<data['doc'].length;ctr++){
                list+="<option value='"+data['doc'][ctr]['doc_no']+"' data-revision='"+data['doc'][ctr]['rev_no']+"'>"+data['doc'][ctr]['doc_no']+' '+data['doc'][ctr]['doc_title']+"</option>";
              }        
            }

             console.log(data['doc'])

            $(parent).find(".dl_g_drawing").html(list);
          }
        });
      });

      $('#g_drawing_no').change(function () {
  
        if($('#g_drawing_no').val() != 0) {
          var thiss = $(this);
          $('#dl_g_drawing_no option').each(function(index) {
              var id = $(this).val();
              var name = $(this).text();
              var revision = $(this).attr('data-revision');

              if($(thiss).val()==id){
              $('#g_revision').val(revision);
              }
          });

        }else{
            $('#g_revision').val('N/A');
        }
      });

      $(".txt_o_drawing").on("keyup", function(e){
        var parent        = $(this).closest(".row_container");
        var data = {
          "action"          : "get_odrawing_no",
          "device_name"     : $("#txt_device_name_lbl").val()
        }
        data = $.param(data);
        $.ajax({
            type      : "get",
            dataType  : "json",
            data      : data,
            url       : "get_odrawing_no",
          success       : function(data){

            var list    = "";
           
            if ($.trim(data['doc'])){
             list+="<option value='N/A' data-revision='N/A' >N/A</option>";
              for(var ctr=0;ctr<data['doc'].length;ctr++){
                list+="<option value='"+data['doc'][ctr]['doc_no']+"' data-revision='"+data['doc'][ctr]['rev_no']+"'>"+data['doc'][ctr]['doc_no']+' '+data['doc'][ctr]['doc_title']+' '+data['doc'][ctr]['rev_no']+"</option>";
              }        
            }

             console.log(data['doc'])

            $(parent).find(".dl_o_drawing").html(list);
          }
        });
      });

      $('#o_drawing_no').change(function () {
  
        if($('#o_drawing_no').val() != 0) {
          var thiss = $(this);
          $('#dl_o_drawing_no option').each(function(index) {
              var id = $(this).val();
              var name = $(this).text();
              var revision = $(this).attr('data-revision');

              if($(thiss).val()==id){
              $('#o_revision').val(revision);
              }
          });

        }else{
            $('#o_revision').val('N/A');
        }
      });

      //WI Doc
      $(".class_txt_WIDoc").on("keyup", function(e){
        var parent        = $(this).closest(".row_container");
        var data = {
          "action"          : "get_WIDoc",
          "device_name"     : $("#txt_device_name_lbl").val()
        }
        data = $.param(data);
        $.ajax({
            type      : "get",
            dataType  : "json",
            data      : data,
            url       : "get_WIDoc",
          success       : function(data){

            var list    = "";

            if ($.trim(data['doc'])){
             list+="<option value='N/A' data-revision='N/A' >N/A</option>";
              for(var ctr=0;ctr<data['doc'].length;ctr++){
                list+="<option value='"+data['doc'][ctr]['doc_no']+"' data-revision='"+data['doc'][ctr]['rev_no']+"'>"+data['doc'][ctr]['doc_no']+' '+data['doc'][ctr]['doc_title']+' '+data['doc'][ctr]['rev_no']+"</option>";
              }        
            }

             console.log(data['doc'])

            $(parent).find(".class_dl_WIDoc").html(list);
          }
        });
      });

      $('#txt_WIDoc').change(function () {
        if($('#txt_WIDoc').val() != 0) {
          var thiss = $(this);
          $('#list_txt_WIDoc option').each(function(index) {
              var id = $(this).val();
              var name = $(this).text();
              var revision = $(this).attr('data-revision');

              if($(thiss).val()==id){
              $('#txt_WIDoc_rev').val(revision);
              }
          });

        }else{
            $('#txt_WIDoc_rev').val('N/A');
        }
      });

      //OGM/VIG/IG Doc
      $(".class_txt_OGM_VIG_IGDoc").on("keyup", function(e){
        var parent        = $(this).closest(".row_container");
        var data = {
          "action"          : "get_OGM_VIG_IGDoc",
          "device_name"     : $("#txt_device_name_lbl").val()
        }
        data = $.param(data);
        $.ajax({
            type      : "get",
            dataType  : "json",
            data      : data,
            url       : "get_OGM_VIG_IGDoc",
          success       : function(data){

            var list    = "";

            if ($.trim(data['doc'])){
             list+="<option value='N/A' data-revision='N/A' >N/A</option>";
              for(var ctr=0;ctr<data['doc'].length;ctr++){
                list+="<option value='"+data['doc'][ctr]['doc_no']+"' data-revision='"+data['doc'][ctr]['rev_no']+"'>"+data['doc'][ctr]['doc_no']+' '+data['doc'][ctr]['doc_title']+' '+data['doc'][ctr]['rev_no']+"</option>";
              }        
            }

             console.log(data['doc'])

            $(parent).find(".class_dl_OGM_VIG_IGDoc").html(list);
          }
        });
      });

      $('#txt_OGM_VIG_IGDoc').change(function () {
        if($('#txt_OGM_VIG_IGDoc').val() != 0) {
          var thiss = $(this);
          $('#list_txt_OGM_VIG_IGDoc option').each(function(index) {
              var id = $(this).val();
              var name = $(this).text();
              var revision = $(this).attr('data-revision');

              if($(thiss).val()==id){
              $('#txt_OGM_VIG_IGDoc_rev').val(revision);
              }
          });

        }else{
            $('#txt_OGM_VIG_IGDoc_rev').val('N/A');
        }
      });

      //PP
      $(".class_txt_PPDoc").on("keyup", function(e){
        var parent        = $(this).closest(".row_container");
        var data = {
          "action"          : "get_PPDoc",
          "device_name"     : $("#txt_device_name_lbl").val()
        }
        data = $.param(data);
        $.ajax({
            type      : "get",
            dataType  : "json",
            data      : data,
            url       : "get_PPDoc",
          success       : function(data){

            var list    = "";

            if ($.trim(data['doc'])){
             list+="<option value='N/A' data-revision='N/A' >N/A</option>";
              for(var ctr=0;ctr<data['doc'].length;ctr++){
                list+="<option value='"+data['doc'][ctr]['doc_no']+"' data-revision='"+data['doc'][ctr]['rev_no']+"'>"+data['doc'][ctr]['doc_no']+' '+data['doc'][ctr]['doc_title']+' '+data['doc'][ctr]['rev_no']+"</option>";
              }        
            }

             console.log(data['doc'])

            $(parent).find(".class_dl_PPDoc").html(list);
          }
        });
      });

      $('#txt_PPDoc').change(function () {
        if($('#txt_PPDoc').val() != 0) {
          var thiss = $(this);
          $('#list_txt_PPDoc option').each(function(index) {
              var id = $(this).val();
              var name = $(this).text();
              var revision = $(this).attr('data-revision');

              if($(thiss).val()==id){
              $('#txt_PPDoc_rev').val(revision);
              }
          });

        }else{
            $('#txt_PPDoc_rev').val('N/A');
        }
      });

      //UD
      $(".class_txt_UDDoc").on("keyup", function(e){
        var parent        = $(this).closest(".row_container");
        var data = {
          "action"          : "get_UDDoc",
          "device_name"     : $("#txt_device_name_lbl").val()
        }
        data = $.param(data);
        $.ajax({
            type      : "get",
            dataType  : "json",
            data      : data,
            url       : "get_UDDoc",
          success       : function(data){

            var list    = "";

            if ($.trim(data['doc'])){
             list+="<option value='N/A' data-revision='N/A' >N/A</option>";
              for(var ctr=0;ctr<data['doc'].length;ctr++){
                list+="<option value='"+data['doc'][ctr]['doc_no']+"' data-revision='"+data['doc'][ctr]['rev_no']+"'>"+data['doc'][ctr]['doc_no']+' '+data['doc'][ctr]['doc_title']+' '+data['doc'][ctr]['rev_no']+"</option>";
              }        
            }

             console.log(data['doc'])

            $(parent).find(".class_dl_UDDoc").html(list);
          }
        });
      });

      $('#txt_UDDoc').change(function () {
        if($('#txt_UDDoc').val() != 0) {
          var thiss = $(this);
          $('#list_txt_UDDoc option').each(function(index) {
              var id = $(this).val();
              var name = $(this).text();
              var revision = $(this).attr('data-revision');

              if($(thiss).val()==id){
              $('#txt_UDDoc_rev').val(revision);
              }
          });

        }else{
            $('#txt_UDDoc_rev').val('N/A');
        }
      });

      //PM
      $(".class_txt_PMDoc").on("keyup", function(e){
        var parent        = $(this).closest(".row_container");
        var data = {
          "action"          : "get_PMDoc",
          "device_name"     : $("#txt_device_name_lbl").val()
        }
        data = $.param(data);
        $.ajax({
            type      : "get",
            dataType  : "json",
            data      : data,
            url       : "get_PMDoc",
          success       : function(data){

            var list    = "";

            if ($.trim(data['doc'])){
             list+="<option value='N/A' data-revision='N/A' >N/A</option>";
              for(var ctr=0;ctr<data['doc'].length;ctr++){
                list+="<option value='"+data['doc'][ctr]['doc_no']+"' data-revision='"+data['doc'][ctr]['rev_no']+"'>"+data['doc'][ctr]['doc_no']+' '+data['doc'][ctr]['doc_title']+' '+data['doc'][ctr]['rev_no']+"</option>";
              }        
            }

             console.log(data['doc'])

            $(parent).find(".class_dl_PMDoc").html(list);
          }
        });
      });

      $('#txt_PMDoc').change(function () {
        if($('#txt_PMDoc').val() != 0) {
          var thiss = $(this);
          $('#list_txt_PMDoc option').each(function(index) {
              var id = $(this).val();
              var name = $(this).text();
              var revision = $(this).attr('data-revision');

              if($(thiss).val()==id){
              $('#txt_PMDoc_rev').val(revision);
              }
          });

        }else{
            $('#txt_PMDoc_rev').val('N/A');
        }
      });



      $(document).on('keyup','input',function(){
        // GetDrawingNo($(this));
        console.log( $('.drawing_no').val() )
      });



      $("#btnAddMODTable").click(function(){
        // $('#txt_edit_prod_runcard_operator').val(25).trigger('change');
        let appendHTML = '<tr>';
        appendHTML += '<td>';
          appendHTML += '<select class="form-control select2 select2bs4 selectMOD" name="mod[]">';
            appendHTML += '<option value="">N/A</option>';
          appendHTML += '</select>';
        appendHTML += '</td>';
        appendHTML += '<td>';
          appendHTML += '<input type="number" class="form-control txtEditProdRunStaMODQty" name="mod_qty[]" value="1" min="1">';
        appendHTML += '</td>';
        // appendHTML += '<td>';
        // appendHTML += '<select class="form-control" name="type_of_ng[]">';
        //     appendHTML += '<option value="1">MAT\'L NG.</option>';
        //     appendHTML += '<option value="2">PROD\'N NG.</option>';
        //   appendHTML += '</select>';
        appendHTML += '<td>';
          appendHTML += '<center><button class="btn btn-xs btn-danger btnRemoveMOD" title="Remove" type="button"><i class="fa fa-times"></i></button></center>';
        appendHTML += '</td>';
        appendHTML += '</tr>'

        $("#tblEditProdRunStaMOD tbody").append(appendHTML);
        $('.select2bs4').select2({
          theme: 'bootstrap4'
        });

        GetCboMOD($("#tblEditProdRunStaMOD tr:last").find('.selectMOD'));

        let totalNoOfMOD = 0;

        $('#tblEditProdRunStaMOD .txtEditProdRunStaMODQty').each(function(i, obj) {
          if($(this).val() == null || $(this).val() == ""){
          }
          else{
            totalNoOfMOD += parseFloat($(this).val());
          }
        });
        if($("#txt_edit_prod_runcard_station_ng").val() != totalNoOfMOD){
          toastr.warning('MOD NG Qty not Tally!');
          $("#pRCStatTotNoOfNG").css({color: 'red'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', true);
          $("#pRCStatTotNoOfNG").css({color: 'red'});
        }
        else{
          $("#pRCStatTotNoOfNG").css({color: 'green'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', false);
          $("#pRCStatTotNoOfNG").css({color: 'green'});
        }
        $("#pRCStatTotNoOfNG").text(totalNoOfMOD);
      });

      $("#tblEditProdRunStaMOD").on('click', '.btnRemoveMOD', function(){
        $(this).closest ('tr').remove();
        let totalNoOfMOD = 0;

        $('#tblEditProdRunStaMOD .txtEditProdRunStaMODQty').each(function(i, obj) {
          if($(this).val() == null || $(this).val() == ""){
          }
          else{
            totalNoOfMOD += parseFloat($(this).val());
          }
        });
        if($("#txt_edit_prod_runcard_station_ng").val() != totalNoOfMOD){
          toastr.warning('MOD NG Qty not Tally!');
          $("#pRCStatTotNoOfNG").css({color: 'red'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', true);
        }
        else{
          $("#pRCStatTotNoOfNG").css({color: 'green'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', false);
        }
        $("#pRCStatTotNoOfNG").text(totalNoOfMOD);
      });

      // Insert FVI
      $('#frm_edit_material_details').submit(function(e){
        e.preventDefault();

        $.ajax({
          'data'      : $(this).serialize() +'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&device_name=' + $("#txt_device_name_lbl").val(),
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'save_prod_runcard_details_rev',
          success     : function(data){
              // $('#mdl_alert #mdl_alert_title').html(data['title']);
              // $('#mdl_alert #mdl_alert_body').html(data['body']);
              // $('#mdl_alert').modal('show');

              // $("#txt_prod_runcard_id_query").val(data['prod_runcard_id']);

              // if( $("#txt_show_modal_to_inform").val() == 0 ){
              //   alert( "show modal" )
              // }

              $('#modalScan_EmployeeID').modal('hide')

              if(data['result'] == 1){
                let prod_runcard_id = data['prod_runcard_id']

                $.ajax({
                  type      : "get",
                  dataType  : "json",
                  data      : { po_no: $('#txt_po_number_lbl').val() },
                  url       : "check_pilot_ran_by_po_no",
                  success   : function(data){

                    if( data['result'] ){
                      $("#txt_prod_runcard_id_query").val( prod_runcard_id );
                      GetProdRuncardById($("#txt_prod_runcard_id_query").val());
                      dt_prod_runcard.draw();
                      readonly_material_details_primary(true);
                      toastr.success('FVI', 'Saved Successfully!');
                      // location.reload()
                    }else{
                      $('#modal_to_inform_user_for_pilot_run_runcard_id').val( prod_runcard_id )
                      $('#modal_to_inform_user_for_pilot_run').modal('show')
                    }

                  }
                })

              }
              else if(data['result'] == 2){
                toastr.warning('FVI', 'Scanned Operator not found!');
              }
              else if(data['result'] == 0){
                toastr.error('Assembly Line or Drawing not found!', 'Saving Failed!');
                if(data['error'] != null){
                  if(data['error']['txt_employee_number_scanner'] != null){
                      $("#txt_employee_number_scanner").addClass('is-invalid');
                      $("#txt_employee_number_scanner").attr('title', data['error']['txt_employee_number_scanner']);
                  }
                  else{
                      $("#txt_employee_number_scanner").removeClass('is-invalid');
                      $("#txt_employee_number_scanner").attr('title', '');
                  }

                  if(data['error']['txt_po_number'] != null){
                      $("#txt_po_number").addClass('is-invalid');
                      $("#txt_po_number").attr('title', data['error']['txt_po_number']);
                  }
                  else{
                      $("#txt_po_number").removeClass('is-invalid');
                      $("#txt_po_number").attr('title', '');
                  }

                  if(data['error']['txt_lot_qty'] != null){
                      $("#txt_lot_qty").addClass('is-invalid');
                      $("#txt_lot_qty").attr('title', data['error']['txt_lot_qty']);
                  }
                  else{
                      $("#txt_lot_qty").removeClass('is-invalid');
                      $("#txt_lot_qty").attr('title', '');
                  }
                  
                  if(data['error']['setup_qualification'] != null){
                      $("#setup_qualification").addClass('is-invalid');
                      $("#setup_qualification").attr('title', data['error']['setup_qualification']);
                  }
                  else{
                      $("#setup_qualification").removeClass('is-invalid');
                      $("#setup_qualification").attr('title', '');
                  }

                  if(data['error']['txt_prod_runcard_id_query'] != null){
                      $("#txt_prod_runcard_id_query").addClass('is-invalid');
                      $("#txt_prod_runcard_id_query").attr('title', data['error']['txt_prod_runcard_id_query']);
                  }
                  else{
                      $("#txt_prod_runcard_id_query").removeClass('is-invalid');
                      $("#txt_prod_runcard_id_query").attr('title', '');
                  }
                  
                  if(data['error']['txt_pair_no'] != null){
                      $("#txt_pair_no").addClass('is-invalid');
                      $("#txt_pair_no").attr('title', data['error']['pair_no']);
                  }
                  else{
                      $("#txt_pair_no").removeClass('is-invalid');
                      $("#txt_pair_no").attr('title', '');
                  }
                }
              }
          },
          completed     : function(data){

          },
          error     : function(data){

          },
        });
      });

      $('#modal_to_inform_user_for_pilot_run_btn_yes').click(function() {
        $.ajax({
          type      : "post",
          dataType  : "json",
          data      : { _token: "{{ csrf_token() }}", id: $('#modal_to_inform_user_for_pilot_run_runcard_id').val() },
          url       : "set_have_pilot_ran",
          success   : function(data){

            if( data['result']==1 ){
              $('#modal_to_inform_user_for_pilot_run').modal('hide')
              $("#txt_prod_runcard_id_query").val( $('#modal_to_inform_user_for_pilot_run_runcard_id').val() );
              GetProdRuncardById($("#txt_prod_runcard_id_query").val());
              dt_prod_runcard.draw();
              readonly_material_details_primary(true);
              toastr.success('FVI', 'Saved Successfully!');
              // location.reload()
            }

          }
        })
      })

      $('#modal_to_inform_user_for_pilot_run_btn_no').click(function() {
        $('#modal_to_inform_user_for_pilot_run').modal('hide')
        $("#txt_prod_runcard_id_query").val( $('#modal_to_inform_user_for_pilot_run_runcard_id').val() );
        GetProdRuncardById($("#txt_prod_runcard_id_query").val());
        dt_prod_runcard.draw();
        readonly_material_details_primary(true);
        toastr.success('FVI', 'Saved Successfully!');
        // location.reload()
      })

      $("#tbl_prod_runcard").on('click','.btnOpenRuncardDetails',function(e){

        checked_draw_count_reset()

        viewMatKitAction = 1;
        viewMatKitActionLotNo = null;
        viewSakidashiAction = 1;
        viewSakidashiActionLotNo = null;

        $("#txt_po_number").removeClass('is-invalid');
        $("#txt_lot_qty").removeClass('is-invalid');
        $("#setup_qualification").removeClass('is-invalid');
        $("#txt_prod_runcard_id_query").removeClass('is-invalid');
        $("#txt_pair_no").removeClass('is-invalid');

        // viewEmbossAction = 1;
        // viewEmbossActionLotNo = null;
        $("#txtScannedMatKitLot").val('')
        $("#txtScannedSakidashiLot").val('')
        // $("#txtScannedEmbossLot").val('')
        $("#txtScannedEmployeeNo").val('')
        $("#txtSearchedMatKitLot").val('');
        $("#txtSearchedSakidashiLotNo").val('');
        // $("#txtSearchedEmbossLotNo").val('');
        let prodRuncardId = $(this).attr('runcard-id');
        $("#txt_prod_runcard_id_query").val(prodRuncardId);
        materialKitIssuanceLotNo = "";
        $("#txt_material_lot_no_lbl").val("");
        sakidashiIssuanceLotNo = "";
        $("#txt_sakidashi_lot_no_lbl").val("");

        arrSelectedMaterial = [];
        arrSelectedSakidashi = [];
        // arrSelectedEmboss = [];
        materialKitTransferSlip = "";
        $("#txt_material_transfer_slip_lbl").val("");
        sakidashiCtrlNo = "";
        $("#txt_sakidashi_ctrl_no_lbl").val("");
        GetProdRuncardById(prodRuncardId);
        dt_materials.draw();
        dtAccessories.draw();

      });      

      // $("#txt_edit_prod_runcard_operator").change(function(){
      //   let certifiedOperators = $("#txt_edit_prod_runcard_cert_operator").val();
      //   let selectedOperators = $("#txt_edit_prod_runcard_operator").val();
      //   // console.log(certifiedOperators);

      //   if(selectedOperators.length > 0){
      //     for(let index = 0; index < selectedOperators.length; index++){
      //       if(!certifiedOperators.includes(selectedOperators[index])){
      //         // toastr.warning('Not Certified!');
      //         $('#txt_edit_prod_runcard_operator option[value="'+selectedOperators[index]+'"]').prop('selected', false).trigger('change');
      //       }
      //     }
      //   }
      // });

      // $("#txt_edit_prod_runcard_station_machine").change(function(){
      //   let assignedMachine = $("#txt_edit_prod_runcard_station_assigned_machine").val();
      //   let selectedMachines = $("#txt_edit_prod_runcard_station_machine").val();
      //   // console.log(assignedMachine);
      //   if(selectedMachines.length > 0){
      //     for(let index = 0; index < selectedMachines.length; index++){
      //       if(!assignedMachine.includes(selectedMachines[index])){
      //         // toastr.warning('Not Assigned!');
      //         $('#txt_edit_prod_runcard_station_machine option[value="'+selectedMachines[index]+'"]').prop('selected', false).trigger('change');
      //       }
      //     }
      //   }
      // });

      $(document).on('click','#btn_edit_material_details_primary',function(e){
        // GetProdRuncardById($("#txt_prod_runcard_id_query").val());
        readonly_material_details_primary(false);

        if($('select[name="qc_qualified"]', $("#frm_edit_material_details")).val() == 1){
          $('textarea[name="qc_remarks"]', $("#frm_edit_material_details")).prop('readonly', true).val('');
        }
        else{
          $('textarea[name="qc_remarks"]', $("#frm_edit_material_details")).prop('readonly', false);
        }
      });

      $(document).on('click','#btn_cancel_material_details_primary',function(e){
        GetProdRuncardById($("#txt_prod_runcard_id_query").val());
        readonly_material_details_primary(true);
      });

      $(document).on('click','#btn_edit_material_details_verification',function(e){
        readonly_material_details_secondary(false);
      });

      $(document).on('click','#btn_cancel_material_details_secondary',function(e){
        readonly_material_details_secondary(true);
      });

      $(document).on('click','#btn_setup_stations',function(e){
        let noOfSelected = parseInt(arrSelectedMaterial.length) + parseInt(arrSelectedSakidashi.length);
        if(noOfSelected > 0){
          dt_setup_stations.draw();
          $('#mdl_setup_stations').modal('show');
        }
        else{
          toastr.warning('Select Material List First');
          // toastr.warning('Fill-out Material Details First');
        }
      });

      $(".btnSearchPoNo").click(function(){
        $("#txtSearchPoTransLotNo").val('');
        $("#scanPOTransLotBody").text('Please Scan the PO code.');
        $('#modalScanPOTransLotCode').attr('data-formid', 'search_po_number').modal('show');
      });

      $(".btnScanMatKitLot").click(function(){
        $("#txtSearchPoTransLotNo").val('');
        $("#scanPOTransLotBody").text('Please Scan the Material Lot code.');
        $('#modalScanPOTransLotCode').attr('data-formid', 'scan_mat_kit').modal('show');
        $("#txtScannedMatKitLot").val('');
      });

      $(".btnSearchMatKitLot").click(function(){
        $("#txtSearchPoTransLotNo").val('');
        $("#scanPOTransLotBody").text('Please Scan the Material Lot code to search.');
        $('#modalScanPOTransLotCode').attr('data-formid', 'search_mat_kit').modal('show');
        $("#txtSearchedMatKitLot").val('');
      });

      $(".btnShowSavedMatKitLot").click(function(){
        viewMatKitAction = 1;
        viewMatKitActionLotNo = null;
        dt_materials.draw();
        $("#txtSearchedMatKitLot").val('');
      });

      $(".btnScanWhsSlipNo").click(function(){
        $("#txtSearchPoTransLotNo").val('');
        $("#scanPOTransLotBody").text('Please Scan the Material Lot code.');
        $('#modalScanPOTransLotCode').attr('data-formid', 'scan_whs_slip_no').modal('show');
      });

      $(".btnScanSakidashiLot").click(function(){
        $("#txtSearchPoTransLotNo").val('');
        $("#scanPOTransLotBody").text('Please Scan the Sakidashi Lot code.');
        $('#modalScanPOTransLotCode').attr('data-formid', 'scan_sakidashi').modal('show');
        $("#txtScannedSakidashiLot").val('');
      });

      $(".btnSearchSakidashiLotNo").click(function(){
        $("#txtSearchPoTransLotNo").val('');
        $("#scanPOTransLotBody").text('Please Scan the Sakidashi Lot code to search.');
        $('#modalScanPOTransLotCode').attr('data-formid', 'search_sakidashi').modal('show');
        $("#txtScannedSakidashiLot").val('');
      });

      $(".btnShowSavedSakidashiLot").click(function(){
        viewSakidashiAction = 1;
        viewSakidashiActionLotNo = null;
        dt_sakidashi.draw();
        $("#txtSearchedSakidashiLot").val('');
      });

      // $(".btnScanEmbossLot").click(function(){
        // $("#txtSearchPoTransLotNo").val('');
        // $("#scanPOTransLotBody").text('Please Scan the Emboss Lot code.');
        // $('#modalScanPOTransLotCode').attr('data-formid', 'scan_emboss').modal('show');
        // $("#txtScannedEmbossLot").val('');
      // });

      // $(".btnSearchEmbossLotNo").click(function(){
      //   $("#txtSearchPoTransLotNo").val('');
      //   $("#scanPOTransLotBody").text('Please Scan the Emboss Lot code to search.');
      //   $('#modalScanPOTransLotCode').attr('data-formid', 'search_emboss').modal('show');
      //   $("#txtScannedEmbossLot").val('');
      // });

      // $(".btnSearchManualEmbossLot").click(function(){
      //   let lotNo = $("#txtSearchedManualEmbossLot").val();
      //   $(this).focus();
      //   viewEmbossAction = 2;
      //   viewEmbossActionLotNo = lotNo;
      //   $("#txtSearchedEmbossLot").val(lotNo.trim());
      //   // dt_emboss.draw();
      // });

      // $("#txtSearchedManualEmbossLot").keypress(function(e){

      //   if(e.keyCode == 13){
      //     let lotNo = $("#txtSearchedManualEmbossLot").val();
      //     $(this).focus();
      //     viewEmbossAction = 2;
      //     viewEmbossActionLotNo = lotNo;
      //     $("#txtSearchedEmbossLot").val(lotNo.trim());
      //     // dt_emboss.draw();
      //   }

      // });

      // $(".btnShowSavedEmbossLot").click(function(){
      //   viewEmbossAction = 1;
      //   viewEmbossActionLotNo = null;
      //   // dt_emboss.draw();
      //   $("#txtSearchedEmbossLot").val('');
      //   $("#txtSearchedManualEmbossLot").val('');
      // });

      $(".btnScanEmpNo").click(function(){
        $("#txtSearchPoTransLotNo").val('');
        $("#scanPOTransLotBody").text('Please Scan the Operator Code.');
        $('#modalScanPOTransLotCode').attr('data-formid', 'scan_employee_no').modal('show');
        $("#txtScannedEmployeeNo").val('');
      });

      $(document).on('click','#btn_save_prod_runcard_station_stations',function(e){
        $('#txt_employee_number_scanner').val('');
        // $('#mdl_employee_number_scanner').attr('data-formid','#frm_edit_prod_runcard_station_details').modal('show');
        $('#frm_edit_prod_runcard_station_details').submit();
      });

      $(document).on('click','#btn_save_material_details_secondary',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#frm_edit_material_details_secondary').modal('show');
      });

      $(document).on('click','#btn_approve_prod',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','save_approve_prod').modal('show');
      });

      $(document).on('click','#btn_approve_qc',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','save_approve_qc').modal('show');
      });

      $(document).on('click','#btnSubmitToOQCLotApp',function(e){
        // $('#txt_employee_number_scanner').val('');
        // $('#mdl_employee_number_scanner').attr('data-formid','submit_to_oqc_lot_app').modal('show');
        submit_to_oqc_lot_app();
      });

      $(document).on('click','.btnSelEngQual',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','select_eng_qual').modal('show');
      });

      $(document).on('click','.btnSelQCStamp',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','select_qc_qual').modal('show');
      });

      $(document).on('click','.btnClearEngQual',function(e){
        $('input[name="eng_qualification_id"]', $("#frm_edit_material_details")).val('');
        $('input[name="eng_qualification_name"]', $("#frm_edit_material_details")).val('');
      });

      $(document).on('click','.btnClearQCStamp',function(e){
        $('input[name="qc_stamp_id"]', $("#frm_edit_material_details")).val('');
        $('input[name="qc_stamp_name"]', $("#frm_edit_material_details")).val('');
      });

      $("#chkCheckAllSetupStations").click(function(){
        if($(this).prop('checked')){
          $("#tbl_setup_stations .ckb_station").prop('checked', true);
        }
        else{
          $("#tbl_setup_stations .ckb_station").prop('checked', false);
        }
      });

      $("#tbl_setup_stations").on('click', '.ckb_station', function(){
        if(!$(this).prop('checked')){
          $("#chkCheckAllSetupStations").prop('checked', false);
        }
      });

      $("#txt_edit_prod_runcard_station_output").keyup(function(){
        $("#txt_edit_prod_runcard_station_ng").val(parseInt($("#txt_edit_prod_runcard_station_input").val()) - parseInt($(this).val()));

        if($("#txt_edit_prod_runcard_station_ng").val() > 0){
          $("#btnAddMODTable").prop('disabled', false);
        }
        else{
          $("#btnAddMODTable").prop('disabled', true);
        }

        if($("#txt_edit_prod_runcard_station_ng").val() != $("#pRCStatTotNoOfNG").text()){
          $("#pRCStatTotNoOfNG").css({color: 'red'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', true);
        }
        else{
          $("#pRCStatTotNoOfNG").css({color: 'green'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', false);
        }
      });

      $("#txt_edit_prod_runcard_station_input").keyup(function(){
        $("#txt_edit_prod_runcard_station_ng").val(parseInt($("#txt_edit_prod_runcard_station_input").val()) - parseInt($("#txt_edit_prod_runcard_station_output").val()));

        if($("#txt_edit_prod_runcard_station_ng").val() > 0){
          $("#btnAddMODTable").prop('disabled', false);
        }
        else{
          $("#btnAddMODTable").prop('disabled', true);
        }

        if($("#txt_edit_prod_runcard_station_ng").val() != $("#pRCStatTotNoOfNG").text()){
          $("#pRCStatTotNoOfNG").css({color: 'red'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', true);
        }
        else{
          $("#pRCStatTotNoOfNG").css({color: 'green'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', false);
        }
      });

      $("#txt_edit_prod_runcard_station_ng").keyup(function(){
        $("#txt_edit_prod_runcard_station_output").val(parseInt($("#txt_edit_prod_runcard_station_input").val()) - parseInt($("#txt_edit_prod_runcard_station_ng").val()));

        if($(this).val() > 0){
          $("#btnAddMODTable").prop('disabled', false);
        }
        else{
          $("#btnAddMODTable").prop('disabled', true);
        }

        if($(this).val() != $("#pRCStatTotNoOfNG").text()){
          console.log($(this).val() + " = " + $("#pRCStatTotNoOfNG").text());
          // toastr.warning('MOD NG Qty not Tally!');
          $("#pRCStatTotNoOfNG").css({color: 'red'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', true);
        }
        else{
          $("#pRCStatTotNoOfNG").css({color: 'green'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', false);
        }

      });

      $("#txt_edit_prod_runcard_station_ng").change(function(){
        $("#txt_edit_prod_runcard_station_output").val(parseInt($("#txt_edit_prod_runcard_station_input").val()) - parseInt($("#txt_edit_prod_runcard_station_ng").val()));

        if($(this).val() > 0){
          $("#btnAddMODTable").prop('disabled', false);
        }
        else{
          $("#btnAddMODTable").prop('disabled', true);
        }

        if($(this).val() != $("#pRCStatTotNoOfNG").text()){
          console.log($(this).val() + " = " + $("#pRCStatTotNoOfNG").text());
          // toastr.warning('MOD NG Qty not Tally!');
          $("#pRCStatTotNoOfNG").css({color: 'red'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', true);
        }
        else{
          $("#pRCStatTotNoOfNG").css({color: 'green'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', false);
        }

      });

      $('#frm_edit_material_details_secondary').submit(function(e){
        e.preventDefault();

        $.ajax({
          'data'      : $(this).serialize() + '&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val(),
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'update_prod_runcard_secondary',
          success     : function(data){
            if(data == '0'){
              toastr.error('User not found!');
            }
            else{
              $('#mdl_alert #mdl_alert_title').html(data['title']);
              $('#mdl_alert #mdl_alert_body').html(data['body']);
              $('#mdl_alert').modal('show');
              readonly_material_details_secondary(true);
              //---
              var data_arr = [];
              data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
              data_arr['material_code']   = $('#txt_part_number').val();
              // fn_select_material_details(data_arr);
              //---

              GetProdRuncardById($("#txt_prod_runcard_id_query").val());

              dt_prod_runcard.draw();
              // dt_materials.ajax.reload();
              // dt_sakidashi.ajax.reload();

              $('#mdl_setup_stations').modal('hide');
              $('#mdl_alert #mdl_alert_title').html(data['title']);
              $('#mdl_alert #mdl_alert_body').html(data['body']);
              $('#mdl_alert').modal('show');
              
              if(data['result'] != '0'){
                dt_prod_runcard_stations.ajax.reload();
              }
            }
          },
          completed     : function(data){

          },
          error     : function(data){

          },
        });
      });

      readonly_material_details_primary(true);
      readonly_material_details_secondary(true);

      // function GetProdRuncardById(prodRuncardId){
      //   $.ajax({
      //     url: "get_prod_runcard_by_id",
      //     method: 'get',
      //     dataType: 'json',
      //     data: {
      //       prod_runcard_id: prodRuncardId
      //     },
      //     beforeSend: function(){
      //       arrSelectedMaterial = [];
      //       arrSelectedSakidashi = [];
      //       readonly_material_details_primary(true);
      //       readonly_material_details_secondary(true);
      //       // reset_material_details_primary();
      //       // reset_material_details_secondary();
      //     },
      //     success: function(data){
      //       if(data['prod_runcard'] != null){
      //         $("#modalRuncardDetails").modal('show');
      //         $("#txt_po_number").val($("#txt_po_number_lbl").val());
      //         $("#txt_po_qty").val($("#txt_po_qty_lbl").val());
      //         $("#txt_use_for_device").val($("#txt_device_name_lbl").val());
      //         $("#txt_device_code").val($("#txt_device_code_lbl").val());
      //         $("#txt_prod_runcard_id_query").val(data['prod_runcard']['id']);
      //         $("#txt_prod_runcard_status").val(data['prod_runcard']['status']);
      //         $("#txt_prod_runcard_verification_id_query").val(data['prod_runcard']['id']);
      //         $("#txt_runcard_no").val(data['prod_runcard']['runcard_no']);
      //         $("#txt_lot_qty").val(data['prod_runcard']['lot_qty']);
      //         $("#txt_assessment_no").val(data['prod_runcard']['assessment_no']);
      //         $("#txt_a_drawing_no").val(data['prod_runcard']['a_drawing_no']);
      //         $("#txt_a_drawing_rev").val(data['prod_runcard']['a_drawing_rev']);
      //         $("#txt_g_drawing_no").val(data['prod_runcard']['g_drawing_no']);
      //         $("#txt_g_drawing_rev").val(data['prod_runcard']['g_drawing_rev']);
      //         $("#txt_other_docs_no").val(data['prod_runcard']['other_docs_no']);
      //         $("#txt_other_docs_rev").val(data['prod_runcard']['other_docs_rev']);

      //         if(data['prod_runcard']['discrepant_qty'] != null){
      //           if(data['prod_runcard']['discrepant_qty'] < 0){
      //             $('#txt_discrepant_qty_sign').val("-");
      //             $('#txt_discrepant_qty').val(data['prod_runcard']['discrepant_qty'] * -1);
      //           }
      //           else if(data['prod_runcard']['discrepant_qty'] == 0){
      //             $('#txt_discrepant_qty_sign').val("0");
      //             $('#txt_discrepant_qty').val(data['prod_runcard']['discrepant_qty']);
      //           }
      //           else{
      //             $('#txt_discrepant_qty_sign').val("+");
      //             $('#txt_discrepant_qty').val(data['prod_runcard']['discrepant_qty']);
      //           }
      //         }
      //         else{
      //           $('#txt_discrepant_qty_sign').val("0");
      //           $('#txt_discrepant_qty').val("");
      //         }

      //         $('#txt_analysis').val(data['prod_runcard']['analysis']);
      //         $('#txt_recount_ok').val(data['prod_runcard']['recount_ok']);
      //         $('#txt_recount_ng').val(data['prod_runcard']['recount_ng']);

      //         if(data['prod_runcard']['supervisor_prod_info'] != null){
      //           $('#txt_prod_approval').val(data['prod_runcard']['supervisor_prod_info']['name']);
      //         }
      //         else{
      //           $('#txt_prod_approval').val("");
      //         }

      //         if(data['prod_runcard']['supervisor_qc_info'] != null){
      //           $('#txt_qc_approval').val(data['prod_runcard']['supervisor_qc_info']['name']);
      //         }
      //         else{
      //           $('#txt_qc_approval').val("");
      //         }

      //         if(data['prod_runcard']['prod_runcard_material_list'].length > 0){
      //           // $("#btn_edit_material_details_primary").prop('disabled', true);
      //           // $("#btnSaveSelectedMatSak").prop('disabled', true);

      //           for(let index = 0; index < data['prod_runcard']['prod_runcard_material_list'].length; index++){
      //             if(data['prod_runcard']['prod_runcard_material_list'][index]['tbl_wbs'] == 1){
      //               arrSelectedMaterial.push(data['prod_runcard']['prod_runcard_material_list'][index]['issuance_id']);
      //             }
      //             else{
      //               arrSelectedSakidashi.push(data['prod_runcard']['prod_runcard_material_list'][index]['issuance_id']);
      //             }
      //           }
      //         }
      //         else{
      //           // $("#btn_edit_material_details_primary").prop('disabled', false);
      //           // $("#btnSaveSelectedMatSak").prop('disabled', false);
      //         }

      //         if(data['prod_runcard']['prod_runcard_material_list'].length > 0){
      //           hasProdMatSakList = true;
      //           // $("#btn_setup_stations").prop('disabled', false);
      //             if(data['prod_runcard']['discrepant_qty'] != null) {
      //             // $("#btn_edit_material_details_verification").prop('disabled', true);
      //             // $("#btn_setup_stations").prop('disabled', true);
      //             // $("#btn_approve_prod").prop('disabled', false);
      //             // $("#btn_approve_qc").prop('disabled', false);

      //             if(data['prod_runcard']['supervisor_prod_info'] != null){
      //               // $("#btn_edit_material_details_verification").prop('disabled', true);
      //               // $("#btn_approve_prod").prop('disabled', true);
      //               // $("#btn_approve_prod").prop('disabled', true);
      //               if(data['prod_runcard']['supervisor_qc_info'] != null){
      //                 // $("#btn_approve_qc").prop('disabled', true);
      //               }
      //               else{
      //                 // $("#btn_approve_qc").prop('disabled', false); 
      //               }
      //             }
      //             else{
      //               // $("#btn_edit_material_details_verification").prop('disabled', false);
      //               // $("#btn_approve_prod").prop('disabled', false);
      //               // $("#btn_approve_qc").prop('disabled', true);
      //             }

      //             if(data['prod_runcard']['discrepant_qty'] == 0){
      //               // $("#btn_approve_prod").prop('disabled', true);
      //               // $("#btn_approve_qc").prop('disabled', true);
      //             }
      //           }
      //           else{
      //             // $("#btn_setup_stations").prop('disabled', false);
      //             // $("#btn_approve_prod").prop('disabled', true);
      //             // $("#btn_approve_qc").prop('disabled', true);
      //             // $("#btn_edit_material_details_verification").prop('disabled', false);
      //           }
      //         }
      //         else{
      //           // $("#btn_setup_stations").prop('disabled', true);
      //           hasProdMatSakList = true;
      //         }

      //         // if(data['prod_runcard']['discrepant_qty'] != null) {
      //           // $("#btn_edit_material_details_verification").prop('disabled', true);
      //           // $("#btn_setup_stations").prop('disabled', true);
      //         //   $("#btn_approve_prod").prop('disabled', false);
      //         //   $("#btn_approve_qc").prop('disabled', false);

      //         //   if(data['prod_runcard']['supervisor_prod_info'] != null){
      //             // $("#btn_edit_material_details_verification").prop('disabled', true);
      //         //     $("#btn_approve_prod").prop('disabled', true);
      //         //     $("#btn_approve_prod").prop('disabled', true);
      //         //     if(data['prod_runcard']['supervisor_qc_info'] != null){
      //         //       $("#btn_approve_qc").prop('disabled', true);
      //         //     }
      //         //     else{
      //         //       $("#btn_approve_qc").prop('disabled', false); 
      //         //     }
      //         //   }
      //         //   else{
      //             // $("#btn_edit_material_details_verification").prop('disabled', false);
      //         //     $("#btn_approve_prod").prop('disabled', false);
      //         //     $("#btn_approve_qc").prop('disabled', true);
      //         //   }

      //         //   if(data['prod_runcard']['discrepant_qty'] == 0){
      //         //     $("#btn_approve_prod").prop('disabled', true);
      //         //     $("#btn_approve_qc").prop('disabled', true);
      //         //   }
      //         // }
      //         // else{
      //           // $("#btn_setup_stations").prop('disabled', false);
      //         //   $("#btn_approve_prod").prop('disabled', true);
      //         //   $("#btn_approve_qc").prop('disabled', true);
      //           // $("#btn_edit_material_details_verification").prop('disabled', false);
      //         // }

      //         HandleButtons(true);
      //         let prodRuncardStat = data['prod_runcard']['status'];
      //         if(prodRuncardStat == 1){
      //           $("#btn_edit_material_details_primary").prop('disabled', true);  
      //           $("#btnSaveSelectedMatSak").prop('disabled', false);
      //           $("#btn_setup_stations").prop('disabled', true);
      //           $("#btn_edit_material_details_verification").prop('disabled', true);
      //           $("#btn_approve_prod").prop('disabled', true);
      //           $("#btn_approve_qc").prop('disabled', true);
      //         }
      //         else if(prodRuncardStat == 2){
      //           $("#btn_edit_material_details_primary").prop('disabled', true);  
      //           $("#btnSaveSelectedMatSak").prop('disabled', true);
      //           $("#btn_setup_stations").prop('disabled', false);
      //           $("#btn_edit_material_details_verification").prop('disabled', true);
      //           $("#btn_approve_prod").prop('disabled', true);
      //           $("#btn_approve_qc").prop('disabled', true);
      //         }
      //         else if(prodRuncardStat == 3){
      //           $("#btn_edit_material_details_primary").prop('disabled', true);  
      //           $("#btnSaveSelectedMatSak").prop('disabled', true);
      //           $("#btn_setup_stations").prop('disabled', true);
      //           $("#btn_edit_material_details_verification").prop('disabled', false);
      //           $("#btn_approve_prod").prop('disabled', true);
      //           $("#btn_approve_qc").prop('disabled', true);
      //         }
      //         else if(prodRuncardStat == 4){
      //           $("#btn_edit_material_details_primary").prop('disabled', true);  
      //           $("#btnSaveSelectedMatSak").prop('disabled', true);
      //           $("#btn_setup_stations").prop('disabled', true);
      //           $("#btn_edit_material_details_verification").prop('disabled', true);
      //           $("#btn_approve_prod").prop('disabled', false);
      //           $("#btn_approve_qc").prop('disabled', true);
      //         }
      //         else if(prodRuncardStat == 5){
      //           $("#btn_edit_material_details_primary").prop('disabled', true);  
      //           $("#btnSaveSelectedMatSak").prop('disabled', true);
      //           $("#btn_setup_stations").prop('disabled', true);
      //           $("#btn_edit_material_details_verification").prop('disabled', true);
      //           $("#btn_approve_prod").prop('disabled', true);
      //           $("#btn_approve_qc").prop('disabled', false);
      //         }
      //         else{
      //           HandleButtons(true);
      //         }

      //         // $("#btn_setup_stations").removeAttr('disabled');
      //         // $("#btn_edit_material_details_verification").removeAttr('disabled');
      //         // $("#btnSaveSelectedMatSak").removeAttr('disabled');
      //         // $("#btn_approve_prod").removeAttr('disabled');
      //         // $("#btn_approve_qc").removeAttr('disabled');
      //       }
      //       else{
      //         $("#txt_po_number").val('');
      //         $("#txt_po_qty").val('');
      //         $("#txt_use_for_device").val('');
      //         $("#txt_device_code").val('');
      //         $("#txt_runcard_no").val('');
      //         $("#txt_lot_qty").val('');
      //         $("#txt_assessment_no").val('');
      //         $("#txt_a_drawing_no").val('');
      //         $("#txt_a_drawing_rev").val('');
      //         $("#txt_g_drawing_no").val('');
      //         $("#txt_g_drawing_rev").val('');
      //         $("#txt_other_docs_no").val('');
      //         $("#txt_other_docs_rev").val('');

      //         // $("#btn_setup_stations").prop('disabled', 'disabled');
      //         // $("#btnSaveSelectedMatSak").prop('disabled', true);
      //         // $("#btn_edit_material_details_verification").prop('disabled', true);
      //         // $("#btn_setup_stations").prop('disabled', true);
      //         // $("#btn_approve_prod").prop('disabled', true);
      //         // $("#btn_approve_qc").prop('disabled', true);
      //       }

      //       let noOfSelected = parseInt(arrSelectedMaterial.length) + parseInt(arrSelectedSakidashi.length);
      //       if(noOfSelected > 0 && hasProdMatSakList){
      //         $("#spanNoOfSelectedMatSak").text("(" + noOfSelected + ")");
      //         // $("#btnSaveSelectedMatSak").prop('disabled', true);
      //       }
      //       else{
      //         $("#spanNoOfSelectedMatSak").text("");
      //         // $("#btnSaveSelectedMatSak").prop('disabled', false);
      //       }
      //       dt_materials.draw();
      //       dt_sakidashi.draw();
      //       dt_prod_runcard_stations.draw();
      //       // console.log(arrSelectedMaterial);
      //       // console.log(arrSelectedSakidashi);
      //     }
      //   });
      // }

      // function HandleButtons(status){
      //   $("#btn_edit_material_details_primary").prop('disabled', status);  
      //   $("#btnSaveSelectedMatSak").prop('disabled', status);
      //   $("#btn_setup_stations").prop('disabled', status);
      //   $("#btn_edit_material_details_verification").prop('disabled', status);
      //   $("#btn_approve_prod").prop('disabled', status);
      //   $("#btn_approve_qc").prop('disabled', status);
      // }

      // function save_approve_prod(){
      //   var data = 'txt_prod_runcard_id_query=' + $("#txt_prod_runcard_id_query").val()+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&_token=' + '{{ csrf_token() }}';

      //   $.ajax({
      //     'data'      : data,
      //     'type'      : 'post',
      //     'dataType'  : 'json',
      //     'url'       : 'update_prod_runcard_approval_prod',
      //     success     : function(data){
      //       $('#mdl_alert #mdl_alert_title').html(data['title']);
      //       $('#mdl_alert #mdl_alert_body').html(data['body']);
      //       $('#mdl_alert').modal('show');

      //       var data_arr = [];
      //       data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
      //       data_arr['material_code']   = $('#txt_part_number').val();
      //       // fn_select_material_details(data_arr);
      //       GetProdRuncardById($("#txt_prod_runcard_id_query").val());
      //     },
      //     completed     : function(data){
      //       var data_arr = [];
      //       data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
      //       data_arr['material_code']   = $('#txt_part_number').val();
      //       // fn_select_material_details(data_arr);
      //       $('#mdl_alert #mdl_alert_title').html(data['title']);
      //       $('#mdl_alert #mdl_alert_body').html(data['body']);
      //       $('#mdl_alert').modal('show');
      //       GetProdRuncardById($("#txt_prod_runcard_id_query").val());
      //     },
      //     error     : function(data){
      //     },
      //   });
      // }

      // function save_approve_qc(){
      //   var data = 'txt_prod_runcard_id_query=' + $("#txt_prod_runcard_id_query").val()+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&_token=' + '{{ csrf_token() }}';

      //   $.ajax({
      //     'data'      : data,
      //     'type'      : 'post',
      //     'dataTypesdsd'  : 'json',
      //     'url'       : 'update_prod_runcard_approval_qc',
      //     success     : function(data){
      //       $('#mdl_alert #mdl_alert_title').html(data['title']);
      //       $('#mdl_alert #mdl_alert_body').html(data['body']);
      //       $('#mdl_alert').modal('show');
      //       var data_arr = [];
      //       data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
      //       data_arr['material_code']   = $('#txt_part_number').val();
      //       // fn_select_material_details(data_arr);
      //       GetProdRuncardById($("#txt_prod_runcard_id_query").val());
      //     },
      //     completed     : function(data){
      //       var data_arr = [];
      //       data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
      //       data_arr['material_code']   = $('#txt_part_number').val();
      //       // fn_select_material_details(data_arr);
      //       $('#mdl_alert #mdl_alert_title').html(data['title']);
      //       $('#mdl_alert #mdl_alert_body').html(data['body']);
      //       $('#mdl_alert').modal('show');
      //       GetProdRuncardById($("#txt_prod_runcard_id_query").val());
      //     },
      //     error     : function(data){

      //     },
      //   });
      // }




      //-------------------------------------------------------

      // function OutputDataCounter(api) {
      //   let counter = 0;

      //   api.column().data(0, {page:'current'}).each( function (group, i) {

      //         let data = api.row(i).data();
      //         let output = data.qty_output;
      //         let status = data.status;
      //         // if(output != null){
      //         //   counter++;
      //         // }

      //         if(status == 1){
      //           counter++;
      //         }
      //   });

      //   if(api.rows().count() > 0 && (counter == api.rows().count())){
      //     dtOutputStatus = true;
      //   }
      //   else{
      //     dtOutputStatus = false;
      //   }

      //   if(api.rows().count() > 0){
      //     // $("#btnSaveSelectedMatSak").prop('disabled', true);
      //   }
      //   // else{
      //    // $("#btnSaveSelectedMatSak").prop('disabled', false);
      //   // }

      //   // alert(dtOutputStatus);
      //   if(dtOutputStatus){
      //     // $("#btn_edit_material_details_verification").prop('disabled', false);
      //     if($("#txt_prod_runcard_status").val() == 1){
      //       // $("#btn_edit_material_details_verification").prop('disabled', true);
      //     }
      //     else{
      //       // $("#btn_edit_material_details_verification").prop('disabled', false);
      //     }
      //   }
      //   else{ 
      //     // $("#btn_edit_material_details_verification").prop('disabled', true);
      //   }
      // }

      $('select[name="qc_qualified"]', $("#frm_edit_material_details")).change(function(){
        if($(this).val() == 1){
          $('textarea[name="qc_remarks"]', $("#frm_edit_material_details")).prop('readonly', true).val('');
        }
        else{
          $('textarea[name="qc_remarks"]', $("#frm_edit_material_details")).prop('readonly', false);
        }
      });

      $("#btnShowNGSummary").click(function(){
        if($("#txt_po_number_lbl").val() != ""){
          $("#spanNGSummaryPoNo").text(currentPoNo);
          dt_ng_summary.draw();
          $("#modalNGSummary").modal('show');
        }
        else{
          toastr.warning('PO not found!');
          $("#spanNGSummaryPoNo").text('--');
        }
      });

      $(document).on('click','.btnEditAccessory',function(e){
        let accessoryId = $(this).attr('accessory-id');
        GetAccessoryById(accessoryId);
      });

      $(document).on('click','#btn_scan_machine_code',function(e){
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_machine_code').modal('show');
      });

      $(document).on('click','#btn_scan_add_runcard_material_kitting',function(e){
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_material_kitting').modal('show');
      });

      $(document).on('click','#btn_scan_add_runcard_sakidashi',function(e){
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_sakidashi').modal('show');
      });

      $(document).on('click','#btnScanMaterialLotNo',function(e){
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','btnScanMaterialLotNo').modal('show');
      });

      // $(document).on('click','#btn_scan_add_runcard_emboss',function(e){
      //   $('#txt_qrcode_scanner').val('');
      //   $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_emboss').modal('show');
      // });

      $(document).on('click','#btn_scan_add_runcard_operator_code',function(e){
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_runcard_operator_code').modal('show');
      });

      $(document).on('click','.btn_scan_delete_process',function(e){
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_delete_process').modal('show');
        let runcardStationId = $(this).attr('runcard-station-id');
        // alert(runcardStationId);
        deleteRuncardStationId = runcardStationId;
      });

      $(document).on('click','.btn_edit_prod_runcard_station',function(e){
        var data_arr = [];
        data_arr['col_prod_runcard_station_id']     = $(this).closest('tr').find('.col_prod_runcard_station_id').val();
        // data_arr['has_emboss']     = $(this).attr('has_emboss');
        // data_arr['material_code']   = $(this).closest('tr').find('.col_material_code').val();
        $("#txt_edit_prod_runcard_operator").val("0").trigger('change');
        let matProcId = $(this).attr('mat-proc-id');
        fn_select_prod_runcard_station_details(data_arr, matProcId);

        $("#tblEditProdRunStaMOD tbody").html('');
        $("#pRCStatTotNoOfNG").css({color: 'green'});
        $("#pRCStatTotNoOfNG").text('0');

        // let stepNumOnly = $(this).closest('td').siblings().text();
        // let stepNumOnly = $(this).closest('td').siblings().eq(0).text().split('-')[0];
        // let sameStepNumCounter = 0;
        // // $('#tbl_prod_runcard_stations tbody tr').each(function(i, obj) {
        // //   console.log($("#tbl_prod_runcard_stations tbody tr").closest('td').child().eq(0).text().split('-')[0] + 'wew');
        // // });

        // $('#tbl_prod_runcard_stations tbody').find('tr').each(function (key, val) {
        //       var this_row = $(this);
        //       let tdStepNum = $.trim(this_row.find('td:eq(1)').html()).split('-')[0];
              
        //       if(stepNumOnly == tdStepNum){
        //         sameStepNumCounter++;
        //       }

        //       // console.log(tdStepNum);
        // });
        // // console.log(sameStepNumCounter);
        // $("#txt_edit_prod_runcard_station_input").val(parseInt($(
        //         "#txt_lot_qty").val() / sameStepNumCounter));
        // $("#txt_edit_prod_runcard_station_output").val(parseInt($(
        //         "#txt_lot_qty").val() / sameStepNumCounter));
        $("#txt_edit_prod_runcard_station_ng").val(0);

        $("#btn_save_prod_runcard_station_stations").prop('disabled', false);
      });

      $('#frm_edit_prod_runcard_station_details').submit(function(e){
        e.preventDefault();

        $.ajax({
          'data'      : $(this).serialize() +'&txt_prod_runcard_id_query=' + $("#txt_prod_runcard_id_query").val() +'&selected_prod_runcard=' + arrSelectedRuncards + '&txt_po_number=' + $("#txt_po_number").val(),
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'edit_prod_runcard_station',
          success     : function(data){
            $('#mdl_alert #mdl_alert_title').html(data['title']);
            $('#mdl_alert #mdl_alert_body').html(data['body']);
            $('#mdl_alert').modal('show');
            arrSelectedRuncards = [];
            $("#btnOverallInspection").prop('disabled', true);
            $(".spanOICount").html('');
            dt_prod_runcard_stations.draw();
            dt_prod_runcard.draw();
            $('#mdl_edit_prod_runcard_station_details').modal('hide');

            check_print_po_if_show()
            check_btnSubmitToOQCLotApp_if_disabled()

          },
          completed     : function(data){

          },
          error     : function(data){

          },
        });
      });

      $('#modalScanPOTransLotCode').on('shown.bs.modal', function () {
        $('#txtSearchPoTransLotNo').val('')
        $('#txtSearchPoTransLotNo').trigger('focus');
      })

      $(document).on('click','#btn_save_material_details_primary',function(e){
        // $('#txt_employee_number_scanner').val('');
        // $('#mdl_employee_number_scanner').attr('data-formid','#frm_edit_material_details').modal('show');

        // $('#frm_edit_material_details').submit();

        let ids = ['txt_Adrawing_no', 'txt_orig_Adrawing_no', 'txt_Gdrawing_no', 'txt_WIDoc', 'txt_OGM_VIG_IGDoc', 'txt_PPDoc', 'txt_UDDoc', 'txt_PMDoc', 'txt_JRDJKSDCGJDoc_no', 'txt_GPMDDoc_no']
        let need_to_cherck_all_drawings = false
        for (var i = 0; i < ids.length; i++) {
          let txt_Adrawing = $('#' + ids[i]).val()
          if ( txt_Adrawing != 'N/A' && txt_Adrawing != '' ){
            if( checked_draw_count[i] == 0 )
              need_to_cherck_all_drawings = true
          }
        }

        if( need_to_cherck_all_drawings ){
          alert( 'Please check all drawings first.' )
        }else{
          $("#modalScan_EmployeeID_id").val('')
          $('#modalScan_EmployeeID').modal('show')
          $("#modalScan_EmployeeID_id").focus()          
        }

      });

      $("#modalScan_EmployeeID_id").keydown(function (e) {
        if (e.keyCode == 13) {
          
          $.ajax({
            'data'      : { employee_id: $("#modalScan_EmployeeID_id").val() },
            'type'      : 'get',
            'dataType'  : 'json',
            'url'       : 'check_employee_id',
            success     : function(data){
              if( data['result'] ){
                $("#frm_edit_material_details_employee_id").val( $("#modalScan_EmployeeID_id").val() )
                $('#frm_edit_material_details').submit();
              }
              else
                alert( 'Invalid Employee ID' )
            },
            completed     : function(data){

            },
            error     : function(data){

            },
          });

        }
      });

      $(document).on('click','#btnSaveSelectedMatSak',function(e){
        let noOfSelected = parseInt(arrSelectedSakidashi.length) + parseInt(arrSelectedMaterial.length);
        if($("#txt_prod_runcard_id_query").val() == "" || $("#txt_prod_runcard_id_query").val() == null){
          toastr.warning('Fill-out Material Details First!');
        }
        else{
          if($("#txt_prod_runcard_status").val() == 1){
            if(noOfSelected > 0){
              if($("#txt_prod_runcard_id_query").val() == "" || $("#txt_prod_runcard_id_query").val() == null){
                toastr.warning('Fill-out Material Details First');
              }
              else{
                $('#txt_employee_number_scanner').val('');
                $('#mdl_employee_number_scanner').attr('data-formid','SaveProdMaterialList').modal('show');
              }
            }
            else{
              toastr.warning('Please Select Material!');
            }
          }
          else{
            toastr.warning('Materials Already Saved!');
          }
        }

      });

      

      $(document).on('click','#btn_save_setup_stations',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','save_setup_stations').modal('show');
      });

      $("#tbl_sakidashi").on('click','.btnSaveSakidashiLot',function(e){
        let issuanceId = $(this).attr('issuance-id');
        let itemDesc = $(this).attr('item-desc');
        let item = $(this).attr('item');

        saveSakidashiLotIssuanceId = issuanceId;
        saveSakidashiLotItem = item;
        saveSakidashiLotItemDesc = itemDesc;

        // console.log(saveSakidashiLotIssuanceId + "\n" + saveSakidashiLotItem + "\n" + saveSakidashiLotItemDesc);
        // console.log(issuanceId);
        $("#txtSearchPoTransLotNo").val('');
        $("#scanPOTransLotBody").text('Please Scan your ID.');
        $('#modalScanPOTransLotCode').attr('data-formid', 'save_sakidashi_lot_issuance').modal('show');
      });

      $("#btnAddMaterial").click(function(){
        // alert('wew');
        // $("#frmSaveMaterial")[0].reset();
        // $("input[name='material_id']", $("#frmSaveMaterial")).val('0').trigger('change');
      });

    });

function GetAccessoryById(accessoryId){
    // let url = globalLink.replace('link', 'get_accessory_by_id');
    // let login = globalLink.replace('link', 'login');

    $.ajax({
        url: 'get_accessory_by_id',
        method: 'get',
        data: {
            accessory_id: accessoryId
        },
        dataType: 'json',
        beforeSend() {
            $("#btnSaveAccessory").prop('disabled', true);
            $("#btnSaveAccessory").html('Saving...');
            $(".input-error", $('#frmSaveAccessory')).text('');
            $(".form-control", $('#frmSaveAccessory')).removeClass('is-invalid');
            // cnfrmLoading.open();
            $('#frmSaveAccessory')[0].reset();
            $('input[name="accessory_id"]', $('#frmSaveAccessory')).val('');
        },
        success(data){
            $("#btnSaveAccessory").prop('disabled', false);
            $("#btnSaveAccessory").html('Save');
            // cnfrmLoading.close();
            $("input[name='description']", $('#frmSaveAccessory')).focus();

            // if(data['auth'] == 1){
                if(data['accessory_info'] != null){
                    $("input[name='product_name']", $("#frmSaveAccessory")).val($("#txt_use_for_device").val());
                    $("#mdlSaveAccessory").modal('show');
                    $('input[name="accessory_id"]', $('#frmSaveAccessory')).val(data['accessory_info']['id']);
                    $('select[name="issuance_id"]', $('#frmSaveAccessory')).val(data['accessory_info']['item_code'] + '--' + data['accessory_info']['item_desc'] + '--' + data['accessory_info']['issuance_id']).trigger('change');
                    // alert(data['accessory_info']['item_code'] + '--' + data['accessory_info']['item_desc'] + '--' + data['accessory_info']['issuance_id']);
                    $('input[name="quantity"]', $('#frmSaveAccessory')).val(data['accessory_info']['quantity']);
                    $('input[name="usage_per_socket"]', $('#frmSaveAccessory')).val(data['accessory_info']['usage_per_socket']);
                    $('select[name="counted_by"]', $('#frmSaveAccessory')).val(data['accessory_info']['counted_by']).trigger('change');
                    $('input[name="counted_by_date"]', $('#frmSaveAccessory')).val(data['accessory_info']['counted_by_date']);
                    $('select[name="checked_by"]', $('#frmSaveAccessory')).val(data['accessory_info']['checked_by']).trigger('change');
                    $('input[name="checked_by_date"]', $('#frmSaveAccessory')).val(data['accessory_info']['checked_by_date']);
                    $('select[name="prod_supervisor"]', $('#frmSaveAccessory')).val(data['accessory_info']['prod_supervisor']).trigger('change');
                    $('input[name="prod_supervisor_date"]', $('#frmSaveAccessory')).val(data['accessory_info']['prod_supervisor_date']);

                    if(data['accessory_info']['require_supervisor'] == 1){
                      $('input[name="require_supervisor"]', $('#frmSaveAccessory')).prop('checked', true);
                      $("select[name='prod_supervisor']", $("#frmSaveAccessory")).trigger('change').prop('disabled', true);
                      $("input[name='prod_supervisor_date']", $("#frmSaveAccessory")).prop('disabled', true);
                    }
                    else{
                      $('input[name="require_supervisor"]', $('#frmSaveAccessory')).prop('checked', false);
                      $("select[name='prod_supervisor']", $("#frmSaveAccessory")).trigger('change').prop('disabled', false);
                      $("input[name='prod_supervisor_date']", $("#frmSaveAccessory")).prop('disabled', false);
                    }
                }
                else{
                    toastr.error('No record found.');
                }
            // }
            // else{ // if session expired
            //     // cnfrmAutoLogin.open();
            //     toastr.error('Session Expired!');
            // }
        },
        error(xhr, data, status){
            // cnfrmLoading.close();
            $("#btnSaveAccessory").prop('disabled', false);
            $("#btnSaveAccessory").html('Save');
            toastr.error('An error occured!');
        }
    });
}

function OutputDataCounter(api) {
        let counter = 0;

        api.column().data(0, {page:'current'}).each( function (group, i) {

              let data = api.row(i).data();
              let output = data.qty_output;
              let status = data.status;
              // if(output != null){
              //   counter++;
              // }

              if(status == 1){
                counter++;
              }
        });

        if(api.rows().count() > 0 && (counter == api.rows().count())){
          dtOutputStatus = true;
        }
        else{
          dtOutputStatus = false;
        }

        if(api.rows().count() > 0){
          // $("#btnSaveSelectedMatSak").prop('disabled', true);
        }
        // else{
         // $("#btnSaveSelectedMatSak").prop('disabled', false);
        // }

        // alert(dtOutputStatus);
        if(dtOutputStatus){
          // $("#btn_edit_material_details_verification").prop('disabled', false);
          if($("#txt_prod_runcard_status").val() == 1){
            // $("#btn_edit_material_details_verification").prop('disabled', true);
          }
          else{
            // $("#btn_edit_material_details_verification").prop('disabled', false);
          }
        }
        else{ 
          // $("#btn_edit_material_details_verification").prop('disabled', true);
        }
      }


      function HandleButtons(status){
        // $("#btn_edit_material_details_primary").prop('disabled', status);  
        $("#btnSaveSelectedMatSak").prop('disabled', status);
        // $("#btnSaveSelectedEmboss").prop('disabled', status);
        $("#btn_setup_stations").prop('disabled', status);
        $("#btn_edit_material_details_verification").prop('disabled', status);
        $("#btn_approve_prod").prop('disabled', status);
        $("#btn_approve_qc").prop('disabled', status);
      }

      function save_approve_prod(){
        var data = 'txt_prod_runcard_id_query=' + $("#txt_prod_runcard_id_query").val()+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&_token=' + '{{ csrf_token() }}';

        $.ajax({
          'data'      : data,
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'update_prod_runcard_approval_prod',
          success     : function(data){
            $('#mdl_alert #mdl_alert_title').html(data['title']);
            $('#mdl_alert #mdl_alert_body').html(data['body']);
            $('#mdl_alert').modal('show');

            var data_arr = [];
            data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
            data_arr['material_code']   = $('#txt_part_number').val();
            // fn_select_material_details(data_arr);
            GetProdRuncardById($("#txt_prod_runcard_id_query").val());
          },
          completed     : function(data){
            var data_arr = [];
            data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
            data_arr['material_code']   = $('#txt_part_number').val();
            // fn_select_material_details(data_arr);
            $('#mdl_alert #mdl_alert_title').html(data['title']);
            $('#mdl_alert #mdl_alert_body').html(data['body']);
            $('#mdl_alert').modal('show');
            GetProdRuncardById($("#txt_prod_runcard_id_query").val());
          },
          error     : function(data){
          },
        });
      }

      function submit_to_oqc_lot_app(){
        // alert('Submitted!');
        // return 'wew';
        var data = 'txt_prod_runcard_id_query=' + $("#txt_prod_runcard_id_query").val() + '&_token=' + '{{ csrf_token() }}';

        $.ajax({
          'data'      : data,
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'submit_to_oqc_lot_app',
          success     : function(data){
            var data_arr = [];
            data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
            data_arr['material_code']   = $('#txt_part_number').val();
            // fn_select_material_details(data_arr);
            // $('#mdl_alert #mdl_alert_title').html(data['title']);
            // $('#mdl_alert #mdl_alert_body').html(data['body']);
            // $('#mdl_alert').modal('show');
            console.log(data);
            if(data['result'] == 1){
              toastr.success('Submit to OQC', 'Successfully Saved!');
              GetProdRuncardById($("#txt_prod_runcard_id_query").val());
              dt_prod_runcard.draw();
            }
            else if(data['result'] == 2){
              toastr.warning('Submit to OQC', 'Qualification Failed!');
            }
            else if(data['result'] == 3){
              toastr.warning('Submit to OQC', 'Runcard not found!');
            }
            else{
              toastr.error('Submit to OQC', 'saving Failed!');
            }
          },
          completed     : function(data){
            var data_arr = [];
            data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
            data_arr['material_code']   = $('#txt_part_number').val();
            // fn_select_material_details(data_arr);
            // $('#mdl_alert #mdl_alert_title').html(data['title']);
            // $('#mdl_alert #mdl_alert_body').html(data['body']);
            // $('#mdl_alert').modal('show');

            if(data['result'] == 1){
              toastr.success('Submit to OQC', 'Successfully Saved!');
              GetProdRuncardById($("#txt_prod_runcard_id_query").val());
              dt_prod_runcard.draw();
            }
            else if(data['result'] == 2){
              toastr.warning('Submit to OQC', 'Qualification Failed!');
            }
            else if(data['result'] == 3){
              toastr.warning('Submit to OQC', 'Runcard not found!');
            }
            else{
              toastr.error('Submit to OQC', 'saving Failed!');
            }
          },
          error     : function(data){
          },
        });
      }

      function save_material(){
        // alert('Submitted!');
        // return 'wew';
        var data = $("#frmSaveMaterial").serialize() + '&txt_prod_runcard_id_query=' + $("#txt_prod_runcard_id_query").val() + '&txt_po_number=' + $("#txt_po_number").val() + '&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&_token=' + '{{ csrf_token() }}';

        $.ajax({
          'data'      : data,
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'save_material',
          success     : function(data){
            $('#mdl_alert #mdl_alert_title').html(data['title']);
            $('#mdl_alert #mdl_alert_body').html(data['body']);
            $('#mdl_alert').modal('show');

            var data_arr = [];
            data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
            data_arr['material_code']   = $('#txt_part_number').val();
            // fn_select_material_details(data_arr);
            // GetProdRuncardById($("#txt_prod_runcard_id_query").val());
            dt_materials.draw();
            $("#mdlSaveMaterial").modal('hide');
          },
          completed     : function(data){
            var data_arr = [];
            data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
            data_arr['material_code']   = $('#txt_part_number').val();
            // fn_select_material_details(data_arr);
            $('#mdl_alert #mdl_alert_title').html(data['title']);
            $('#mdl_alert #mdl_alert_body').html(data['body']);
            $('#mdl_alert').modal('show');
          },
          error     : function(data){
          },
        });
      }

      function save_approve_qc(){
        var data = 'txt_prod_runcard_id_query=' + $("#txt_prod_runcard_id_query").val()+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&_token=' + '{{ csrf_token() }}';

        $.ajax({
          'data'      : data,
          'type'      : 'post',
          'dataTypesdsd'  : 'json',
          'url'       : 'update_prod_runcard_approval_qc',
          success     : function(data){
            $('#mdl_alert #mdl_alert_title').html(data['title']);
            $('#mdl_alert #mdl_alert_body').html(data['body']);
            $('#mdl_alert').modal('show');
            var data_arr = [];
            data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
            data_arr['material_code']   = $('#txt_part_number').val();
            // fn_select_material_details(data_arr);
            GetProdRuncardById($("#txt_prod_runcard_id_query").val());
          },
          completed     : function(data){
            var data_arr = [];
            data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
            data_arr['material_code']   = $('#txt_part_number').val();
            // fn_select_material_details(data_arr);
            $('#mdl_alert #mdl_alert_title').html(data['title']);
            $('#mdl_alert #mdl_alert_body').html(data['body']);
            $('#mdl_alert').modal('show');
            GetProdRuncardById($("#txt_prod_runcard_id_query").val());
          },
          error     : function(data){

          },
        });
      }

    function GetMaterialKitting(){
      $.ajax({
        url: "get_wbs_material_kitting_rev",
        method: 'get',
        dataType: 'json',
        data: {
          po_number: currentPoNo
        },
        beforeSend: function(){
          boxing = "";
          assessment = "";
          aDrawing = "";
          aDrawingRev = "";
          gDrawing = "";
          gDrawingRev = "";
        },
        success: function(data){
          if(data['material_kitting'] != null){
            $("#txt_po_number_lbl").val(data['material_kitting']['po_no']);
            $("#img_barcode_PO").attr('src', data['po_no_qr']);
            $("#txt_device_name_lbl").val(data['material_kitting']['device_name']);
            $('#lbl_device_name').attr('device_name_print', data['device_name_print'] );
            $("#txt_device_code_lbl").val(data['material_kitting']['device_code']);
            $("#txt_po_qty_lbl").val(data['material_kitting']['po_qty']);

            if (data['a_drawing'] == '' ){
              $("#txt_Adrawing").val('N/A');
              $("#txt_Adrawing_rev").val('N/A');
              $('.btnSearchADrawing').prop('disabled', true);
            }else{
              $("#txt_Adrawing").val(data['a_drawing'][0]['doc_no']);
              $("#txt_Adrawing_rev").val(data['a_drawing'][0]['rev_no']);
              $("#txt_Adrawing_fkid_document").val(data['a_drawing'][0]['fkid_document']);
            }

            if (data['orig_a_drawing'] == '' ){
              $("#txt_orig_Adrawing").val('N/A');
              $("#txt_orig_Adrawing_rev").val('N/A');
              $('.btnSearchOrigADrawing').prop('disabled', true);
            }else{
              $("#txt_orig_Adrawing").val(data['orig_a_drawing'][0]['doc_no']);
              $("#txt_orig_Adrawing_rev").val(data['orig_a_drawing'][0]['rev_no']);
              $("#txt_orig_Adrawing_fkid_document").val(data['orig_a_drawing'][0]['fkid_document']);
            }

            if (data['g_drawing'] == ''){
              $("#txt_Gdrawing").val('N/A');
              $("#txt_Gdrawing_rev").val('N/A');
              $('.btnSearchGDrawing').prop('disabled', true);
            }else{
              $("#txt_Gdrawing").val(data['g_drawing'][0]['doc_no']);
              $("#txt_Gdrawing_rev").val(data['g_drawing'][0]['rev_no']);
              $("#txt_Gdrawing_fkid_document").val(data['g_drawing'][0]['fkid_document']);
            }

            // if (data['o_drawing'] == ''){
            //   $("#txt_Odrawing").val('N/A');
            //   $("#txt_Odrawing_rev").val('N/A');
            //   $('.btnSearchODrawing').prop('disabled', true);
            // }else{
            //   $("#txt_Odrawing").val(data['o_drawing'][0]['doc_no']);
            //   $("#txt_Odrawing_rev").val(data['o_drawing'][0]['rev_no']);
            // }

            if (data['jrdjksdcgj_drawing'] == ''){
              $("#txt_JRDJKSDCGJDoc").val('N/A');
              $("#txt_JRDJKSDCGJDoc_rev").val('N/A');
              $('.btnSearchJRDJKSDCGJ').prop('disabled', true);
            }else{
              $("#txt_JRDJKSDCGJDoc").val(data['jrdjksdcgj_drawing'][0]['doc_no']);
              $("#txt_JRDJKSDCGJDoc_rev").val(data['jrdjksdcgj_drawing'][0]['rev_no']);
            }

            if (data['gpmd_drawing'] == ''){
              $("#txt_GPMD").val('N/A');
              $("#txt_GPMD_rev").val('N/A');
              $('.btnSearchJRDJKSDCGJ').prop('disabled', true);
            }else{
              $("#txt_GPMD").val(data['gpmd_drawing'][0]['doc_no']);
              $("#txt_GPMD_rev").val(data['gpmd_drawing'][0]['rev_no']);
            }

            if(data['material_kitting']['device_info'] != null){
              // $("#txt_lot_qty").val(data['material_kitting']['device_info']['boxing']);
              boxing = data['material_kitting']['device_info']['boxing'];
              // alert(boxing);
            }
            else{
              boxing = "";
              // $("#txt_lot_qty").val("");
            }

            if(data['material_kitting']['assessment'] != null){
              assessment = data['material_kitting']['device_info']['assessment'];
            }
            else{
              assessment = "";
            }

            $("#txt_ct_supplier").val('YEC');

            // TEMP REMOVE Due to Request of operatr to fix it in "YEC"
            // if(data['material_kitting']['material_issuance_details'].length > 0){
            //     $("#txt_ct_supplier").val(data['material_kitting']['material_issuance_details'][0]['supplier']);
            // }
            // else{
            //     $("#txt_ct_supplier").val('');
            // }
            // ..TEMP REMOVE Due to Request of operatr to fix it in "YEC"

            // if(data['material_kitting']['documents_details'].length > 0){
            //   for(let index = 0; index < data['material_kitting']['documents_details'].length; index++){
            //     if(data['material_kitting']['documents_details'][index]['doc_no'].charAt(0).toUpperCase() == "A"){
            //       aDrawing = data['material_kitting']['documents_details'][index]['doc_no'];
            //       aDrawingRev = data['material_kitting']['documents_details'][index]['rev_no'];
            //     }
            //     else if(data['material_kitting']['documents_details'][index]['doc_no'].charAt(0).toUpperCase() == "G"){
            //       gDrawing = data['material_kitting']['documents_details'][index]['doc_no'];
            //       gDrawingRev = data['material_kitting']['documents_details'][index]['rev_no'];
            //     }
            //   }

            //   $("#txt_a_drawing_no").val(aDrawing);
            //   $("#txt_a_drawing_rev").val(aDrawingRev);
            //   $("#txt_g_drawing_no").val(gDrawing);
            //   $("#txt_g_drawing_rev").val(gDrawingRev);
            // }
            // if(data['doc_a_drawing_query'].length > 0){
            //   aDrawing = data['doc_a_drawing_query'][0].doc_no;
            //   aDrawingRev = data['doc_a_drawing_query'][0].rev_no;
            // }
            // else{
            //   aDrawing = "";
            //   aDrawingRev = "";
            // }
            // $("#txt_a_drawing_no").val(aDrawing);
            // $("#txt_a_drawing_rev").val(aDrawingRev);

            // if(data['doc_g_drawing_query'].length > 0){
            //   gDrawing = data['doc_g_drawing_query'][0].doc_no;
            //   gDrawingRev = data['doc_g_drawing_query'][0].rev_no;
            // }
            // else{
            //   gDrawing = "";
            //   gDrawingRev = "";
            // }
            $("#txt_g_drawing_no").val(gDrawing);
            $("#txt_g_drawing_rev").val(gDrawingRev);

            // materialKitTransferSlip = data['material_kitting']['issuance_no'];
            // $("#txt_material_transfer_slip_lbl").val(materialKitTransferSlip);
            // dt_materials.draw();

            // $("#txt_lot_qty").val(boxing);
          }
          else{
            $("#txt_po_number_lbl").val('');
            $("#txt_device_name_lbl").val('');
            $("#txt_device_code_lbl").val('');
            $("#txt_po_qty_lbl").val(''); 
            $("#txt_lot_qty").val('');

            $('#txt_orig_Adrawing').val('');
            $('#txt_orig_Adrawing_rev').val('');
            $('#txt_Adrawing').val('');
            $('#txt_Adrawing_rev').val('');
            $('#txt_Gdrawing').val('');
            $('#txt_Gdrawing_rev').val('');
            $('#txt_JRDJKSDCGJDoc').val('');
            $('#txt_JRDJKSDCGJDoc_rev').val('');
            $('#txt_GPMD').val('');
            $('#txt_GPMD_rev').val('');

            // $('#txt_Odrawing').val('');
            // $('#txt_Odrawing_rev').val('');



          }
        }
      });
    }

    function GetSakidashiIssuance(){
      $.ajax({
        url: "get_wbs_sakidashi_issuance",
        method: 'get',
        dataType: 'json',
        data: {
          po_number: currentPoNo
        },
        beforeSend: function(){
          // boxing = "";
        },
        success: function(data){
          if(data['sakidashi_issuance'] != null){
            sakidashiCtrlNo = data['sakidashi_issuance']['issuance_no'];
            $("#txt_sakidashi_ctrl_no_lbl").val(sakidashiCtrlNo);
            dt_sakidashi.draw();
          }
          else{
            sakidashiCtrlNo = "";
            $("#txt_sakidashi_ctrl_no_lbl").val("");
            dt_sakidashi.draw();
          }
        }
      });
    }

    function GetProdRuncardById(prodRuncardId){
      $.ajax({
        url: "get_prod_runcard_by_id",
        method: 'get',
        dataType: 'json',
        data: {
          prod_runcard_id: prodRuncardId
        },
        beforeSend: function(){
          arrSelectedMaterial = [];
          arrSelectedSakidashi = [];
          // arrSelectedEmboss = [];
          readonly_material_details_primary(true);
          readonly_material_details_secondary(true);
          // reset_material_details_primary();
          // reset_material_details_secondary();
        },
        success: function(data){

        $("#txt_po_number").removeClass('is-invalid');
        $("#txt_lot_qty").removeClass('is-invalid');
        $("#setup_qualification").removeClass('is-invalid');
        $("#txt_prod_runcard_id_query").removeClass('is-invalid');
        $("#txt_pair_no").removeClass('is-invalid');

          if(data['prod_runcard'] != null){  
            $("#modalRuncardDetails").modal('show');
            $("#txt_po_number").val($("#txt_po_number_lbl").val());
            $("#txt_po_qty").val($("#txt_po_qty_lbl").val());
            $("#txt_use_for_device").val($("#txt_device_name_lbl").val());
            $("#txt_device_code").val($("#txt_device_code_lbl").val());
            $("#txt_prod_runcard_id_query").val(data['prod_runcard']['id']);
            $("#txt_prod_runcard_status").val(data['prod_runcard']['status']);
            // $("#txt_prod_runcard_has_emboss").val(data['prod_runcard']['has_emboss']);
            // $("#txt_prod_runcard_require_oqc_before_emboss").val(data['prod_runcard']['require_oqc_before_emboss']);
            $("#txt_prod_runcard_verification_id_query").val(data['prod_runcard']['id']);
            // $("#txt_runcard_no").val(data['prod_runcard']['runcard_no']);
            $("#txt_lot_no").val(data['prod_runcard']['lot_no']);
            $("#txt_reel_lot_no").val(data['prod_runcard']['reel_lot_no']);
            $("#txt_lot_qty").val(data['prod_runcard']['lot_qty']);

            $('select[name="setup_qualification"]', $("#frm_edit_material_details")).val(data['prod_runcard']['setup_qualification']);
            $('select[name="setup_qualified"]', $("#frm_edit_material_details")).val(data['prod_runcard']['setup_qualified']);
            $('select[name="qc_qualification"]', $("#frm_edit_material_details")).val(data['prod_runcard']['qc_qualification']);
            $('select[name="qc_qualified"]', $("#frm_edit_material_details")).val(data['prod_runcard']['qc_qualified']);
            
            $('input[name="eng_qualification_id"]', $("#frm_edit_material_details")).val(data['prod_runcard']['eng_qualification_id']);
            $('input[name="qc_stamp_id"]', $("#frm_edit_material_details")).val(data['prod_runcard']['qc_stamp_id']);
            $('textarea[name="qc_remarks"]', $("#frm_edit_material_details")).val(data['prod_runcard']['qc_remarks']);

            if(data['prod_runcard']['eng_qualification_info'] != null){
              $('input[name="eng_qualification_name"]', $("#frm_edit_material_details")).val(data['prod_runcard']['eng_qualification_info']['name']);
            }
            else{
              $('input[name="eng_qualification_name"]', $("#frm_edit_material_details")).val('');
            }

            if(data['prod_runcard']['qc_stamp_qualification_info'] != null){
              $('input[name="qc_stamp_name"]', $("#frm_edit_material_details")).val(data['prod_runcard']['qc_stamp_qualification_info']['name']);
            }
            else{
              $('input[name="qc_stamp_name"]', $("#frm_edit_material_details")).val('');
            }


            $("#txt_WIDoc").val( data['prod_runcard']['wi_d'] )
            $("#txt_WIDoc_no").val($("#txt_WIDoc").val());
            $("#txt_WIDoc_no").attr('readonly', true)
            $("#txt_WIDoc").attr('readonly', true)
            $("#txt_WIDoc_rev").val( data['prod_runcard']['wi_d_revision'] )
            $("#WIDoc_revision").val($("#txt_WIDoc_rev").val());

            $("#txt_OGM_VIG_IGDoc").val( data['prod_runcard']['ogm_vig_ig_d'] )
            $("#txt_OGMVIGIGDoc_no").val($("#txt_OGM_VIG_IGDoc").val());
            $("#txt_OGMVIGIGDoc_no").attr('readonly', true)
            $("#txt_OGM_VIG_IGDoc").attr('readonly', true)
            $("#txt_OGM_VIG_IGDoc_rev").val( data['prod_runcard']['ogm_vig_ig_d_revision'] )
            $("#txt_OGMVIGIGDoc_revision").val($("#txt_OGM_VIG_IGDoc_rev").val());

            $("#txt_PPDoc").val( data['prod_runcard']['pp_d'] )
            $("#txt_PPDoc_no").val($("#txt_PPDoc").val());
            $("#txt_PPDoc_no").attr('readonly', true)
            $("#txt_PPDoc").attr('readonly', true)
            $("#txt_PPDoc_rev").val( data['prod_runcard']['pp_d_revision'] )
            $("#txt_PPDoc_revision").val($("#txt_PPDoc_rev").val());

            $("#txt_UDDoc").val( data['prod_runcard']['ud_d'] )
            $("#txt_UDDoc_no").val($("#txt_UDDoc").val());
            $("#txt_UDDoc_no").attr('readonly', true)
            $("#txt_UDDoc").attr('readonly', true)
            $("#txt_UDDoc_rev").val( data['prod_runcard']['ud_d_revision'] )
            $("#txt_UDDoc_revision").val($("#txt_UDDoc_rev").val());

            $("#txt_PMDoc").val( data['prod_runcard']['pm'] )
            $("#txt_PMDoc_no").val($("#txt_PMDoc").val());
            $("#txt_PMDoc_no").attr('readonly', true)
            $("#txt_PMDoc").attr('readonly', true)
            $("#txt_PMDoc_rev").val( data['prod_runcard']['pm_revision'] )
            $("#txt_PMDoc_revision").val($("#txt_PMDoc_rev").val());

            // $("#txt_assessment_no").val(data['prod_runcard']['assessment_no']);
            // $("#txt_a_drawing_no").val(data['prod_runcard']['a_drawing_no']);
            // $("#txt_a_drawing_rev").val(data['prod_runcard']['a_drawing_rev']);
            // $("#txt_g_drawing_no").val(data['prod_runcard']['g_drawing_no']);
            // $("#txt_g_drawing_rev").val(data['prod_runcard']['g_drawing_rev']);
            // $("#txt_other_docs_no").val(data['prod_runcard']['other_docs_no']);
            // $("#txt_other_docs_rev").val(data['prod_runcard']['other_docs_rev']);

            $("#txt_mold").val(data['prod_runcard']['mold']);
            $("#txt_ct_supplier").val(data['prod_runcard']['ct_supplier']);
            $("#txt_die_no").val(data['prod_runcard']['die_no']);
            $("#txt_pair_no").val(data['prod_runcard']['pair_no']);
            $("#txt_remarks").val(data['prod_runcard']['remarks']);
            $("#txt_application_datetime").val(data['prod_runcard']['application_datetime']);
            $("#sel_assembly_line").val(data['prod_runcard']['assembly_line_id']).trigger('change');
           
            $("#txt_Adrawing_no").val(data['prod_runcard']['a_drawing_no']);
            $("#a_revision").val(data['prod_runcard']['a_revision']);
            $("#txt_Gdrawing_no").val(data['prod_runcard']['g_drawing_no']);
            $("#g_revision").val(data['prod_runcard']['g_revision']);
            
            // $("#txt_Odrawing_no").val(data['prod_runcard']['o_drawing_no']);
            // $("#o_revision").val(data['prod_runcard']['o_revision']);


            $("#txt_created_at").val(data['prod_runcard']['created_at']);

            // alert(data['prod_runcard']['require_oqc_before_emboss']);

            if(data['prod_runcard']['discrepant_qty'] != null){
              if(data['prod_runcard']['discrepant_qty'] < 0){
                $('#txt_discrepant_qty_sign').val("-");
                $('#txt_discrepant_qty').val(data['prod_runcard']['discrepant_qty'] * -1);
              }
              else if(data['prod_runcard']['discrepant_qty'] == 0){
                $('#txt_discrepant_qty_sign').val("0");
                $('#txt_discrepant_qty').val(data['prod_runcard']['discrepant_qty']);
              }
              else{
                $('#txt_discrepant_qty_sign').val("+");
                $('#txt_discrepant_qty').val(data['prod_runcard']['discrepant_qty']);
              }
            }
            else{
              $('#txt_discrepant_qty_sign').val("0");
              $('#txt_discrepant_qty').val("");
            }

            $('#txt_analysis').val(data['prod_runcard']['analysis']);
            $('#txt_recount_ok').val(data['prod_runcard']['recount_ok']);
            $('#txt_recount_ng').val(data['prod_runcard']['recount_ng']);

            if(data['prod_runcard']['supervisor_prod_info'] != null){
              $('#txt_prod_approval').val(data['prod_runcard']['supervisor_prod_info']['name']);
            }
            else{
              $('#txt_prod_approval').val("");
            }

            if(data['prod_runcard']['supervisor_qc_info'] != null){
              $('#txt_qc_approval').val(data['prod_runcard']['supervisor_qc_info']['name']);
            }
            else{
              $('#txt_qc_approval').val("");
            }

            if(data['prod_runcard']['prod_runcard_material_list'].length > 0){
              // $("#btn_edit_material_details_primary").prop('disabled', true);
              // $("#btnSaveSelectedMatSak").prop('disabled', true);

              for(let index = 0; index < data['prod_runcard']['prod_runcard_material_list'].length; index++){
                if(data['prod_runcard']['prod_runcard_material_list'][index]['tbl_wbs'] == 1){
                  arrSelectedMaterial.push(data['prod_runcard']['prod_runcard_material_list'][index]['issuance_id']);
                }
                else if(data['prod_runcard']['prod_runcard_material_list'][index]['tbl_wbs'] == 2){
                  arrSelectedSakidashi.push(data['prod_runcard']['prod_runcard_material_list'][index]['issuance_id']);
                }
                // else if(data['prod_runcard']['prod_runcard_material_list'][index]['tbl_wbs'] == 2 && data['prod_runcard']['prod_runcard_material_list'][index]['for_emboss'] == 1){
                //   arrSelectedEmboss.push(data['prod_runcard']['prod_runcard_material_list'][index]['issuance_id']);
                // }
              }
            }
            else{
              // $("#btn_edit_material_details_primary").prop('disabled', false);
              // $("#btnSaveSelectedMatSak").prop('disabled', false);
            }

            if(data['prod_runcard']['prod_runcard_material_list'].length > 0){
              hasProdMatSakList = true;
              // $("#btn_setup_stations").prop('disabled', false);
                if(data['prod_runcard']['discrepant_qty'] != null) {
                // $("#btn_edit_material_details_verification").prop('disabled', true);
                // $("#btn_setup_stations").prop('disabled', true);
                // $("#btn_approve_prod").prop('disabled', false);
                // $("#btn_approve_qc").prop('disabled', false);

                if(data['prod_runcard']['supervisor_prod_info'] != null){
                  // $("#btn_edit_material_details_verification").prop('disabled', true);
                  // $("#btn_approve_prod").prop('disabled', true);
                  // $("#btn_approve_prod").prop('disabled', true);
                  if(data['prod_runcard']['supervisor_qc_info'] != null){
                    // $("#btn_approve_qc").prop('disabled', true);
                  }
                  else{
                    // $("#btn_approve_qc").prop('disabled', false); 
                  }
                }
                else{
                  // $("#btn_edit_material_details_verification").prop('disabled', false);
                  // $("#btn_approve_prod").prop('disabled', false);
                  // $("#btn_approve_qc").prop('disabled', true);
                }

                if(data['prod_runcard']['discrepant_qty'] == 0){
                  // $("#btn_approve_prod").prop('disabled', true);
                  // $("#btn_approve_qc").prop('disabled', true);
                }
              }
              else{
                // $("#btn_setup_stations").prop('disabled', false);
                // $("#btn_approve_prod").prop('disabled', true);
                // $("#btn_approve_qc").prop('disabled', true);
                // $("#btn_edit_material_details_verification").prop('disabled', false);
              }
            }
            else{
              // $("#btn_setup_stations").prop('disabled', true);
              hasProdMatSakList = true;
            }

            // if(data['prod_runcard']['discrepant_qty'] != null) {
              // $("#btn_edit_material_details_verification").prop('disabled', true);
              // $("#btn_setup_stations").prop('disabled', true);
            //   $("#btn_approve_prod").prop('disabled', false);
            //   $("#btn_approve_qc").prop('disabled', false);

            //   if(data['prod_runcard']['supervisor_prod_info'] != null){
                // $("#btn_edit_material_details_verification").prop('disabled', true);
            //     $("#btn_approve_prod").prop('disabled', true);
            //     $("#btn_approve_prod").prop('disabled', true);
            //     if(data['prod_runcard']['supervisor_qc_info'] != null){
            //       $("#btn_approve_qc").prop('disabled', true);
            //     }
            //     else{
            //       $("#btn_approve_qc").prop('disabled', false); 
            //     }
            //   }
            //   else{
                // $("#btn_edit_material_details_verification").prop('disabled', false);
            //     $("#btn_approve_prod").prop('disabled', false);
            //     $("#btn_approve_qc").prop('disabled', true);
            //   }

            //   if(data['prod_runcard']['discrepant_qty'] == 0){
            //     $("#btn_approve_prod").prop('disabled', true);
            //     $("#btn_approve_qc").prop('disabled', true);
            //   }
            // }
            // else{
              // $("#btn_setup_stations").prop('disabled', false);
            //   $("#btn_approve_prod").prop('disabled', true);
            //   $("#btn_approve_qc").prop('disabled', true);
              // $("#btn_edit_material_details_verification").prop('disabled', false);
            // }

            HandleButtons(true);
            let prodRuncardStat = data['prod_runcard']['status'];
            if(prodRuncardStat == 1){
              $("#btn_edit_material_details_primary").prop('disabled', false);  
              $("#btnSaveSelectedMatSak").prop('disabled', false);
              // $("#btnSaveSelectedEmboss").prop('disabled', true);
              $("#btn_setup_stations").prop('disabled', true);
              $("#btn_edit_material_details_verification").prop('disabled', true);
              $("#btn_approve_prod").prop('disabled', true);
              $("#btn_approve_qc").prop('disabled', true);
            }
            else if(prodRuncardStat == 2){
              $("#btn_edit_material_details_primary").prop('disabled', false);  
              // $("#btnSaveSelectedMatSak").prop('disabled', true);
              $("#btnSaveSelectedMatSak").prop('disabled', false);
              // $("#btnSaveSelectedEmboss").prop('disabled', true);
              $("#btn_setup_stations").prop('disabled', true);
              $("#btn_edit_material_details_verification").prop('disabled', false);
              $("#btn_approve_prod").prop('disabled', true);
              $("#btn_approve_qc").prop('disabled', true);
            }
            else if(prodRuncardStat == 3){
              $("#btn_edit_material_details_primary").prop('disabled', false);  
              $("#btnSaveSelectedMatSak").prop('disabled', false);
              // $("#btnSaveSelectedEmboss").prop('disabled', true);
              $("#btn_setup_stations").prop('disabled', true);
              $("#btn_edit_material_details_verification").prop('disabled', false);
              $("#btn_approve_prod").prop('disabled', true);
              $("#btn_approve_qc").prop('disabled', true);
            }
            else if(prodRuncardStat == 4){
              $("#btn_edit_material_details_primary").prop('disabled', false);  
              $("#btnSaveSelectedMatSak").prop('disabled', true);
              // $("#btnSaveSelectedEmboss").prop('disabled', true);
              $("#btn_setup_stations").prop('disabled', true);
              $("#btn_edit_material_details_verification").prop('disabled', true);
              $("#btn_approve_prod").prop('disabled', false);
              $("#btn_approve_qc").prop('disabled', true);
            }
            else if(prodRuncardStat == 5){
              $("#btn_edit_material_details_primary").prop('disabled', false);  
              $("#btnSaveSelectedMatSak").prop('disabled', true);
              // $("#btnSaveSelectedEmboss").prop('disabled', true);
              $("#btn_setup_stations").prop('disabled', true);
              $("#btn_edit_material_details_verification").prop('disabled', true);
              $("#btn_approve_prod").prop('disabled', true);
              $("#btn_approve_qc").prop('disabled', false);
            }
            else if(prodRuncardStat == 7){
              $("#btn_edit_material_details_primary").prop('disabled', true);  
              // $("#btnSaveSelectedEmboss").prop('disabled', false);
            }
            else if(prodRuncardStat == 8){
              $("#btn_edit_material_details_primary").prop('disabled', true);  
              // $("#btnSaveSelectedEmboss").prop('disabled', true); 
            }
            else{
              HandleButtons(true);
            }

            //-7232021
            if(data['prod_runcard']['status'] == 3){
              $("#btn_edit_material_details_primary").prop('disabled', true);
              $("#btnAddMaterial").prop('disabled', true);
              $("#btnAddProcess").prop('disabled', true);
              $("#btnAddAccessory").prop('disabled', true);
              $("#btnSubmitToOQCLotApp").prop('disabled', true);
            }
            else{
              $("#btn_edit_material_details_primary").prop('disabled', false);
              $("#btnAddMaterial").prop('disabled', false);
              $("#btnAddProcess").prop('disabled', false);
              $("#btnAddAccessory").prop('disabled', false);
              $("#btnSubmitToOQCLotApp").prop('disabled', false);
            }

            // $("#btn_setup_stations").removeAttr('disabled');
            // $("#btn_edit_material_details_verification").removeAttr('disabled');
            // $("#btnSaveSelectedMatSak").removeAttr('disabled');
            // $("#btn_approve_prod").removeAttr('disabled');
            // $("#btn_approve_qc").removeAttr('disabled');
          }
          else{
            $("#txt_po_number").val('');
            $("#txt_po_qty").val('');
            $("#txt_use_for_device").val('');
            $("#txt_device_code").val('');
            // $("#txt_runcard_no").val('');
            $("#txt_lot_no").val('');
            $("#txt_reel_lot_no").val('');
            $("#txt_lot_qty").val('');
            $("#txt_assessment_no").val('');
            $("#txt_a_drawing_no").val('');
            $("#txt_a_drawing_rev").val('');
            $("#txt_g_drawing_no").val('');
            $("#txt_g_drawing_rev").val('');
            $("#txt_other_docs_no").val('');
            $("#txt_other_docs_rev").val('');
            $("#txt_mold").val('');
            $("#txt_ct_supplier").val('');
            $("#txt_die_no").val('');
            $("#txt_pair_no").val('');
            $("#txt_remarks").val('');
            $("#txt_application_datetime").val('');
            $("#sel_assembly_line").val(null).trigger('change');
            $("#a_drawing_no").val(null).trigger('change');
            $("#g_drawing_no").val(null).trigger('change');

            $("#txt_created_at").val('');

            // $("#btn_setup_stations").prop('disabled', 'disabled');
            // $("#btnSaveSelectedMatSak").prop('disabled', true);
            // $("#btn_edit_material_details_verification").prop('disabled', true);
            // $("#btn_setup_stations").prop('disabled', true);
            // $("#btn_approve_prod").prop('disabled', true);
            // $("#btn_approve_qc").prop('disabled', true);
          }

          let noOfSelected = parseInt(arrSelectedMaterial.length) + parseInt(arrSelectedSakidashi.length);
          if(noOfSelected > 0 && hasProdMatSakList){
            $("#spanNoOfSelectedMatSak").text("(" + noOfSelected + ")");
            // $("#btnSaveSelectedMatSak").prop('disabled', true);
          }
          else{
            $("#spanNoOfSelectedMatSak").text("");
            // $("#btnSaveSelectedMatSak").prop('disabled', false);
          }
          dt_materials.draw();
          dt_sakidashi.draw();
          // dt_emboss.draw();
          dt_prod_runcard_stations.draw();
          dtAccessories.draw();
          // console.log(arrSelectedMaterial);
          // console.log(arrSelectedSakidashi);
          check_print_po_if_show()
          check_btnSubmitToOQCLotApp_if_disabled()
          
        }
      });
    }

    function check_print_po_if_show() {
      $('#btnPrintPO_process_station').attr('hidden', true)
      setTimeout(() => {
        if ( dt_prod_runcard_stations.data().count() ) {
          $('#btnPrintPO_process_station').attr('hidden', false)
        }
      }, 3000)
    }

    function check_btnSubmitToOQCLotApp_if_disabled() {
      $('#btnSubmitToOQCLotApp').attr('disabled', true)
      setTimeout(() => {
        if ( dt_prod_runcard_stations.data().count() && dtAccessories.data().count() ) {
          $('#btnSubmitToOQCLotApp').attr('disabled', false)
        }
      }, 3000)
    }

    function SaveProductMaterialList(arrSelectedMaterials, arrSelectedSakidashis, _token, employeeId, prodRuncardId){
      // alert(prodRuncardId);
      // return 'wew';
      $.ajax({
        url : 'save_prod_material_list',
        method : 'post',
        data: {
          _token: _token,
          material_issuance: arrSelectedMaterials,
          sakidashi_issuance: arrSelectedSakidashis,
          employee_id: employeeId,
          prod_runcard_id: prodRuncardId
        },
        dataType : 'json',
        before: function(){
          alert("Loading...");
        },
        success     : function(data){
          $('#mdl_alert #mdl_alert_title').html(data['title']);
          $('#mdl_alert #mdl_alert_body').html(data['body']);
          $('#mdl_alert').modal('show');

          // $("#btnSaveSelectedMatSak").prop('disabled', true);
          // arrSelectedMaterial = arrSelectedMaterials;
          // arrSelectedSakidashi = arrSelectedSakidashis;
          // dt_prod_runcard.draw();
          // dt_materials.draw();
          // dt_sakidashi.draw();

          // alert('Saved!');
          GetProdRuncardById($("#txt_prod_runcard_id_query").val());
        },
        completed     : function(data){
          // alert('Saved!');
          GetProdRuncardById($("#txt_prod_runcard_id_query").val()); 
        },
        error     : function(data){

        },
      });
    }

    // function SaveProductEmbossMaterialList(arrSelectedEmbosses, _token, employeeId, prodRuncardId){
    //   // alert(prodRuncardId);
    //   // return 'wew';
    //   $.ajax({
    //     url : 'save_prod_emboss_material_list',
    //     method : 'post',
    //     data: {
    //       _token: _token,
    //       emboss_issuance: arrSelectedEmbosses,
    //       employee_id: employeeId,
    //       prod_runcard_id: prodRuncardId
    //     },
    //     dataType : 'json',
    //     before: function(){
    //       alert("Loading...");
    //     },
    //     success     : function(data){
    //       $('#mdl_alert #mdl_alert_title').html(data['title']);
    //       $('#mdl_alert #mdl_alert_body').html(data['body']);
    //       $('#mdl_alert').modal('show');

    //       // $("#btnSaveSelectedMatSak").prop('disabled', true);
    //       // arrSelectedMaterial = arrSelectedMaterials;
    //       // arrSelectedSakidashi = arrSelectedSakidashis;
    //       // dt_prod_runcard.draw();
    //       // dt_materials.draw();
    //       // dt_sakidashi.draw();

    //       // alert('Saved!');
    //       GetProdRuncardById($("#txt_prod_runcard_id_query").val());
    //     },
    //     completed     : function(data){
    //       // alert('Saved!');
    //       GetProdRuncardById($("#txt_prod_runcard_id_query").val()); 
    //     },
    //     error     : function(data){

    //     },
    //   });
    // }

    function SaveSakidashiLotIssuanceId(issuanceId, item, itemDesc, operator){

      // console.log("item : " + item + '\n' + 'item desc: ' + itemDesc);
      $.ajax({
        url : 'save_sakidashi_lot_issuance',
        method : 'post',
        data: {
          _token: _token,
          issuance_id: issuanceId,
          item: item,
          item_desc: itemDesc,
          operator: operator,
          prod_runcard_id: $("#txt_prod_runcard_id_query").val(),
          device_name: $("#txt_device_name_lbl").val(),
        },
        dataType : 'json',
        before: function(){
          
        },
        success: function(data){
          
          $("#modalScanPOTransLotCode").modal('hide');

          if(data['final_result'] == 1){
            toastr.success('Material Saved!');
            if(data['runcard_id'] != null){
              viewSakidashiAction = 1;
              viewSakidashiActionLotNo = null;
              $("#txtSearchedSakidashiLot").val('');
              GetProdRuncardById(data['runcard_id']);
            }
          }
          else{
           toastr.error(data['remarks']); 
          }
        },
        completed: function(data){
          GetProdRuncardById($("#txt_prod_runcard_id_query").val()); 
        },
        error: function(data){

        },
      });
    }

    // function SaveEmbossLotIssuanceId(issuanceId, item, itemDesc, operator){

    //   // console.log("item : " + item + '\n' + 'item desc: ' + itemDesc);
    //   $.ajax({
    //     url : 'save_emboss_lot_issuance',
    //     method : 'post',
    //     data: {
    //       _token: _token,
    //       issuance_id: issuanceId,
    //       item: item,
    //       item_desc: itemDesc,
    //       operator: operator,
    //       prod_runcard_id: $("#txt_prod_runcard_id_query").val(),
    //       device_name: $("#txt_device_name_lbl").val(),
    //     },
    //     dataType : 'json',
    //     before: function(){
          
    //     },
    //     success: function(data){
          
    //       $("#modalScanPOTransLotCode").modal('hide');

    //       if(data['final_result'] == 1){
    //         toastr.success('Material Saved!');
    //         if(data['runcard_id'] != null){
    //           viewEmbossAction = 1;
    //           viewEmbossActionLotNo = null;
    //           $("#txtSearchedEmbossLot").val('');
    //           GetProdRuncardById(data['runcard_id']);
    //         }
    //       }
    //       else{
    //        toastr.error(data['remarks']); 
    //       }
    //     },
    //     completed: function(data){
    //       GetProdRuncardById($("#txt_prod_runcard_id_query").val()); 
    //     },
    //     error: function(data){

    //     },
    //   });
    // }

    function save_setup_stations(){
      var arr_substations = [];
      var ctr = 0;
      $('#tbl_setup_stations tbody .ckb_station:checked').each(function(){
        if(!$(this).attr('disabled')){
          // console.log('disabled');
          arr_substations[ctr] = {
              'step' : $(this).closest('td').find('.col_station_step').val(),
              'station' : $(this).closest('td').find('.col_station_id').val(),
              'substation' : $(this).closest('td').find('.col_sub_station_id').val(),
            };
          ctr++;
        }
        // else{
        //   console.log('enabled');
        // }
      });
      if( !jQuery.isEmptyObject(arr_substations) ){
        // console.log(arr_substations);

        if($("#txt_prod_runcard_id_query").val() != ""){
          var data = {
            'txt_prod_runcard_id_query'      : $("#txt_prod_runcard_id_query").val(),
            'txt_employee_number_scanner'   : $("#txt_employee_number_scanner").val(),
            '_token'                        : '{{ csrf_token() }}',
            'arr_substations'               : arr_substations,
          }
          $.ajax({
            'data'      : data,
            'type'      : 'post',
            'dataType'  : 'json',
            'url'       : 'insert_prod_runcard_setup_stations',
            success     : function(data){
                $('#mdl_setup_stations').modal('hide');
                $('#mdl_alert #mdl_alert_title').html(data['title']);
                $('#mdl_alert #mdl_alert_body').html(data['body']);
                $('#mdl_alert').modal('show');
                
                if(data['result'] != '0'){
                  dt_prod_runcard_stations.draw();
                }
                GetProdRuncardById($("#txt_prod_runcard_id_query").val());
            },
            completed     : function(data){

            },
            error     : function(data){

            },
          });
        }
        else{
          toastr.error('Runcard No not found!', 'Set-up Stations');
        }
      }
      else{
        $('#mdl_alert #mdl_alert_title').html('<i class="fa fa-exclamation-triangle text-warning"></i> Message');
        $('#mdl_alert #mdl_alert_body').html('Nothing to save.');
        $('#mdl_alert').modal('show');
      }
    }

    function fn_select_prod_runcard_station_details(data_arr, matProcId){
      // reset_material_details_primary();
      // reset_material_details_secondary();
      var data = {
        'col_prod_runcard_station_id' : data_arr['col_prod_runcard_station_id'],
        // 'has_emboss' : data_arr['has_emboss'],
        '_token'                   : '{{ csrf_token() }}',
        'device_code': $("#txt_device_code").val(),
        'material_process_id': matProcId
      }
      $.ajax({
        'data'      : data,
        'type'      : 'get',
        'dataType'  : 'json',
        'url'       : 'select_prod_runcard_station_details',
        beforeSend: function(){
          $("#txt_edit_prod_runcard_cert_operator").val(0).trigger('change');
          $("#txt_edit_prod_runcard_cert_operator_visible").val(0).trigger('change');
          $("#txt_edit_prod_runcard_station_assigned_machine").val(0).trigger('change');
          $("#txt_edit_prod_runcard_station_assigned_machine_visible").val(0).trigger('change');
          $("#txt_edit_prod_runcard_station_materials").val("");
          $("#txt_fvi_remarks").val("");

          $('#sel_edit_prod_runcard_ct_area').val(0).trigger('change');
            $('#sel_edit_prod_runcard_terminal_area').val(0).trigger('change');
        },
        success     : function(jsonObject){
          // alert(data[0]['station']['name'])
          let data = jsonObject['prod_runcard_stations'];
          if ($.trim(data)){
            $('#mdl_edit_prod_runcard_station_details').modal('show');
            $('#txt_prod_runcard_station_id_query').val(data['id']);
            $('#txt_fvi_remarks').val(data['remarks']);
            // $('#txt_edit_prod_runcard_station_step').val(data['step_num']);
            // $('#txt_edit_prod_runcard_station_has_emboss').val(data['has_emboss']);
            // $('#txt_edit_prod_runcard_station_station').val(data['station']['name']);
            // $('#txt_edit_prod_runcard_substation').val(data['sub_station']['name']);
            // $('#txt_edit_prod_runcard_substation').val(data['sub_station_id']).trigger('change');
            $('#sel_edit_prod_runcard_ct_area').val(data['ct_area']).trigger('change');
            $('#sel_edit_prod_runcard_terminal_area').val(data['terminal_area']).trigger('change');

            $('#txt_edit_prod_runcard_station_date').val( getdate( data['created_at']?data['created_at']:getcurrentdate() ) );

            if(data['qty_input'] != null){
              $('#txt_edit_prod_runcard_station_input').val(data['qty_input']);
            }
            else{
              // $('#txt_edit_prod_runcard_station_input').val($("#txt_lot_qty").val());
            }

            // if(data['machine_id'] != null){
            //   $('#txt_edit_prod_runcard_station_machine').val(data['machine_id']).trigger('change');
            // }
            // else{
            //   $('#txt_edit_prod_runcard_station_machine').val("-1").trigger('change');
            // }
            if(data['qty_output'] != null){
              $('#txt_edit_prod_runcard_station_output').val(data['qty_output']);
            }
            if(data['qty_ng'] != null){
              $('#txt_edit_prod_runcard_station_ng').val(data['qty_ng']);
            }
            // $('#txt_edit_prod_runcard_station_output').val(data['qty_output']);
            // $('#txt_edit_prod_runcard_station_ng').val(data['qty_ng']);
            // $('#txt_edit_prod_runcard_station_mod').val(data['mod']);

            // if(data['operator'] != null){
            //   let operators = data['operator'].split(',');
            //   $('#txt_edit_prod_runcard_operator').val(operators).trigger('change');
            //   // console.log(operators);
            // }
            // else{
            //   $('#txt_edit_prod_runcard_operator').val("0").trigger('change');
            // }

            $("#txt_edit_prod_runcard_cert_operator").val(0).trigger('change');
            $("#txt_edit_prod_runcard_station_assigned_machine").val(0).trigger('change');
            $("#txt_edit_prod_runcard_station_assigned_machine_visible").val(0).trigger('change');

            let operatorsId = [];
            if(data['prod_runcard_station_operator_details'].length > 0){
              for(let index = 0; index < data['prod_runcard_station_operator_details'].length; index++){
                operatorsId.push(data['prod_runcard_station_operator_details'][index].operator_id);
              }
              $("#txt_edit_prod_runcard_operator").val(operatorsId).trigger('change');
              $("#txt_edit_prod_runcard_cert_operator").val(operatorsId).trigger('change');
              console.log(operatorsId);
            }

            let machineId = [];
            if(data['prod_runcard_station_machine_details'].length > 0){
              for(let index = 0; index < data['prod_runcard_station_machine_details'].length; index++){
                machineId.push(data['prod_runcard_station_machine_details'][index].machine_id);
              }
              $("#txt_edit_prod_runcard_station_machine").val(machineId).trigger('change');
              $("#txt_edit_prod_runcard_station_assigned_machine").val(machineId).trigger('change');
              console.log(machineId);
            }

            // $("#txt_edit_prod_runcard_station_materials").val(data['material_process']['material']);

            if($("#txt_edit_prod_runcard_station_ng").val() == 0 || $("#txt_edit_prod_runcard_station_ng").val() == ""){
              $("#btnAddMODTable").prop('disabled', true);
            }
            else{
              $("#btnAddMODTable").prop('disabled', false);
            }

            if(data['prod_runcard_station_mod_details'].length > 0){
              for(let index = 0; index < data['prod_runcard_station_mod_details'].length; index++){
                let appendHTML = '<tr>';
                appendHTML += '<td>';
                  appendHTML += '<select class="form-control select2 select2bs4 selectMOD" name="mod[]">';
                    appendHTML += '<option value="">N/A</option>';
                  appendHTML += '</select>';
                appendHTML += '</td>';
                appendHTML += '<td>';
                  appendHTML += '<input type="number" class="form-control txtEditProdRunStaMODQty" name="mod_qty[]" value="' + data['prod_runcard_station_mod_details'][index]['mod_qty'] + '" min="1">';
                appendHTML += '</td>';
                // appendHTML += '<td>';
                // appendHTML += '<select class="form-control" name="type_of_ng[]">';
                //     if(data['prod_runcard_station_mod_details'][index]['type_of_ng'] == 1){
                //       appendHTML += '<option value="1" selected="true">MAT\'L NG.</option>';
                //       appendHTML += '<option value="2">PROD\'N NG.</option>';
                //     }
                //     else{
                //       appendHTML += '<option value="1">MAT\'L NG.</option>';
                //       appendHTML += '<option value="2" selected="true">PROD\'N NG.</option>'; 
                //     }
                //   appendHTML += '</select>';
                appendHTML += '<td>';
                  appendHTML += '<center><button class="btn btn-xs btn-danger btnRemoveMOD" title="Remove" type="button"><i class="fa fa-times"></i></button></center>';
                appendHTML += '</td>';
                appendHTML += '</tr>'

                $("#tblEditProdRunStaMOD tbody").append(appendHTML);
                $('.select2bs4').select2({
                  theme: 'bootstrap4'
                });

                GetCboMOD2($("#tblEditProdRunStaMOD tr:last").find('.selectMOD'), data['prod_runcard_station_mod_details'][index]['mod_id']);
                // $("#tblEditProdRunStaMOD tr:last").find('.selectMOD').val(data['prod_runcard_station_mod_details'][index]['mod_id']).trigger('change');

                let totalNoOfMOD = 0;

                $('#tblEditProdRunStaMOD .txtEditProdRunStaMODQty').each(function(i, obj) {
                  if($(this).val() == null || $(this).val() == ""){
                  }
                  else{
                    totalNoOfMOD += parseFloat($(this).val());
                  }
                });
                if($("#txt_edit_prod_runcard_station_ng").val() != totalNoOfMOD){
                  toastr.warning('MOD NG Qty not Tally!');
                  $("#pRCStatTotNoOfNG").css({color: 'red'});
                  $("#btn_save_prod_runcard_station_stations").prop('disabled', true);
                  $("#pRCStatTotNoOfNG").css({color: 'red'});
                }
                else{
                  $("#pRCStatTotNoOfNG").css({color: 'green'});
                  $("#btn_save_prod_runcard_station_stations").prop('disabled', false);
                  $("#pRCStatTotNoOfNG").css({color: 'green'});
                }
                $("#pRCStatTotNoOfNG").text(totalNoOfMOD);
              }
            }

            // Get assigned materials (kitting, sakidashi, emboss)

            let arrMaterialKittingData = [];
            let arrSakidashiIssuanceData = [];
            // let arrEmbossData = [];

            // if(jsonObject['material_process'] != null){
            //   for(let index = 0; index < jsonObject['material_process']['material_details'].length; index++){
            //     if(jsonObject['material_process']['material_details'][index].tbl_wbs == 1){
            //       arrMaterialKittingData.push(jsonObject['material_process']['material_details'][index].item + '--' + jsonObject['material_process']['material_details'][index].item_desc);
            //     }
            //     else if(jsonObject['material_process']['material_details'][index].tbl_wbs == 2){
            //       arrSakidashiIssuanceData.push(jsonObject['material_process']['material_details'][index].item + '--' + jsonObject['material_process']['material_details'][index].item_desc); 
            //     }
            //     // else if(jsonObject['material_process']['material_details'][index].tbl_wbs == 2 && jsonObject['material_process']['material_details'][index].has_emboss == 1){
            //     //   arrEmbossData.push(jsonObject['material_process']['material_details'][index].item + '--' + jsonObject['material_process']['material_details'][index].item_desc);  
            //     // }
            //   }
            // }

            // $("#txt_edit_prod_runcard_station_material_kitting").val(0).trigger('change');
            // $("#txt_edit_prod_runcard_station_sakidashi").val(0).trigger('change');
            // // $("#txt_edit_prod_runcard_station_emboss").val(0).trigger('change');

            // $("#txt_edit_prod_runcard_station_assigned_material_kitting").val(arrMaterialKittingData).trigger('change');
            // $("#txt_edit_prod_runcard_station_assigned_material_kitting_visible").val(arrMaterialKittingData).trigger('change');
            // $("#txt_edit_prod_runcard_station_assigned_sakidashi").val(arrSakidashiIssuanceData).trigger('change');
            // $("#txt_edit_prod_runcard_station_assigned_sakidashi_visible").val(arrSakidashiIssuanceData).trigger('change');
            // $("#txt_edit_prod_runcard_station_assigned_emboss").val(arrEmbossData).trigger('change');
            // $("#txt_edit_prod_runcard_station_assigned_emboss_visible").val(arrEmbossData).trigger('change');

            // List the MOD Here

            // $("#txt_edit_prod_runcard_station_assigned_machine").val(jsonObject['material_process']['machine_id']).trigger('change');
          }
          else{
            toastr.error('Error loading data!');
          }

          // if($('#txt_prod_runcard_station_id_query').val() == ""){
          //   $('#txt_edit_prod_runcard_station_input').val($("#txt_lot_qty").val());
          // }

          // $('#mdl_edit_prod_runcard_station_details').modal('show');
        },
        completed     : function(data){

        },
        error     : function(data){

        },
      });

    }

    // SCAN MATERIAL LOT # CODE
    function ScanProdRuncardMaterialKiting(materialLotNo){
      $.ajax({
        url: 'scan_material_kitting_lot_no',
        method: 'get',
        dataType: 'json',
        data: {
          _token: "{{ csrf_token() }}",
          po_number: currentPoNo,
          lot_no: materialLotNo,
          runcard_id: $("#txt_prod_runcard_id_query").val(),
          device_name: $("#txt_device_name_lbl").val(),
          lot_qty: $("#txt_lot_qty").val(),
          a_drawing: $("#txt_a_drawing_no").val(),
          a_drawing_rev: $("#txt_a_drawing_rev").val(),
          g_drawing: $("#txt_g_drawing_no").val(),
          g_drawing_rev: $("#txt_g_drawing_rev").val(),
          other_docs_no: $("#txt_other_docs_no").val(),
          other_docs_rev: $("#txt_other_docs_rev").val(),
          mold: $("#txt_mold").val(),
          ct_supplier: $("#txt_ct_supplier").val(),
          die_no: $("#txt_die_no").val(),
          pair_no: $("#txt_pair_no").val(),
          remarks: $("#txt_remarks").val(),
          assembly_line_id: $("#sel_assembly_line").val(),
          a_drawing_no: $("#a_drawing_no").val(),
          a_revision: $("#a_revision").val(),
          g_drawing_no: $("#g_drawing_no").val(),
          g_revision: $("#g_revision").val(),
          o_drawing_no: $("#o_drawing_no").val(),
          o_revision: $("#o_revision").val(),
          employee_number_scanner: $("#txt_employee_number_scanner").val(),
          // has_emboss: $("#txt_prod_runcard_has_emboss").val(),
          // whs_slip_no: $("#txtScannedWHSSlipNo").val(),
        },
        beforeSend: function(){
          $("#scanPOTransLotIcon").removeClass('fa-qrcode');
          $("#scanPOTransLotIcon").addClass('fa-spinner fa-pulse');
        },
        success: function(data){
          // $("#modal")

          $("#scanPOTransLotIcon").addClass('fa-qrcode');
          $("#scanPOTransLotIcon").removeClass('fa-spinner fa-pulse');
          $("#txtSearchPoTransLotNo").val('');
          $("#txtSearchPoTransLotNo").focus();

          if(data['final_result'] == 1){
            if(data['prep_status']){
              if(data['runcard_material_status']){
                if(data['material_certified']){
                    dt_prod_runcard.draw();
                    toastr.success('Material Saved!');
                    if(data['runcard_id'] != null){
                      GetProdRuncardById(data['runcard_id']);
                    }
                }
                else{
                  toastr.error(data['material_certified_label']);
                }
              }
              else{
                toastr.error(data['runcard_material_status_label']);
              }
            }
            else{
              toastr.error(data['prep_status_label']);
            }
          }
          else{
            // toastr.error('Saving Failed!');
            if(!data['prep_status']){
              toastr.error(data['prep_status_label']);
            }
            else{
              if(!data['runcard_material_status']){
                toastr.error(data['runcard_material_status_label']);
              }
              else{
                if(!data['material_certified']){
                  toastr.error(data['material_certified_label']);
                }
              }
            }
          }
        },
        error: function(data, xhr, status){
          $("#scanPOTransLotIcon").addClass('fa-qrcode');
          $("#scanPOTransLotIcon").removeClass('fa-spinner fa-pulse');
          $("#txtSearchPoTransLotNo").val('');
          $("#txtSearchPoTransLotNo").focus();
        }
      });
    }

    // SCAN SAKIDASHI ISSUANCE LOT # CODE
    function ScanProdRuncardSakidashiIssuance(sakidashiLotNo){
      $.ajax({
        url: 'scan_sakidashi_issuance_lot_no',
        method: 'get',
        dataType: 'json',
        data: {
          _token: "{{ csrf_token() }}",
          po_number: currentPoNo,
          lot_no: sakidashiLotNo,
          runcard_id: $("#txt_prod_runcard_id_query").val(),
          device_name: $("#txt_device_name_lbl").val(),
        },
        beforeSend: function(){
          $("#scanPOTransLotIcon").removeClass('fa-qrcode');
          $("#scanPOTransLotIcon").addClass('fa-spinner fa-pulse');
        },
        success: function(data){
          $("#scanPOTransLotIcon").addClass('fa-qrcode');
          $("#scanPOTransLotIcon").removeClass('fa-spinner fa-pulse');
          $("#txtSearchPoTransLotNo").val('');
          $("#txtSearchPoTransLotNo").focus();
          // if(data['final_result'] == 1){
          //   dt_prod_runcard.draw();
          //   toastr.success('Material Saved!');
          //   if(data['runcard_id'] != null){
          //     GetProdRuncardById(data['runcard_id']);
          //   }
          // }
          // else{
          //   toastr.error('Saving Failed!');
          // }

          if(data['final_result'] == 2){
            toastr.error(data['remarks']);
          }
          else if(data['final_result'] == 1){
            if(data['prep_status']){
              if(data['runcard_material_status']){
                if(data['material_certified']){
                    dt_prod_runcard.draw();
                    toastr.success('Material Saved!');
                    if(data['runcard_id'] != null){
                      GetProdRuncardById(data['runcard_id']);
                    }
                }
                else{
                  toastr.error(data['material_certified_label']);
                }
              }
              else{
                toastr.error(data['runcard_material_status_label']);
              }
            }
            else{
              toastr.error(data['prep_status_label']);
            }
          }
          else{
            // toastr.error('Saving Failed!');
            if(!data['prep_status']){
              toastr.error(data['prep_status_label']);
            }
            else{
              if(!data['runcard_material_status']){
                toastr.error(data['runcard_material_status_label']);
              }
              else{
                if(!data['material_certified']){
                  toastr.error(data['material_certified_label']);
                }
              }
            }
          }
        },
        error: function(data, xhr, status){
          $("#scanPOTransLotIcon").addClass('fa-qrcode');
          $("#scanPOTransLotIcon").removeClass('fa-spinner fa-pulse');
          $("#txtSearchPoTransLotNo").val('');
          $("#txtSearchPoTransLotNo").focus();
        }
      });
    }

    // SCAN EMBOSS LOT # CODE
    // function ScanProdRuncardEmboss(embossLotNo){
    //   $.ajax({
    //     url: 'scan_emboss_lot_no',
    //     method: 'get',
    //     dataType: 'json',
    //     data: {
    //       _token: "{{ csrf_token() }}",
    //       po_number: currentPoNo,
    //       lot_no: embossLotNo,
    //       runcard_id: $("#txt_prod_runcard_id_query").val(),
    //       device_name: $("#txt_device_name_lbl").val(),
    //     },
    //     beforeSend: function(){
    //       $("#scanPOTransLotIcon").removeClass('fa-qrcode');
    //       $("#scanPOTransLotIcon").addClass('fa-spinner fa-pulse');
    //     },
    //     success: function(data){
    //       // if(data['final_result'] == 1){
    //       //   dt_prod_runcard.draw();
    //       //   toastr.success('Material Saved!');
    //       //   if(data['runcard_id'] != null){
    //       //     GetProdRuncardById(data['runcard_id']);
    //       //   }
    //       // }
    //       // else{
    //       //   toastr.error('Saving Failed!');
    //       // }

    //       $("#scanPOTransLotIcon").addClass('fa-qrcode');
    //       $("#scanPOTransLotIcon").removeClass('fa-spinner fa-pulse');
    //       $("#txtSearchPoTransLotNo").val('');
    //       $("#txtSearchPoTransLotNo").focus();

    //       if(data['final_result'] == 2){
    //         toastr.error(data['remarks']);
    //       }
    //       else if(data['final_result'] == 1){
    //         if(data['prep_status']){
    //           if(data['runcard_material_status']){
    //             if(data['material_certified']){
    //                 dt_prod_runcard.draw();
    //                 toastr.success('Material Saved!');
    //                 if(data['runcard_id'] != null){
    //                   GetProdRuncardById(data['runcard_id']);
    //                 }
    //             }
    //             else{
    //               toastr.warning(data['material_certified_label']);
    //             }
    //           }
    //           else{
    //             toastr.warning(data['runcard_material_status_label']);
    //           }
    //         }
    //         else{
    //           toastr.warning(data['prep_status_label']);
    //         }
    //       }
    //       else{
    //         // toastr.error('Saving Failed!');
    //         if(!data['prep_status']){
    //           toastr.error(data['prep_status_label']);
    //         }
    //         else{
    //           if(!data['runcard_material_status']){
    //             toastr.error(data['runcard_material_status_label']);
    //           }
    //           else{
    //             if(!data['material_certified']){
    //               toastr.error(data['material_certified_label']);
    //             }
    //           }
    //         }
    //       }
    //     },
    //     error: function(data, xhr, status){
    //       $("#scanPOTransLotIcon").addClass('fa-qrcode');
    //       $("#scanPOTransLotIcon").removeClass('fa-spinner fa-pulse');
    //       $("#txtSearchPoTransLotNo").val('');
    //       $("#txtSearchPoTransLotNo").focus();
    //     }
    //   });
    // }

    // SCAN EMPLOYEE # CODE
    function ScanProdRuncardEmployee(employeeNo){
      $.ajax({
        url: 'scan_employee_no',
        method: 'get',
        dataType: 'json',
        data: {
          _token: "{{ csrf_token() }}",
          po_number: currentPoNo,
          employee_no: employeeNo,
          runcard_id: $("#txt_prod_runcard_id_query").val(),
          device_name: $("#txt_device_name_lbl").val(),
        },
        beforeSend: function(){
          $("#scanPOTransLotIcon").removeClass('fa-qrcode');
          $("#scanPOTransLotIcon").addClass('fa-spinner fa-pulse');
        },
        success: function(data){
          $("#scanPOTransLotIcon").addClass('fa-qrcode');
          $("#scanPOTransLotIcon").removeClass('fa-spinner fa-pulse');
          $("#txtSearchPoTransLotNo").val('');
          $("#txtSearchPoTransLotNo").focus();
          
          if(data['final_result'] == 1){
            if(data['user_status']){
              if(data['certified_status']){
                dt_prod_runcard.draw();
                toastr.success('Operator Saved!');
                if(data['runcard_id'] != null){
                  GetProdRuncardById(data['runcard_id']);
                }
              }
              else{
                toastr.warning(data['certified_status_label']);
              }
            }
            else{
              toastr.warning(data['user_status_label']);
            }
          }
          else{
            // toastr.error('Saving Failed!');
            if(!data['user_status']){
              toastr.error(data['user_status_label']);
            }
            else{
              if(!data['certified_status']){
                toastr.error(data['certified_status_label']);
              }
              else{
                toastr.success('Operator Saved!');
              }
            }
          }
        },
      });
    }

    function reset_material_details_primary(){
      $('#txt_lot_qty').val('');
      $('#txt_assessment_no').val('');
      // $('#txt_runcard_no').val('');
      $('#txt_lot_no').val('');
      $('#txt_reel_lot_no').val('');
      $('#txt_a_drawing_no').val('');
      $('#txt_a_drawing_rev').val('');
      $('#txt_g_drawing_no').val('');
      $('#txt_g_drawing_rev').val('');
      $('#txt_other_docs_no').val('');
      $('#txt_other_docs_rev').val('');
      $('#txt_mold').val('');
      $('#txt_ct_supplier').val('');
      $('#txt_die_no').val('');
      $('#txt_pair_no').val('');
      $('#sel_assembly_line').val(null).trigger('change');     
      $('#a_drawing_no').val(null).trigger('change');
      $('#g_drawing_no').val(null).trigger('change');
      $('#other_drawing').val(null).trigger('change');

      $('#txt_remarks').val('');
      $('#txt_application_datetime').val('');
      $('#txt_created_at').val('');


      $("#txt_po_number").removeClass('is-invalid');
      $("#txt_lot_qty").removeClass('is-invalid');
      $("#setup_qualification").removeClass('is-invalid');
      $("#txt_prod_runcard_id_query").removeClass('is-invalid');
      $("#txt_pair_no").removeClass('is-invalid');

      $('#txt_material_details_emp_num').val('');
      $('#txt_prod_runcard_id_query').val('');
      $('#txt_prod_runcard_status').val('');
      // $('#txt_prod_runcard_require_oqc_before_emboss').val('');
      $('#txt_prod_runcard_verification_id_query').val('');
      $('#txt_prod_runcard_verification_id_query').val('');
      $('#txt_wbs_kit_issuance_id_query').val('');
      $('#txt_wbs_kit_issuance_device_code_query').val('');

      readonly_material_details_primary(true);
    }

    function readonly_material_details_primary(v){
      $('#txt_assessment_no').prop('readonly',v);
      // $('#txt_lot_qty').prop('readonly',v);
      // $('#txt_runcard_no').prop('readonly',v);
      // $('#txt_assessed_qty').prop('readonly',v);
     
      // $('#txt_a_drawing_no').prop('readonly',v);
      // $('#txt_a_drawing_rev').prop('readonly',v);
      // $('#txt_g_drawing_no').prop('readonly',v);
      // $('#txt_g_drawing_rev').prop('readonly',v);
      $('#txt_other_docs_no').prop('readonly',v);
      $('#txt_other_docs_rev').prop('readonly',v);
      $('#txt_mold').prop('readonly',v);
      $('#txt_ct_supplier').prop('readonly',v);
      $('#txt_die_no').prop('readonly',v);
      $('#txt_pair_no').prop('readonly',v);
      $('#txt_remarks').prop('readonly',v);
      $('#sel_assembly_line').prop('disabled',v);
      $('#a_drawing_no').prop('disabled',v);
      $('#g_drawing_no').prop('disabled',v);     
      $('#o_drawing_no').prop('disabled',v);     


      // $('select[name="for_qualification"]', $("#frm_edit_material_details")).prop('disabled', v);

      $('select[name="setup_qualification"]', $("#frm_edit_material_details")).prop('disabled', v);
      // $('input[name="eng_qualification_name"]', $("#frm_edit_material_details")).val('');
      $('select[name="setup_qualified"]', $("#frm_edit_material_details")).prop('disabled', v);
      $('.btnSelEngQual', $("#frm_edit_material_details")).prop('disabled', v);
      $('.btnClearEngQual', $("#frm_edit_material_details")).prop('disabled', v);

      $('select[name="qc_qualification"]', $("#frm_edit_material_details")).prop('disabled', v);
      // $('input[name="qc_stamp_name"]', $("#frm_edit_material_details")).val('');
      $('select[name="qc_qualified"]', $("#frm_edit_material_details")).prop('disabled', v);
      $('.btnSelQCStamp', $("#frm_edit_material_details")).prop('disabled', v);
      $('.btnClearQCStamp', $("#frm_edit_material_details")).prop('disabled', v);

      $('textarea[name="qc_remarks"]', $("#frm_edit_material_details")).prop('readonly', v);

      $('#btn_save_material_details_primary').closest('.row').hide();
      if(!v){
        $('#btn_save_material_details_primary').closest('.row').show();
      }
    }

    function reset_material_details_secondary(){
      $('#txt_discrepant_qty_sign').val('0');
      $('#txt_discrepant_qty').val('');
      $('#txt_analysis').val('');
      $('#txt_recount_ok').val('');
      $('#txt_recount_ng').val('');
      $('#txt_prod_approval').val('');
      $('#txt_qc_approval').val('');
      $("#sel_comp_under_runcard_no").val("").trigger('change');
    }

    function readonly_material_details_secondary(v){
      $('#txt_discrepant_qty_sign').prop('disabled',v);
      $('#txt_discrepant_qty').prop('readonly',v);
      $('#txt_analysis').prop('readonly',v);
      $('#txt_recount_ok').prop('readonly',v);
      $('#txt_recount_ng').prop('readonly',v);
      $('#sel_comp_under_runcard_no').prop('disabled',v);

      $('#btn_save_material_details_secondary').closest('.row').hide();
      if(!v){
        $('#btn_save_material_details_secondary').closest('.row').show();
      }
    }

    function getdate(datetime){
      if(!datetime){
        return '';
      }
      var d = new Date(datetime);
      // var d = new Date();

      var month = d.getMonth()+1;
      var day = d.getDate();

      var output = d.getFullYear() + '-' +
          (month<10 ? '0' : '') + month + '-' +
          (day<10 ? '0' : '') + day;

      // alert(d+'xxxxxx'+output);
      return output;
    }

    function getcurrentdate(){
      var d = new Date();
      return d;

      // var month = d.getMonth()+1;
      // var day = d.getDate();

      // var output = d.getFullYear() + '-' +
      //     (month<10 ? '0' : '') + month + '-' +
      //     (day<10 ? '0' : '') + day;
      // return output;
    }

    function CheckMaterialLotNo(lotNo){
      $.ajax({
        url: 'check_material_no',
        method: 'get',
        dataType: 'json',
        data: {
          _token: "{{ csrf_token() }}",
          po_number: currentPoNo,
          lot_no: lotNo,
          runcard_id: $("#txt_prod_runcard_id_query").val(),
          device_name: $("#txt_device_name_lbl").val(),
        },
        beforeSend: function(){
          // $("#scanPOTransLotIcon").removeClass('fa-qrcode');
          // $("#scanPOTransLotIcon").addClass('fa-spinner fa-pulse');
        },
        success: function(data){
          // $("#scanPOTransLotIcon").addClass('fa-qrcode');
          // $("#scanPOTransLotIcon").removeClass('fa-spinner fa-pulse');
          // $("#txtSearchPoTransLotNo").focus();
          
          if(data['data'].length > 0){
            // toastr.success('Valid Lot No.!');
            // if(data['result'] == 2){
              toastr.success('Valid Material');
              $("input[name='type']", $("#frmSaveMaterial")).val(data['type']);
              if(data['type'] == 1){
                $("input[name='lot_no']", $("#frmSaveMaterial")).val(data['data'][0]['lot_no']);
                $("input[name='text_type']", $("#frmSaveMaterial")).val('Material Issuance');
              }
              else{
                $("input[name='lot_no']", $("#frmSaveMaterial")).val(data['data'][0]['tbl_wbs_sakidashi_issuance_item']['lot_no']);
                $("input[name='text_type']", $("#frmSaveMaterial")).val('Sakidashi');
              }
              
            // }
            // else if(data['result'] == 4){
            //   toastr.warning('Material not yet received!');
            // }
          }
          else{
            $("input[name='lot_no']", $("#frmSaveMaterial")).val('');
            toastr.error('Invalid Material!');
          }
        },
      });

    }

    function DeleteRuncardStation(employeeNo, runcardStationId){
      $.ajax({
        url: 'delete_runcard_station',
        method: 'post',
        dataType: 'json',
        data: {
          _token: "{{ csrf_token() }}",
          po_number: currentPoNo,
          employee_no: employeeNo,
          runcard_station_id: runcardStationId,
        },
        beforeSend: function(){
          // $("#scanPOTransLotIcon").removeClass('fa-qrcode');
          // $("#scanPOTransLotIcon").addClass('fa-spinner fa-pulse');
        },
        success: function(data){          
          if(data['result'] == 1){
              toastr.success('Process Deleted!');
              dt_prod_runcard_stations.draw();
          }
          else if(data['result'] == 2){
            toastr.warning('Invalid Operator!');
          }
          else{
            // $("input[name='lot_no']", $("#frmSaveMaterial")).val('');
            toastr.error('Deletion Failed!');
          }

          check_print_po_if_show()
          check_btnSubmitToOQCLotApp_if_disabled()

        },
      });

    }
    //---------------------

    //-Nessa
    $("#btnPrintPO_process_station").click(function(){
        if($("#txt_po_number_lbl").val() == "" || $("#txt_po_number_lbl").val() == null || $("#txt_device_name_lbl").val() == "" || $("#txt_device_name_lbl").val() == null){
          toastr.warning('PO & Device Name not found!');
        }else{
          $("#modal_PO_QRcode").modal('show');
          img_barcode_PO      = $('#img_barcode_PO').attr('src');    
          lbl_PO              = $('#txt_po_number_lbl').val();    
          // lbl_device_name     = $('#txt_device_name_lbl').val();   
          lbl_device_name     = $('#lbl_device_name').attr('device_name_print');

          // let device_name = $('#lbl_device_name').attr('device_name_print');


          $('#lbl_PO').text(lbl_PO);
          $('#lbl_device_name').text(lbl_device_name);
        }
    });

    $("#btn_print_drawing").click(function(){
       
          $("#modal_Drawing_QRcode").modal('show');

            if ($('#txt_Adrawing').val() == '' || $('#txt_Adrawing').val() == null){

              $('#lbl_PO_adrawing').text('');
              $('#lbl_device_name_adrawing').text('');
              $('#lbl_adrawing_no').text('');
              lbl_PO_adrawing = '';
              lbl_device_name_adrawing = '';
              lbl_adrawing = '';
              lbl_adrawing_no = 'N/A';
              img_A_drawing = '';
              $('#img_A_drawing').attr('src', '');

            }else{

              lbl_PO_adrawing            = $('#txt_po_number_lbl').val();    
              lbl_device_name_adrawing   = $('#lbl_device_name').attr('device_name_print');
              lbl_adrawing_no            = $('#txt_Adrawing').val();  
              lbl_a_revision             = $('#txt_Adrawing_rev').val();  
              lbl_adrawing               = lbl_adrawing_no+" "+lbl_a_revision;

              $('#lbl_PO_adrawing').text(lbl_PO_adrawing);
              $('#lbl_device_name_adrawing').text(lbl_device_name_adrawing);
              $('#lbl_adrawing_no').text(lbl_adrawing);

            }  

            if ($('#txt_Gdrawing').val() == '' || $('#txt_Gdrawing').val() == null){

              $('#lbl_PO_gdrawing').text('');
              $('#lbl_device_name_gdrawing').text('');
              $('#lbl_gdrawing_no').text('');
              lbl_PO_gdrawing = '';
              lbl_device_name_gdrawing = '';
              lbl_gdrawing = '';
              lbl_gdrawing_no = 'N/A';
              img_G_drawing = '';
              $('#img_G_drawing').attr('src', '');

            }else{

              lbl_PO_gdrawing            = $('#txt_po_number_lbl').val();    
              lbl_device_name_gdrawing   = $('#lbl_device_name').attr('device_name_print');
              lbl_gdrawing_no            = $('#txt_Gdrawing').val();  
              lbl_g_revision             = $('#txt_Gdrawing_rev').val();  
              lbl_gdrawing               = lbl_gdrawing_no+" "+lbl_g_revision;

              $('#lbl_PO_gdrawing').text(lbl_PO_gdrawing);
              $('#lbl_device_name_gdrawing').text(lbl_device_name_gdrawing);
              $('#lbl_gdrawing_no').text(lbl_gdrawing);

            }  

            if ($('#txt_Odrawing').val() == '' || $('#txt_Odrawing').val() == null){

              $('#lbl_PO_odrawing').text('');
              $('#lbl_device_name_odrawing').text('');
              $('#lbl_odrawing_no').text('');
              lbl_PO_odrawing = '';
              lbl_device_name_odrawing = '';
              lbl_odrawing = '';
              lbl_odrawing_no = 'N/A';
              img_O_drawing = '';
              $('#img_O_drawing').attr('src', '');

            }else{

              lbl_PO_odrawing            = $('#txt_po_number_lbl').val();    
              lbl_device_name_odrawing   = $('#lbl_device_name').attr('device_name_print');
              lbl_odrawing_no            = $('#txt_Odrawing').val();  
              lbl_o_revision             = $('#txt_Odrawing_rev').val();  
              lbl_odrawing               = lbl_odrawing_no+" "+lbl_o_revision;

              $('#lbl_PO_odrawing').text(lbl_PO_odrawing);
              $('#lbl_device_name_odrawing').text(lbl_device_name_odrawing);
              $('#lbl_odrawing_no').text(lbl_odrawing);

            }     

          //-Qr code
            $.ajax({
              url: "get_drawingno_qr",
              method: 'get',
              dataType: 'json',
              data: {
                a_drawingno: lbl_adrawing_no,
                g_drawingno: lbl_gdrawing_no,
                o_drawingno: lbl_odrawing_no
              },
              beforeSend: function(){
              },
              success: function(data){
                console.log(data)

                if(lbl_odrawing_no == 'N/A')
                {
                  $('.drawing-odrawing').addClass('d-none');
                }
                else
                {
                   $('#img_O_drawing').attr('src', data['o_drawing_no_qr']);
                    img_O_drawing       = data['o_drawing_no_qr'];

                    $('.drawing-odrawing').removeClass('d-none');
                }

                if(lbl_gdrawing_no == 'N/A')
                {
                  $('.drawing-gdrawing').addClass('d-none');
                }
                else
                {
                   $('#img_G_drawing').attr('src', data['g_drawing_no_qr']);
                    img_G_drawing       = data['g_drawing_no_qr'];

                    $('.drawing-gdrawing').removeClass('d-none');
                }

                if(lbl_adrawing_no == 'N/A')
                {
                  $('.drawing-adrawing').addClass('d-none');
                }
                else
                {
                   $('#img_A_drawing').attr('src', data['a_drawing_no_qr']);
                    img_A_drawing       = data['a_drawing_no_qr'];

                    $('.drawing-adrawing').removeClass('d-none');
                }

               console.log(lbl_gdrawing_no + " " + lbl_adrawing_no + " " + lbl_odrawing_no);

              }
            });
          //-
    });

    $("#btn_print_barcode").click(function(){
          popup = window.open();
          let content = '';
          content += '<html>';
          content += '<head>';
            content += '<title></title>';
            content += '<style type="text/css">';
              content += '.rotated {';
                content += 'width: 150px;';
                content += 'position: absolute;';
                content += 'left: 17.5px;';
                content += 'top: 13px;';
              content += '}';
            content += '</style>';
          content += '</head>';
          content += '<body>';
            content += '<center>';
            content += '<div class="rotated">';
            content += '<table>';
            content += '<tr>';
            content += '<td>';
            content += '<center>';
            content += '<img src="' + img_barcode_PO + '" style="max-width: 55px;">';
            content += '<br><label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 8px;">' + lbl_PO + '</label>';
            content += '<br><label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 8px;">' + lbl_device_name + '</label>';
            content += '</center>';
            content += '</tr>';
            content += '</table>';
            content += '</div>';
            content += '</center>';
          content += '</body>';
          content += '</html>';
          popup.document.write(content);
          popup.focus(); //required for IE
          popup.print();
          popup.close();
    });

    $("#btn_print_qr").click(function(){
      popup = window.open();

        let content = '';
        
        content += '<html>';
        content += '<head>';
        content += '<title></title>';
        content += '<style type="text/css">';
        
        content += '.rotated {';
        content += 'width: 200px;';
        content += 'position: absolute;';
        content += 'left: 5px;';
        content += '}';

        content += '.s {';
        content += 'border-left: 1px dashed black;';
        content += 'height: 15px;';
        content += '}';

        content += '.s1 {';
        content += 'border-left: 1px dashed black;';
        content += 'height: 30px;';
        content += '}';

        content += '</style>';
        content += '</head>';
        content += '<body>';

        content += '<table>';
        
        content += '<div class="rotated">';
            content += '<table>';

            content += '<tr>';

                if(lbl_adrawing_no != 'N/A'){

                    content += '<td style="width: 50%; text-align: center;">';
                    content += '<img src="' + img_A_drawing + '" style="min-width: 50px; max-width: 50px;">';
                    content += '</td>';
                }

                if(lbl_gdrawing_no != 'N/A'){ 
                  if(lbl_adrawing_no != 'N/A'){
                     content += '<td>';
                    content += '<div class="s"></div>';
                    content += '</td>';
                  }
                     content += '<td style="width: 50%; text-align: center;">';
                    content += '<img src="' + img_G_drawing + '" style="min-width: 50px; max-width: 50px;">';
                    content += '</td>';
                }

                if(lbl_odrawing_no != 'N/A'){ 

                  if(lbl_gdrawing_no != 'N/A' || lbl_adrawing_no != 'N/A'){
                    content += '<td>';
                    content += '<div class="s"></div>';
                    content += '</td>';
                  }
                  
                    content += '<td style="width: 50%; text-align: center;">';
                    content += '<img src="' + img_O_drawing + '" style="min-width: 50px; max-width: 50px;">';
                    content += '</td>';
                }             

            content += '</tr>';

            content += '<tr>';

                if(lbl_adrawing_no != 'N/A'){
                  content += '<td style="font-family: Arial; font-size: 5px; text-align: center; vertical-align:top;">';
                  content += '<label>' + lbl_PO_adrawing + '</label>';
                  content += '<br>';
                  content += '<label>' + lbl_device_name_adrawing + '</label>';
                  content += '<br>';
                  content += '<label style="font-weight: bold;">' + lbl_adrawing + '</label>';
                  content += '</td>';
                 
                }

                if(lbl_gdrawing_no != 'N/A'){   
                  if(lbl_adrawing_no != 'N/A'){
                    content += '<td>';
                    content += '<div class="s1"></div>';
                    content += '</td>';
                  }

                    content += '<td style="font-family: Arial; font-size: 5px; text-align: center; vertical-align:top;">';
                    content += '<label>' + lbl_PO_gdrawing + '</label>';
                    content += '<br>';
                    content += '<label>' + lbl_device_name_gdrawing + '</label>';
                    content += '<br>';
                    content += '<label style="font-weight: bold;">' + lbl_gdrawing + '</label>';
                    content += '</td>';                    
                }

                if(lbl_odrawing_no != 'N/A'){   
                    if(lbl_gdrawing_no != 'N/A' || lbl_adrawing_no != 'N/A')
                    {
                      content += '<td>';
                      content += '<div class="s1"></div>';
                      content += '</td>';
                    }
                    
                    content += '<td style="font-family: Arial; font-size: 5px; text-align: center; vertical-align:top;">';
                    content += '<label>' + lbl_PO_odrawing + '</label>';
                    content += '<br>';
                    content += '<label>' + lbl_device_name_odrawing + '</label>';
                    content += '<br>';
                    content += '<label style="font-weight: bold;">' + lbl_odrawing + '</label>';
                    content += '</td>';
                }
             
            content += '</tr>';
            content += '</table>';
        content += '</div>';
       
        content += '</table>';     

        content += '</body>';
        content += '</html>';
        popup.document.write(content);
        popup.focus(); //required for IE
        popup.print();
        popup.close();
    })

    $(document).on('click', '.btn-print-lot-no', function(){

    $('#modalPrintLotQRCode').modal('show');

    lbl_lot_po = $(this).attr('po-no');
    lbl_lot = $(this).attr('lot-no');
    lbl_lot_qty = $(this).attr('lot-qty');
    // lbl_lot_device_name = $('#txt_device_name_lbl').val(); 
    lbl_lot_device_name = $('#lbl_device_name').attr('device_name_print');
    

    $.ajax({
      url: "generate_qrcode_tspts",
      method: "get",
      data:
      {
        po_no: lbl_lot_po,
        lot_no: lbl_lot,
      },
      beforeSend: function()
      {
        img_barcode_lot_po = '';
        img_barcode_lot = '';
      },
      success: function(JsonObject)
      { 
        img_barcode_lot_po = JsonObject['po_no_code'];
        img_barcode_lot = JsonObject['lot_no_code'];

        $('#img_barcode_lot_po').prop('src', JsonObject['po_no_code']);
        $('#img_barcode_lot').prop('src', JsonObject['lot_no_code'])
      }
    });

    $('#lbl_lot_PO').text(lbl_lot_po);
    $('#lbl_lot_device_name').text(lbl_lot_device_name);
    $('#lbl_lot_no').text(lbl_lot);
    $('#lbl_lot_qty').text(lbl_lot_qty);
  });

  $("#btn_print_lot_barcode").click(function(){
        popup = window.open();
       
        let content = '';
        content += '<html>';
        content += '<head>';
          content += '<title></title>';
          content += '<style type="text/css">';
            content += '.rotated {';
              // content += 'transform: rotate(270deg); /* Equal to rotateZ(45deg) */';
             /* content += 'border: 2px solid black;';*/
              content += 'width: 150px;';
              content += 'position: absolute;';
              content += 'left: 17.5px;';
              content += 'top: 13px;';
            content += '}';
          content += '</style>';
        content += '</head>';
        content += '<body>';

          content += '<div class="rotated">';
          content += '<table>';
          content += '<tr>';

          content += '<td>';
          content += '<center>';
          content += '<img src="' + img_barcode_lot_po + '" style="max-width: 55px;">';
          content += '<br><label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">' +  $('#lbl_lot_PO').text() + '</label>';
          content += '<br><label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">' +$('#lbl_lot_device_name').text() + '</label>';
           content += '</center>';
          content += '</td>';

           content += '<td>';
            content += '<center>';
          content += '<img src="' + img_barcode_lot + '" style="max-width: 55px;">';
          content += '<br><label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">' + $('#lbl_lot_no').text() + '</label>';
          content += '<br><label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">' + $('#lbl_lot_qty').text() + '</label>';
          content += '</center>';
          content += '</td>';
          content += '</tr>';


          content += '</table>';
          content += '</div>';
          content += '</center>';
        content += '</body>';
        content += '</html>';
        popup.document.write(content);
        popup.focus(); //required for IE
        popup.print();
        popup.close();
  });

  $("#btnReference").click(function(){
    let lbl_device_name = $('#lbl_device_name').attr('device_name_print');
    // alert(lbl_device_name)
    device_name = lbl_device_name.split('-');
    device_name = device_name[0]+'-'+device_name[1];
    // alert(device_name)
    window.open("http://rapid/ACDCS/prdn_home_tsppts?doc_title="+device_name);    
    $('#btnShowAddProdRuncard').prop('disabled', false);
    $('.btnOpenRuncardDetails').prop('disabled', false);
    $('#btnReference').prop('disabled', true);
  }); 

// -------------------------------------------------------------------------------
  // $(".btnSearchADrawing").click(function(){
  // $("#btnView_a_drawing").click(function(){
  //   let txt_Adrawing = $('#txt_Adrawing_no').val();
  //   // alert(txt_Adrawing)
  //   if ( txt_Adrawing == 'N/A' || txt_Adrawing == '' ){
  //     alert('No Document')
  //     $('.btnSearchADrawing').prop('disabled', true);
  //   }else{
  //     window.open("http://rapid/ACDCS/prdn_home_tsppts?doc_no="+txt_Adrawing);   
  //     $('.btnSearchADrawing').prop('disabled', false);
  //   }
  // });

  $(".btnSearchADrawing").click(function(){
    
    let txt_Adrawing = $('#txt_Adrawing').val();
    let txt_Adrawing_fkid_document = $('#txt_Adrawing_fkid_document').val();

    drawing = txt_Adrawing.charAt(1);

    if ( txt_Adrawing == 'N/A'){
      alert('No Document')
      $('.btnSearchADrawing').prop('disabled', true);

    }else{

      if ( txt_Adrawing == 'N/A'){
        alert('No Document')
        $('.btnSearchADrawing').prop('disabled', true);
      }else{
        window.open("http://rapid/ACDCS/prdn_home_tsppts?doc_no="+txt_Adrawing);   
        $('.btnSearchADrawing').prop('disabled', false);
      }
    }  
  });  

  $(".btnSearchOrigADrawing").click(function(){
    
    let txt_orig_Adrawing = $('#txt_orig_Adrawing').val();
    let txt_orig_Adrawing_fkid_document = $('#txt_orig_Adrawing_fkid_document').val();

    drawing = txt_orig_Adrawing.charAt(1);
    // alert(drawing)

    if ( txt_orig_Adrawing == 'N/A'){
      alert('No Document')
      $('.btnSearchOrigADrawing').prop('disabled', true);

    }else{

      if ( txt_orig_Adrawing == 'N/A'){
        alert('No Document')
        $('.btnSearchADrawing').prop('disabled', true);
      }else{
        window.open("http://rapid/ACDCS/prdn_home_tsppts?doc_no="+txt_orig_Adrawing);   
        $('.btnSearchOrigADrawing').prop('disabled', false);
      }
    }  
  }); 

  $(".btnSearchGDrawing").click(function(){
    
    let txt_Gdrawing = $('#txt_Gdrawing').val();
    let txt_Gdrawing_fkid_document = $('#txt_Gdrawing_fkid_document').val();

    drawing = txt_Gdrawing.charAt(1);

    if ( txt_Gdrawing == 'N/A'){
      alert('No Document')
      $('.btnSearchGDrawing').prop('disabled', true);

    }else{

      if ( txt_Gdrawing == 'N/A'){
        alert('No Document')
        $('.btnSearchGDrawing').prop('disabled', true);
      }else{
        window.open("http://rapid/ACDCS/prdn_home_tsppts?doc_no="+txt_Gdrawing);   
        $('.btnSearchGDrawing').prop('disabled', false);
      }
    }  
  });

  $('#btn_download').click(function(){
    window.open('public/storage/file_templates/user_manual/TS PTS User Manual - FVI Lot Application.pdf','_blank');
  });  

  //-test only
  // $("#btn_print_lot_barcode").click(function(){
  //       popup = window.open();
       
  //       let content = '';
  //       content += '<html>';
  //       content += '<head>';
  //         content += '<title></title>';
  //         content += '<style type="text/css">';
  //           content += '.rotated {';
  //             content += 'width: 200px;';
  //             content += 'position: absolute;';
  //             content += 'left: 0px;';
  //             content += 'top: 13px;';
  //           content += '}';
  //         content += '</style>';
  //       content += '</head>';
  //       content += '<body>';

  //         content += '<div class="rotated">';
  //         content += '<table>';

  //         content += '<tr>';
            
  //           content += '<td>';
  //           content += '<center>';
  //           content += '<img src="' + img_barcode_lot_po + '" style="max-width: 60px;">';
  //           content += '</td>';
  //           content += '<td>';
  //           content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 10px;">450215777100010</label>';
  //           content += '<br><label style="text-align: center; font-family: Arial; font-size: 10px;">IC354-0562-010P</label>';
  //           content += '<br><label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 10px;">LOT-049</label>';
  //           content += '<br><label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 10px;">21070021-065</label>';
  //           content += '<br><label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 10px;">1234-24-A0627</label>';
  //           content += '<br><label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 10px;">240</label>';
  //           content += '<br><label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 10px;">1/1</label>';
  //           content += '</td>';

  //         content += '</tr>';

  //         content += '</table>';
  //         content += '</div>';
  //         content += '</center>';
  //       content += '</body>';
  //       content += '</html>';
  //       popup.document.write(content);
  //       popup.focus(); //required for IE
  //       popup.print();
  //       popup.close();
  // });

    $("#btnView_a_drawing").click(function(){
      redirect_to_drawing( $('#txt_Adrawing_no').val(), 0 )
    });

    $("#btnView_orig_a_drawing").click(function(){
      redirect_to_drawing( $('#txt_orig_Adrawing_no').val(), 1 )
    });

    $("#btnView_g_drawing").click(function(){
      redirect_to_drawing( $('#txt_Gdrawing_no').val(), 2 )
    });

    $("#btnView_wi_d_document").click(function(){
      redirect_to_drawing( $('#txt_WIDoc').val(), 3 )
    });

    $("#btnView_ogm_vig_ig_d_document").click(function(){
      redirect_to_drawing( $('#txt_OGM_VIG_IGDoc').val(), 4 )
    });

    $("#btnView_pp_d_document").click(function(){
      redirect_to_drawing( $('#txt_PPDoc').val(), 5 )
    });

    $("#btnView_ud_d_document").click(function(){
      redirect_to_drawing( $('#txt_UDDoc').val(), 6 )
    });

    $("#btnView_pm_document").click(function(){
      redirect_to_drawing( $('#txt_PMDoc').val(), 7 )
    });

    $("#btnView_j_r_dj_ks_dc_gj_document").click(function(){
      redirect_to_drawing( $('#txt_JRDJKSDCGJDoc_no').val(), 8 )
    });

    $("#btnView_gp_md_document").click(function(){
      redirect_to_drawing( $('#txt_GPMDDoc_no').val(), 9 )
    });

    function redirect_to_drawing(txt_Adrawing, index) {
      if ( txt_Adrawing == 'N/A' || txt_Adrawing == '' )
        alert('No Document')
      else{
        window.open("http://rapid/ACDCS/prdn_home_tsppts?doc_no="+txt_Adrawing)
        checked_draw_count[index] = 1
      }
    }

      let checked_draw_count
      function checked_draw_count_reset() {
        checked_draw_count = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
      }

  </script>

  @include('prod_runcard.rev_script')
  @endsection
@endauth