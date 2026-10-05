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

@section('title', 'Packing Confirmation')

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
          <h1>Packing Confirmation</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Packing Confirmation</li>
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
                <button type="button" data-toggle="modal" data-target="#modalPackingConfirmation">test</button>
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
                <h3 class="card-title">2. Packing Confirmation Summary</h3>
              </div>
                <div class="card-body">
                  <div class="table-responsive dt-responsive">
                      <table id="tbl_packing_confirmation" class="table table-bordered table-striped table-hover" style="width: 100%;">
                          <thead>
                            <tr>
                              <th>Action</th>
                              <!-- <th>Packing Code</th> -->
                              <th>Lot Number</th>
                              <th>Lot Qty</th>
                              <th>Packing Operator</th>
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



<div class="modal fade" id="modalPackingConfirmation">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">Packing Confirmation (Responsible: Packing Operator)</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </div>

      <form id="formPackingConfirmation" method="post">
      @csrf

        <div class="modal-body">

          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">PO Number</span>
                </div>
                <input type="hidden" name="confirmed_partial" id="confirmed_partial" value="0">
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
              <!-- <h5 class="card-title">Accessory</h5> -->
               <div class="row">
                <div class="col">
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

          <div class="card card-primary">
            <div class="card-header">
              <h5 class="card-title">List of Tray's</h5>

             <div class="row" >
                <div class="col">
                   <button type="button" class="btn btn-success btn-sm" id="btn_scan_tray" style="float: right;">Scan Tray</button>
                </div>
              </div>

               <div class="row">
                  <div class="col">
                     <div class="table-responsive dt-responsive">
                        <table id="tblTray" class="table table-bordered" style="width: 100%; font-size: 75%">
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
                                <th style="padding: 5px; width: 15%; text-align: center; background-color:#51FF51;" id="tblTrayChecker_ttl_quantity_scanned"></th>
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
                  <span class="input-group-text w-100" id="basic-addon1">Series Name on QA Application VS. Label Tally?</span>
                </div>
                <select class="form-control form-control-sm" id="add_series_v_label" name="add_series_v_label">
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
                    <span class="input-group-text w-100" id="basic-addon1">Series Name on Label VS. Actual Product Tally?</span>
                  </div>
                  <select class="form-control form-control-sm" id="add_label_v_actual" name="add_label_v_actual">
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
                        <span class="input-group-text w-100" id="basic-addon1">Actual Packing VS. Packing Document Tally?</span>
                        </div>
                        <select class="form-control form-control-sm" id="add_actual_v_packing_doc" name="add_actual_v_packing_doc">
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

            <div class="row">
              <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">No. of Tray/Boxes</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="add_packing_conf_no_of_tray_boxes" name="add_packing_conf_no_of_tray_boxes" readonly>
                </div>
              </div>
            </div>

            <div class="row">
               <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Packing Operator Name</span>

                     <input type="hidden" id="add_packing_operator_name" name="add_packing_operator_name">
                  </div>
                   <input type="text" class="form-control" id="add_packing_operator_name2" name="add_packing_operator_name2" readonly>

                  <div class="input-group-prepend">
                    <button type="button" class="btn btn-info btn-sm" id="btnSearchInspector" data-toggle="modal" data-target="#modalSearchInspector" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                    <button type="button" class="btn btn-danger btn-sm" id="btnPopLastOperator" title="Remove Last Operator"><i class="fa fa-retweet"></i></button>
                  </div>

                </div>
              </div>
            </div>

            <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">Confirmation Date/Time</span>
                </div>
                 <input type="text" class="form-control form-control-sm" id="add_confirmation_datetime" name="add_confirmation_datetime" readonly="true" placeholder="Auto generated">

              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">Remarks (Optional)</span>
                </div>
                <input type="text" class="form-control form-control-sm" id="add_packing_conf_remarks" name="add_packing_conf_remarks">
              </div>
            </div>
          </div>


        </div>

      </form>

      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-success" id="btnSubmitConfirmation">Submit</button>
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
         <h4 class="modal-title">Packing Confirmation (Responsible: Packing Operator)</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </div>

      <div class="modal-body">

          <input type="hidden" id="view_lotapp_id">

          <div class="card card-primary">

            <div class="card-header">
              <h5 class="card-title">Confirmation Summary</h5>
            </div>

            <div class="card-body">
              <div class="table-responsive dt-responsive">
                  <table id="tbl_packing_confirmation_results" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 75%;">
                      <thead>
                        <tr>
                          <th>Packing Operator</th>
                          <th>Confirmation Date/Time</th>
                          <th>Series V. Label</th>
                          <th>Label V. Actual</th>
                          <th>Silica Gel / Anti-Rust</th>
                          <th>No. of Tray/Boxes</th>
                          <th>YD Label</th>
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

  <div class="modal fade" id="modal_print_qrcode">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Packing Confirmation - QR Code</h4>
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

            {{-- <p style="font-size:50px; color:red;">Invalid QR Code Details, Please check!</p> --}}
            <p style="font-size:50px;"><strong>Invalid QR Code Details found,</strong></p>
            <p style="font-size:50px; color:red;"><strong>CALL THE ATTENTION OF IMMEDIATE SUPERVISOR</strong></p>

          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_scan_tray_sorted_notif" tabindex="-1" style="overflow-y: auto;">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">

            <p style="font-size:50px;"><strong>Invalid TRAYS QR Code Details found,</strong></p>
            <p style="font-size:50px; color:red;"><strong>CALL THE ATTENTION OF IMMEDIATE SUPERVISOR</strong></p>

          </div>
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
  let arrayPackingOperators = [];
  let arrayPackingOperatorsName = [];

  let draw_lotapp_id
  let draw_device_name

  let tray_check_list

  $(document).ready(function () {


    $('#btn_scan_tray').click(function() {
        console.log('btn_scan_tray_pts');
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
                    console.log('nabaril na!');
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

    // $('#modalScan_Drawing').modal({
    //   backdrop: 'static',
    //   keyboard: false,
    //   show: true
    // });

    bsCustomFileInput.init();

     GetUserList($(".selectUser"));
      $('.selectUser').select2({
            theme: 'bootstrap4'
          });

      dt_packing_confirmation = $('#tbl_packing_confirmation').DataTable({
          "processing"    : false,
          "serverSide"  : true,
          "ajax"        :
          {
            url: "load_packingconfirmation_pts_table",
              data: function (param){
                  param.po_no = $('#id_po_no').val();
                }
          },

          "columns":[
            { "data" : "action", orderable:false, searchable:false, width: "100px" },
            // { "data" : "packing_code" },
            { "data" : "lot_no" },
            { "data" : "lot_qty" , width: "100px" },
            { "data" : "packing_operator" },

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
          ],
      });

      // dt_packing_accessories = $('#tbl_packing_confirmation_accessories').DataTable()
      // dt_packing_accessories.destroy()
      // dt_packing_accessories = $('#tbl_packing_confirmation_accessories').DataTable()


      dt_packing_confirmation_results = $('#tbl_packing_confirmation_results').DataTable({

         "processing"    : false,
          "serverSide"  : true,
          "ajax"        :
          {
            url: "load_packing_confirmation_pts_results",
              data: function (param){
                param.lotapp_id = $("#view_lotapp_id").val();
                }
          },

          "columns":[
            { "data" : "operator" },
            { "data" : "confirmation_datetime" },
            { "data" : "series_v_label" },
            { "data" : "label_v_actual" },
            { "data" : "silica_gel" },
            { "data" : "no_tray_boxes" },
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

  $(document).on('click','.btnPrintQRCode', function(){

      $.ajax({
          data        : { id: $(this).attr('prod_id'), inspector_code: $(this).attr('packing-code') },
          type        : 'get',
          dataType    : 'json',
          url         : "generate_qrcode_for_packing_confirmation",
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
                        dt_packing_confirmation.draw();
                    }, 500);

                }
            }
        } catch (error) {
            alert(`Error: ${error}`)
        }
    });

   $(document).on('click','.btn-packing-confirmation',function(){

      draw_lotapp_id = $(this).attr('lotapp-id');
      draw_device_name = $('#id_device_name').val();

      // TSPTSViewPackingConfirmationDetails(lotapp_id, device_name);

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

      // dt_packing_accessories.destroy()
      // dt_packing_accessories = $('#tbl_packing_confirmation_accessories').DataTable({
      //     "processing"    : false,
      //     "serverSide"  : true,
      //     "ajax"        :
      //     {
      //       url: "load_accessories_pts_table",
      //         data: function (param){
      //           param.lotapp_id = $("#add_lot_id").val();
      //           }
      //     },
      //     "columns":[
      //       { "data" : "accessory_name" },
      //       { "data" : "quantity" , width: "50px" },
      //     ],
      // });
      // dt_packing_accessories.order( [ [0, 'asc'] ] )
   });

    // $("#btnView_orig_a_drawing").click(function(){
    //   redirect_to_drawing( $('#modalScan_Drawing_OrigAdrawing_no').val(), 0 )
    // });

    // $("#btnView_a_drawing").click(function(){
    //   redirect_to_drawing( $('#modalScan_Drawing_Adrawing_no').val(), 1 )
    // });

    // $("#btnView_g_drawing").click(function(){
    //   redirect_to_drawing( $('#modalScan_Drawing_Gdrawing_no').val(), 2 )
    // });

    // $("#btnView_o_drawing").click(function(){
    //   redirect_to_drawing( $('#modalScan_Drawing_Odrawing_no').val(), 3 )
    // });


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

      TSPTSViewPackingConfirmationDetails(draw_lotapp_id, draw_device_name);

      $.ajax({
        url: "getTrayListByLotAppID",
        method: "get",
        data:
        {
          lotapp_id: draw_lotapp_id,
        },
        dataType: "json",
        success: function(JsonObject)
        {
            tray_check_list = JsonObject['data']
            let html = ""
            let ttl_qtt = 0
            for (var i = 0; i < tray_check_list.length; i++) {
                html += "<tr id='tray_check_list_tr_id_" + i + "'>"
                html +=     "<td style='padding: 5px; width: 15%;'>" + tray_check_list[i]['po_no'] + "</td>"
                html +=     "<td style='padding: 5px; width: 15%;'>" + tray_check_list[i]['lot_no'] + "</td>"
                html +=     "<td style='padding: 5px; width: 15%;'>" + tray_check_list[i]['qtt'] + "</td>"
                html +=     "<td style='padding: 5px; width: 15%;'>" + tray_check_list[i]['counter'] + "</td>"
                html +=     "<td style='padding: 5px; width: 15%;' id='tray_check_list_id_" + i + "'>pending</td>"
                html += "</tr>"

                ttl_qtt += tray_check_list[i]['qtt']
            }

            $('#tblTrayChecker').html(html)
            $('#tblTrayChecker_ttl_quantity').html(ttl_qtt)
            $('#tblTrayChecker_ttl_quantity_scanned').html(0)

            // $("#modalScan_Drawing").modal('show')
            // $("#modalRuncardDetails").modal('show')

            $("#modalScan_Drawing").modal('hide')
            $('#modalPackingConfirmation').modal('show')

            // checked_draw_count_reset()

            //10292021
            var rowCount = $('#tblTray >tbody >tr').length;
            $('#add_packing_conf_no_of_tray_boxes').val(rowCount);

        }

      });



      // $("#modalScan_Drawing").modal('hide')
      // $('#modalPackingConfirmation').modal('show')

    });


   $('#btnSubmitConfirmation').click(function(){

        let is_complete = true
        for (var i = 0; i < tray_check_list.length; i++) {
          if( tray_check_list[i]['stt']==0 ){
            is_complete = false
            break
          }
        }
        if( is_complete ){
          $('#formPackingConfirmation').submit();
        } else{
          alert('All tray/box are need to be scan.')
        }


   });

   $('#formPackingConfirmation').submit(function(e){

      e.preventDefault();
      TSPTSSubmitPackingConfirmation();

   });

    $('#modalPackingConfirmation').on('hidden.bs.modal',function(){
      $('#formPackingConfirmation')[0].reset();
      $('#add_series_v_label').removeClass('is-invalid');
      $('#add_label_v_actual').removeClass('is-invalid');
      $('#add_silica_gel').removeClass('is-invalid');
      $('#add_packing_operator_name').removeClass('is-invalid');
      $('#add_confirmation_datetime').removeClass('is-invalid');

      arrayPackingOperators = [];
      arrayPackingOperatorsName = [];
  });

  $(document).on('click','.btn-view-application', function(){

    let lotapp_id = $(this).attr('lotapp-id');
    $('#view_lotapp_id').val(lotapp_id);
    dt_packing_confirmation_results.draw();
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
  let device_name = $('#id_device_name').attr('device_name_print');

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
            content += '<label style="text-align: center; font-family: Arial; font-size: 10px;">' + packing_code + '</label>';
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
            content += '<label style="text-align: center; font-family: Arial; font-size: 10px;">' + device_name + '</label>';
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
        alert('No Required')
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

  function GetOperatorDetails(employee_id, array_operators, array_operators_name)
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
        let operator_names

        if(!array_operators.includes(JsonObject['user_details'][0].id))
        {
          array_operators.push(JsonObject['user_details'][0].id);
          array_operators_name.push(JsonObject['user_details'][0].name);

          $('#add_packing_operator_name').val(array_operators.toString());
          $('#add_packing_operator_name2').val(array_operators_name.toString());
        }
        else
        {
          toastr.error('Operator already added!');
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

      // alert($('#txt_employee_id').val());

          $.ajax({
            url: "employee_id_checker",
            method: "get",
            data:
            {
              employee_id: $('#txt_employee_id').val(),
              // position: 4,
              user_level_id: 6,
            },
            dataType: "json",
            success: function(JsonObject)
            {
              if(JsonObject['result'] == 1){
                GetOperatorDetails(JsonObject['emp_id'], arrayPackingOperators, arrayPackingOperatorsName);
              }
              else if(JsonObject['result'] == 0){
                toastr.error('Scanned Employee ID is not Packing Operator.');
              }
              else{
                toastr.error(JsonObject['error_msg']);
              }
            },
            error: function(data, xhr, status){
              toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            }

          });
          $('#modalSearchInspector').modal('hide');

        // GetOperatorDetails($('#txt_employee_id').val(), arrayPackingOperators, arrayPackingOperatorsName);

      }
    }
});

  $('#btnPopLastOperator').click(function(){

    arrayPackingOperators.pop();
    arrayPackingOperatorsName.pop();

    $('#add_packing_operator_name').val(arrayPackingOperators.toString());
    $('#add_packing_operator_name2').val(arrayPackingOperatorsName.toString());

  });

  $('#btn_download').click(function(){
    window.open('public/storage/file_templates/user_manual/TS PTS User Manual - Packing Confirmation.pdf','_blank');
  });

  $(document).on('keydown',function(e){
    if ($("#txt_employee_id").is(":focus")) {
      setTimeout(function() {
        $("#txt_employee_id").val('');
      }, 300);
    }
  });


</script>
@endsection
@endauth
