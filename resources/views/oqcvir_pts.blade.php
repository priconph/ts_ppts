@extends('layouts.super_user_layout')

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
          <h1>OQC Inspection Result</h1>
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
              <div class="card-header">
                <h3 class="card-title">Search PO Number</h3>
              </div>
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
                      <label>PO Number</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-primary btn_search_POno" title="Click to Scan PO Code"><i class="fa fa-qrcode"></i></button>
                        </div>

                         <input type="text" id="id_po_no" class="form-control" autocomplete="off" readonly>

                      </div>
                    </div>

                    <div class="col-sm-2">
                      <label>Device Name</label>
                        <input type="text" class="form-control" id="id_device_name" name="" readonly="">
                    </div>
                    <div class="col-sm-1">
                      <label>PO Qty</label>
                        <input type="text" class="form-control" id="id_po_qty" readonly="">
                    </div>
                  </div>
                  <br>
              </div>
              <!-- !-- End Page Content -->
          </div>
          <!-- /.card -->

           <div class="card card-primary">

                <div class="card-body">
                  <div class="table-responsive dt-responsive">
                      <table id="tbl_oqcvir" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 85%;">
                          <thead>
                            <tr>
                              <th>Action</th>
                              <th>Lot</th>
                              <th>Lot Quantity</th>
                              <th>Inspected By</th>
                              <th>Status</th>
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


<div class="modal fade" id="modalAddInspection">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">Visual Inspection Result (Responsible: OQC Inspector)</h4>
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
                        <tr>
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
                          <th>C.O.C. Req.</th>
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
                          <th>Packing Code</th>
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


@endsection

@section('js_content')
<script type="text/javascript">

  let arrayPackingCodeBatch = [];

  $(document).ready(function () {
    bsCustomFileInput.init();

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
                param.po_num = $('#txt_search_po_number').val();
                }
          },

          "columns":[
            { "data" : "action", orderable:false, searchable:false, width: "150px" },
            { "data" : "lot_no" },
            { "data" : "output_qty" , width: "100px" },
            { "data" : "inspected_by" },
            { "data" : "status" },

          ],

      });

      dt_oqcvir_results = $('#tbl_oqcvir_results').DataTable({

          "processing"    : false,
          "serverSide"  : true,
          "ajax"        :
          {
            url: "load_oqcvir_results_table",
              data: function (param){
                param.lotapp_id = $('#view_lotapp_id').val();
                }
          },

          "columns":[
            { "data" : "action"},
            { "data" : "result_raw"},
            { "data" : "inspection_starttime" },
            { "data" : "inspection_datetime" },
            { "data" : "inspector_id"},
            { "data" : "oqc_sample" },
            { "data" : "terminal_use" },
            { "data" : "yd_label" },
            { "data" : "csh_coating" },
            { "data" : "accessory_req" },
            { "data" : "coc_req" },

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

        if( e.keyCode == 13 ){

          $('#id_po_no').val('');
          $('#id_device_name').val('');
          $('#id_po_qty').val('');

          var data = {
          'po'      : $('#txt_search_po_number').val()
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
            $('#id_po_qty').val('-- Data Loading --');

          },
          success : function(data){

            dt_oqcvir.draw();

            if(data['po_details'][0]['wbs_kitting'] == null){
              console.log('null', null);
            }

            $('#id_po_no').val( data['po_details'][0]['po_no'] );
            // $('#id_device_name').val( data['po_details'][0]['wbs_kitting']['device_name'] );
            // $('#add_series_name').val(data['po_details'][0]['wbs_kitting']['device_name'] );
            // $('#id_po_qty').val( data['po_details'][0]['wbs_kitting']['po_qty'] );

          },error : function(data){

            $('#id_po_no').val('-- Data Error, Please Refresh --');
            $('#id_device_name').val('-- Data Error, Please Refresh --');
            $('#id_po_qty').val('-- Data Error, Please Refresh --');

          }

        });


        }
    });

  $(document).on('click','.btn-inspection-result',function(){

    let lotapp_id = $(this).attr('lotapp-id');

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

  });

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


</script>
@endsection
