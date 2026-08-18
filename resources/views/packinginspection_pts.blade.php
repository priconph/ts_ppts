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

@section('title', 'Preliminary Packing Inspection')

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
          <h1>Preliminary Packing</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Preliminary Packing</li>
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
                <h3 class="card-title">1. Scan PO Number</h3>
              </div>
          <!--   <div class="card-header">

              <div class="float-sm-right">
                <button type="button" data-toggle="modal" data-target="#modalPackingInspection">test</button>
              </div>

            </div> -->
            <div class="card-body">
                  <div class="row">
                    <div class="col-sm-3">
                      <label>PO Number</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-primary btn_search_POno" title="Click to Scan PO Code"><i class="fa fa-qrcode"></i></button>
                        </div>

                         <input type="text" id="id_po_no" class="form-control" autocomplete="off" readonly>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <label>Device Name</label>
                        <input type="text" class="form-control" id="id_device_name" name="" readonly="">
                    </div>
                    <div class="col-sm-2">
                      <label>Device Code</label>
                        <input type="text" class="form-control" id="txt_device_code_lbl" readonly="">
                    </div>
                    <div class="col-sm-1">
                      <label>PO Qty</label>
                        <input type="text" class="form-control" id="id_po_qty" readonly="">
                    </div>
<!--                     <div class="col-sm-3">
                      <button class="btn btn-primary btn-sm" id="btn_download"><i class="fa fa-file"></i> User Manual</button>
                    </div>
 -->
                  </div>
                  <br>
              </div>
              <!-- !-- End Page Content -->
          </div>
          <!-- /.card -->

           <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">2. Preliminary Packing Summary</h3>
              </div>
                <div class="card-body">
                  <div class="table-responsive dt-responsive">
                      <table id="tbl_packing_inspection" class="table table-bordered table-striped table-hover" style="width: 100%;">
                          <thead>
                            <tr>
                              <th>Action</th>
                              <!-- <th>Packing Code</th> -->
                              <th>Lot Number</th>
                              <th>Lot Qty</th>
                              <th>Packing Operator</th>
                              <th>OQC Inspector</th>
                            </tr>
                          </thead>
                      </table>
                  </div>
                </div>
              </div>



        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<div class="modal fade" id="modalScan_PO" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please scan the PO number.
          <br>
          <br>
          <h1><i class="fa fa-qrcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_search_po_number" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>

<div class="modal fade" id="modalPackingInspection">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">Preliminary Packing Inspection (Responsible: OQC Inspector)</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </div>

      <form id="formPackingInspection" method="post">
      @csrf

        <div class="modal-body">

          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">PO Number</span>
                </div>
                <input type="text" class="form-control form-control-sm" id="add_po_no" name="add_po_no" readonly>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">Lot Number</span>
                </div>
                  <input type="text" class="form-control form-control-sm" id="add_lot_no" name="add_lot_no" readonly>
                  <input type="hidden" class="form-control form-control-sm" id="add_lot_id" name="add_lot_id" readonly>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">Total Lot Qty</span>
                </div>
                <input type="text" class="form-control form-control-sm" id="add_lot_qty" name="add_lot_qty" readonly>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">Series  Name</span>
                </div>
                <input type="text" class="form-control form-control-sm" id="add_series_name" name="add_series_name" readonly>
              </div>
            </div>
          </div>

          <div class="card card-primary">
            <div class="card-header">
             <div class="row">
                <div class="col">
                   <div class="table-responsive dt-responsive">
                      <table id="tbl_packing_confirmation_accessories" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 75%">
                          <thead>
                            <tr>
                              <th>Accessory Name</th>
                              <th>Quantity</th>
                            </tr>
                          </thead>
                      </table>
                    </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card card-primary">
            <div class="card-header">
              <h5 class="card-title">List of Tray's</h5>

              <div class="row" >
                <div class="col" >
                   <button type="button" class="btn btn-success btn-sm" id="btn_scan_tray" style="float: right;">Scan Tray</button>
                </div>
              </div>

              <div class="row">
                <div class="col">
                   <div class="table-responsive dt-responsive">
                      <table class="table table-bordered" style="width: 100%; font-size: 75%">
                          <thead>
                            <tr>
                              <th style="padding: 5px; width: 35%;">PO Number</th>
                              <th style="padding: 5px; width: 20%;">Lot Number</th>
                              <th style="padding: 5px; width: 15%;">Qty Per Tray</th>
                              <th style="padding: 5px; width: 15%;">Counter</th>
                              <th style="padding: 5px; width: 15%;">Status</th>
                            </tr>
                          </thead>
                          <tbody id="tblTrayChecker"></tbody>
                      </table>
                    </div>
                </div>
              </div>

              <div class="row">
                <div class="col">
                   <div class="table-responsive dt-responsive">
                      <table class="table table-bordered" style="width: 100%; font-size: 75%">
                          <thead>
                            <tr>
                              <th style="padding: 5px; width: 35%;">Total Qty:</th>
                              <th style="padding: 5px; width: 20%; text-align: center; background-color:#00FFFF;" id="tblTrayChecker_ttl_quantity"></th>
                              <th style="padding: 5px; width: 30%;">Total Scanned Qty:</th>
                              <th style="padding: 5px; width: 15%; text-align: center;background-color:#51FF51;" id="tblTrayChecker_ttl_quantity_scanned"></th>
                            </tr>
                          </thead>
                      </table>
                    </div>
                </div>
              </div>

          </div>
        </div>

          <div class="row">
               <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Packing Type</span>
                  </div>
                  <select class="form-control form-control-sm" id="add_packing_type" name="add_packing_type">
                    <option selected disabled>-- Choose One --</option>
                    <option value='1'>Box</option>
                    <option value='2'>Tray</option>
                    <option value='3'>Cylinder</option>
                    <option value='4'>Pallet Case</option>
                    <option value='6'>Esafoam</option>
                    <option value='7'>PolyBag</option>
                    <option value='5'>N/A</option>
                  </select>
                </div>
              </div>
            </div>

          <div class="row">
               <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Unit Condition</span>
                  </div>
                  <select class="form-control form-control-sm" id="add_unit_condition" name="add_unit_condition">
                    <option selected disabled>-- Choose One --</option>
                    <option value='1'>Terminal Mounted on Esafoam</option>
                    <option value='2'>Terminal Down</option>
                    <option value='3'>Terminal Up</option>
                    <option value='7'>Readable</option>
<!--                     <option value='4'>Esafoam</option>
                    <option value='5'>PolyBag</option>
 -->
                    <option value='6'>N/A</option>
                  </select>
                </div>
              </div>
            </div>

          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">Series Name on QA Application VS. Label Tally?</span>
                </div>
                <select class="form-control form-control-sm" id="add_series_v_label" name="add_series_v_label">
                    <option selected disabled>-- Choose One --</option>
                    <option value='1'>Yes</option>
                    <option value='2'>No</option>
                    <option value='3'>N/A</option>
                </select>
              </div>
            </div>
          </div>

           <div class="row">
             <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Series Name on Label VS. Actual Product Tally?</span>
                  </div>
                  <select class="form-control form-control-sm" id="add_label_v_actual" name="add_label_v_actual">
                      <option selected disabled>-- Choose One --</option>
                      <option value='1'>Yes</option>
                      <option value='2'>No</option>
                      <option value='3'>N/A</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
               <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Silica Gel / Anti-Rust Requirement</span>
                  </div>
                  <select class="form-control form-control-sm" id="add_silica_gel" name="add_silica_gel">
                      <option selected disabled>-- Choose One --</option>
                      <option value='1'>With</option>
                      <option value='2'>Without</option>
                      <option value='3'>N/A</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
               <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">COC</span>
                  </div>
                  <select class="form-control form-control-sm" id="add_coc" name="add_coc">
                      <option selected disabled>-- Choose One --</option>
                      <option value='1'>Yes</option>
                      <option value='2'>No</option>
                      <option value='3'>N/A</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
               <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">YD Label</span>
                  </div>
                  <select class="form-control form-control-sm" id="add_yd_label" name="add_yd_label">
                    <option selected disabled>-- Choose One --</option>
                      <option value='1'>YES</option>
                      <option value='2'>NO</option>
                      <option value='3'>N/A</option>
                  </select>
                </div>
              </div>
            </div>


          <!--   <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">NEEDS SUPERVISOR VALIDATION?</span>
                </div>
                <select class="form-control form-control-sm" id="add_supervisor_validation" name="add_supervisor_validation">
                  <option selected disabled>-- Choose One --</option>
                  <option value='1'>YES</option>
                  <option value='2'>NO</option>
                  <option value='3'>N/A</option>
                </select>
              </div>
            </div>
          </div>
 -->
            <div class="row">
               <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">OQC Inspector Name</span>

                     <input type="hidden" id="add_packing_inspector_name" name="add_packing_inspector_name">
                  </div>
                   <input type="text" class="form-control" id="add_packing_inspector_name2" name="add_packing_inspector_name2" readonly>

                  <div class="input-group-prepend">
                    <button type="button" class="btn btn-info btn-sm" id="btnSearchInspector" data-toggle="modal" data-target="#modalSearchInspector" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                    <button type="button" class="btn btn-danger btn-sm" id="btnPopLastOperator" title="Remove Last Operator"><i class="fa fa-retweet"></i></button>
                  </div>

                </div>
              </div>
            </div>

<!--             <div class="row">
               <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">OQC INSPECTOR NAME:</span>

                        <input type="hidden" id="add_packing_inspector_name" name="add_packing_inspector_name">
                  </div>
                   <input type="text" class="form-control" id="add_packing_inspector_name2" name="add_packing_inspector_name2" readonly>
                  <div class="input-group-prepend">
                    <button type="button" class="btn btn-info btn-sm" id="btnSearchInspector" data-toggle="modal" data-target="#modalSearchInspector" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                  </div>

                </div>
              </div>
            </div>
 -->
          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">Inspection Date/Time</span>
                </div>
<!--                 <input type="datetime-local" class="form-control form-control-sm" id="add_inspection_datetime" name="add_inspection_datetime">
 -->
                <input type="text" class="form-control form-control-sm" id="add_inspection_datetime" name="add_inspection_datetime" readonly="true" placeholder="Auto generated">
              </div>
            </div>
          </div>
        </div>

      </form>

      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-success" id="btnSubmitInspection">Submit</button>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="modal_scan_tray" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Scan Tray QR Code.
          <br>
          <br>
          <h1><i class="fa fa-qrcode fa-lg"></i></h1>
          </div>
          <input type="text" id="modal_scan_tray_qrcode" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>

<div class="modal fade" id="modalSearchInspector" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please scan your Employee ID.
          <br>
          <br>
          <h1><i class="fa fa-barcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_employee_id" name="txt_employee_id" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>

<div class="modal fade" id="modalViewApplication">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">Preliminary Packing Inspection (Responsible: OQC Inspector)</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </div>

      <div class="modal-body">

          <input type="hidden" id="view_lotapp_id">

         <div class="card card-primary">

            <div class="card-header">
              <h5 class="card-title">Inspection Summary</h5>
            </div>

            <div class="card-body">
              <div class="table-responsive dt-responsive">
                  <table id="tbl_packinginspection_results" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 75%;">
                      <thead>
                        <tr>
                          <th>OQC Inspector</th>
                          <th>Inspection Date/Time</th>
                          <th>Packing Type</th>
                          <th>Unit Condition</th>
                          <th>Series V. Label</th>
                          <th>Label V. Actual</th>
                          <th>Silica Gel</th>
                          <th>YD Label</th>
                          <!-- <th>Supv. Validation</th> -->
                        </tr>
                      </thead>
                  </table>
              </div>
            </div>
          </div>

           <div class="card card-primary">

            <div class="card-header">
              <h5 class="card-title">Runcard Details</h5>

             <!--  <div class="float-sm-right"><button class="btn btn-primary btn-sm"><i class="fa fa-print"></i> Batch Print Packing Codes</button></div> -->
            </div>

            <div class="card-body">
              <div class="table-responsive dt-responsive">
                  <table id="tbl_runcards" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 75%;">
                      <thead>
                        <tr>
                          <!-- <th></th> -->
                          <!-- <th>Action</th> -->
                          <th>Inspector Code</th>
                          <th>Runcard #</th>
                          <th>C/T Area</th>
                          <th>Terminal Area</th>
                          <th>Output Quantity</th>
                        </tr>
                      </thead>
                  </table>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Total Output Qty:</span>
                    </div>
                    <input style="text-align: center; background-color:#51FF51;"" type="text" class="form-control form-control-sm" id="total_output" name="total_output" readonly>
                  </div>
                </div>
              </div>
            </div>
          </div>

      </div>

    </div>
  </div>
</div>


<div class="modal fade" id="modalScan_Drawing" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-0">
            <center>
              <h4>Please check all required Packing Document</h4>
            </center>
         </div>
         <br>
          <div class="modal-body pt-0">
            <input type="text" id="id_search_Drawing" class="hidden_scanner_input">
            <input type="text" id="id_search_Drawing_id" class="hidden_scanner_input">

           <!--  <div class="row">
              <input type="text" id="id_orig_a_drawing" placeholder="orig a drawing">
              <input type="text" id="id_a_drawing" placeholder="a drawing">
              <input type="text" id="id_g_drawing" placeholder="g drawing">
              <input type="text" id="id_o_drawing" placeholder="o drawing">
            </div> -->

<!--             <div class="row row_container">
              <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_orig_a_drawing">
                      <i class="fa fa-file" title="View"></i>
                    </button>
                    <span class="input-group-text w-100">Orig A Drawing</span>
                  </div>
                    <input type="text" class="form-control" id="modalScan_Drawing_OrigAdrawing_no" readonly="">
                  <input type="text" value="N/A" class="form-control form-control-sm" id="modalScan_Drawing_orig_a_revision" readonly="">
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
                    <input type="text" class="form-control" id="modalScan_Drawing_Adrawing_no" readonly="">
                  <input type="text" value="N/A" class="form-control form-control-sm" id="modalScan_Drawing_a_revision" readonly="">
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
                    <input type="text" class="form-control" id="modalScan_Drawing_Gdrawing_no" readonly="">
                  <input type="text" value="N/A" class="form-control form-control-sm" id="modalScan_Drawing_g_revision" readonly="">
                 </div>
              </div>
            </div>

            <div class="row row_container">
              <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_o_drawing">
                      <i class="fa fa-file" title="View"></i>
                    </button>
                    <span class="input-group-text w-100">O Drawing</span>
                  </div>
                    <input type="text" class="form-control" id="modalScan_Drawing_Odrawing_no" readonly="">
                  <input type="text" value="N/A" class="form-control form-control-sm" id="modalScan_Drawing_o_revision" readonly="">
                 </div>
              </div>
            </div> -->

            <div class="row row_container">
              <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_PM">
                      <i class="fa fa-file" title="View"></i>
                    </button>
                    <span class="input-group-text w-100">PM</span>
                  </div>
                    <input type="text" class="form-control" id="modalScan_Drawing_PM" readonly="">
                    <input type="text" value="N/A" class="form-control form-control-sm" id="modalScan_Drawing_REV_PM" readonly="">
                 </div>
              </div>
            </div>

            <div class="row row_container">
              <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_JRDJKSDCGJ">
                      <i class="fa fa-file" title="View"></i>
                    </button>
                    <span class="input-group-text w-100">J/R/DJ/KS/DC/GJ</span>
                  </div>
                    <input type="text" class="form-control" id="modalScan_Drawing_JRDJKSDCGJ" readonly="">
                    <input type="text" value="N/A" class="form-control form-control-sm" id="modalScan_Drawing_REV_JRDJKSDCGJ" readonly="">
                 </div>
              </div>
            </div>

            <div class="row row_container">
              <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_GPMD">
                      <i class="fa fa-file" title="View"></i>
                    </button>
                    <span class="input-group-text w-100">GP MD</span>
                  </div>
                    <input type="text" class="form-control" id="modalScan_Drawing_GPMD" readonly="">
                    <input type="text" value="N/A" class="form-control form-control-sm" id="modalScan_Drawing_REV_GPMD" readonly="">
                 </div>
              </div>
            </div>

            <br>

            <div class="row row_container">
              <div class="col">
                  <center>
                    <button class="form-control" id="btnGotoPMIOQCInspection">Next</button>
                  </center>
              </div>
            </div>

          </div>
        </div>
      </div>
  </div>

  <div class="modal fade" id="modal_print_qrcode">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-qrcode"></i> Preliminary Packing - QR Code</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
              <div class="row">
                <div class="col-sm-12">
                  <center>
                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->margin(5)->errorCorrection('H')
                            ->generate('0')) !!}" id="modal_print_qrcode_image" style="max-width: 200px;">
                    <br>
                  </center>
                    <label id="modal_print_qrcode_text"></label>
                </div>

              </div>
        </div>
        <div class="modal-footer">
            <button type="button" id="modal_print_qrcode_print" class="btn btn-primary btn-sm"><i class="fa fa-print fa-xs"></i> Print</button>
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalScanEmployeeId" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <div class="text-center text-secondary">
                <div id="alert_notif"></div>
          </div>
        </div>
        <div class="modal-body pt-0" >
            <div class="card shadow w-50 mx-auto">
                <div class="card-body">
                    <div class="text-center text-secondary">
                        Please scan your ID.
                      <br>
                      <br>
                      <h1><i class="fa fa-qrcode fa-lg"></i></h1>
                        <input type="text" id="id_search_employee_id" class="hidden_scanner_input" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('js_content')
<script type="text/javascript">
  let dt_packing_accessories;

  let arrayPackingCodeBatch = [];
  let arrayPackingInspectors = [];
  let arrayPackingInspectorsName = [];

  let draw_lotapp_id
  let img_barcode_PO_text_hidden

  let tray_check_list

  $(document).ready(function () {

    $('#btn_scan_tray').click(function() {
        console.log('btn_scan_tray');
      let is_complete = true
      for (var i = 0; i < tray_check_list.length; i++) {
        if( tray_check_list[i]['stt']==0 ){
          is_complete = false
          break
        }
      }
      if( is_complete ){
        alert( 'All tray already scanned.' )
        return
      }

      $('#modal_scan_tray').modal('show')
      $('#modal_scan_tray_qrcode').val('')
      $('#modal_scan_tray_qrcode').focus()
    })

    /*added by Nessa*/
    $(document).on('keypress',function(e){
      if( ($("#modal_scan_tray").data('bs.modal') || {})._isShown ){
        $('#modal_scan_tray_qrcode').focus();

        if( e.keyCode == 13 && $('#modal_scan_tray_qrcode').val() !='' && ($('#modal_scan_tray_qrcode').val().length >= 4) ){
            $('#modal_scan_tray').modal('hide');
          }
        }
    });

    $('#modal_scan_tray_qrcode').keypress(function(e) {
      if( e.keyCode == 13 && $('#modal_scan_tray_qrcode').val() !='' ){
        let is_complete = true
        for (var i = 0; i < tray_check_list.length; i++) {
          if( tray_check_list[i]['stt']==0 ){
            is_complete = false
            break
          }
        }
        if( is_complete ){
          alert( 'All tray already scanned.' )
          return
        }

        let data = JSON.parse($('#modal_scan_tray_qrcode').val());
        let index = null

        if( tray_check_list[i]['po_no'] == data.oqc_lotapp_po_no && tray_check_list[i]['lot_no'] == data.oqc_lotapp_lot_batch_no && tray_check_list[i]['qtt'] == data.oqc_lotapp_qtt_tray && tray_check_list[i]['counter'] == data.oqc_lotapp_lot_sticker_cnt ){
            index = i
        }
        console.log('ObjectData',data);
        console.log('TrayCheckList',tray_check_list[i]);

        if( index!=null ){
          if( tray_check_list[index]['stt'] == 1 ){
            alert('Tray was already scanned.')
            $('#modal_scan_tray_qrcode').val('')
            $('#modal_scan_tray_qrcode').focus()
          }else{
            if(tray_check_list[index].count_per_tray > 1){
                    data_tray_check_list=[];
                    for (let j = 0; j < tray_check_list.length; j++) {
                        if(tray_check_list[j].stt == 0){
                            var array_tray_check_list = tray_check_list[j].count_per_tray;
                            data_tray_check_list.push(array_tray_check_list);
                        }
                    }
                    /* remove all data that already scan based on count_per_tray*/
                    for (let k = 0; k < data_tray_check_list.length; k++) {
                        var splice_data_tray_check_list = data_tray_check_list[0];
                    }
                }
                /* condition that requires FIFO (first in first out / ascending)
                    splice_data_tray_check_list - array that already remove the scanned tray
                    tray_check_list[index].count_per_tray - first scan first serve
                */
                if(splice_data_tray_check_list < tray_check_list[index].count_per_tray){
                    $('#modal_scan_tray').modal('hide');
                    let notif_alert = ` <p style="font-size:50px;"><strong>Invalid TRAYS QR Code Details found,</strong></p>
                                        <p style="font-size:50px; color:red;"><strong>CALL THE ATTENTION OF IMMEDIATE SUPERVISOR</strong></p>`;
                    $('#alert_notif').html(notif_alert);
                    $('#modalScanEmployeeId').modal('show'); //enable this
                    $('#id_search_employee_id').val(''); //enable this
                }else{
                    // console.log('nabaril na!');
                    tray_check_list[index]['stt'] = 1
                    $('#tray_check_list_id_' + index).html('scanned')
                    $('#tray_check_list_tr_id_' + index).css("background-color","#51FF51")

                    let ttl = 0
                    for (var i = 0; i < tray_check_list.length; i++) {
                    if( tray_check_list[i]['stt']== 1 )
                        ttl += tray_check_list[i]['qtt']
                    }
                    $('#tblTrayChecker_ttl_quantity_scanned').html(ttl)
                    if( $('#tblTrayChecker_ttl_quantity').html() == $('#tblTrayChecker_ttl_quantity_scanned').html() )
                    $('#modal_scan_tray').modal('hide')

                    $('#modal_scan_tray_qrcode').val('')
                    $('#modal_scan_tray_qrcode').focus()
                }

          }
        }else{
          // alert('Invalid details, please check.')
            $('#modal_scan_tray').modal('hide');
            let notif_alert = `<p style="font-size:50px; color:red;">Invalid QR Code Details, Please check!</p>
                                <p style="font-size:50px; color:red;"><strong>CALL THE ATTENTION OF IMMEDIATE SUPERVISOR</strong></p>`;
            $('#alert_notif').html(notif_alert);
            $('#modalScanEmployeeId').modal('show');  //enable this
            $('#id_search_employee_id').val(''); //enable this
        }
      }
    })

    $(document).on('keypress',function(e){ //enable this
        if( ($("#modalScanEmployeeId").data('bs.modal') || {})._isShown ){
            $('#id_search_employee_id').focus();

            let employee_id = $('#id_search_employee_id').val();
            if( e.keyCode == 13 && employee_id !='' && (employee_id.length >= 4) ){
                fnValidateEmployeeId(employee_id); //Located at User.js
            }
        }
    });

    $(document).on('keydown',function(e){
        if ($("#id_search_employee_id").is(":focus")) {
            setTimeout(function() {
                $("#id_search_employee_id").val('');
            }, 200);
        }
    });

    bsCustomFileInput.init();

     GetUserList($(".selectUser"));
      $('.selectUser').select2({
            theme: 'bootstrap4'
          });

      dt_packing_inspection = $('#tbl_packing_inspection').DataTable({
          "processing"    : false,
          "serverSide"  : true,
          "ajax"        :
          {
            url: "load_packinginspection_pts_table",
              data: function (param){
                    param.po_no = $('#id_po_no').val();
                }
          },

          "columns":[
            { "data" : "action", orderable:false, searchable:false, width: "150px" },
            // { "data" : "packing_code" },
            { "data" : "lot_no" },
            { "data" : "lot_qty" , width: "100px" },
            { "data" : "packing_operator" },
            { "data" : "oqc_inspector" },

          ],

      });

       dt_packing_accessories = $('#tbl_packing_confirmation_accessories').DataTable({
          "processing"    : false,
          "serverSide"  : true,
          "ajax"        :
          {
            url: "load_accessories_pts_table",
              data: function (param){
                param.lotapp_id = $("#add_lot_id").val();
                }
          },

          "columns":[
            { "data" : "accessory_name" },
            { "data" : "quantity" , width: "50px" },
            /*{ "data" : "result" },*/

          ],

      });

       dt_packinginspection_results = $('#tbl_packinginspection_results').DataTable({

         "processing"    : false,
          "serverSide"  : true,
          "ajax"        :
          {
            url: "load_packing_inspection_pts_results",
              data: function (param){
                param.lotapp_id = $("#view_lotapp_id").val();
                }
          },

          "columns":[
            { "data" : "inspector" },
            { "data" : "inspection_datetime" },
            { "data" : "packing_type" },
            { "data" : "unit_condition" },
            { "data" : "series_v_label" },
            { "data" : "label_v_actual" },
            { "data" : "silica_gel" },
            // { "data" : "supervisor_conformance" },
            { "data" : "yd_label" },
          ],

      });

        dt_runcards = $('#tbl_runcards').DataTable({

          "processing"    : false,
          "serverSide"  : true,
          "ajax"        :
          {
            url: "load_runcards_tspts_table",
              data: function (param){
                param.lotapp_id = $('#view_lotapp_id').val();
                param.array_batch = arrayPackingCodeBatch;
                }
          },

          "columns":[
             /*{ "data" : "action_batch", orderable:false, searchable:false, width: "20px" },*/
             // { "data" : "action", orderable:false, searchable:false, width: "150px" },
            { "data" : "packing_code" },
            { "data" : "runcard_no"},
            { "data" : "ct_area" },
            { "data" : "terminal_area" },
            { "data" : "output_qty" },
          ],

      });
  });

   //SEARCH PO
    $(document).on('click','.btn_search_POno',function(e){
      $('#txt_search_po_number').val('');
      $('#modalScan_PO').attr('data-formid', '').modal('show');
    });

    $(document).on('keypress',function(e){
      if( ($("#modalScan_PO").data('bs.modal') || {})._isShown ){
        $('#txt_search_po_number').focus();

        if( e.keyCode == 13 && $('#txt_search_po_number').val() !='' && ($('#txt_search_po_number').val().length >= 4) ){
            $('#modalScan_PO').modal('hide');
          }
        }
    });
    $(document).on('keypress','#txt_search_po_number',function(e){
        try { // nmodify - Search PO using object
            if( e.keyCode == 13 ){
                $('#id_po_no').val('');
                $('#id_device_name').val('');
                $('#txt_device_code_lbl').val('');
                $('#id_po_qty').val('');
                let data = JSON.parse($('#txt_search_po_number').val()).oqc_lotapp_po_no;
                if(data == undefined){
                    alert('Invalid QR Code')
                }else{
                    getWbsPoDetails(data); //Common.js
                    setTimeout(() => {
                        dt_packing_inspection.draw();
                    }, 500);

                }
            }
        } catch (error) {
            alert(`Error: ${error}`)
        }
    });

    $(document).on('click','.btn-packing-inspection',function(){

      draw_lotapp_id = $(this).attr('lotapp-id');

      // TSPTSViewLotAppDetails(draw_lotapp_id);

      $.ajax({
        url: "get_first_lot_data_by_po_no",
        method: "get",
        data:
        {
          po_no: $('#id_po_no').val(),
        },
        dataType: "json",
        success: function(JsonObject)
        {
          if( JsonObject['data'].length > 0 ){

            // $("#modalScan_Drawing_OrigAdrawing_no").val( (JsonObject['data'][0]['orig_a_drawing_no'] != null && JsonObject['data'][0]['orig_a_drawing_no'] != '') ? JsonObject['data'][0]['orig_a_drawing_no'] : 'N/A' )
            // $("#modalScan_Drawing_orig_a_revision").val( (JsonObject['data'][0]['orig_a_revision'] != null && JsonObject['data'][0]['orig_a_revision'] != '') ? JsonObject['data'][0]['orig_a_revision'] : 'N/A' )

            // $("#modalScan_Drawing_Adrawing_no").val( (JsonObject['data'][0]['a_drawing_no'] != null && JsonObject['data'][0]['a_drawing_no'] != '') ? JsonObject['data'][0]['a_drawing_no'] : 'N/A' )
            // $("#modalScan_Drawing_a_revision").val( (JsonObject['data'][0]['a_revision'] != null && JsonObject['data'][0]['a_revision'] != '') ? JsonObject['data'][0]['a_revision'] : 'N/A' )

            // $("#modalScan_Drawing_Gdrawing_no").val( (JsonObject['data'][0]['g_drawing_no'] != null && JsonObject['data'][0]['g_drawing_no'] != '') ? JsonObject['data'][0]['g_drawing_no'] : 'N/A' )
            // $("#modalScan_Drawing_g_revision").val( (JsonObject['data'][0]['g_revision'] != null && JsonObject['data'][0]['g_revision'] != '') ? JsonObject['data'][0]['g_revision'] : 'N/A' )

            // $("#modalScan_Drawing_Odrawing_no").val( (JsonObject['data'][0]['o_drawing_no'] != null && JsonObject['data'][0]['o_drawing_no'] != '') ? JsonObject['data'][0]['o_drawing_no'] : 'N/A' )
            // $("#modalScan_Drawing_o_revision").val( (JsonObject['data'][0]['o_revision'] != null && JsonObject['data'][0]['o_revision'] != '') ? JsonObject['data'][0]['o_revision'] : 'N/A' )

            $("#modalScan_Drawing_PM").val( (JsonObject['data'][0]['pm'] != null && JsonObject['data'][0]['pm'] != '') ? JsonObject['data'][0]['pm'] : 'N/A' )
            $("#modalScan_Drawing_REV_PM").val( (JsonObject['data'][0]['pm_revision'] != null && JsonObject['data'][0]['pm_revision'] != '') ? JsonObject['data'][0]['pm_revision'] : 'N/A' )

            $("#modalScan_Drawing_JRDJKSDCGJ").val( (JsonObject['data'][0]['j_r_dj_ks_dc_gj'] != null && JsonObject['data'][0]['j_r_dj_ks_dc_gj'] != '') ? JsonObject['data'][0]['j_r_dj_ks_dc_gj'] : 'N/A' )
            $("#modalScan_Drawing_REV_JRDJKSDCGJ").val( (JsonObject['data'][0]['j_r_dj_ks_dc_gj_revision'] != null && JsonObject['data'][0]['j_r_dj_ks_dc_gj_revision'] != '') ? JsonObject['data'][0]['j_r_dj_ks_dc_gj_revision'] : 'N/A' )

            $("#modalScan_Drawing_GPMD").val( (JsonObject['data'][0]['gp_md'] != null && JsonObject['data'][0]['gp_md'] != '') ? JsonObject['data'][0]['gp_md'] : 'N/A' )
            $("#modalScan_Drawing_REV_GPMD").val( (JsonObject['data'][0]['gp_md_revision'] != null && JsonObject['data'][0]['gp_md_revision'] != '') ? JsonObject['data'][0]['gp_md_revision'] : 'N/A' )

            $('#btnGotoPMIOQCInspection').attr('disabled', true)

            $("#modalScan_Drawing").modal('show')
            checked_draw_count_reset()

          }
        }

      });

    });

    $("#btnView_PM").click(function(){
      redirect_to_drawing( $('#modalScan_Drawing_PM').val(), 0 )
    });

    $("#btnView_JRDJKSDCGJ").click(function(){
      redirect_to_drawing( $('#modalScan_Drawing_JRDJKSDCGJ').val(), 1 )
    });

    $("#btnView_GPMD").click(function(){
      redirect_to_drawing( $('#modalScan_Drawing_GPMD').val(), 2 )
    });

    $("#btnGotoPMIOQCInspection").click(function(){

      // let lotapp_id = $(this).attr('lotapp-id');
      // let device_name = $('#id_device_name').val();

      TSPTSViewLotAppDetails(draw_lotapp_id);
      $("#modalScan_Drawing").modal('hide')
      $('#modalPackingInspection').modal('show')

    });



   $('#btnSubmitInspection').click(function(){

    let is_complete = true
    for (var i = 0; i < tray_check_list.length; i++) {
      if( tray_check_list[i]['stt']==0 ){
        is_complete = false
        break
      }
    }
    if( is_complete ){
      $('#formPackingInspection').submit();
    }else{
      alert('All tray/box are need to be scan.')
    }

   });

   $('#formPackingInspection').submit(function(e){

      e.preventDefault();
      TSPTSSubmitPackingInspection();

   });

   $('#modalPackingInspection').on('hidden.bs.modal',function(){

      $('#formPackingInspection')[0].reset();

      $('#add_packing_type').removeClass('is-invalid');
      $('#add_unit_condition').removeClass('is-invalid');
      $('#add_series_v_label').removeClass('is-invalid');
      $('#add_label_v_actual').removeClass('is-invalid');
      $('#add_silica_gel').removeClass('is-invalid');
      $('#add_yd_label').removeClass('is-invalid');
      $('#add_packing_inspector_name').removeClass('is-invalid');
      $('#add_inspection_datetime').removeClass('is-invalid');

      arrayPackingInspectors = [];
      arrayPackingInspectorsName = [];


  });


  $(document).on('click','.btn-view-application', function(){

    let lotapp_id = $(this).attr('lotapp-id');
    $('#view_lotapp_id').val(lotapp_id);
    dt_packinginspection_results.draw();
    dt_runcards.draw();

    $.ajax({
      url: "getTotalQuantityByRuncard",
      method: "get",
      data:
      {
        lotapp_id: lotapp_id,
      },
      dataType: "json",
      success: function(JsonObject)
      {

        // $('#total_input').html( JsonObject['data']['ttl_input'] );
        $('#total_output').val( JsonObject['data']['ttl_output'] );
        // $('#total_ng').html( JsonObject['data']['ttl_ng'] );

      }

    });

  });


  $(document).on('click','.btnPrintQRCode', function(){

        // data = $.param(data);
        $.ajax({
            data        : { id: $(this).attr('prod_id'), inspector_code: $(this).attr('packing-code') },
            type        : 'get',
            dataType    : 'json',
            url         : "generate_qrcode_for_preliminary_packing",
            success     : function (data) {

              $("#modal_print_qrcode_image").attr('src', data['QrCode']);
              $("#modal_print_qrcode_text").html(data['label']);
              img_barcode_PO_text_hidden = data['label_hidden']

              $('#modal_print_qrcode').modal('show')

            }, error    : function (data) {
            alert('ERROR: '+data);
            }
        });

  });

      //- Print Barcode
    $("#modal_print_qrcode_print").click(function(){
      popup = window.open();
        let content = '';

        content += '<html>';
        content += '<head>';
        content += '<title></title>';
        content += '<style type="text/css">';

        // content += '@page { margin: 0px; padding: 0px; }';
        content += '@media print { .pagebreak { page-break-before: always; } }';

        content += '.rotated {';
        content += 'width: 110px;';
        // content += 'position: relative;';
        // content += 'left: 5px;';
        // content += 'border: 5px solid red;';
        // // content += 'margin-top: 100px;';
        // content += 'height: 120px;';

        content += '}';
        content += '</style>';
        content += '</head>';
        content += '<body>';
        for (var i = 0; i < img_barcode_PO_text_hidden.length; i++) {
          content += '<table>';
          content += '<tr class="rotated">';
              content += '<td style="text-align: left;">';
              content += '<img src="' + img_barcode_PO_text_hidden[i]['img'] + '" style="min-width: 55px; max-width: 55px;">';
              content += '</td>';
              content += '<td style="font-size: 9px; font-family: Arial;">' + img_barcode_PO_text_hidden[i]['text'] + '</td>';
          content += '</tr>';
          content += '</table>';
          content += '<div class="pagebreak"> </div>';
        }
        content += '</body>';
        content += '</html>';
        popup.document.write(content);
        popup.focus();
        popup.print();
        popup.close();
    });


 $(document).on('click','.btn-print-packing-code',function(){

  let packing_code = $(this).attr('packing-code');
  let device_name = $('#id_device_name').val();

  popup = window.open();

          let content = '';
          content += '<html>';
          content += '<head>';
            content += '<title></title>';
            content += '<style type="text/css">';
              content += '.rotated {';
                content += 'border: 2px solid black;';
                content += 'width: 150px;';
                content += 'position: absolute;';
                content += 'left: 17.5px;';
                content += 'top: 15px;';
              content += '}';

               content += '.rotated2 {';
                content += 'border: 2px solid black;';
                content += 'width: 150px;';
                content += 'position: absolute;';
                content += 'left: 17.5px;';
                content += 'top: 50px;';
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
            content += '<label style="text-align: center; font-weight: bold; font-family: Times New Roman; font-size: 15px;">' + packing_code + '</label>';
            content += '</center>';
            content += '</tr>';
            content += '</table>';
            content += '</div>';
            content += '</center>';

             content += '<center>';
            content += '<div class="rotated2">';
            content += '<table>';
            content += '<tr>';
            content += '<td>';
            content += '<center>';
            content += '<label style="text-align: center; font-weight: bold; font-family: Times New Roman; font-size: 10px;">' + device_name + '</label>';
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

    $('#btnSearchInspector').click(function(){
    $('#txt_employee_id').val('');
});

            function redirect_to_drawing(txt_Adrawing, index) {
      if ( txt_Adrawing == 'N/A' || txt_Adrawing == '' )
        alert('Not equired')
      else{
        window.open("http://rapid/ACDCS/prdn_home_tsppts?doc_no="+txt_Adrawing)
        checked_draw_count[index] = 1
      }

      // let ids = ['modalScan_Drawing_OrigAdrawing_no','modalScan_Drawing_Adrawing_no','modalScan_Drawing_Gdrawing_no','modalScan_Drawing_Odrawing_no']
      let ids = ['modalScan_Drawing_PM','modalScan_Drawing_JRDJKSDCGJ','modalScan_Drawing_GPMD']
      let need_to_cherck_all_drawings = false
      for (var i = 0; i < ids.length; i++) {
        let txt_Adrawing = $('#' + ids[i]).val()
        if ( txt_Adrawing != 'N/A' && txt_Adrawing != '' ){
          if( checked_draw_count[i] == 0 )
            need_to_cherck_all_drawings = true
        }
      }

      if( !need_to_cherck_all_drawings ){
        // window.open("http://192.168.3.246/pmi-subsystem/oqcinspection")
        $('#btnGotoPMIOQCInspection').attr('disabled', false)
      }
    }

      let checked_draw_count
      function checked_draw_count_reset() {
        checked_draw_count = [0, 0, 0]
      }


function GetInspectorDetails(employee_id, array_inspectors, array_inspectors_name)
{
  $.ajax({
    url: "load_inspector_user_details",
    method: "get",
    data:
    {
      employee_id: employee_id,
    },
    dataType: "json",
    beforeSend: function()
    {

    },
    success: function(JsonObject)
    {

      // if(JsonObject['result'] == 1)
      // {
      //   $('#add_packing_inspector_name').val(JsonObject['user_details'][0].id);
      //   $('#add_packing_inspector_name2').val(JsonObject['user_details'][0].name);
      // }
      // else
      // {
      //   toastr.error('Employee ID not Found!');
      // }


     if(JsonObject['result'] == 1)
      {
        let inspector_names

        if(!array_inspectors.includes(JsonObject['user_details'][0].id))
        {
          array_inspectors.push(JsonObject['user_details'][0].id);
          array_inspectors_name.push(JsonObject['user_details'][0].name);

          $('#add_packing_inspector_name').val(array_inspectors.toString());
          $('#add_packing_inspector_name2').val(array_inspectors_name.toString());
        }
        else
        {
          toastr.error('Inspector already added!');
        }
      }
      else
      {
        toastr.error('Employee ID not Found!');
      }



    },
    error: function(data, xhr, status){
      toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
    }

  });
}

  $(document).on('keypress',function(e){
    if( ($("#modalSearchInspector").data('bs.modal') || {})._isShown ){
      $('#txt_employee_id').focus();

      if( e.keyCode == 13 && $('#txt_employee_id').val() !='' && ($('#txt_employee_id').val().length >= 4) ){

          $('#modalSearchInspector').modal('hide');

            $.ajax({
              url: "employee_id_checker",
              method: "get",
              data:
              {
                employee_id: $('#txt_employee_id').val(),
                // position: 5,
                user_level_id: 5,
              },
              dataType: "json",
              success: function(JsonObject)
              {
                if(JsonObject['result'] == 1)
                  GetInspectorDetails(JsonObject['emp_id'], arrayPackingInspectors, arrayPackingInspectorsName);
                else if(JsonObject['result'] == 0)
                  toastr.error('Scanned Employee ID is not OQC Inspector.');
                else
                  toastr.error(JsonObject['error_msg']);
              },
              error: function(data, xhr, status){
                toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
              }

            });

          // GetInspectorDetails($('#txt_employee_id').val(), arrayPackingInspectors, arrayPackingInspectorsName);

        }
      }
  });

 $('#btn_download').click(function(){
    window.open('public/storage/file_templates/user_manual/TS PTS User Manual - Preliminary Packing Inspection.pdf','_blank');
  });

  $('#btnPopLastOperator').click(function(){

    arrayPackingInspectors.pop();
    arrayPackingInspectorsName.pop();

    $('#add_packing_inspector_name').val(arrayPackingInspectors.toString());
    $('#add_packing_inspector_name2').val(arrayPackingInspectorsName.toString());

  });


</script>
@endsection
@endauth
