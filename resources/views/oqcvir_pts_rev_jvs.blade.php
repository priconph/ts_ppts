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

@section('title', 'QC Visual Inspection')

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
          <h1>OQC Inspection Result (jvs)</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">OQC Inspection Result</li>
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
<!--               <div class="card-header">
                <h3 class="card-title">Search PO Number</h3>
              </div>
 -->
 <!--  <div class="card-header">


              <div class="float-sm-right">
                <button type="button" data-toggle="modal" data-target="#modalAddInspection">test</button>
              </div>

            </div>
 -->
            <!-- Start Page Content -->
              <div class="card-body">
                  <div class="row">
                    <div class="col-sm-3">
                      <label>Scan PO Number</label>
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
                    <div class="col-sm-2">
                    </div>
                  <!-- <div class="col-sm-3">
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
                  </div> -->

                  <br>
              </div>
              <!-- !-- End Page Content -->
          </div>
          <!-- /.card -->

        </div>
      </div>
      <!-- /.row -->

    </div><!-- /.container-fluid -->

  </section>

      <!-- <button id="show_modal">show modal</button> -->

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Visual Inspection Result / Stamp Affixing</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <div class="table-responsive dt-responsive">
                        <table id="tbl_oqcvir" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 85%;">
                            <thead>
                              <tr>
                                <!-- <th>Action</th>
                                <th>Lot</th>
                                <th>Lot Quantity</th>
                                <th>Inspected By</th>
                                <th>Status</th> -->
                                <th></th>
                                <th>FY-WW</th>
                                <th>Date Inspected</th>
                                <th>Lot No</th>
                                <!-- <th>P.O.</th> -->
                                <!-- <th>Device Name</th> -->
                                <th>From</th>
                                <th>To</th>
                                <th># of Sub</th>
                                <th>Lot Size</th>
                                <th>Sample Size</th>
                                <th>No of Defective</th>
                                <th>Mode of Defects</th>
                                <th>Qty</th>
                                <th>Judgement</th>
                                <th>Inspector</th>
                                <th>OQC Stamp</th>
                                <th>Type</th>
                                <th>Remarks</th>
                              </tr>
                            </thead>
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
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-success" id="btnSubmitInspection">Submit Inspection</button>
        </div> -->
      </div>
    </div>
  </div>


<div class="modal fade" id="modalAddInspection">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">OQC Inspection Result (Responsible: OQC Inspector)</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </div>

      <form id="formAddInspection" method="post">
      @csrf

        <div class="modal-body">

          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">PO NUMBER</span>
                </div>
                <input type="text" class="form-control form-control-sm" id="add_po_no" name="add_po_no" readonly>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">LOT NUMBER</span>
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
                  <span class="input-group-text w-100" id="basic-addon1">TOTAL LOT QTY</span>
                </div>
                <input type="text" class="form-control form-control-sm" id="add_lot_qty" name="add_lot_qty" readonly>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">OQC SAMPLE SIZE</span>
                </div>
                <input type="text" class="form-control form-control-sm" id="add_oqc_sample_size" name="add_oqc_sample_size">
              </div>
            </div>

            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">OK QTY</span>
                </div>
                <input type="text" class="form-control form-control-sm" id="add_ok_qty" name="add_ok_qty" disabled>
              </div>
            </div>

            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">NG QTY</span>
                </div>
                <input type="text" class="form-control form-control-sm" id="add_ng_qty" name="add_ng_qty" readonly>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">OQC INSPECTION START DATE/TIME</span>
                </div>
                <input type="datetime-local" class="form-control form-control-sm" id="add_inspection_starttime" name="add_inspection_starttime">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">OQC INSPECTION END DATE/TIME</span>
                </div>
                <input type="datetime-local" class="form-control form-control-sm" id="add_inspection_datetime" name="add_inspection_datetime">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">USE OF TERMINAL OR MINIMUM GAUGE / TEMPLATE OR DUMMY</span>
                </div>
                <select class="form-control form-control-sm" id="add_terminal" name="add_terminal">
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
                    <span class="input-group-text w-100" id="basic-addon1">YD LABEL REQUIREMENT</span>
                  </div>
                  <select class="form-control form-control-sm" id="add_yd_label" name="add_yd_label">
                    <option selected disabled>-- Choose One --</option>
                    <option value='1'>With</option>
                    <option value='2'>Without</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
               <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">CSH COATING</span>
                  </div>
                  <select class="form-control form-control-sm" id="add_csh_coating" name="add_csh_coating">
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
                    <span class="input-group-text w-100" id="basic-addon1">ACCESSORIES REQUIREMENT</span>
                  </div>
                  <select class="form-control form-control-sm" id="add_accessories_requirement" name="add_accessories_requirement">
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
                    <span class="input-group-text w-100" id="basic-addon1">C.O.C. REQUIREMENT</span>
                  </div>
                  <select class="form-control form-control-sm" id="add_coc_requirement" name="add_coc_requirement">
                    <option selected disabled>-- Choose One --</option>
                    <option value='1'>YES</option>
                    <option value='2'>NO</option>
                    <option value='3'>N/A</option>
                  </select>
                </div>
              </div>
            </div>

            <hr>

            <div class="row">
               <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">RESULT</span>
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
                    <span class="input-group-text w-100" id="basic-addon1">REMARKS</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="add_remarks" placeholder="(Optional)" name="add_remarks">
                </div>
              </div>
            </div>

            <!-- <div class="row">
               <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">OQC INSPECTOR NAME</span>
                  </div>
                  <select class="form-control form-control-sm selectUser" id="add_oqc_inspector_name" name="add_oqc_inspector_name">
                    <option selected disabled>-- Choose One --</option>
                  </select>
                </div>
              </div>
            </div> -->

            <div class="row">
               <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">OQC INSPECTOR NAME</span>

                    <input type="hidden" id="add_oqc_inspector_name" name="add_oqc_inspector_name">

                  </div>
                 <!--  <select class="form-control form-control-sm selectUser" id="add_oqc_inspector_name" name="add_oqc_inspector_name">
                    <option selected disabled>-- Choose One --</option>
                  </select> -->
                  <input type="text" class="form-control" id="add_oqc_inspector_name2" name="add_oqc_inspector_name2" readonly>

                  <div class="input-group-prepend">
                    <button type="button" class="btn btn-info btn-sm" id="btnSearchInspector" data-toggle="modal" data-target="#modalSearchInspector" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                  </div>
                </div>
              </div>
            </div>

        </div>

      </form>

      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-success" id="btnSubmitInspection">Submit Inspection</button>
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
         <h4 class="modal-title"><i class="fa fa-eye"></i> Visual Inspection Result (Responsible: OQC Inspector)</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </div>

      <div class="modal-body">

          <input type="hidden" id="view_lotapp_id">

         <div class="card card-primary">

            <div class="card-header">
              <h5 class="card-title">Inspection Results</h5>
            </div>

            <div class="card-body">
              <div class="table-responsive dt-responsive">
                  <table id="tbl_oqcvir_results" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 75%;">
                      <thead>
                        <tr><!--
                          <th></th>
                          <th>Result</th>
                          <th>Inspection Start Date/Time</th>
                          <th>Inspection End Date/Time</th>
                          <th>Inspector Name</th>
                          <th>OK QTY / SAMPLE</th>
                          <th>Terminal Use</th>
                          <th>YD Label Req.</th>
                          <th>CSH Coating</th>
                          <th>Accessory Req.</th>
                          <th>C.O.C. Req.</th> -->
                                <th></th>
                                <th>FY-WW</th>
                                <th>Date Inspected</th>
                                <th>Lot No</th>
                                <th>From</th>
                                <th>To</th>
                                <th># of Sub</th>
                                <th>Lot Size</th>
                                <th>Sample Size</th>
                                <th>No of Defective</th>
                                <th>Mode of Defects</th>
                                <th>Qty</th>
                                <th>Judgement</th>
                                <th>Inspector</th>
                                <th>OQC Stamp</th>
                                <th>Type</th>
                                <th>Remarks</th>
                        </tr>
                      </thead>
                  </table>
              </div>
            </div>
          </div>

          <div class="card card-primary">

            <div class="card-header">
              <h5 class="card-title">Runcard Details</h5>

              <!-- <div class="float-sm-right"><button class="btn btn-primary btn-sm"><i class="fa fa-print"></i> Batch Print Packing Codes</button></div> -->
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
            </div>
          </div>

      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="modalEditInspection">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">Visual Inspection Result (Responsible: OQC Inspector)</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </div>

      <form id="formEditInspection" method="post">
      @csrf

        <div class="modal-body">

          <input type="hidden" class="form-control form-control-sm" id="edit_inspection_id" name="edit_inspection_id" readonly>

          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">PO NUMBER</span>
                </div>
                <input type="text" class="form-control form-control-sm" id="edit_po_no" name="edit_po_no" readonly>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">LOT NUMBER</span>
                </div>
                <input type="text" class="form-control form-control-sm" id="edit_lot_no" name="edit_lot_no" readonly>

                <input type="hidden" class="form-control form-control-sm" id="edit_lot_id" name="edit_lot_id" readonly>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">TOTAL LOT QTY</span>
                </div>
                <input type="text" class="form-control form-control-sm" id="edit_lot_qty" name="edit_lot_qty" readonly>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">OQC SAMPLE SIZE</span>
                </div>
                <input type="text" class="form-control form-control-sm" id="edit_oqc_sample_size" name="edit_oqc_sample_size">
              </div>
            </div>

            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">OK QTY</span>
                </div>
                <input type="text" class="form-control form-control-sm" id="edit_ok_qty" name="edit_ok_qty">
              </div>
            </div>

            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">NG QTY</span>
                </div>
                <input type="text" class="form-control form-control-sm" id="edit_ng_qty" name="edit_ng_qty" readonly>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">OQC INSPECTION START DATE/TIME</span>
                </div>
                <input type="datetime-local" class="form-control form-control-sm" id="edit_inspection_starttime" name="edit_inspection_starttime">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">OQC INSPECTION END DATE/TIME</span>
                </div>
                <input type="datetime-local" class="form-control form-control-sm" id="edit_inspection_datetime" name="edit_inspection_datetime">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend w-50">
                  <span class="input-group-text w-100" id="basic-addon1">USE OF TERMINAL OR MINIMUM GAUGE / TEMPLATE OR DUMMY</span>
                </div>
                <select class="form-control form-control-sm" id="edit_terminal" name="edit_terminal">
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
                    <span class="input-group-text w-100" id="basic-addon1">YD LABEL REQUIREMENT</span>
                  </div>
                  <select class="form-control form-control-sm" id="edit_yd_label" name="edit_yd_label">
                    <option selected disabled>-- Choose One --</option>
                    <option value='1'>With</option>
                    <option value='2'>Without</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
               <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">CSH COATING</span>
                  </div>
                  <select class="form-control form-control-sm" id="edit_csh_coating" name="edit_csh_coating">
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
                    <span class="input-group-text w-100" id="basic-addon1">ACCESSORIES REQUIREMENT</span>
                  </div>
                  <select class="form-control form-control-sm" id="edit_accessories_requirement" name="edit_accessories_requirement">
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
                    <span class="input-group-text w-100" id="basic-addon1">C.O.C. REQUIREMENT</span>
                  </div>
                  <select class="form-control form-control-sm" id="edit_coc_requirement" name="edit_coc_requirement">
                    <option selected disabled>-- Choose One --</option>
                    <option value='1'>YES</option>
                    <option value='2'>NO</option>
                    <option value='3'>N/A</option>
                  </select>
                </div>
              </div>
            </div>

            <hr>

            <div class="row">
               <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">RESULT</span>
                  </div>
                  <select class="form-control form-control-sm" id="edit_result" name="edit_result" disabled>
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
                    <span class="input-group-text w-100" id="basic-addon1">REMARKS</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="edit_remarks" placeholder="(Optional)" name="edit_remarks">
                </div>
              </div>
            </div>

            <!-- <div class="row">
               <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">OQC INSPECTOR NAME</span>
                  </div>
                  <select class="form-control form-control-sm selectUser" id="edit_oqc_inspector_name" name="edit_oqc_inspector_name">
                    <option selected disabled>-- Choose One --</option>
                  </select>
                </div>
              </div>
            </div> -->

            <div class="row">
               <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">OQC INSPECTOR NAME</span>

                    <input type="hidden" id="edit_oqc_inspector_name" name="edit_oqc_inspector_name">

                  </div>
                 <!--  <select class="form-control form-control-sm selectUser" id="edit_oqc_inspector_name" name="edit_oqc_inspector_name">
                    <option selected disabled>-- Choose One --</option>
                  </select> -->
                  <input type="text" class="form-control" id="edit_oqc_inspector_name2" name="edit_oqc_inspector_name2" readonly>

                  <div class="input-group-prepend">
                    <button type="button" class="btn btn-info btn-sm" id="btnSearchInspectorEdit" data-toggle="modal" data-target="#modalSearchInspectorEdit" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                  </div>
                </div>
              </div>
            </div>

        </div>

      </form>

      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-success" id="btnSubmitEditInspection">Submit Inspection Edit</button>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="modalSearchInspectorEdit" tabindex="-1">
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
          <input type="text" id="txt_employee_edit_id" name="txt_employee_edit_id" class="hidden_scanner_input" autocomplete="off">
        </div>
      </div>
    </div>
</div>

  <div class="modal fade" id="modalScan_Drawing" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-0">
            <center>
              <h4>Please Check all required drawings</h4>
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

            <div class="row row_container">
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
            </div>
            <br>

            <div class="row row_container">
              <div class="col">
                  <center>
                    <button class="form-control" id="btnGotoPMIOQCInspection">Go to WBS - OQC Inspection</button>
                  </center>
              </div>
            </div>

          </div>
        </div>
      </div>
  </div>



<div id="inspection_modal" class="modal fade" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content ">
        <div class="modal-header border-bottom-0 pb-0">
          <!-- <h4 class="modal-title">OQC Inspection Result (Responsible: OQC Inspector)</h4> -->
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <table class="table">
                <tbody>
                <tr>
                    <td width="50%">
                        <div class="form-group" id="assembly_line_div">
                            <label class="control-label col-sm-3">Assembly Line</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_assemblyLine" class="form-control">
                            </div>
                        </div>
                        <div class="form-group" id="lot_no_div">
                            <label class="control-label col-sm-3">Lot No.</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_LotNo" class="form-control">
                            </div>
                        </div>
                        <div class="form-group" id="app_date_div">
                            <label class="control-label col-sm-3">Application Date</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_ApplicationDate" class="form-control">
                            </div>
                        </div>
                        <div class="form-group" id="app_time_div">
                            <label class="control-label col-sm-9">Application Time</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_ApplicationTime" class="form-control">
                            </div>
                        </div>
                        <div class="form-group" id="prod_category_div">
                            <label class="control-label col-sm-9">Product Category</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_ProductCategory" class="form-control">
                            </div>
                        </div>
                    </td>

                    <td width="50%">
                        <div class="form-group">
                            <label class="control-label col-sm-3"></label>
                            <div class="md-checkbox-inline">
                                <div class="md-checkbox">
                                    <input type="checkbox" id="is_probe" class="md-check" name="is_probe" value="0" class="form-control">
                                    <label for="is_probe">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span>
                                    Check if PROBE </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="po_no_div">
                            <label class="control-label col-sm-3">P.O. No.</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_po_no" class="form-control">
                            </div>
                        </div>
                        <div class="form-group" id="series_name_div">
                            <label class="control-label col-sm-3">Device Name</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_DeviceName" class="form-control">
                            </div>
                        </div>
                        <div class="form-group" id="customer_div">
                            <label class="control-label col-sm-3">Customer</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_Customer" class="form-control">
                            </div>
                        </div>
                        <div class="form-group" id="po_qty_div">
                            <label class="control-label col-sm-3">P.O. Qty</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_po_qty" class="form-control">
                            </div>
                        </div>
                        <div class="form-group" id="family_div">
                            <label class="control-label col-sm-3">Family</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_Family" class="form-control">
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <th colspan="2">Sampling Plan</th>
                </tr>
                <tr>
                    <td width="50%">
                        <div class="form-group" id="type_of_inspection_div">
                            <label class="control-label col-sm-9">Type of Inspection</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_TypeofInspection" class="form-control">
                            </div>
                        </div>
                        <div class="form-group" id="severity_of_inspection_div">
                            <label class="control-label col-sm-9">Severity of Inspection</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_SeverityofInspection" class="form-control">
                            </div>
                        </div>
                        <div class="form-group" id="inspection_lvl_div">
                            <label class="control-label col-sm-3">Inspection Level</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_InspectionLevel" class="form-control">
                            </div>
                        </div>
                        <div class="form-group" id="lot_qty_div">
                            <label class="control-label col-sm-3">Lot Quantity</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_LotQuantity" class="form-control">
                            </div>
                        </div>
                    </td>

                    <td width="50%">
                        <div class="form-group" id="aql_div">
                            <label class="control-label col-sm-3">AQL</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_AQL" class="form-control">
                            </div>
                        </div>
                        <div class="form-group" id="sample_size_div">
                            <label class="control-label col-sm-3">Sample Size</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_SampleSize" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Accept</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_Accept" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Reject</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_Reject" class="form-control">
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <th colspan="2">Visual Inspection</th>
                </tr>

                <tr>
                    <td width="50%">
                        <div class="form-group" id="date_inspected_div">
                            <label class="control-label col-sm-3">Date Inspected</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_DateInspected" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">WW#</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_ww" class="form-control">
                            </div>
                            <label class="control-label col-sm-3">FY#</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_fy" class="form-control">
                            </div>
                        </div>
                        <div class="form-group" id="time_ins_div">
                            <label class="control-label col-sm-9">Time Inspected</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_from" class="form-control">
                            </div>
                            <div class="col-sm-1"></div>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_to" class="form-control">
                            </div>
                        </div>
                        <div class="form-group" id="shift_div">
                            <label class="control-label col-sm-3">Shift</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_Shift" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Inspector</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_Inspector" class="form-control">
                            </div>
                        </div>
                        <div class="form-group" id="submission_div">
                            <label class="control-label col-sm-3">Submission</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_Submission" class="form-control">
                            </div>
                        </div>
                        <div class="form-group" id="coc_req_div">
                            <label class="control-label col-sm-9">COC Requirements</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_COCRequirements" class="form-control">
                            </div>
                        </div>
                        <div class="form-group" id="judgement_div">
                            <label class="control-label col-sm-3">Judgement</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_Judgement" class="form-control">
                            </div>
                        </div>
                    </td>

                    <td width="50%">
                        <div class="form-group" id="lot_inspected_div">
                            <label class="control-label col-sm-3">Lot Inspected</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_LotInspected" class="form-control">
                            </div>
                        </div>
                        <div class="form-group" id="lot_accepted_div">
                            <label class="control-label col-sm-3">Lot Accepted</label>
                            <div class="col-sm-9">
                                <input type="text" disabled id="inspection_modal_LotAccepted" class="form-control">
                            </div>
                        </div>
                        <div class="form-group" id="remarks_div">
                            <label class="control-label col-sm-3">Remarks</label>
                            <div class="col-sm-9">
                                <textarea disabled id="inspection_modal_Remarks" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">COC</label>
                            <div class="col-sm-9">
                                <!-- <textarea disabled id="inspection_modal_COC" class="form-control"></textarea> -->
                                <select disabled id="inspection_modal_COC" class="form-control">
                                  <option value="Yes">Yes</option>
                                  <option value="No">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Gauge</label>
                            <div class="col-sm-9">
                                <!-- <textarea disabled id="inspection_modal_Gauge" class="form-control"></textarea> -->
                                <select disabled id="inspection_modal_Gauge" class="form-control">
                                  <option value="With">With</option>
                                  <option value="Without">Without</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Accessory</label>
                            <div class="col-sm-9">
                                <!-- <textarea disabled id="inspection_modal_Accessory" class="form-control"></textarea> -->
                                <select disabled id="inspection_modal_Accessory" class="form-control">
                                  <option value="Yes">Yes</option>
                                  <option value="No">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-9">YD Label Requirement</label>
                            <div class="col-sm-9">
                                <!-- <textarea disabled id="inspection_modal_YDLabalRequirement" class="form-control"></textarea> -->
                                <select disabled id="inspection_modal_YDLabalRequirement" class="form-control">
                                  <option value="With">With</option>
                                  <option value="Without">Without</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">CHS Coating</label>
                            <div class="col-sm-9">
                                <!-- <textarea disabled id="inspection_modal_CHSCoating" class="form-control"></textarea> -->
                                <select disabled id="inspection_modal_CHSCoating" class="form-control">
                                  <option value="Yes">Yes</option>
                                  <option value="No">No</option>
                                </select>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-success" id="btnSaveWBSDetails">Save</button>
        </div>
      </div>
    </div>
</div>

    <div class="modal fade" id="modal_qrcode_scanner_for_save_wbs_deatail" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
              <input type="text" id="modal_qrcode_scanner_for_save_wbs_deatail_employee_id_holder" class="form-control" disabled>
              <br>
              <h1><i class="fa fa-barcode fa-lg"></i></h1>
            </div>
            <input type="text" id="modal_qrcode_scanner_for_save_wbs_deatail_employee_id" class="hidden_scanner_input" autocomplete="off">
            <input type="hidden" id="modal_qrcode_scanner_for_save_wbs_deatail_id">
          </div>
        </div>
      </div>
    </div>

@endsection

@section('js_content')
<script type="text/javascript">

  let arrayPackingCodeBatch = [];

  $(document).ready(function () {
    bsCustomFileInput.init();

      $(document).on('click','#btnSaveWBSDetails',function(e){
        $('#modal_qrcode_scanner_for_save_wbs_deatail').modal('show')
        $('#modal_qrcode_scanner_for_save_wbs_deatail_employee_id').val('')
        $('#modal_qrcode_scanner_for_save_wbs_deatail_employee_id_holder').val('')
        $('#modal_qrcode_scanner_for_save_wbs_deatail_employee_id').focus()
      });

      $(document).on('keyup','#modal_qrcode_scanner_for_save_wbs_deatail_employee_id',function(e){
        if( e.keyCode == 13 ){
          // alert( $('#modal_qrcode_scanner_for_delete_accesory_id').val() )

          $.ajax({
            url: "employee_id_checker",
            method: "get",
            data:
            {
              employee_id: $('#modal_qrcode_scanner_for_save_wbs_deatail_employee_id').val(),
              position: 5,
            },
            dataType: "json",
            success: function(JsonObject)
            {
              if(JsonObject['result'] == 1){
                // GetOperatorDetails($('#txt_operator_employee_id').val());
                  $.ajax({
                    url: "load_user_details",
                    method: "get",
                    data:
                    {
                      employee_id: $('#modal_qrcode_scanner_for_save_wbs_deatail_employee_id').val(),
                    },
                    dataType: "json",
                    beforeSend: function()
                    {

                    },
                    success: function(JsonObject)
                    {

                    },
                    error: function(data, xhr, status){
                      toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
                    }

                  });
              }
              else if(JsonObject['result'] == 0)
                toastr.error('Scanned Employee ID is not inspector.');
              else
                toastr.error(JsonObject['error_msg']);
            },
            error: function(data, xhr, status){
              toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            }

          });

          // $.ajax({
          //   'data'      : { employee_id: $("#modal_qrcode_scanner_for_save_wbs_deatail_employee_id").val() },
          //   'type'      : 'get',
          //   'dataType'  : 'json',
          //   'url'       : 'check_employee_id',
          //   success     : function(data){
          //     if( data['result'] ){

          //       // alert( $('#view_lotapp_id').val() )

          //       let ids = $("#modal_qrcode_scanner_for_save_wbs_deatail_employee_id_holder").val()
          //       ids = ids.slice(0,-1)
          //       ids = ids.split(',')
          //       // alert( ids )
          //       // alert( (jQuery.inArray($("#modal_qrcode_scanner_for_save_wbs_deatail_employee_id").val(), ids) !== -1) )

          //       if( !(jQuery.inArray($("#modal_qrcode_scanner_for_save_wbs_deatail_employee_id").val(), ids) !== -1) )
          //         $("#modal_qrcode_scanner_for_save_wbs_deatail_employee_id_holder").val( $("#modal_qrcode_scanner_for_save_wbs_deatail_employee_id_holder").val() + $("#modal_qrcode_scanner_for_save_wbs_deatail_employee_id").val() + ',' )
          //       $('#modal_qrcode_scanner_for_save_wbs_deatail_employee_id').val('')

          //       // $.ajax({
          //       //   'data'      : {
          //       //     _token: '{{ csrf_token() }}',
          //       //     prod_runcard_id:  $('#view_lotapp_id').val(),
          //       //     coc:              $('#inspection_modal_COC').val(),
          //       //     guage:            $('#inspection_modal_Gauge').val(),
          //       //     accessory:        $('#inspection_modal_Accessory').val(),
          //       //     yd_lbl_req:       $('#inspection_modal_YDLabalRequirement').val(),
          //       //     chs_coating:      $('#inspection_modal_CHSCoating').val(),
          //       //     employee_id:      $("#modal_qrcode_scanner_for_save_wbs_deatail_employee_id").val(),
          //       //     judgement:        $('#inspection_modal_Judgement').val(),
          //       //   },
          //       //   'type'      : 'post',
          //       //   'dataType'  : 'json',
          //       //   'url'       : 'saveOQCInspection_2',
          //       //   success     : function(data){
          //       //     if( data['result']==1 ){
          //       //       dt_oqcvir_results.draw()
          //       //       dt_runcards.draw()
          //       //       toastr.success('Save successfully.')
          //       //       $('#modal_qrcode_scanner_for_save_wbs_deatail').modal('hide')
          //       //       $('#inspection_modal').modal('hide')
          //       //     }else{
          //       //       toastr.error(data['error_msg']);
          //       //     }

          //       //   }
          //       // })

          //     }
          //     else
          //       toastr.error( 'Invalid Employee ID' )
          //   },
          //   completed     : function(data){

          //   },
          //   error     : function(data){

          //   },
          // });

        }
      });

      GetUserList($(".selectUser"));
      $('.selectUser').select2({
            theme: 'bootstrap4'
          });

      dt_oqcvir = $('#tbl_oqcvir').DataTable({
          "processing"    : false,
          "serverSide"  : true,
          "ajax"        :
          {
            url: "load_oqcvir_pts_table",
                data: function (param){
                    param.po_num = $('#id_po_no').val();
                }
          },

          "columns":[
            { "data" : "action", orderable:false, searchable:false, width: "150px" },
            { "data" : "fy_ww" },
            { "data" : "date_inspected" },
            { "data" : "lot_no" },
            { "data" : "from" },
            { "data" : "to" },
            { "data" : "sub_lot" },
            { "data" : "lot_size" },
            { "data" : "sample_size" },
            { "data" : "num_of_defects" },
            { "data" : "mod" },
            { "data" : "qty" },
            { "data" : "judgement" },
            { "data" : "inspector" },
            { "data" : "oqc_stamp" },
            { "data" : "type" },
            { "data" : "remarks" },
            // { "data" : "lot_no" },
            // { "data" : "lot_no" },
            // { "data" : "output_qty" , width: "100px" },
            // { "data" : "inspected_by" },
            // { "data" : "status" },

          ],

      });

      dt_oqcvir_results = $('#tbl_oqcvir_results').DataTable({

          "processing"    : false,
          "serverSide"  : true,
          "ajax"        :
          {
            url: "load_oqcvir_pts_table_by_id",
              data: function (param){
                param.id = $('#view_lotapp_id').val();
                }
          },

          "columns":[
            { "data" : "action", orderable:false, searchable:false, width: "150px" },
            { "data" : "fy_ww" },
            { "data" : "date_inspected" },
            { "data" : "lot_no" },
            { "data" : "from" },
            { "data" : "to" },
            { "data" : "sub_lot" },
            { "data" : "lot_size" },
            { "data" : "sample_size" },
            { "data" : "num_of_defects" },
            { "data" : "mod" },
            { "data" : "qty" },
            { "data" : "judgement" },
            { "data" : "inspector" },
            { "data" : "oqc_stamp" },
            { "data" : "type" },
            { "data" : "remarks" },

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
             /*{ "data" : "action", orderable:false, searchable:false, width: "150px" },*/
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
    try{ // nmodify - Search PO using object
      if( e.keyCode == 13 ){
          $('#id_po_no').val('');
          $('#id_device_name').val('');
          $('#txt_device_code_lbl').val('');
          $('#id_po_qty').val('');

          let oqc_lotapp_po_no = JSON.parse($('#txt_search_po_number').val()).oqc_lotapp_po_no;
          if(oqc_lotapp_po_no == undefined){
              alert('Invalid QR Code, Please try again !')
          }else{
              getWbsPoDetails(oqc_lotapp_po_no); //Common.js+
              setTimeout(() => {
                  dt_oqcvir.draw();
              }, 500);
          }
      }
    } catch (error) {
        alert(`Error: ${error}`)
    }
  });

  $(document).on('click','.btn-inspection-result',function(){


    $('#modalScan_Drawing').modal({
      backdrop: 'static',
      keyboard: false,
      show: true
    });

    let lotapp_id = $(this).attr('lotapp-id');

    $.ajax({
      url: "get_drawingno",
      method: 'get',
      dataType: 'json',
      data: {
        lotapp_id: lotapp_id,
      },
      beforeSend: function(){
      },
      success: function(data){

        if(data['drawing_no'] != null){
          if ( data['drawing_no']['orig_a_drawing_no'] == 'N/A' || data['drawing_no']['orig_a_drawing_no'] == '' || data['drawing_no']['orig_a_drawing_no'] == null){
              $("#id_orig_a_drawing").hide();
          }else{
            $('#id_orig_a_drawing').val(data['drawing_no']['orig_a_drawing_no']);
          }
          if ( data['drawing_no']['a_drawing_no'] == 'N/A' || data['drawing_no']['a_drawing_no'] == '' || data['drawing_no']['a_drawing_no'] == null){
              $("#id_a_drawing").hide();
          }else{
            $('#id_a_drawing').val(data['drawing_no']['a_drawing_no']);
          }
          if ( data['drawing_no']['g_drawing_no'] == 'N/A' || data['drawing_no']['g_drawing_no'] == '' || data['drawing_no']['g_drawing_no'] == null){
              $("#id_g_drawing").hide();
          }else{
            $('#id_g_drawing').val(data['drawing_no']['g_drawing_no']);
          }
          if ( data['drawing_no']['o_drawing_no'] == 'N/A' || data['drawing_no']['o_drawing_no'] == '' || data['drawing_no']['o_drawing_no'] == null){
              $("#id_o_drawing").hide();
          }else{
            $('#id_o_drawing').val(data['drawing_no']['o_drawing_no']);
          }

        }

      }
    });

    TSPTSViewLotAppDetails(lotapp_id);
  });

  $('#btnSubmitInspection').click(function(){

    $('#formAddInspection').submit();

  });

  $('#formAddInspection').submit(function(e){

    e.preventDefault();
    TSPTSSubmitOQCInspection();

  });

  $('#modalAddInspection').on('hidden.bs.modal',function(){

      $('#formAddInspection')[0].reset();

      $('#add_oqc_sample_size').removeClass('is-invalid');
      $('#add_ok_qty').removeClass('is-invalid');
      $('#add_inspection_datetime').removeClass('is-invalid');
      $('#add_terminal').removeClass('is-invalid');
      $('#add_yd_label').removeClass('is-invalid');
      $('#add_csh_coating').removeClass('is-invalid');
      $('#add_accessories_requirement').removeClass('is-invalid');
      $('#add_coc_requirement').removeClass('is-invalid');
      $('#add_result').removeClass('is-invalid');
      $('#add_oqc_inspector_name').removeClass('is-invalid');

  });

  $(document).on('click','.btn-view-application', function(){

    let lotapp_id = $(this).attr('lotapp-id');
    $('#view_lotapp_id').val(lotapp_id);
    dt_oqcvir_results.draw();
    dt_runcards.draw();
    $('#modalViewApplication').modal('show')

  });

  $(document).on('click','.btnView', function(){

    $('#btnSaveWBSDetails').attr('disabled', true)
    $('#btnSaveWBSDetails').attr('hidden', true)

    let lotapp_id = $(this).attr('lotapp-id');
    $.ajax({
      url: "load_oqcvir_pts_details_by_id",
      method: "get",
      data:
      { id: $(this).attr('lotapp-id'), },
      dataType: "json",
      success: function(JsonObject)
      {

        if( JsonObject['data'].length > 0 ){

          let data = JsonObject['data']
          let oqc_inspec = data[0]['oqc_inspec']
          let oqc_inspection = data[0]['oqc_inspection']

          $('#inspection_modal_assemblyLine').val( oqc_inspec[0]['assembly_line'] )
          $('#inspection_modal_LotNo').val( oqc_inspec[0]['lot_no'] )
          $('#inspection_modal_ApplicationDate').val( oqc_inspec[0]['app_date'] )
          $('#inspection_modal_ApplicationTime').val( oqc_inspec[0]['app_time'] )
          $('#inspection_modal_ProductCategory').val( oqc_inspec[0]['prod_category'] )
          $('#inspection_modal_po_no').val( oqc_inspec[0]['po_no'] )
          $('#inspection_modal_DeviceName').val( oqc_inspec[0]['device_name'] )
          $('#inspection_modal_Customer').val( oqc_inspec[0]['customer'] )
          $('#inspection_modal_po_qty').val( oqc_inspec[0]['po_qty'] )
          $('#inspection_modal_Family').val( oqc_inspec[0]['family'] )
          $('#inspection_modal_TypeofInspection').val( oqc_inspec[0]['type_of_inspection'] )
          $('#inspection_modal_SeverityofInspection').val( oqc_inspec[0]['severity_of_inspection'] )
          $('#inspection_modal_InspectionLevel').val( oqc_inspec[0]['inspection_lvl'] )
          $('#inspection_modal_LotQuantity').val( oqc_inspec[0]['lot_qty'] )
          $('#inspection_modal_AQL').val( oqc_inspec[0]['aql'] )
          $('#inspection_modal_SampleSize').val( oqc_inspec[0]['sample_size'] )
          $('#inspection_modal_Accept').val( oqc_inspec[0]['accept'] )
          $('#inspection_modal_Reject').val( oqc_inspec[0]['reject'] )
          $('#inspection_modal_DateInspected').val( oqc_inspec[0]['date_inspected'] )
          $('#inspection_modal_ww').val( oqc_inspec[0]['ww'] )
          $('#inspection_modal_fy').val( oqc_inspec[0]['fy'] )
          $('#inspection_modal_from').val( oqc_inspec[0]['time_ins_from'] )
          $('#inspection_modal_to').val( oqc_inspec[0]['time_ins_to'] )
          $('#inspection_modal_Shift').val( oqc_inspec[0]['shift'] )
          $('#inspection_modal_Inspector').val( oqc_inspec[0]['inspector'] )
          $('#inspection_modal_Submission').val( oqc_inspec[0]['submission'] )
          $('#inspection_modal_COCRequirements').val( oqc_inspec[0]['coc_req'] )
          $('#inspection_modal_Judgement').val( oqc_inspec[0]['judgement'] )
          $('#inspection_modal_LotInspected').val( oqc_inspec[0]['lot_inspected'] )
          $('#inspection_modal_LotAccepted').val( oqc_inspec[0]['lot_accepted'] )
          $('#inspection_modal_Remarks').val( oqc_inspec[0]['remarks'] )

          if( oqc_inspection != null ){
            $('#inspection_modal_COC').val( oqc_inspection['coc'] )
            $('#inspection_modal_COC').attr('disabled', true)
            $('#inspection_modal_Gauge').val( oqc_inspection['guage'] )
            $('#inspection_modal_Gauge').attr('disabled', true)
            $('#inspection_modal_Accessory').val( oqc_inspection['accessory'] )
            $('#inspection_modal_Accessory').attr('disabled', true)
            $('#inspection_modal_YDLabalRequirement').val( oqc_inspection['yd_lbl_req'] )
            $('#inspection_modal_YDLabalRequirement').attr('disabled', true)
            $('#inspection_modal_CHSCoating').val( oqc_inspection['chs_coating'] )
            $('#inspection_modal_CHSCoating').attr('disabled', true)
            $('#btnSaveWBSDetails').attr('disabled', true)
            $('#btnSaveWBSDetails').attr('hidden', true)
          }else{
            $('#inspection_modal_COC').val( 'Yes' )
            $('#inspection_modal_COC').attr('disabled', false)
            $('#inspection_modal_Gauge').val( 'With' )
            $('#inspection_modal_Gauge').attr('disabled', false)
            $('#inspection_modal_Accessory').val( 'Yes' )
            $('#inspection_modal_Accessory').attr('disabled', false)
            $('#inspection_modal_YDLabalRequirement').val( 'With' )
            $('#inspection_modal_YDLabalRequirement').attr('disabled', false)
            $('#inspection_modal_CHSCoating').val( 'Yes' )
            $('#inspection_modal_CHSCoating').attr('disabled', false)
            $('#btnSaveWBSDetails').attr('disabled', false)
            $('#btnSaveWBSDetails').attr('hidden', false)
          }

          $('#inspection_modal').modal('show')

        }

      }

    });

  });

   $(document).on('click','.btn-goto-wbsoqc', function(){

    // get_first_lot_data_by_po_no


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

          $("#modalScan_Drawing_OrigAdrawing_no").val( (JsonObject['data'][0]['orig_a_drawing_no'] != null && JsonObject['data'][0]['orig_a_drawing_no'] != '') ? JsonObject['data'][0]['orig_a_drawing_no'] : 'N/A' )
          $("#modalScan_Drawing_orig_a_revision").val( (JsonObject['data'][0]['orig_a_revision'] != null && JsonObject['data'][0]['orig_a_revision'] != '') ? JsonObject['data'][0]['orig_a_revision'] : 'N/A' )

          $("#modalScan_Drawing_Adrawing_no").val( (JsonObject['data'][0]['a_drawing_no'] != null && JsonObject['data'][0]['a_drawing_no'] != '') ? JsonObject['data'][0]['a_drawing_no'] : 'N/A' )
          $("#modalScan_Drawing_a_revision").val( (JsonObject['data'][0]['a_revision'] != null && JsonObject['data'][0]['a_revision'] != '') ? JsonObject['data'][0]['a_revision'] : 'N/A' )

          $("#modalScan_Drawing_Gdrawing_no").val( (JsonObject['data'][0]['g_drawing_no'] != null && JsonObject['data'][0]['g_drawing_no'] != '') ? JsonObject['data'][0]['g_drawing_no'] : 'N/A' )
          $("#modalScan_Drawing_g_revision").val( (JsonObject['data'][0]['g_revision'] != null && JsonObject['data'][0]['g_revision'] != '') ? JsonObject['data'][0]['g_revision'] : 'N/A' )

          $("#modalScan_Drawing_Odrawing_no").val( (JsonObject['data'][0]['o_drawing_no'] != null && JsonObject['data'][0]['o_drawing_no'] != '') ? JsonObject['data'][0]['o_drawing_no'] : 'N/A' )
          $("#modalScan_Drawing_o_revision").val( (JsonObject['data'][0]['o_revision'] != null && JsonObject['data'][0]['o_revision'] != '') ? JsonObject['data'][0]['o_revision'] : 'N/A' )

          $('#btnGotoPMIOQCInspection').attr('disabled', true)

          $("#modalScan_Drawing").modal('show')
          checked_draw_count_reset()

        }
      }

    });

    //
    // window.open("http://192.168.3.246/pmi-subsystem/");

  });

    $("#btnView_orig_a_drawing").click(function(){
      redirect_to_drawing( $('#modalScan_Drawing_OrigAdrawing_no').val(), 0 )
    });

    $("#btnView_a_drawing").click(function(){
      redirect_to_drawing( $('#modalScan_Drawing_Adrawing_no').val(), 1 )
    });

    $("#btnView_g_drawing").click(function(){
      redirect_to_drawing( $('#modalScan_Drawing_Gdrawing_no').val(), 2 )
    });

    $("#btnView_o_drawing").click(function(){
      redirect_to_drawing( $('#modalScan_Drawing_Odrawing_no').val(), 3 )
    });

    $("#btnGotoPMIOQCInspection").click(function(){
      window.open("http://192.168.3.246/pmi-subsystem/oqcinspection")
    });

    function redirect_to_drawing(txt_Adrawing, index) {
      if ( txt_Adrawing == 'N/A' || txt_Adrawing == '' )
        alert('No Document')
      else{
        window.open("http://rapid/ACDCS/prdn_home_tsppts?doc_no="+txt_Adrawing)
        checked_draw_count[index] = 1
      }

      let ids = ['modalScan_Drawing_OrigAdrawing_no','modalScan_Drawing_Adrawing_no','modalScan_Drawing_Gdrawing_no','modalScan_Drawing_Odrawing_no']
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
        checked_draw_count = [0, 0, 0, 0]
      }

function ComputeNGQuantity(sample_size, ok_qty)
{
  let ng_qty = 0;

  if(sample_size >= ok_qty)
  {
    ng_qty = sample_size - ok_qty;
    $('#add_ng_qty').val(ng_qty);
  }
  else
  {
    toastr.error('OK Quantity cannot be greater than Sample Size!');
    $('#add_ng_qty').val(0);
  }
}

function ComputeNGQuantityEdit(sample_size, ok_qty)
{
  let ng_qty = 0;

  if(sample_size >= ok_qty)
  {
    ng_qty = sample_size - ok_qty;
    $('#edit_ng_qty').val(ng_qty);
  }
  else
  {
    toastr.error('OK Quantity cannot be greater than Sample Size!');
    $('#edit_ng_qty').val(0);
  }
}

$('#add_oqc_sample_size').on('keyup',function() {

  if($('#add_oqc_sample_size').val() != '' && $('#add_oqc_sample_size').val() > 0)
  {
    $('#add_ok_qty').removeAttr('disabled');
  }
  else
  {
    $('#add_ok_qty').prop('disabled','disabled');
  }

});

$('#add_ok_qty').on('keyup',function(){

    let sample_size = $('#add_oqc_sample_size').val();
    let ok_qty = $('#add_ok_qty').val();

    ComputeNGQuantity(sample_size, ok_qty);

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

          /*   content += '<center>';
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
            content += '</center>';*/
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

          GetInspectorDetails($('#txt_employee_id').val());

        }
      }
  });

function LoadVirInspectionDetails(inspection_id)
{
  $.ajax({
    url: "load_vir_inspection_details",
    method: "get",
    data:
    {
      inspection_id: inspection_id,
    },
    dataType: "json",
    beforeSend: function()
    {

    },
    success: function(JsonObject)
    {
      if(JsonObject['result'] == 1)
      {
        let datetime = JsonObject['inspection_details'][0].inspection_datetime;
        let strdatetime = datetime.split(" ");

         $('#edit_inspection_id').val(JsonObject['inspection_details'][0].id);

        $('#edit_po_no').val(JsonObject['inspection_details'][0].lotapp_info.po_no);
        $('#edit_lot_no').val(JsonObject['inspection_details'][0].lotapp_info.runcard_no);
        $('#edit_lot_id').val(JsonObject['inspection_details'][0].lotapp_info.id);
        $('#edit_lot_qty').val(JsonObject['total_lot_qty']);

        $('#edit_oqc_sample_size').val(JsonObject['inspection_details'][0].sample_size);
        $('#edit_ok_qty').val(JsonObject['inspection_details'][0].ok_qty);
        $('#edit_ng_qty').val(JsonObject['inspection_details'][0].sample_size - JsonObject['inspection_details'][0].ok_qty);
        $('#edit_inspection_datetime').val(strdatetime[0] + "T" + strdatetime[1]);
        $('#edit_terminal').val(JsonObject['inspection_details'][0].terminal_use);
        $('#edit_yd_label').val(JsonObject['inspection_details'][0].yd_label);
        $('#edit_csh_coating').val(JsonObject['inspection_details'][0].csh_coating);
        $('#edit_accessories_requirement').val(JsonObject['inspection_details'][0].accessories_requirement);
        $('#edit_coc_requirement').val(JsonObject['inspection_details'][0].coc_requirement);
        $('#edit_result').val(JsonObject['inspection_details'][0].result);


        if(JsonObject['inspection_details'][0].inspection_starttime != null)
        {
          let startdatetime = JsonObject['inspection_details'][0].inspection_datetime;
          let strstartdatetime = datetime.split(" ");

          $('#edit_inspection_starttime').val(strstartdatetime[0] + "T" + strstartdatetime[1]);
        }


      }
      else
      {
        toastr.error('Inspection Details not Found!');
      }
    },
    error: function(data, xhr, status){
      toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
    }

  });
}

$(document).on('click','.btn-edit-inspection',function(){

  let inspection_id = $(this).attr('inspection-id');
  LoadVirInspectionDetails(inspection_id);

});

$('#edit_oqc_sample_size').on('keyup',function() {

  if($('#edit_oqc_sample_size').val() != '' && $('#edit_oqc_sample_size').val() > 0)
  {
    $('#edit_ok_qty').removeAttr('disabled');
  }
  else
  {
    $('#edit_ok_qty').prop('disabled','disabled');
  }

});

$('#edit_ok_qty').on('keyup',function(){

    let sample_size = $('#edit_oqc_sample_size').val();
    let ok_qty = $('#edit_ok_qty').val();

    ComputeNGQuantityEdit(sample_size, ok_qty);

});

function GetInspectorDetailsEdit(employee_id)
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
        $('#edit_oqc_inspector_name').val(JsonObject['user_details'][0].id);
        $('#edit_oqc_inspector_name2').val(JsonObject['user_details'][0].name);
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


$('#btnSearchInspectorEdit').click(function(){
    $('#txt_employee_edit_id').val('');
});

$(document).on('keypress',function(e){
    if( ($("#modalSearchInspectorEdit").data('bs.modal') || {})._isShown ){
      $('#txt_employee_edit_id').focus();

      if( e.keyCode == 13 && $('#txt_employee_edit_id').val() !='' && ($('#txt_employee_edit_id').val().length >= 4) ){

          $('#modalSearchInspectorEdit').modal('hide');

          GetInspectorDetailsEdit($('#txt_employee_edit_id').val());

        }
      }
  });

 $('#btnSubmitEditInspection').click(function(){

    $('#formEditInspection').submit();

  });

  $('#formEditInspection').submit(function(e){

    e.preventDefault();
    TSPTSSubmitOQCInspectionEdit();

  });

  //- Search Drawing no
    $(document).on('click','.btn_search_drawingNo',function(e){
      $('#id_search_Drawing').val('');
      $('#modalScan_Drawing').attr('data-formid', '').modal('show');
    });

    $(document).on('keypress',function(e){
      if( ($("#modalScan_Drawing").data('bs.modal') || {})._isShown ){
        $('#id_search_Drawing').focus();

        if( e.keyCode == 13 && $('#id_search_Drawing').val() !='' && ($('#id_search_Drawing').val().length >= 4) ){
            $('#id_search_Drawing').val('');
            $('#id_search_Drawing_id').val('');
          }
        }
    });

   $(document).on('keypress','#id_search_Drawing',function(e){

        if( e.keyCode == 13 ){
          var data = {
            'doc_no'      : $('#id_search_Drawing').val()
          }

          dt_oqcvir.draw();

          data = $.param(data);
          $.ajax({
            type      : "get",
            dataType  : "json",
            data      : data,
            url       : "get_docNo_details",
            success : function(data){

              if ( data['docNo_details'].length > 0 ){
                $('#id_search_Drawing_id').val( data['docNo_details'][0]['fkid_document'] );
              }

              fkid_document = data['docNo_details'][0]['fkid_document'];
              fkid_doc_no = data['docNo_details'][0]['doc_no'];

              drawing = fkid_doc_no.charAt(0);
              // alert(drawing)
              if ( drawing == 'A' || drawing == 'K'){
                window.open("http://192.168.3.235/ACDCS/edcs_doc_viewer.php?pkid_doc_stat="+fkid_document+'_1', '_blank');
              }else if (drawing == 'A' || drawing == 'G'){
                window.open("http://192.168.3.235/ACDCS/pdf_viewer_dquick_document.php?pkid_doc_stat="+fkid_document+'_1', '_blank');
              } else {
                window.open("http://192.168.3.235/ACDCS/pdf_viewer_document.php?pkid_doc_stat="+fkid_document+'_1', '_blank');
              }

              if ( !$('#lbl_drawings').text().indexOf(fkid_doc_no) >=0 ){
                $('#lbl_drawings').text($('#lbl_drawings').text() + fkid_doc_no);
              }

              var ctr = 0;
              var ctr2 = 0;

              if( $('#id_orig_a_drawing').val() != 'N/A' ){
                ctr ++;
              }

              if( $('#id_a_drawing').val() != 'N/A' ){
                ctr ++;
              }

              if( $('#id_g_drawing').val() != 'N/A' ){
                ctr ++;
              }

              if( $('#id_o_drawing').val() != 'N/A' ){
                ctr ++;
              }

              if ( $('#lbl_drawings').text().indexOf($('#id_orig_a_drawing').val()) >=0 ){
                  ctr2 ++;
              }
              if ( $('#lbl_drawings').text().indexOf($('#id_a_drawing').val()) >=0 ){
                  ctr2 ++;
              }
              if ( $('#lbl_drawings').text().indexOf($('#id_g_drawing').val()) >=0 ){
                  ctr2 ++;
              }
              if ( $('#lbl_drawings').text().indexOf($('#id_o_drawing').val()) >=0 ){
                  ctr2 ++;
              }

              if( ctr == ctr2 ){
                $('#modalScan_Drawing').modal('hide');
                $('#modalAddInspection').modal('show');
              }

            },error : function(data){

            }

          });

        }
    });

    $('#btn_download').click(function(){
      window.open('public/storage/file_templates/user_manual/TS PTS User Manual - OQC Inspection Result.pdf','_blank');
    });

</script>
@endsection
@endauth