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

@section('title', 'Final QC Packing Inspection')

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
          <h1>Final QC Packing Inspection</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Final QC Packing Inspection</li>
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
            <!-- <div class="card-header">

              <div class="float-sm-right">
                <button type="button" data-toggle="modal" data-target="#modalFinalPackingInspection">test</button>
              </div>

            </div> -->

            <!-- Start Page Content -->
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
<!-- s -->
                  </div>
                  <br>
              </div>
              <!-- !-- End Page Content -->
          </div>
          <!-- /.card -->

           <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">2. Final QC Packing Inspection (Responsible: Packing Operator/OQC Inspector)</h3>
              </div>
                <div class="card-body">
                   <div class="table-responsive dt-responsive">
                      <table id="tbl_final_inspection" class="table table-bordered table-striped table-hover" style="width: 100%;">
                          <thead>
                            <tr>
                              <th>Action</th>
                              <th>Packing Code</th>
                              <th>Lot Number</th>
                              <th>Lot Qty</th>
                              <th>QC Inspector</th>
                              <th>OQC Stamp</th>
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



<div class="modal fade" id="modalFinalPackingInspection">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">Final QC Packing Inspection</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id ="formFinalPackingInspection" method="post">
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
                  <span class="input-group-text w-100" id="basic-addon1">Series Name</span>
                </div>
                <input type="text" class="form-control form-control-sm" id="add_series_name" name="add_series_name" readonly>
              </div>
            </div>
          </div>

          <div class="card card-primary">
            <div class="card-header">
              <h5 class="card-title">Responsible: Packing Operator</h5>

               <div class="row">
                  <div class="col">
                     <button type="button" class="btn btn-success btn-sm" id="btn_scan_tray" style="float: right;">Scan Tray</button>
                  </div>
                </div>

                 <div class="row">
                    <div class="col">
                       <div class="table-responsive dt-responsive">
                          <table class="table table-bordered" style="width: 100%; font-size: 75%">
                              <thead>
                                <tr>
                                  <th style="padding: 5px; width: 35%;">PO No.</th>
                                  <th style="padding: 5px; width: 20%;">Lot No.</th>
                                  <th style="padding: 5px; width: 15%;">Quantity Per Tray</th>
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
                                    <th style="padding: 5px; width: 15%; text-align: center; background-color:#51FF51;" id="tblTrayChecker_ttl_quantity_scanned"></th>
                                  </tr>
                                </thead>
                            </table> 
                          </div>
                      </div>
                    </div>

                    <div class="row">
                       <div class="col">
                        <div class="input-group input-group-sm mb-3">
                          <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100" id="basic-addon1">Packing Operator Conformance</span>
                            <input type="hidden" id="add_packing_operator_name" name="add_packing_operator_name">
                          </div>
                        <!--   <select class="form-control form-control-sm selectUser" id="add_packing_operator_name" name="add_packing_operator_name">
                            <option selected disabled>-- Choose One --</option>
                          </select> -->
                           <input type="text" class="form-control" id="add_packing_operator_name2" name="add_packing_operator_name2" readonly>

                          <div class="input-group-prepend">
                            <button type="button" class="btn btn-info btn-sm" id="btnSearchOperator" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                          </div>

                        </div>
                      </div>
                    </div>
            </div>
          </div>

          <div class="card card-primary">
            <div class="card-header">
              <h5 class="card-title">Responsible: OQC Inspector</h5>
               <div class="row">
                  <div class="col">
                     <button type="button" class="btn btn-danger btn-sm" id="btn_scan_tray_3" style="float: right;">Scan DLABEL</button>
                  </div>
                </div>

               <div class="row">
                  <div class="col">
                     <div class="table-responsive dt-responsive">
                        <table class="table table-bordered" style="width: 100%; font-size: 75%">
                            <thead>
                              <tr>
                                <th style="padding: 5px; width: 35%;">PO No.</th>
                                <th style="padding: 5px; width: 20%;">Lot No.</th>
                                <th style="padding: 5px; width: 15%;">Total Lot Qty</th>
                                <th style="padding: 5px; width: 15%;">Counter</th>
                                <th style="padding: 5px; width: 15%;">Status</th>
                              </tr>
                            </thead>
                            <tbody id="tblTrayChecker_3"></tbody>
                        </table> 
                      </div>
                  </div>
                </div>

               <div class="row">
                  <div class="col">
                     <button type="button" class="btn btn-danger btn-sm" id="btn_scan_tray_casemark" style="float: right;">Scan Casemark</button>
                  </div>
                </div>

               <div class="row">
                  <div class="col">
                     <div class="table-responsive dt-responsive">
                        <table class="table table-bordered" style="width: 100%; font-size: 75%">
                            <thead>
                              <tr>
                                <th style="padding: 5px; width: 18%;">Packing Ctrl No.</th>
                                <th style="padding: 5px; width: 15%;">PO No.</th>
                                <th style="padding: 5px; width: 10%;">Box No.</th>
                                <th style="padding: 5px; width: 7%;">Qty</th>
                                <th style="padding: 5px; width: 40%;">Destination</th>
                                <th style="padding: 5px; width: 10%;">Status</th>
                              </tr>
                            </thead>
                            <tbody id="tblTrayChecker_casemark"></tbody>
                        </table> 
                      </div>
                  </div>
                </div>

               <div class="row">
                  <div class="col">
                     <button type="button" class="btn btn-success btn-sm" id="btn_scan_tray_2" style="float: right;">Scan Tray</button>
                  </div>
                </div>

               <div class="row">
                  <div class="col">
                     <div class="table-responsive dt-responsive">
                        <table class="table table-bordered" style="width: 100%; font-size: 75%">
                            <thead>
                              <tr>
                                <th style="padding: 5px; width: 35%;">PO No.</th>
                                <th style="padding: 5px; width: 20%;">Lot No.</th>
                                <th style="padding: 5px; width: 15%;">Quantity Per Tray</th>
                                <th style="padding: 5px; width: 15%;">Counter</th>
                                <th style="padding: 5px; width: 15%;">Status</th>
                              </tr>
                            </thead>
                            <tbody id="tblTrayChecker_2"></tbody>
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
                                <th style="padding: 5px; width: 20%; text-align: center; background-color:#00FFFF;" id="tblTrayChecker_ttl_quantity_2"></th>
                                <th style="padding: 5px; width: 30%;">Total Scanned Qty:</th>
                                <th style="padding: 5px; width: 15%; text-align: center; background-color:#00FF00;" id="tblTrayChecker_ttl_quantity_scanned_2"></th>
                              </tr>
                            </thead>
                        </table> 
                      </div>
                  </div>
                </div>

                <div class="row">
                   <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">COC Attachment</span>
                      </div>
                      <select class="form-control form-control-sm" id="add_coc_attachment" name="add_coc_attachment">
                        <option selected disabled>-- Choose One --</option>
                        <option value='1'>YES</option>
                        <option value='2'>NO</option>
                        <option value='3'>N/A</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row">
                   <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Result</span>
                      </div>
                      <select class="form-control form-control-sm" id="add_result" name="add_result">
                        <option selected disabled>-- Choose One --</option>
                        <option value='1'>NO DEFECT FOUND</option>
                        <option value='2'>WITH DEFECT FOUND</option>
                        <option value='3'>N/A</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row">
                   <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">OQC Inspector Name</span>

                        <input type="hidden" id="add_oqc_inspector_name" name="add_oqc_inspector_name">

                      </div>
                     <!--  <select class="form-control form-control-sm selectUser" id="add_oqc_inspector_name" name="add_oqc_inspector_name">
                        <option selected disabled>-- Choose One --</option>
                      </select> -->
                      <input type="text" class="form-control" id="add_oqc_inspector_name2" name="add_oqc_inspector_name2" readonly>

                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-info btn-sm" id="btnSearchInspector" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Inspection Date/Time</span>
                      </div>
                     <input type="text" class="form-control form-control-sm" id="add_confirmation_datetime" name="add_confirmation_datetime" readonly="true" placeholder="Auto generated">
                    </div>
                  </div>
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

<div class="modal fade" id="modalSearchOperator" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please scan your Operator Employee ID.
          <br>
          <br>
          <h1><i class="fa fa-barcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_operator_employee_id" name="txt_operator_employee_id" class="hidden_scanner_input" autocomplete="off">
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
        <h4 class="modal-title">Final QC Packing Inspection</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

          <input type="hidden" id="view_lotapp_id">

         <div class="card card-primary">

            <div class="card-header">
              <h5 class="card-title">Inspection Results</h5>
            </div>

            <div class="card-body">
              <div class="table-responsive dt-responsive">
                  <table id="tbl_finalinspection_results" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 75%;">
                      <thead>
                        <tr>
                          <th>QC Inspector</th>
                          <th>Inspection Date/Time</th>
                          <th>Result</th>
                          <th>C.O.C Attachment</th>
                          <th>Packing Operator Conformance</th>
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
                          <th>Packing Code</th>
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
          Scan Tray QR Code
          <br>
          <br>
          <h1><i class="fa fa-qrcode fa-lg"></i></h1>
          </div>
          <input type="text" id="modal_scan_tray_qrcode" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>

<div class="modal fade" id="modal_scan_tray_2" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Scan Tray QR Code
          <br>
          <br>
          <h1><i class="fa fa-qrcode fa-lg"></i></h1>
          </div>
          <input type="text" id="modal_scan_tray_qrcode_2" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_scan_tray_3" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Scan DLabel QR Code
          <br>
          <br>
          <h1><i class="fa fa-qrcode fa-lg"></i></h1>
          </div>
          <input type="text" id="modal_scan_tray_qrcode_3" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_scan_tray_casemark" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Scan Casemark QR Code
          <br>
          <br>
          <h1><i class="fa fa-qrcode fa-lg"></i></h1>
          </div>
          <input type="text" id="modal_scan_tray_qrcode_casemark" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_scan_tray_notif" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">

            <p style="font-size:50px; color:red;">Invalid QR Code Details, Please check!</p>

          </div>
        </div>
      </div>
    </div>
  </div>


@endsection

@section('js_content')
<script type="text/javascript">

  let arrayPackingCodeBatch = [];

  let tray_check_list
  let tray_check_list_2
  let dlabel_to_scan
  let casemark_list

  let indexes = []
  let input_text_filtered = []
  let casemark_ship_to = ''
  let casemark_scanned = false

  $(document).ready(function () {
    bsCustomFileInput.init();

    $('#modal_scan_tray_qrcode_3').keypress(function(e) {
      if( e.keyCode == 13 && $('#modal_scan_tray_qrcode_3').val() !='' ){

        let data = $('#modal_scan_tray_qrcode_3').val().split(' ')
        console.log(data)
        if( dlabel_to_scan[0] == data[0] && dlabel_to_scan[1] == (data[3]+' '+data[4]) && dlabel_to_scan[3] == data[5] ){
          $('#tbl_dlabel_scan').css("background-color","#51FF51")
          dlabel_to_scan[4] = 1
          $('#modal_scan_tray_3').modal('hide');
        }else{
          alert('Invalid DLabel.')
        }

      }
    })

    $('#modal_scan_tray_qrcode_casemark').keypress(function(e) {
      if( e.keyCode == 13 && $('#modal_scan_tray_qrcode_casemark').val() !='' ){

        let data = $('#modal_scan_tray_qrcode_casemark').val().split(' ')
        console.log(data)
        // if( dlabel_to_scan[0] == data[0] && dlabel_to_scan[1] == (data[3]+' '+data[4]) && dlabel_to_scan[3] == data[5] ){
        //   $('#tbl_dlabel_scan').css("background-color","#51FF51")
        //   dlabel_to_scan[4] = 1
        //   $('#modal_scan_tray_3').modal('hide');
        // }else{
        //   alert('Invalid DLabel.')
        // }
        indexes = []
        for (var i = 0; i < data.length; i++) {
          if( !isNaN(data[i]) ){
            if( data[i].length == 15 )
              if( (data[i][data[i].length-5] + "" + data[i][data[i].length-4] + data[i][data[i].length-3] + data[i][data[i].length-2] + data[i][data[i].length-1]) == "00010" )
                indexes.push(i)
          }
        }

        input_text_filtered = []
        for (var i = 0; i < indexes.length; i++) {
          let idx = indexes[i]
          input_text_filtered.push( [ data[idx], data[idx+1], data[idx+2], data[idx+3] ] )
        }

        casemark_ship_to = ''
        for (var i = parseInt(indexes[indexes.length-1])+4; i < data.length; i++) {
          casemark_ship_to += data[i]
          if( i < (data.length-1) )
            casemark_ship_to += " "
        }

        // console.log("casemark_ship_to" + " - " + casemark_ship_to)
        // console.log("casemark_ship_to" + " - " + casemark_ship_to.length)

        let index_found = null
        for (var i = 0; i < casemark_list.length; i++) {

          // console.log("casemark_list_" + i + " - " + casemark_list[i][4])
          // console.log("casemark_list_" + i + " - " + casemark_list[i][4].length)

          for (var j = 0; j < input_text_filtered.length; j++) {

            let ship_to = casemark_list[i][4].toLowerCase()
            for (var nn = 1; nn <= 100; nn++)
              ship_to = ship_to.replace(" ", "")

            let _ship_to = casemark_ship_to.toLowerCase()
            for (var nn = 1; nn <= 100; nn++)
              _ship_to = _ship_to.replace(" ", "")

            if( casemark_list[i][0] == input_text_filtered[j][0] && casemark_list[i][1] == input_text_filtered[j][1] && 
                casemark_list[i][2] == input_text_filtered[j][2] && casemark_list[i][3] == input_text_filtered[j][3] &&
                ship_to == _ship_to ){
                index_found = i
                break
              }

          }

          if( index_found != null ){
            casemark_scanned = true
            $('#packing_list_check_list_id_' + index_found).html('scanned')
            $('#tbl_packing_list_scan_' + index_found).css("background-color","#51FF51")
            $('#modal_scan_tray_casemark').modal('hide')
            break
          }

        }

        if( index_found == null )
          alert('Casemark QRCode not match in the list.')

        $('#modal_scan_tray_qrcode_casemark').val('')
      }
    })

    $('#btnSearchOperator').click(function(){

      let is_complete = true
      for (var i = 0; i < tray_check_list.length; i++) {
        if( tray_check_list[i]['stt']==0 ){
          is_complete = false
          break
        }
      }
      if( is_complete ){
        $('#modalSearchOperator').modal('show');
        $('#txt_operator_employee_id').val('');
        $('#txt_operator_employee_id').focus();
      }else{
          alert('All tray/box are need to be scan.')
      }

    });

    $('#btnSearchInspector').click(function(){

      let is_complete = true
      for (var i = 0; i < tray_check_list.length; i++) {
        if( tray_check_list[i]['stt']==0 ){
          is_complete = false
          break
        }
      }
      if( !is_complete ){
        alert( 'Packing Operator is not yet done in scanning all trays.' )
        return
      } 

      is_complete = true
      for (var i = 0; i < tray_check_list_2.length; i++) {
        if( tray_check_list_2[i]['stt']==0 ){
          is_complete = false
          break
        }
      }
      if( is_complete ){
        $('#modalSearchInspector').modal('show');
        $('#txt_employee_id').val('');
        $('#txt_employee_id').focus();
      }else{
          alert('All tray/box are need to be scanned.')
      }

    });

    $('#btn_scan_tray').click(function() {

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

    $('#btn_scan_tray_2').click(function() {

      // let is_complete2 = true
      // for (var i = 0; i < tray_check_list.length; i++) {
      //   if( tray_check_list[i]['stt']==0 ){
      //     is_complete2 = false
      //     break
      //   }
      // }
      // if( !is_complete2 ){
      //   alert('Packing Operator is not yet done in scanning of all trays.' )
      //   return
      // } 

      // if( dlabel_to_scan[4] == 0 ){
      //   alert( 'DLabel is not yet done to scan.' )
      //   return
      // }

      if( !casemark_scanned ){
        alert( 'Casemark is not yet done to scan.' )
        return
      }

      is_complete2 = true
      for (var i = 0; i < tray_check_list_2.length; i++) {
        if( tray_check_list_2[i]['stt']==0 ){
          is_complete2 = false
          break
        }
      }
      if( is_complete2 ){
        alert( 'All tray already scanned (OQC).' )
        return
      } 

      $('#modal_scan_tray_2').modal('show')
      $('#modal_scan_tray_qrcode_2').val('')
      $('#modal_scan_tray_qrcode_2').focus()
    })

    $('#btn_scan_tray_3').click(function() {

      let is_complete2 = true
      for (var i = 0; i < tray_check_list.length; i++) {
        if( tray_check_list[i]['stt']==0 ){
          is_complete2 = false
          break
        }
      }
      if( !is_complete2 ){
        alert('Packing Operator is not yet done in scanning of all trays.' )
        return
      }

      if( dlabel_to_scan[4] == 1 ){
        alert( 'DLabel already scanned.' )
        return
      } 

      $('#modal_scan_tray_3').modal('show')
      $('#modal_scan_tray_qrcode_3').val('')
      $('#modal_scan_tray_qrcode_3').focus()
    })
    
    $(document).on('keypress',function(e){
      if( ($("#modal_scan_tray_3").data('bs.modal') || {})._isShown ){
        $('#modal_scan_tray_qrcode_3').focus();

        if( e.keyCode == 13 && $('#modal_scan_tray_qrcode_3').val() !='' && ($('#modal_scan_tray_qrcode_3').val().length >= 4) ){
            $('#modal_scan_tray_3').modal('hide');
          }
        }
    });

    $('#btn_scan_tray_casemark').click(function() { 

      if( dlabel_to_scan[4] == 0 ){
        alert( 'DLabel is not yet done to scan.' )
        return
      }

      if( casemark_scanned ){
        alert( 'Casemark is already scanned.' )
        return
      }

      // let is_complete2 = true
      // for (var i = 0; i < tray_check_list.length; i++) {
      //   if( tray_check_list[i]['stt']==0 ){
      //     is_complete2 = false
      //     break
      //   }
      // }
      // if( !is_complete2 ){
      //   alert('Packing Operator is not yet done in scanning of all trays.' )
      //   return
      // }

      // if( dlabel_to_scan[4] == 1 ){
      //   alert( 'DLabel already scanned.' )
      //   return
      // } 

      $('#modal_scan_tray_casemark').modal('show')
      $('#modal_scan_tray_qrcode_casemark').val('')
      $('#modal_scan_tray_qrcode_casemark').focus()
    })
    
    $(document).on('keypress',function(e){
      if( ($("#modal_scan_tray_casemark").data('bs.modal') || {})._isShown ){
        $('#modal_scan_tray_qrcode_casemark').focus();

        if( e.keyCode == 13 && $('#modal_scan_tray_qrcode_casemark').val() !='' && ($('#modal_scan_tray_qrcode_casemark').val().length >= 4) ){
            $('#modal_scan_tray_casemark').modal('hide');
          }
        }
    });
    
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

        let data = $('#modal_scan_tray_qrcode').val().split(' ')
        let data_cnt = data.length
          console.log( data )
        let index = null
        for (var i = 0; i < tray_check_list.length; i++) {

          // if( data.length == 6 ){
          //   if( tray_check_list[i]['po_no'] == data[0] && tray_check_list[i]['lot_no'] == data[2] && tray_check_list[i]['qtt'] == data[4] && tray_check_list[i]['counter'] == data[5] ){
          //     index = i
          //   }
          // }else{
          //   if( tray_check_list[i]['po_no'] == data[0] && tray_check_list[i]['lot_no'] == data[3] && tray_check_list[i]['qtt'] == data[5] && tray_check_list[i]['counter'] == data[6] ){
          //     index = i
          //   }
          // }

          if( tray_check_list[i]['po_no'] == data[0] && tray_check_list[i]['lot_no'] == (data[2]+' '+data[3]) && tray_check_list[i]['qtt'] == data[data_cnt-2] && tray_check_list[i]['counter'] == data[data_cnt-1] )
            index = i

        }
        if( index!=null ){
          if( tray_check_list[index]['stt'] == 1 ){
            alert('Tray was already scanned.')
            $('#modal_scan_tray_qrcode').val('')
            $('#modal_scan_tray_qrcode').focus()
          }else{
            tray_check_list[index]['stt'] = 1
            $('#tray_check_list_id_' + index).html('scanned')
            $('#tray_check_list_tr_id_' + index).css("background-color","#51FF51")

            let ttl = 0
            for (var i = 0; i < tray_check_list.length; i++) {
              if( tray_check_list[i]['stt']==1 )
                ttl += tray_check_list[i]['qtt']
            }
            $('#tblTrayChecker_ttl_quantity_scanned').html(ttl)
            if( $('#tblTrayChecker_ttl_quantity').html() == $('#tblTrayChecker_ttl_quantity_scanned').html() )
              $('#modal_scan_tray').modal('hide')

            $('#modal_scan_tray_qrcode').val('')
            $('#modal_scan_tray_qrcode').focus()
          }
        }else{
          // alert('Invalid details, please check.')

          $('#modal_scan_tray_notif').modal('show')
          $('#modal_scan_tray_qrcode').val('')
          $('#modal_scan_tray_qrcode').focus()
        }

      }
    })
    
    /*added by Nessa*/
    $(document).on('keypress',function(e){
      if( ($("#modal_scan_tray_2").data('bs.modal') || {})._isShown ){
        $('#modal_scan_tray_qrcode_2').focus();

        if( e.keyCode == 13 && $('#modal_scan_tray_qrcode_2').val() !='' && ($('#modal_scan_tray_qrcode_2').val().length >= 4) ){
            $('#modal_scan_tray_2').modal('hide');
          }
        }
    });

    $('#modal_scan_tray_qrcode_2').keypress(function(e) {
      if( e.keyCode == 13 && $('#modal_scan_tray_qrcode_2').val() !='' ){

        let is_complete = true
        for (var i = 0; i < tray_check_list_2.length; i++) {
          if( tray_check_list_2[i]['stt']==0 ){
            is_complete = false
            break
          }
        }
        if( is_complete ){
          alert( 'All tray already scanned.' )
          return
        } 

        let data = $('#modal_scan_tray_qrcode_2').val().split(' ')
        let data_cnt = data.length
          console.log( data )
        let index = null
        for (var i = 0; i < tray_check_list_2.length; i++) {

          // if( data.length == 6 ){
          //   if( tray_check_list_2[i]['po_no'] == data[0] && tray_check_list_2[i]['lot_no'] == data[2] && tray_check_list_2[i]['qtt'] == data[4] && tray_check_list_2[i]['counter'] == data[5] ){
          //     index = i
          //   }
          // }else{
          //   if( tray_check_list_2[i]['po_no'] == data[0] && tray_check_list_2[i]['lot_no'] == data[3] && tray_check_list_2[i]['qtt'] == data[5] && tray_check_list_2[i]['counter'] == data[6] ){
          //     index = i
          //   }
          // }

          if( tray_check_list_2[i]['po_no'] == data[0] && tray_check_list_2[i]['lot_no'] == (data[2]+' '+data[3]) && tray_check_list_2[i]['qtt'] == data[data_cnt-2] && tray_check_list_2[i]['counter'] == data[data_cnt-1] )
            index = i

        }
        if( index!=null ){
          if( tray_check_list_2[index]['stt'] == 1 ){
            alert('Tray was already scanned.')
            $('#modal_scan_tray_qrcode_2').val('')
            $('#modal_scan_tray_qrcode_2').focus()
          }else{
            tray_check_list_2[index]['stt'] = 1
            $('#tray_check_list_id_' + index + '_2').html('scanned')
            $('#tray_check_list_tr_id_' + index + '_2').css("background-color","#51FF51")

            let ttl = 0
            for (var i = 0; i < tray_check_list_2.length; i++) {
              if( tray_check_list_2[i]['stt']==1 )
                ttl += tray_check_list_2[i]['qtt']
            }
            $('#tblTrayChecker_ttl_quantity_scanned_2').html(ttl)
            if( $('#tblTrayChecker_ttl_quantity_2').html() == $('#tblTrayChecker_ttl_quantity_scanned_2').html() )
              $('#modal_scan_tray_2').modal('hide')

            $('#modal_scan_tray_qrcode_2').val('')
            $('#modal_scan_tray_qrcode_2').focus()
          }
        }else{
          // alert('Invalid details, please check.')
          $('#modal_scan_tray_notif').modal('show')
          $('#modal_scan_tray_qrcode_2').val('')
          $('#modal_scan_tray_qrcode_2').focus()
        }

      }
    })

     GetUserList($(".selectUser"));
      $('.selectUser').select2({
            theme: 'bootstrap4'
          });

      dt_final_inspection = $('#tbl_final_inspection').DataTable({
          "processing"    : false,
          "serverSide"  : true,
          "ajax"        : 
          {
            url: "load_finalpacking_pts_table",
              data: function (param){
                param.po_num = $("#txt_search_po_number").val().split(' ')[0];
                }
          },

          "columns":[
            { "data" : "action", orderable:false, searchable:false, width: "150px" },
            { "data" : "device_code" },
            { "data" : "lot_no" },
            { "data" : "lot_qty" , width: "100px" },
            { "data" : "supervisor" },
            { "data" : "oqc_stamp" }

          ],

      });

      dt_finalinspection_results = $('#tbl_finalinspection_results').DataTable({

          "processing"  : false,
          "serverSide"  : true,
          "ajax"        : 
          {
            url: "load_final_inspection_pts_results",
              data: function (param){
                param.lotapp_id = $('#view_lotapp_id').val();
                }
          },

          "columns":[
            { "data" : "inspector"},
            { "data" : "inspection_datetime" },
            { "data" : "result"},
            { "data" : "coc_attachment" },
            { "data" : "operator_conformance" },
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

        if( e.keyCode == 13 ){

          $('#id_po_no').val('');
          $('#id_device_name').val('');
          $('#txt_device_code_lbl').val('');
          $('#id_po_qty').val('');

          var data = {
          'po'      : $('#txt_search_po_number').val().split(' ')[0]
          }

          data = $.param(data);

        $.ajax({
          type      : "get",
          dataType  : "json",
          data      : data,
          url       : "get_po_details",
          beforeSend: function(){

            $('#id_po_no').val('-- Data Loading --');
            $('#id_device_name').val('-- Data Loading --');
            $('#txt_device_code_lbl').val('-- Data Loading --');
            $('#id_po_qty').val('-- Data Loading --');

          },
          success : function(data){

            dt_final_inspection.draw();

            $('#id_po_no').val( data['po_details'][0]['po_no'] );
            $('#id_device_name').val( data['po_details'][0]['wbs_kitting']['device_name'] );
            $('#txt_device_code_lbl').val( data['po_details'][0]['wbs_kitting']['device_code'] );
            $('#add_series_name').val(data['po_details'][0]['wbs_kitting']['device_name']);
            $('#id_po_qty').val( data['po_details'][0]['wbs_kitting']['po_qty'] );

          },error : function(data){

            $('#id_po_no').val('-- Data Error, Please Refresh --');
            $('#id_device_name').val('-- Data Error, Please Refresh --');
            $('#txt_device_code_lbl').val('-- Data Error, Please Refresh --');
            $('#id_po_qty').val('-- Data Error, Please Refresh --');

          }

        }); 


        }
    });

   $(document).on('click','.btn-final-inspection', function(){

    let lotapp_id = $(this).attr('lotapp-id');

    TSPTSViewLotAppDetails(lotapp_id);
   });

   $('#btnSubmitInspection').click(function(){

    let is_complete = true
    for (var i = 0; i < tray_check_list.length; i++) {
      if( tray_check_list[i]['stt']==0 ){
        is_complete = false
        break
      }
    }

    if( !is_complete ){
      alert( 'Tray in packing operator is/are need to scan.' )
      return
    }

    if( dlabel_to_scan[4] == 0 ){
      alert( 'DLabel is not yet done to scan.' )
      return
    }

    if( !casemark_scanned ){
      alert( 'Casemark is not yet done to scan.' )
      return
    }

    let is_complete_2 = true
    for (var i = 0; i < tray_check_list_2.length; i++) {
      if( tray_check_list_2[i]['stt']==0 ){
        is_complete_2 = false
        break
      }
    }

    if( !is_complete_2 ){
      alert( 'Tray in oqc inspector is/are need to scan.' )
      return
    }

    if( is_complete && is_complete_2 ){
      $('#formFinalPackingInspection').submit();
    }else{
      alert('All trays are need to be scanned')
    }

   });

   $('#formFinalPackingInspection').submit(function(e){

    e.preventDefault();
    TSPTSSubmitFinalPackingInspection();

   });

    $(document).on('click','.btn-view-application', function(){

    let lotapp_id = $(this).attr('lotapp-id');
    $('#view_lotapp_id').val(lotapp_id);
    dt_finalinspection_results.draw();
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

function GetOperatorDetails(employee_id)
{
  $.ajax({
    url: "load_user_details",
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
      if(JsonObject['result'] == 1)
      {
        $('#add_packing_operator_name').val(JsonObject['user_details'][0].id);
        $('#add_packing_operator_name2').val(JsonObject['user_details'][0].name);
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
    if( ($("#modalSearchOperator").data('bs.modal') || {})._isShown ){
      $('#txt_operator_employee_id').focus();

      if( e.keyCode == 13 && $('#txt_operator_employee_id').val() !='' && ($('#txt_operator_employee_id').val().length >= 4) ){

          $('#modalSearchOperator').modal('hide');

            $.ajax({
              url: "employee_id_checker",
              method: "get",
              data:
              {
                employee_id: $('#txt_operator_employee_id').val(),
                // position: 4,
                user_level_id: 6,
              },
              dataType: "json",
              success: function(JsonObject)
              {
                if(JsonObject['result'] == 1)
                  GetOperatorDetails($('#txt_operator_employee_id').val());
                else if(JsonObject['result'] == 0)
                  toastr.error('Scanned Employee ID is not Packing Operator.');
                else
                  toastr.error(JsonObject['error_msg']);
              },
              error: function(data, xhr, status){
                toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
              }

            });

          // GetOperatorDetails($('#txt_operator_employee_id').val());
          
        }
      }
  }); 

//----------------------------------------------------------------------



function GetInspectorDetails(employee_id)
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
      if(JsonObject['result'] == 1)
      {
        $('#add_oqc_inspector_name').val(JsonObject['user_details'][0].id);
        $('#add_oqc_inspector_name2').val(JsonObject['user_details'][0].name);
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
                  GetInspectorDetails($('#txt_employee_id').val());
                else if(JsonObject['result'] == 0)
                  toastr.error('Scanned Employee ID is not a OQC Inspector.');
                else
                  toastr.error(JsonObject['error_msg']);
              },
              error: function(data, xhr, status){
                toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
              }

            });

          // GetInspectorDetails($('#txt_employee_id').val());
          
        }
      }
  }); 

$('#btn_download').click(function(){
    window.open('public/storage/file_templates/user_manual/TS PTS User Manual - Final QC Packing Inspection.pdf','_blank');
  }); 

</script>
@endsection
@endauth