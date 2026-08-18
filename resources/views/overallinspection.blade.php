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

  @section('title', 'OQC Lot Application')

  @section('content_page')
  <!-- Content Wrapper. Contains page content -->
  <style>
    #id_currentPONo, #id_LotBatch, #id_LotQty, #id_OutputQty, #id_device_name {
      color: #0000FF;
      font-weight: bold;
    }

    #id_OutputQty {
      color: #D35400;
      font-weight: bold;
    }

    .radio-label {
       display: inline-block;
        vertical-align: top;
        margin-right: 3%;
    }
    .radio-input {
       display: inline-block;
        vertical-align: top;
    }
    fieldset {
        text-align: center;
    }
    .div-radio-label {
        margin: auto;
        border: 1px solid black;
    }

    .hidden_scanner_input{
      position: absolute;
      opacity: 0;
    }
  </style>

  <div class="content-wrapper">
    <?php
      date_default_timezone_set('Asia/Manila');
    ?>
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Overall Inspection</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Overall Inspection</li>
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

              <!-- Start Page Content -->
              <div class="card-body">
                  <div class="row">
                    <div class="col-sm-3">
                      <label>PO Number</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-primary btn_search_POno" title="Click to Scan PO Code"><i class="fa fa-qrcode"></i></button>
                        </div>
                        <input type="text" class="form-control" id="txt_po_no" readonly="">
                      </div>
                    </div>
                    <!--
                    <div class="col-sm-1">
                    </div>
                    <div class="col-sm-2">
                      <label>PO No</label>
                        <input type="text" class="form-control" id="txt_po_no" readonly="">
                    </div>
                    -->
                    <div class="col-sm-3">
                      <label>Device Name</label>
                        <input type="text" class="form-control" id="txt_device_name" name="" readonly="">
                    </div>
                    <div class="col-sm-2">
                      <label>Device Code</label>
                        <input type="text" class="form-control" id="txt_device_code_lbl" readonly="">
                    </div>
                    <div class="col-sm-1">
                      <label>PO Qty</label>
                        <input type="text" class="form-control" id="txt_po_qty" readonly="">
                    </div>
                  </div>
                  <br>
              </div>
              <!-- !-- End Page Content -->

            </div>
            <!-- /.card -->

            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">2. Overall Inspection Summary</h3>
                <!-- <button class="btn btn-sm btn-primary" style="float: right;" id="btnOverallInspection"><i class="fa fa-plus"></i> Overall Inspection</button> -->
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                  <div class="table responsive">
                    <table class="table table-sm table-bordered table-hover" id="tblOQCLotApp" style="min-width: 900px;">
                      <thead>
                          <tr class="bg-light">
                          <!-- <th></th> -->
                          <th>Action</th>
                          <th>Status</th>
                          <!-- <th>Submission</th> -->
                          <th>Lot #</th>
                          <!-- <th>Sub Lot #</th> -->
                          <th>Lot Qty</th>
                          <th>Balance Qty</th>
                          <!-- <th>Output Qty</th> -->
                          <th>Lot Applied By</th>
                          <!-- <th style="background-color:#00f3ff">Prodn. Supv.</th> -->
                          <!-- <th style="background-color:#FFFF00">OQC Supv.</th> -->
                        </tr>
                      </thead>
                    </table>
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

  <!-- MODALS -->
  <div class="modal fade" id="modalOQCLotApp">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-plus"></i> &nbsp; OQC Lot Application</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formOQCLotApp">
          @csrf
          <div class="modal-body">

            <div class="row">
              <div class="col-sm-4 p-2">
                <input type="hidden" class="form-control" id="hidden_position" value="{{ Auth::user()->position }}">
                <input type="hidden" class="form-control" id="hidden_OQCLotApp_id" name="hidden_OQCLotApp_name">
                <input type="hidden" class="form-control" id="hidden_sub" name="hidden_sub">
                <input type="hidden" class="form-control" id="hidden_runcard_id" name="hidden_runcard_id">
                <input type="hidden" class="form-control" id="hidden_status" name="hidden_status">
                <input type="hidden" class="form-control" id="hidden_require_oqc_before_emboss" name="hidden_require_oqc_before_emboss">
                <input type="hidden" class="form-control" id="hidden_runcard_status" name="hidden_runcard_status">
                <input type="hidden" class="form-control" id="hidden_sub_lot" name="hidden_sub_lot">



                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Current PO Number</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_currentPONo" name="name_po_no" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Device Name</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_device_name" name="device_name" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Lot/Batch No.</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_LotBatch" name="name_LotBatch" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Lot Quantity</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_LotQty" name="name_LotQty" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Output Quantity</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_OutputQty" name="name_OutputQty" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">A Drawing No. / Rev</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_ADrawing" name="name_ADrawing" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Orig A Drawing No. / Rev</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_OrigADrawing" name="name_OrigADrawing" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">G Drawing No. / Rev</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_GDrawing" name="name_GDrawing" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">WI Doc / Rev</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_WIDocu" name="name_WIDocu" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">OGM/VIG/IG Doc / Rev</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_OGMDocu" name="name_OGMDocu" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">PP Doc / Rev</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_PPDocu" name="name_PPDocu" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">UD Doc / Rev</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_UDDocu" name="name_UDDocu" readonly>
                      </div>
                    </div>
                  </div>
<!--                   <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">O Drawing No./Revision</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_ODrawing" name="name_ODrawing" readonly>
                      </div>
                    </div>
                  </div>
 -->
                  <!--
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Application Time</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_AppTime" name="name_AppTime" value="<?php //echo date("H:i:s");?>" readonly>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Production Supervisor</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_prodn_supv" name="name_prodn_supv" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" readonly>
                      </div>
                    </div>
                  </div>
                -->
              </div>

              <div class="col-sm-4 p-2">
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">PM Doc / Rev</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_PMDocu" name="name_PMDocu" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">J/R/DJ/KS/DC/GJ Doc / Rev</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_JRDocu" name="name_JRDocu" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">GP MD Doc / Rev</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_GPMDDocu" name="name_GPMDDocu" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Application Date</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_AppDate" name="name_AppDate" value="<?php echo date("Y-m-d");?>" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Device Category</span>
                        </div>
                        <select class="form-control form-control-sm selectDevice" name="name_select_Device" id="id_select_Device">
                          <option value = "0" selected disabled>---</option>
                          <option value = "1">Automotive</option>
                          <option value = "2">Non-Automotive</option>
                          <option value = "3">Regular Device</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Certification Lot</span>
                        </div>
                        <select class="form-control form-control-sm selectCertLot" name="name_CertLot" id="id_CertLot">
                          <option value = "0" selected disabled>---</option>
                          <option value = "6">N/A</option>
                          <option value = "1">New Operator</option>
                          <option value = "2">New product/model</option>
                          <option value = "3">Evaluation lot</option>
                          <option value = "4">Re-inspection</option>
                          <option value = "5">Flexibility</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Assembly Line</span>
                        </div>
                        <select class="form-control form-control-sm selectAssyLine" name="name_AssyLine" id="id_AssyLine">
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Reel Lot No.</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_ReelNo" name="name_ReelNo" readonly autocomplete="off">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Print Lot No.</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_PrintLotNo" name="name_PrintLotNo" autocomplete="off">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Total No. of Reels</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_TtlNoReels" name="name_TtlNoReels" value="1" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Urgent Directions No.</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_UrgentDirection" name="name_UrgentDirection" autocomplete="off">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Partial Lot Confirmation</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_partial_lot_confirmation" name="partial_lot_confirmation" autocomplete="off">
                      </div>
                    </div>
                  </div>
                  <!--
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">OQC Supervisor</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_oqc_supv" name="name_oqc_supv" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" readonly>
                      </div>
                    </div>
                  </div>
                  -->
              </div>

              <div class="col-sm-4 p-2">
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-100">
                          <span class="input-group-text w-100" id="basic-addon1">Guaranteed Lot (Containtment actions from in-process <br>
                          defects, OQC lot-out, internal & customer claim)</span>
                        </div>
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <input class="form-control-sm" type="radio" id="id_GuaranteedLotWith" name="name_GuaranteedLot" value="1"> &nbsp; WITH &nbsp;&nbsp;
                          <input class="form-control-sm" type="radio" id="id_GuaranteedLotWO" name="name_GuaranteedLot" value="2"> &nbsp; WITHOUT
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Problem</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_Problem" name="name_Problem" autocomplete="off" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Document No.</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_DocNo" name="name_DocNo" autocomplete="off" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row row_container">
                    <div class="col">
                        <div class="input-group input-group-sm mb-3">
                          <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100" id="basic-addon1">Lot Applied By</span>
                          </div>
                          <input type="text" class="form-control txt_search_name" list="dl_search_name_fvo" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                          <datalist class="dl_search_name" id="dl_search_name_fvo"></datalist>
                          <input type="hidden" class="txt_arr_names" name="txt_arr_names_fvo">
                          <div class="input-group-append" style="display:none;">
                            <button class="btn btn-success btn_add_name" type="button" >Add</button>
                          </div>
                        </div>
                        <div class="form-group col-sm-12 names_container">
                        </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                        </div>
                        <textarea class="form-control form-control-sm" rows="3" id="id_OQCRemarks" name="name_OQCRemarks"></textarea>
                      </div>
                    </div>
                  </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12 p-2">
                <span class="fa fa-list"></span> OQC Lot Application Summary
                <br>
                <br>
                  <div class="table-responsive">
                    <table id="tbl_LotApp_summary" class="table table-bordered table-striped table-hover" style="min-width: 1500px!important;">
                      <thead style="font-size:85%;">
                        <tr align="center">
                          <th>Submission</th>
                          <th>Guaranteed Lot</th>
                          <th>Problem</th>
                          <th>Document No.</th>
                          <th>App. Date</th>
                          <th>App. Time</th>
                          <th>Lot Applied By</th>
                          <th>Remarks</th>
                        </tr>
                      </thead>
                      <tbody style="font-size:85%;"></tbody>
                    </table>
                  </div>
              </div>
            </div>


          </div>

          <div class="modal-footer">
            <button type="submit" id="id_btn_AddOQCLotApp" class="btn btn-primary btn-sm"><i class="fa fa-check fa-xs"></i> Save</button>
            <button type="button" id="id_btn_ApprovedProdn" class="btn btn-success btn-sm"><i class="fa fa-thumbs-up fa-xs"></i> Prod Approved</button>
            <button type="button" id="id_btn_ApprovedOQC" class="btn btn-success btn-sm"><i class="fa fa-thumbs-up fa-xs"></i> OQC Approved</button>
            <button type="button" id="id_btn_close_oqcla" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalApprovedByProd" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formApprovedByProd">
          @csrf

        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
            Please scan your ID.
            <br><br>
            <h1><i class="fa fa-qrcode fa-lg"></i></h1>
          </div>
              <input type="hidden" class="form-control" id="hidden_OQCLotApp_id_query" name="hidden_OQCLotApp_id_query">
              <input type="text" class="form-control hidden_scanner_input" id="id_prodn_supv_id" name="name_prodn_supv_id" >
        </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalApprovedByOQC" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formApprovedByOQC">
          @csrf

        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
            Please scan your ID.
            <br><br>
            <h1><i class="fa fa-qrcode fa-lg"></i></h1>
          </div>
              <input type="hidden" class="form-control" id="hidden_OQCLotApp_id_query_oqc" name="hidden_OQCLotApp_id_query_oqc">
              <input type="text" class="form-control hidden_scanner_input" id="id_oqc_supv_id" name="name_oqc_supv_id" >
        </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_LotApp_QRcode">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-qrcode"></i> OQC Lot Application - QR Code</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
              <div class="row">
                <!-- PO 1 -->
                <div class="col-sm-12">

                  <center>

                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->margin(5)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_barcode_PO" style="max-width: 200px;">
                    <br>

                  </center>

                    <label id="img_barcode_PO_text"></label>
                    <!-- <label hidden id="img_barcode_PO_text_hidden"></label> -->

                  <!-- <label id="lbl_po_no_PO"></label> <br> -->
                  <!-- <label id="lbl_device_name_PO"></label> <br> -->
                </div>

                <!-- Lot/batch# 1-->
                <!-- <div class="col-sm-6">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_barcode_lotno1" style="max-width: 200px;">
                  <br>
                  <label id="lbl_device_name"></label> <br>
                  <label id="lbl_po_no"></label> <br>
                  <label id="lbl_lot_batch_no"></label> <br>
                  <label id="lbl_reel_lot_no"></label> <br>
                  <label id="lbl_lot_qty"></label> /
                  <label id="lbl_output_qty1"></label> <br>
                  <label id="lbl_sticker_page_no"></label>
                </div> -->

                <!-- PO 2 -->
                <!-- <div class="col-sm-6">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->margin(5)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_barcode_PO2" style="max-width: 200px;">
                  <br>
                  <label id="lbl_po_no_PO2"></label> <br>
                  <label id="lbl_device_name_PO2"></label> <br>
                </div> -->

                <!-- Lot/batch# 2-->
               <!--  <div class="col-sm-6">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_barcode_lotno2" style="max-width: 200px;">
                  <br>
                  <label id="lbl_device_name2"></label> <br>
                  <label id="lbl_po_no2"></label> <br>
                  <label id="lbl_lot_batch_no2"></label> <br>
                  <label id="lbl_reel_lot_no2"></label> <br>
                  <label id="lbl_lot_qty2"></label> /
                  <label id="lbl_output_qty2"></label> <br>
                  <label id="lbl_sticker_page_no2"></label>
                </div> -->

                <!-- A Drawing -->
                <!-- <div class="col-sm-4">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_A_drawing" style="max-width: 200px;">
                  <br>
                  <label id="lbl_po_Adrawing"></label> <br>
                  <label id="lbl_device_name_Adrawing"></label> <br>
                  <label id="lbl_adrawing"></label> <br>
                </div> -->

                <!-- Inspection Standard -->
                <!-- <div class="col-sm-4">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_IS_drawing" style="max-width: 200px;">
                  <br>
                  <label id="lbl_po_isdrawing"></label> <br>
                  <label id="lbl_device_name_isdrawing"></label> <br>
                  <label id="lbl_isdrawing"></label> <br>
                </div> -->

                <!-- R Drawing -->
                <!-- <div class="col-sm-4">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_R_drawing" style="max-width: 200px;">
                  <br>
                  <label id="lbl_po_rdrawing"></label> <br>
                  <label id="lbl_device_name_rdrawing"></label> <br>
                  <label id="lbl_rdrawing"></label> <br>
                </div> -->

              </div>
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
          <input type="text" id="txt_search_po_number" class="hidden_scanner_input">
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalViewRuncard" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <h4 class="modal-title"></i> Runcards</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">

                    <div class="table-responsive">
                      <table class="table table-sm small table-bordered table-hover" id="tbl_prod_runcard_stations" style="width: 100%;">
                        <thead>
                          <tr class="bg-light">
                            <!-- <th></th> -->
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
                          <th colspan="4"></th>
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
    </div>
  </div>



  <!---MODAL FOR PRINTING OQC LOT APPLICATION STICKER-->
<!--     <div class="modal fade" id="modal_LotApp_QRcode">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-qrcode"></i> OQC Lot Application - QR Code</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <center>
              <div class="row">
                <div class="col-sm-6">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->margin(5)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_barcode_PO" style="max-width: 200px;">
                  <br>
                  <label id="lbl_po_no_PO"></label> <br>
                  <label id="lbl_device_name_PO"></label> <br>
                </div>

                <div class="col-sm-6">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_barcode_lotno1" style="max-width: 200px;">
                  <br>
                  <label id="lbl_device_name"></label> <br>
                  <label id="lbl_po_no"></label> <br>
                  <label id="lbl_lot_batch_no"></label> <br>
                  <label id="lbl_reel_lot_no"></label> <br>
                  <label id="lbl_lot_qty"></label> /
                  <label id="lbl_output_qty1"></label> <br>
                  <label id="lbl_sticker_page_no"></label>
                </div> -->

                <!-- PO 2 -->
<!--                 <div class="col-sm-6">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->margin(5)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_barcode_PO2" style="max-width: 200px;">
                  <br>
                  <label id="lbl_po_no_PO2"></label> <br>
                  <label id="lbl_device_name_PO2"></label> <br>
                </div> -->

                <!-- Lot/batch# 2-->
<!--                 <div class="col-sm-6">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_barcode_lotno2" style="max-width: 200px;">
                  <br>
                  <label id="lbl_device_name2"></label> <br>
                  <label id="lbl_po_no2"></label> <br>
                  <label id="lbl_lot_batch_no2"></label> <br>
                  <label id="lbl_reel_lot_no2"></label> <br>
                  <label id="lbl_lot_qty2"></label> /
                  <label id="lbl_output_qty2"></label> <br>
                  <label id="lbl_sticker_page_no2"></label>
                </div> -->

                <!-- A Drawing -->
<!--                 <div class="col-sm-4">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_A_drawing" style="max-width: 200px;">
                  <br>
                  <label id="lbl_po_Adrawing"></label> <br>
                  <label id="lbl_device_name_Adrawing"></label> <br>
                  <label id="lbl_adrawing"></label> <br>
                </div> -->

                <!-- Inspection Standard -->
<!--                 <div class="col-sm-4">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_IS_drawing" style="max-width: 200px;">
                  <br>
                  <label id="lbl_po_isdrawing"></label> <br>
                  <label id="lbl_device_name_isdrawing"></label> <br>
                  <label id="lbl_isdrawing"></label> <br>
                </div> -->

                <!-- R Drawing -->
<!--                 <div class="col-sm-4">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_R_drawing" style="max-width: 200px;">
                  <br>
                  <label id="lbl_po_rdrawing"></label> <br>
                  <label id="lbl_device_name_rdrawing"></label> <br>
                  <label id="lbl_rdrawing"></label> <br>
                </div> -->

<!--               </div>
            </center>
        </div>
        <div class="modal-footer">
            <button type="submit" id="btn_print_barcode" class="btn btn-primary btn-sm"><i class="fa fa-print fa-xs"></i> Print</button>
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div> -->

<div class="modal fade show" id="modal_addOverAllInspection" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-modal="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg">
          <div class="modal-header">
            <h5 class="modal-title">Overall Inspection</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <!-- <br> -->
            <!-- <h5 class="modal-title h5OIHeaderTitle">Overall Inspection - <span>0</span> Selected Runcards</h5> -->
          </div>
          <div class="modal-body">
            <div id="frm_edit_prod_runcard_station_details">
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Process</span>
                    </div>
                    <input type="text" value="Overall Inspection" class="form-control" disabled>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Date</span>
                    </div>
                    <input type="date" class="form-control form-control-sm" id="modal_addOverAllInspection_date" name="date" readonly>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Input</span>
                    </div>
                    <input type="number" class="form-control form-control-sm" id="modal_addOverAllInspection_input" name="qty_input" min="0">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Output</span>
                    </div>
                    <input type="number" class="form-control form-control-sm" id="modal_addOverAllInspection_output" name="qty_output" min="0">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">NG Qty</span>
                    </div>
                    <input type="number" class="form-control form-control-sm" id="modal_addOverAllInspection_ng" name="qty_ng" min="0" value="0" readonly="true">
                  </div>
                </div>
              </div>
              <hr>
              <div class="table-responsive">
                <div style="float: left;">
                  <label>Total No. of NG: <span id="modal_addOverAllInspection_mod_ng_count" style="color: red;">1</span></label>
                </div>
                <div style="float: right;">
                  <button type="button" id="modal_addOverAllInspection_mod_add_btn" class="btn btn-xs btn-info" title="Add MOD"><i class="fa fa-plus"></i> Add MOD</button>
                </div>
                <br><br>
                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th style="width: 55%;">MOD</th>
                      <th style="width: 15%;">QTY</th>
                      <!-- <th style="width: 20%;">Type of NG</th> -->
                      <th style="width: 10%;">Action</th>
                    </tr>
                  </thead>
                  <tbody id="modal_addOverAllInspection_mod_table"></tbody>
                </table>
              </div>



              <input type="hidden" id="modal_addOverAllInspection_runcard_id">

            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" id="txt_prod_runcard_station_id_query" name="txt_prod_runcard_station_id_query">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" id="modal_addOverAllInspection_btn_save">Save</button>
          </div>
        </div>
      </div>
    </div>

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

  @endsection

  @section('js_content')
  <script type="text/javascript">
    let dataTableOQCLotApp;
    let dataTableOQCLotAppSummary;

    let dt_prod_runcard_stations
    let dt_prod_runcard_stations_id = 0

    let img_barcode_lotno1;
    let img_barcode_lotno2;
    let lbl_device_name;
    let lbl_po_no;
    let lbl_lot_batch_no;
    let lbl_reel_lot_no;
    let lbl_lot_qty;
    let lbl_output_qty;
    let new_lot_qty1;
    let new_lot_qty2;

    let img_barcode_PO;
    let img_A_drawing;
    let lbl_adrawing;
    let img_IS_drawing;
    let lbl_isdrawing;
    let img_R_drawing;
    let lbl_rdrawing;

    let img_barcode_PO_text_hidden

    let list_of_mods = ""
    let list_of_mods_counter = 0
    let mod_counter = 0


    function calculateNG() {
      $('#modal_addOverAllInspection_ng').val( $('#modal_addOverAllInspection_input').val() - $('#modal_addOverAllInspection_output').val() )
    }


    $(document).ready(function () {

      $('#modal_addOverAllInspection_mod_add_btn').click(function(e){
        $('#modal_addOverAllInspection_mod_table').append(
          "<tr id='modal_addOverAllInspection_mod_table_tr_" + list_of_mods_counter + "'>" +
            "<td> <select class='form-control select_mod'>" + list_of_mods + "</select> </td>" +
            "<td> <input class='form-control ng_qtt_mod' type='number' min='1' value='1'> </td>" +
            "<td> <center> <button type='button' class='btn btn-xs btn-danger btnMODRemove' tr_id='modal_addOverAllInspection_mod_table_tr_" + list_of_mods_counter + "'><i class='fa fa-trash'></i></button> </center> </td>" +
          "</tr>"
        )
        list_of_mods_counter++
        mod_counter++
        $('#modal_addOverAllInspection_mod_ng_count').html(mod_counter)
      })

      $(document).on('click', '.btnMODRemove', function(){
        $('#' + $(this).attr('tr_id')).remove()
        mod_counter--
        $('#modal_addOverAllInspection_mod_ng_count').html(mod_counter)
      })

      $('#modal_addOverAllInspection_btn_save').click(function() {
        if( $('#modal_addOverAllInspection_input').val() <= 0 )
          alert( 'Input is required' )
        else{
          if( $('#modal_addOverAllInspection_input').val() < $('#modal_addOverAllInspection_output').val() )
            alert('Output is always less than or equal to input.')
          else{

            let ttl_ng = 0
            $('.ng_qtt_mod').each(function(i, obj) {
              ttl_ng = ttl_ng + parseInt($(this).val())
            })
            // alert(ttl_ng)

            if( $('#modal_addOverAllInspection_ng').val()==0 && mod_counter>0 ){
              alert('MOD is not needed.')
              return
            }

            if( ttl_ng != $('#modal_addOverAllInspection_ng').val() ){
              alert('Total Quantity in MOD is not equal to NG Quantity')
            }else{
              $('#mdl_qrcode_scanner').modal('show')
              $('#txt_qrcode_scanner').val('')
              $('#txt_qrcode_scanner').focus()
            }

          }
        }
      })

      $('#modal_addOverAllInspection_input').change(function(e){
        calculateNG()
      })
      $('#modal_addOverAllInspection_output').change(function(e){
        calculateNG()
      })

      $('#txt_qrcode_scanner').keypress(function(e){
        if(e.keyCode == 13){

          $.ajax({
            'data'      : { employee_id: $("#txt_qrcode_scanner").val() },
            'type'      : 'get',
            'dataType'  : 'json',
            'url'       : 'check_employee_id',
            success     : function(data){
              if( data['result'] ){

                // let ids = ''
                // $('.select_runcard_id').each(function(i, obj) {
                //   if( $(this).prop('checked') )
                //     ids += $(this).attr('data') + ','
                // })

                let ng_qtt_mod = []
                $('.ng_qtt_mod').each(function(i, obj) {
                  ng_qtt_mod.push( $(this).val() )
                })
                let select_mod = []
                $('.select_mod').each(function(i, obj) {
                  select_mod.push( $(this).val() )
                })

                $.ajax({
                  type      : "post",
                  dataType  : "json",
                  url       : "saveOverallInspection",
                  data      : {
                    _token:       "{{ csrf_token() }}",
                    // po_no:        $('#txt_po_no').val(),
                    prod_runcard_id:  $('#modal_addOverAllInspection_runcard_id').val(),
                    input:            $('#modal_addOverAllInspection_input').val(),
                    output:           $('#modal_addOverAllInspection_output').val(),
                    ng:               $('#modal_addOverAllInspection_ng').val(),
                    date:             $('#modal_addOverAllInspection_date').val(),
                    employee_id:      $('#txt_qrcode_scanner').val(),
                    // ids:          ids,
                    ng_qtt_mod:       ng_qtt_mod,
                    select_mod:       select_mod,
                  },
                  success : function(data){
                    if( data['result'] == 1 ){

                      dataTableOQCLotApp.draw()
                      $('#btnOverallInspection').html('Overall Inspection')
                      $('#mdl_qrcode_scanner').modal('hide')
                      $('#modal_addOverAllInspection').modal('hide')

                    }
                  }
                });

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
      })

      $('#btnOverallInspection').click(function() {
        let cnt = 0
        $('.select_runcard_id').each(function(i, obj) {
          if( $(this).prop('checked') )
            cnt++
        })
        if(cnt==0)
          alert('No selected Lot/Batch #')
        else{
          $('#modal_addOverAllInspection_date').val("<?php echo date('Y-m-d'); ?>")
          $('#modal_addOverAllInspection_input').val(0)
          $('#modal_addOverAllInspection_output').val(0)
          $('#modal_addOverAllInspection').modal('show')
        }
      })

      $(document).on('click', '.select_runcard_id', function(){
        let cnt = 0
        $('.select_runcard_id').each(function(i, obj) {
          if( $(this).prop('checked') )
            cnt++
        })
        if(cnt==0)
          $('#btnOverallInspection').html('Overall Inspection')
        else
          $('#btnOverallInspection').html('Overall Inspection (' + cnt + ')')
      })

      GetAssemblyLines($(".selectAssyLine"));

      dataTableOQCLotApp = $("#tblOQCLotApp").DataTable({
        "processing"    : false,
          "serverSide"  : true,
          "ajax"        : {
            url: "get_oqc_lot_app_data_for_overAllInspect",
              data: function (param){
                param.po_no = $("#txt_po_no").val();
                param.device_name = $("#txt_device_name").val();
              }
          },

          "columns":[
            // { "data" : "select", orderable:false, searchable:false },
            { "data" : "action", orderable:false, searchable:false },
            { "data" : "status_raw",defaultContent:'pending' },
            // { "data" : "subm_raw" },
            { "data" : "runcard_no" },
            // { "data" : "sub_lot_raw" },
            // { "data" : "sub_lot_raw" },
            { "data" : "lot_qty" },
            { "data" : "balance" },
            // { "data" : "output_qty_raw" },
            { "data" : "fvo_raw" },
            // { "data" : "oqc_details.supervisor_prod_info.name" },
            // { "data" : "oqc_details.supervisor_qc_info.name" }
          ],
          "bPaginate": false,
          "columnDefs": [
            {
              // "targets": [1,7,8,9,10],
              // "targets": [1,4,6,7,8,9],
              // "targets": [1,5,6],
              "data": null,
              "defaultContent": "---"
            }
          ],

          order:[[3,'asc']]

        }); //end of dataTable

        dataTableOQCLotAppSummary = $("#tbl_LotApp_summary").DataTable({
          "processing"    : false,
            "serverSide"  : true,
            "ajax"        : {
              url: "get_oqc_lot_app_data_summary",
                data: function (param){
                  param.lot_batch_no = $("#id_LotBatch").val();
                }
            },

            "columns":[
              { "data" : "sub_raw" },
              { "data" : "guar_lot_raw" },
              { "data" : "problem" },
              { "data" : "doc_no" },
              { "data" : "app_date_raw" },
              { "data" : "app_time_raw" },
              { "data" : "fvo_raw" },
              { "data" : "remarks" }
            ],

            order:[[0,'asc']]

          }); //end of dataTable


      $(document).on('click', '.btnView', function(){
        dt_prod_runcard_stations_id = $(this).val();
        // alert( dt_prod_runcard_stations_id )
        dt_prod_runcard_stations.draw()
        $('#modalViewRuncard').modal('show')
      });
      $(document).on('click', '.btnOverall', function(){
        id = $(this).val()
        $.ajax({
          type      : "get",
          dataType  : "json",
          url       : "getTotalLotQuantityInOverallInspect",
          data      : {
            prod_runcard_id: id
          },
          success : function(data){
            if( data['data'] != undefined ){
              data = data['data']
              $('#modal_addOverAllInspection_runcard_id').val(id)
              $('#modal_addOverAllInspection_date').val("<?php echo date('Y-m-d'); ?>")
              $('#modal_addOverAllInspection_ng').val(data['need_qtt'])
              $('#modal_addOverAllInspection_input').val(data['need_qtt'])
              $('#modal_addOverAllInspection_input').attr('max', data['need_qtt'])
              $('#modal_addOverAllInspection_input').attr('min', 1)
              if( data['overall_qtt'] > 0 )
                $('#modal_addOverAllInspection_input').attr('disabled', true)
              else
                $('#modal_addOverAllInspection_input').attr('disabled', false)
              $('#modal_addOverAllInspection_output').val(0)
              list_of_mods_counter=1
              mod_counter=0
              $('#modal_addOverAllInspection_mod_ng_count').html(mod_counter)
              $('#modal_addOverAllInspection_mod_table').html('')

              $.ajax({
                'type'      : 'get',
                'dataType'  : 'json',
                'url'       : 'get_mods',
                success     : function(data){
                  if( data['mods'] != undefined ){
                    list_of_mods = data['mods']
                    let html = ""
                    for (var i = 0; i < list_of_mods.length; i++)
                      html += "<option value='" + list_of_mods[i]['id'] + "'>" + list_of_mods[i]['name'] + "</option>"
                    list_of_mods = html
                  }
                  $('#modal_addOverAllInspection').modal('show')
                },
              });

            }
          }
        });


      });

      dt_prod_runcard_stations = $('#tbl_prod_runcard_stations').DataTable({
        "processing" : true,
        "serverSide" : true,
        "ajax" : {
          url: "view_prod_runcard_stations",
          data: function (param){
              param.prod_runcard_id_query = dt_prod_runcard_stations_id;
          }
        },
        "columns":[
          { "data" : "runcard_no", orderable:false, searchable:true },
          { "data" : "process_name", orderable:false, searchable:true },
          { "data" : "updated_at", orderable:false, searchable:true },
          { "data" : "ct_area_info.name", orderable:false, searchable:true },
          { "data" : "terminal_area_info.name", orderable:false, searchable:true },
          { "data" : "qty_input", orderable:false, searchable:true },
          { "data" : "qty_output", orderable:false, searchable:true },
          { "data" : "qty_ng", orderable:false, searchable:true },
          {
              name: 'prod_runcard_station_mod_details',
              data: 'prod_runcard_station_mod_details',
              sortable: false,
              searchable: false,
              render: function (data) {
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
        // order: [[1, "asc"]],
        paging: false,
        info: false,
        searching: false,
        pageLength: -1,
        // "columnDefs": [
        //       {
        //         // "targets": [3, 4, 5, 6, 7, 8, 9],
        //         "data": null,
        //         "defaultContent": "--"
        //       },
        // ],
           "drawCallback": function ( settings ) {
              var api = this.api();
              var rows = api.rows( {page:'current'} ).nodes();
              var last = null;
              let totalNoRuncardOfOk = 0;
              let totalNoRuncardOfInput = 0;
              let totalNoRuncardOfNG = 0;
              if(api.rows().count() > 0){
                api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                    let data = api.row(i).data();
                    totalNoRuncardOfOk += parseInt(data['qty_output']);
                    totalNoRuncardOfInput += parseInt(data['qty_input']);
                    totalNoRuncardOfNG += parseInt(data['qty_ng']);
                });
              }
              $("#thRuncardTotalInput").html('<b>' + totalNoRuncardOfInput + '</b>');
              $("#thRuncardTotalOutput").html('<b>' + totalNoRuncardOfOk + '</b>');
              $("#thRuncardTotalNG").html('<b>' + totalNoRuncardOfNG + '</b>');
            }
      });

      $("#id_btn_close_oqcla").click(function(){
        dataTableOQCLotApp.ajax.reload();
      });


      // Add
      $("#formOQCLotApp").submit(function(event){
        event.preventDefault();

          if ($('#id_select_Device').val() == null){
              alert('Device Category is required.');
              return;
          } else if ($('#id_CertLot').val() == null){
              alert('Certification Lot is required.');
              return;
          } else if ($('#id_AssyLine').val() == null){
              alert('Assembly Line Lot is required.');
              return;
          }

          if ($('#id_ReelNo').val() == ''){
              alert('Reel Lot # is required.');
              return;
          } else if ($('#id_PrintLotNo').val() == ''){
              alert('Print Lot # is required.');
              return;
          }

          var name_GuaranteedLot = $("input[name='name_GuaranteedLot']:checked").val();
          if ( !$("input[name='name_GuaranteedLot']:checked").val()){
                alert('Guaranteed Lot is required.');
                return;
          } else if ( name_GuaranteedLot == 1 ){
              if($('#id_Problem').val() == '' && $('#id_DocNo').val() == ''){
                alert('Problem & Document no. is required.');
                return;
              }
          }

          if ($('#id_OutputQty').val() == ''){
              alert('Output Qty is required.');
              return;
          }

          AddOQCLotApp();
      });

      // View/Update
      $(document).on('click', '.btn_update_lot', function(){

          $('#modalOQCLotApp').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
          });

            id = $(this).val();
            var data = {
                "id"  :  id,
                "device_name" : $('#txt_device_name').val()
            }

            data = $.param(data);
            $.ajax({
                data        : data,
                type        : 'get',
                dataType    : 'json',
                url         : "get_runcard_details",
                success     : function (JSONDATA) {
                  // console.log(data);

                  let data = JSONDATA['runcard_details']

                  $('#hidden_require_oqc_before_emboss').val( data[0]['require_oqc_before_emboss'] );
                  $('#hidden_runcard_status').val( data[0]['status'] );
                  $('#hidden_sub_lot').val( data[0]['sub_lot_no'] );

                  //- Runcard
                  $('#hidden_runcard_id').val( data[0]['id'] );
                  $('#id_currentPONo').val( data[0]['po_no'] );
                  $('#id_device_name').val( $('#txt_device_name').val() );
                  $('#id_LotBatch').val( data[0]['runcard_no'] );
                  $('#id_LotQty').val( data[0]['lot_qty'] );
                  $('#id_OutputQty').val( data[0]['output_qty'] );
                  // $('#id_ReelNo').val( data[0]['reel_lot_no'] );

                  if ( data[0]['orig_a_drawing_no'] == 'N/A' ){
                      $('#id_OrigADrawing').val('--');
                  }else{
                    $('#id_OrigADrawing').val( data[0]['orig_a_drawing_no'] +'-'+ data[0]['orig_a_revision']);
                  }
                  if ( data[0]['a_drawing_no'] == 'N/A' ){
                      $('#id_ADrawing').val('--');
                  }else{
                    $('#id_ADrawing').val( data[0]['a_drawing_no'] +'-'+ data[0]['a_revision']);
                  }
                  if ( data[0]['g_drawing_no'] == 'N/A' ){
                      $('#id_GDrawing').val('--');
                  }else{
                    $('#id_GDrawing').val( data[0]['g_drawing_no'] +'-'+ data[0]['g_revision']);
                  }
                  if ( data[0]['wi_d'] == 'N/A' ){
                      $('#id_WIDocu').val('--');
                  }else{
                    $('#id_WIDocu').val( data[0]['wi_d'] +'-'+ data[0]['wi_d_revision']);
                  }
                  if ( data[0]['ogm_vig_ig_d'] == 'N/A' ){
                      $('#id_OGMDocu').val('--');
                  }else{
                    $('#id_OGMDocu').val( data[0]['ogm_vig_ig_d'] +'-'+ data[0]['ogm_vig_ig_d_revision']);
                  }
                  if ( data[0]['pp_d'] == 'N/A' ){
                      $('#id_PPDocu').val('--');
                  }else{
                    $('#id_PPDocu').val( data[0]['pp_d'] +'-'+ data[0]['pp_d_revision']);
                  }
                  if ( data[0]['ud_d'] == 'N/A' ){
                      $('#id_UDDocu').val('--');
                  }else{
                    $('#id_UDDocu').val( data[0]['ud_d'] +'-'+ data[0]['ud_d_revision']);
                  }
                  if ( data[0]['pm'] == 'N/A' ){
                      $('#id_PMDocu').val('--');
                  }else{
                    $('#id_PMDocu').val( data[0]['pm'] +'-'+ data[0]['pm_revision']);
                  }
                  if ( data[0]['j_r_dj_ks_dc_gj'] == 'N/A' ){
                      $('#id_JRDocu').val('--');
                  }else{
                    $('#id_JRDocu').val( data[0]['j_r_dj_ks_dc_gj'] +'-'+ data[0]['j_r_dj_ks_dc_gj_revision']);
                  }
                  if ( data[0]['gp_md'] == 'N/A' ){
                      $('#id_GPMDDocu').val('--');
                  }else{
                    $('#id_GPMDDocu').val( data[0]['gp_md'] +'-'+ data[0]['gp_md_revision']);
                  }


                  // if ( data[0]['o_drawing_no'] == 'N/A' ){
                  //     $('#id_ODrawing').val('-dfsdfs-');
                  // }else{
                  //   $('#id_ODrawing').val( data[0]['o_drawing_no'] +'-'+ data[0]['o_revision']);
                  // }


                  $("input[name='name_GuaranteedLot']").click(function() {
                        name_GuaranteedLot = this.value;

                        if ( name_GuaranteedLot == 1 ){
                          $('#id_Problem').attr('readonly',false);
                          $('#id_DocNo').attr('readonly',false);
                        }else{
                          $('#id_Problem').attr('readonly',true);
                          $('#id_DocNo').attr('readonly',true);
                          $('#id_Problem').val('');
                          $('#id_DocNo').val('');
                        }

                  });

                  $('.txt_arr_names').val('').change();

                  dataTableOQCLotAppSummary.draw();

                  //- OQC lot app
                  if(data[0]['oqc_details'] != null) {

                      hidden_sub = (data[0]['oqc_details']['submission'] * 1 + 1);
                      $('#hidden_sub').val(hidden_sub);

                      // $('#id_OutputQty').val( data[0]['oqc_details']['output_qty'] );

                      $('#id_select_Device').val( data[0]['oqc_details']['device_cat'] );
                      $('#id_CertLot').val( data[0]['oqc_details']['cert_lot'] );
                      $('#id_AssyLine').val( data[0]['oqc_details']['assy_line'] );
                      $('#id_ReelNo').val( data[0]['oqc_details']['reel_lot'] );
                      $('#id_PrintLotNo').val( data[0]['oqc_details']['print_lot'] );
                      $('#id_UrgentDirection').val( data[0]['oqc_details']['direction'] );
                      $('#id_TtlNoReels').val( data[0]['oqc_details']['ttl_reel'] );
                      $('#id_partial_lot_confirmation').val( data[0]['oqc_details']['partial_lot_confirmation'] );

                      if(data[0]['oqc_details']['guaranteed_lot'] == 1) {
                        $('#id_GuaranteedLotWith').prop('checked', true);
                      }else{
                        $('#id_GuaranteedLotWO').prop('checked', true);
                      }
                      $(':radio:not(:checked)').attr('disabled', true);

                      $('#id_Problem').val( data[0]['oqc_details']['problem'] );
                      $('#id_DocNo').val( data[0]['oqc_details']['doc_no'] );

                      $('#id_select_Device').attr('readonly',true);
                      $('#id_CertLot').attr('readonly',true);
                      $('#id_AssyLine').attr('readonly',true);
                      $('#id_ReelNo').attr('readonly',true);
                      $('#id_PrintLotNo').attr('readonly',true);
                      $('#id_UrgentDirection').attr('readonly',true);
                      $('#id_TtlNoReels').attr('readonly',true);
                      $('#id_Problem').attr('readonly',true);
                      $('#id_DocNo').attr('readonly',true);
                      $('#id_partial_lot_confirmation').attr('readonly',true);

                      $('.txt_arr_names').val('').change();

                      $('#hidden_OQCLotApp_id').val(data[0]['oqc_details']['id']);
                      $('#hidden_OQCLotApp_id_query').val(data[0]['oqc_details']['id']);
                      $('#hidden_OQCLotApp_id_query_oqc').val(data[0]['oqc_details']['id']);
                      $('#hidden_status').val(data[0]['oqc_details']['status']);

                      //- Validation
                      if ( $('#hidden_status').val() == '0'){ // For prodn approval
                        if( $('#hidden_position').val() == '1' ){ // prodn supervisor
                          $('#id_btn_ApprovedProdn').show();
                          $('#id_btn_ApprovedOQC').hide();
                          $('#id_btn_AddOQCLotApp').hide();
                        }else{
                          $('#id_btn_ApprovedProdn').hide();
                          $('#id_btn_ApprovedOQC').hide();
                          $('#id_btn_AddOQCLotApp').hide();
                        }
                      }else if ( $('#hidden_status').val() == '1'){ // For OQC approval
                        if( $('#hidden_position').val() == '2' ){ // oqc supervisor
                          $('#id_btn_ApprovedProdn').hide();
                          $('#id_btn_ApprovedOQC').show();
                          $('#id_btn_AddOQCLotApp').hide();
                        }else{
                          $('#id_btn_ApprovedProdn').hide();
                          $('#id_btn_ApprovedOQC').hide();
                          $('#id_btn_AddOQCLotApp').hide();
                        }
                      }else if ( $('#hidden_status').val() == '2'){ //Done
                        if( $('#hidden_position').val() == '2' || $('#hidden_position').val() == '5'){ // inspector
                          $('#id_btn_ApprovedProdn').hide();
                          $('#id_btn_ApprovedOQC').hide();
                          $('#id_btn_AddOQCLotApp').hide();
                        }else{
                          $('#id_btn_ApprovedProdn').hide();
                          $('#id_btn_ApprovedOQC').hide();
                          $('#id_btn_AddOQCLotApp').show();
                        }
                      }

                      if ( data[0]['oqc_details']['oqcvir_details'] != null){
                        if ( data[0]['oqc_details']['oqcvir_details']['judgement'] == '1'){
                          $('#id_btn_AddOQCLotApp').hide();
                        }else if ( data[0]['oqc_details']['oqcvir_details']['judgement'] == '2'){
                          $('#id_btn_AddOQCLotApp').show();
                        }else{
                          $('#id_btn_AddOQCLotApp').hide();
                        }

                      }else{
                        $('#id_btn_AddOQCLotApp').hide();
                      }

                  } else {

                      $('#id_GuaranteedLotWith').prop('checked', false);
                      $('#id_GuaranteedLotWO').prop('checked', false);

                      $('#hidden_sub').val(1);

                      var ix = [];
                      var ix = data[0]['prod_runcard_station_many_details'].length;
                      // var qty_temp = 0;

                      // for(i=0;i<ix;i++){

                      //   //- Not required EMBOSS
                      //   if (data[0]['require_oqc_before_emboss'] == 1 && data[0]['prod_runcard_station_many_details'][i]['has_emboss'] == 0){
                      //     qty_temp = data[0]['prod_runcard_station_many_details'][i]['qty_output'];
                      //   }
                      //   //- EMBOSS muna after Runcard
                      //   if (data[0]['require_oqc_before_emboss'] == 0 && data[0]['prod_runcard_station_many_details'][i]['has_emboss'] == 1){
                      //     qty_temp = data[0]['prod_runcard_station_many_details'][i]['qty_output'];
                      //   }

                      //   // if (data[0]['require_oqc_before_emboss'] == 1 && data[0]['prod_runcard_station_many_details'][i]['has_emboss'] == 1){
                      //   //   qty_temp = data[0]['prod_runcard_station_many_details'][i]['qty_output'];
                      //   // }

                      // }

                      // $('#id_OutputQty').val( qty_temp );

                      $('.txt_arr_names').val('').change();

                      if ( $('#id_OutputQty').val() != $('#id_LotQty').val() ){
                          alert('Output Quantity is not equal to Lot Quantity.\nNeeds approval from Prodn. Supervisor & OQC Supervisor!');
                      }

                      if ( $('#hidden_position').val() == '2' || $('#hidden_position').val() == '5'){
                          $('#id_btn_ApprovedProdn').hide();
                          $('#id_btn_ApprovedOQC').hide();
                          $('#id_btn_AddOQCLotApp').hide();
                      }else{

                         if ( $('#hidden_require_oqc_before_emboss').val() == 1){
                          $('#id_btn_ApprovedProdn').hide();
                          $('#id_btn_ApprovedOQC').hide();
                          $('#id_btn_AddOQCLotApp').show();

                        }else{
                            if ( $('#hidden_runcard_status').val() == 3){
                              $('#id_btn_ApprovedProdn').hide();
                              $('#id_btn_ApprovedOQC').hide();
                              $('#id_btn_AddOQCLotApp').show();
                            }else{
                              $('#id_btn_ApprovedProdn').hide();
                              $('#id_btn_ApprovedOQC').hide();
                              $('#id_btn_AddOQCLotApp').hide();
                            }
                        }


                          // $('#id_btn_ApprovedProdn').hide();
                          // $('#id_btn_ApprovedOQC').hide();
                          // $('#id_btn_AddOQCLotApp').show();
                      }



                      $('#id_select_Device').attr('readonly',false);
                      $('#id_select_Device').val('');
                      $('#id_CertLot').attr('readonly',false);
                      $('#id_CertLot').val('');
                      $('#id_AssyLine').attr('readonly',false);
                      $('#id_AssyLine').val('');
                      $('#id_ReelNo').attr('readonly',false);
                      $('#id_ReelNo').val('');
                      $('#id_PrintLotNo').attr('readonly',false);
                      $('#id_PrintLotNo').val('');
                      $('#id_UrgentDirection').attr('readonly',false);
                      $('#id_UrgentDirection').val('');
                      $('#hidden_OQCLotApp_id').val('');
                      $('#hidden_status').val('');

                      $('#id_partial_lot_confirmation').attr('readonly',false);
                      $('#id_partial_lot_confirmation').val('');



                  }
                  // $('#id_btn_AddOQCLotApp').show();

                }, error    : function (data) {
                alert('ERROR: '+data);
                }
            });
      });
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
                        dataTableOQCLotApp.draw();
                    }, 500);

                }
            }
        } catch (error) {
            alert(`Error: ${error}`)
        }
    });

    //- search PO no.
    $(document).on('keypress','#txt_search_po_number',function(e){
        let obj_data = JSON.parse($('#txt_search_po_number').val()).oqc_lotapp_po_no;
        if(obj_data == undefined){
            alert('Invalid QR Code')
            return;
        }
        var data = {
          'po'      : obj_data
        }

        $('#tblOQCLotApp tbody tr').removeClass('table-active');
        $('#txt_po_no').val('');
        $('#txt_device_name').val('');
        $('#txt_po_qty').val('');
        $('#id_currentPONo').val('');

        data = $.param(data);
        $.ajax({
          type      : "get",
          dataType  : "json",
          data      : data,
          url       : "get_po_details",
          success : function(data){

            if(data['po_details'][0]['wbs_kitting'] == null || data['po_details'][0]['wbs_kitting'] == ''){
                // alert(data['po_details'][0]['yeu_kitting']['product_name'])
                $('#txt_po_no').val( data['po_details'][0]['yeu_kitting']['po_no'] );
                $('#txt_device_name').val(data['po_details'][0]['yeu_kitting']['product_name'] );
                $('#txt_device_code_lbl').val(data['po_details'][0]['yeu_kitting']['item_code']);
                $('#txt_po_qty').val( data['po_details'][0]['yeu_kitting']['po_qty'] );
                $('#id_currentPONo').val( data['po_details'][0]['yeu_kitting']['po_no'] );
            }else{
                // alert(data['po_details'][0]['wbs_kitting']['device_name'])
                $('#txt_po_no').val( data['po_details'][0]['po_no'] );
                $('#txt_device_name').val(data['po_details'][0]['wbs_kitting']['device_name'] );
                $('#txt_device_code_lbl').val(data['po_details'][0]['wbs_kitting']['device_code'] );
                $('#txt_po_qty').val( data['po_details'][0]['wbs_kitting']['po_qty'] );
                $('#id_currentPONo').val( data['po_details'][0]['po_no'] );

            }
            dataTableOQCLotApp.draw();
            //nmodify

          },error : function(data){

          }

        });
    });

    //- Approval Prodn
    $("#formApprovedByProd").submit(function(event){
      event.preventDefault();
      ApprovedProd_OQCLotApp();
    });

    $(document).on('click','#id_btn_ApprovedProdn',function(e){
      $('#id_prodn_supv_id').val('');
      $('#modalApprovedByProd').attr('data-formid','#formApprovedByProd').modal('show');
    });

    $(document).on('keypress',function(e){
      if( ($("#modalApprovedByProd").data('bs.modal') || {})._isShown ){
        $('#id_prodn_supv_id').focus();

        if( e.keyCode == 13 && $('#id_prodn_supv_id').val() !='' && ($('#id_prodn_supv_id').val().length >= 4) )
          {
            $('#modalApprovedByProd').modal('hide');
          }
        }
    });

    //- Approval OQC
    $("#formApprovedByOQC").submit(function(event){
      event.preventDefault();
      ApprovedOQC_OQCLotApp();
    });

    $(document).on('click','#id_btn_ApprovedOQC',function(e){
      $('#id_oqc_supv_id').val('');
      $('#modalApprovedByOQC').attr('data-formid','#formApprovedByOQC').modal('show');
    });

    $(document).on('keypress',function(e){
      if( ($("#modalApprovedByOQC").data('bs.modal') || {})._isShown ){
        $('#id_oqc_supv_id').focus();

        if( e.keyCode == 13 && $('#id_oqc_supv_id').val() !='' && ($('#id_oqc_supv_id').val().length >= 4) )
          {
            $('#modalApprovedByOQC').modal('hide');
          }
        }
    });

    //- Search employee
    $(".txt_search_name").on("keyup", function(e){
      var parent        = $(this).closest(".row_container");
      var data = {
        "action"          : "get_user_details_lotapp",
        "employee_id"     : $(this).val(),
      }
      data = $.param(data);
      $.ajax({
          type      : "get",
          dataType  : "json",
          data      : data,
          url       : "get_user_details_lotapp",
        success       : function(data){
          var list    = "";

          if ($.trim(data['user_details'])){
            for(var ctr=0;ctr<data['user_details'].length;ctr++){
              list+="<option value='"+data['user_details'][ctr]['id']+"'>"+data['user_details'][ctr]['employee_id']+' '+data['user_details'][ctr]['name']+"</option>";
            }
          }

          $(parent).find(".dl_search_name").html(list);
          if(e.keyCode == 13 || e.keyCode == undefined){
            $(parent).find('.btn_add_name').click();
          }
        }
      });
    });

    $(".txt_arr_names").on("change", function(){
      var names_container = $(this).closest(".row_container").find(".names_container");
      if($(this).val()==''||$(this).val()==0){$(names_container).html("");return;}
      set_operator_details(names_container, $(this).val());
    });

    $(".row_container .btn_add_name").on("click", function(){
      var parent        = $(this).closest(".row_container");
      var txt_search_name   = $(parent).find(".txt_search_name");
      var dl_search_name    = $(parent).find(".dl_search_name");
      var txt_arr_names     = $(parent).find(".txt_arr_names");


      var val         = $(txt_search_name).val();
      var obj         = $(dl_search_name).find("option[value='"+val+"']");

      if(!val){return;}

      if(obj !=null && obj.length>0){}//val is in option
        else{return;}
      // alert( $(obj).val() +' '+ val )
      var names_string    = $(txt_arr_names).val();
      var arr_names       = names_string.split(',');
        if(!names_string){
        arr_names = [];//set to empty array if txt val is null (it adds null element to array if so)
        }
        $(txt_search_name).val('');
      for (var i = arr_names.length - 1; i >= 0; i--) {
        if (arr_names[i]==val){return;}//alrady in the array
      }

      arr_names.push(val);
      $(txt_arr_names).val(arr_names.join(','));
      $(txt_arr_names).change();//this will output the names badge
    });

    $(".names_container").on("click",".badge",function(e){
      var parent      = $(this).closest(".row_container");
      var txt_arr_names   = $(parent).find(".txt_arr_names");

      var nameid      = $(this).data("nameid");
        var names_string  = $(txt_arr_names).val();
      var arr_names     = names_string.split(',');

      arr_names       = jQuery.grep(arr_names, function(value) {
        return value != nameid;//remove click
      });
        $(txt_arr_names).val(arr_names.join(','));

      $(e.target).remove();
    });

    function set_operator_details(names_container, empno_arr){
      var data = {
        "empno_arr"   : empno_arr,
      }
      data = $.param(data);
      $.ajax({
        type      : "get",
        dataType  : "json",
        data      : data,
        url       : "get_user_details_lotapp_arr",
        success     : function(data){
          var names_full ="";
          if ($.trim(data['user_details'])){
            for(var ctr=0;ctr<data['user_details'].length;ctr++){
              names_full += '<span class="badge badge-info" title="Click to remove" data-nameid="'+data['user_details'][ctr][0]['id']+'">'+data['user_details'][ctr][0]['name']+' &times;</span> ';
            }
          }
          $(names_container).html(names_full);

        }
      });
    }

    //- Print lot
    $(document).on('click', '.btn_print_lot', function(){
      var id = $(this).val();

        var data = {
            "id" : id,
            "device_name" : $('#txt_device_name').val(),
        }

        data = $.param(data);
        $.ajax({
            data        : data,
            type        : 'get',
            dataType    : 'json',
            url         : "generate_qrcode_for_oqc_lot_app",
            success     : function (data) {

              // alert( 'asfadf' )
              $("#img_barcode_PO").attr('src', data['QrCode']);
              $("#img_barcode_PO_text").html(data['label']);
              // $("#img_barcode_PO_text_hidden").html(data['label_hidden']);
              img_barcode_PO_text_hidden = data['label_hidden']
              $("#modal_LotApp_QRcode").modal('show');

              // console.log(data);

              //   $("#modal_LotApp_QRcode").modal('show');

              //   //- Lot/Batch# 1
              //   $("#img_barcode_lotno1").attr('src', data['lot_app_code']);
              //   $('#lbl_device_name').text( data['lot_app_by_id'][0]['wbs_kitting']['device_name'] );
              //   $('#lbl_po_no').text( data['lot_app_by_id'][0]['po_no'] );
              //   $('#lbl_lot_batch_no').text( data['lot_app_by_id'][0]['lot_batch_no'] );
              //   $('#lbl_reel_lot_no').text( data['lot_app_by_id'][0]['reel_lot'] );
              //   $('#lbl_lot_qty').text( data['lot_app_by_id'][0]['lot_qty'] );

              //   $("#img_barcode_lotno2").attr('src', data['lot_app_code']);
              //   $('#lbl_device_name2').text( data['lot_app_by_id'][0]['wbs_kitting']['device_name'] );
              //   $('#lbl_po_no2').text( data['lot_app_by_id'][0]['po_no'] );
              //   $('#lbl_lot_batch_no2').text( data['lot_app_by_id'][0]['lot_batch_no'] );
              //   $('#lbl_reel_lot_no2').text( data['lot_app_by_id'][0]['reel_lot'] );
              //   $('#lbl_lot_qty2').text( data['lot_app_by_id'][0]['lot_qty'] );

              //   img_barcode_lotno  = data['lot_app_code'];
              //   lbl_device_name     = data['lot_app_by_id'][0]['wbs_kitting']['device_name'];
              //   lbl_po_no           = data['lot_app_by_id'][0]['po_no'];
              //   lbl_lot_batch_no    = data['lot_app_by_id'][0]['lot_batch_no'];
              //   lbl_reel_lot_no     = data['lot_app_by_id'][0]['reel_lot'];
              //   lbl_lot_qty         = data['lot_app_by_id'][0]['lot_qty'];
              //   lbl_output_qty      = data['lot_app_by_id'][0]['output_qty'];

              //   if ( lbl_output_qty < lbl_lot_qty){
              //     // alert('not equal');
              //     new_lot_qty1 = (120);
              //     new_lot_qty2 = (lbl_output_qty - new_lot_qty1);
              //     $('#lbl_output_qty1').text( new_lot_qty1 );
              //     $('#lbl_output_qty2').text( new_lot_qty2 );

              //   }else if ( lbl_output_qty == lbl_lot_qty){
              //     // alert('equal');
              //       if ( lbl_lot_qty == 240){
              //         // alert('240');
              //         new_lot_qty1 = (lbl_output_qty / 2);
              //         new_lot_qty2 = (lbl_output_qty / 2);
              //         $('#lbl_output_qty1').text( new_lot_qty1 );
              //         $('#lbl_output_qty2').text( new_lot_qty2 );
              //         lbl_sticker_page_no = '1/2';
              //         lbl_sticker_page_no2 = '2/2';
              //         $('#lbl_sticker_page_no').text( '1/2' );
              //         $('#lbl_sticker_page_no2').text( '2/2' );
              //       }else{
              //         // alert('750');
              //         new_lot_qty1 = (lbl_output_qty);
              //         new_lot_qty2 = (lbl_output_qty);
              //         $('#lbl_output_qty1').text( new_lot_qty1 );
              //         $('#lbl_output_qty2').text( new_lot_qty2 );
              //         lbl_sticker_page_no = '1/1';
              //         lbl_sticker_page_no2 = '1/1';
              //         $('#lbl_sticker_page_no').text( '1/1' );
              //         $('#lbl_sticker_page_no2').text( '1/1' );

              //       }
              //   }

              //   //- PO
              //   $("#img_barcode_PO").attr('src', data['po_no']);
              //   $("#img_barcode_PO2").attr('src', data['po_no']);
              //   $('#lbl_po_no_PO').text( data['lot_app_by_id'][0]['po_no'] );
              //   $('#lbl_device_name_PO').text( data['lot_app_by_id'][0]['wbs_kitting']['device_name'] );
              //   $('#lbl_po_no_PO2').text( data['lot_app_by_id'][0]['po_no'] );
              //   $('#lbl_device_name_PO2').text( data['lot_app_by_id'][0]['wbs_kitting']['device_name'] );

              //   img_barcode_PO      = data['po_no'];

              //   //- A Drawing
              //   $("#img_A_drawing").attr('src', data['A_drawing']);
              //   $('#lbl_adrawing').text( data['lot_app_by_id'][0]['Adrawing'] );
              //   $('#lbl_po_Adrawing').text( data['lot_app_by_id'][0]['po_no'] );
              //   $('#lbl_device_name_Adrawing').text( data['lot_app_by_id'][0]['wbs_kitting']['device_name'] );

              //   img_A_drawing   = data['A_drawing'];
              //   lbl_adrawing    = data['lot_app_by_id'][0]['Adrawing'];

              //   //- Inspection Standard
              //   $("#img_IS_drawing").attr('src', data['IS_drawing']);
              //   $("#lbl_isdrawing").text( data['inspection_standard'][0]['doc_no']+'-'+data['inspection_standard'][0]['rev_no']);
              //   $('#lbl_po_isdrawing').text( data['lot_app_by_id'][0]['po_no'] );
              //   $('#lbl_device_name_isdrawing').text( data['lot_app_by_id'][0]['wbs_kitting']['device_name'] );

              //   img_IS_drawing   = data['IS_drawing'];
              //   lbl_isdrawing    = data['inspection_standard'][0]['doc_no']+'-'+data['inspection_standard'][0]['rev_no'];

              //   //- R Drawing
              //   $("#img_R_drawing").attr('src', data['R_drawing']);
              //   $("#lbl_rdrawing").text( data['rdrawing'][0]['doc_no']+'-'+data['rdrawing'][0]['rev_no']);
              //   $('#lbl_po_rdrawing').text( data['lot_app_by_id'][0]['po_no'] );
              //   $('#lbl_device_name_rdrawing').text( data['lot_app_by_id'][0]['wbs_kitting']['device_name'] );

              //   img_R_drawing   = data['R_drawing'];
              //   lbl_rdrawing    = data['rdrawing'][0]['doc_no']+'-'+data['rdrawing'][0]['rev_no'];


              //   $('#modal_LotApp_QRcode').modal({
              //     backdrop: 'static',
              //     keyboard: false,
              //     show: true
              //   });


            }, error    : function (data) {
            alert('ERROR: '+data);
            }
        });

      });

    //- Print Barcode
    $("#btn_print_barcode").click(function(){
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

        // content += '.s {';
        // content += 'border-left: 1px dashed black;';
        // content += 'height: 15px;';
        // content += '}';

        // content += '.s1 {';
        // content += 'border-left: 1px dashed black;';
        // content += 'height: 65px;';
        // content += '}';

        // content += '.s2 {';
        // content += 'border-left: 1px dashed black;';
        // content += 'height: 66px;';
        // content += '}';

        // content += '.s3 {';
        // content += 'border-left: 1px dashed black;';
        // content += 'height: 40px;';
        // content += '}';

        content += '</style>';
        content += '</head>';
        content += '<body>';


        //- 1st sticker QR


        // content += '<div class="rotated">';
        // content += '<tr>';
        //     content += '<td style="text-align: center;">';
        //     content += '<img src="' + $('#img_barcode_PO').attr('src') + '" style="min-width: 55px; max-width: 55px;">';
        //     content += '</td>';
        //     content += '<td style="font-size: 9px;">' + $('#img_barcode_PO_text_hidden').html() + '</td>';
        // content += '</tr>';
        // content += '</div>';

        for (var i = 0; i < img_barcode_PO_text_hidden.length; i++) {
          // content += '<div class="rotated">';
          content += '<table>';
          content += '<tr class="rotated">';
              content += '<td style="text-align: left;">';
              content += '<img src="' + img_barcode_PO_text_hidden[i]['img'] + '" style="min-width: 55px; max-width: 55px;">';
              content += '</td>';
              content += '<td style="font-size: 9px; font-family: Arial;">' + img_barcode_PO_text_hidden[i]['text'] + '</td>';
          content += '</tr>';
          content += '</table>';
          content += '<div class="pagebreak"> </div>';
          // content += '</div>';
        }

        //- 1st QR details
        // content += '<tr>';
        //     content += '<td style="font-family: Arial; font-size: 5px; text-align: center; vertical-align:top;">';
        //     content += '<label style="font-weight: bold;">' + lbl_po_no + '</label>';
        //     content += '<br>';
        //     content += '<label>' + lbl_device_name + '</label>';
        //     content += '</td>';

        //     content += '<td>';
        //     content += '<div class="s1"></div>';
        //     content += '</td>';

        //     content += '<td style="font-family: Arial; font-size: 5px; text-align: center; vertical-align:top;">';
        //     content += '<label>' + lbl_device_name + '</label>';
        //     content += '<br>';
        //     content += '<label>' + lbl_po_no + '</label>';
        //     content += '<br>';
        //     content += '<label style="font-weight: bold;">' + lbl_lot_batch_no + '</label>';
        //     content += '<br>';
        //     content += '<label>' + lbl_reel_lot_no + '</label>';
        //     content += '<br>';
        //     content += '<label>' + lbl_lot_qty + "/" + new_lot_qty1 + '</label>';
        //     content += '<br>';
        //     content += '<label>' + lbl_sticker_page_no + '</label>';
        //     content += '</td>';
        // content += '</tr>';

        // content += '<div class="rotated">';
        // //- 2nd sticker
        // content += '<tr>';
        //     content += '<td style="width: 50%; text-align: center;">';
        //     content += '<img src="' + img_barcode_PO + '" style="min-width: 45px; max-width: 45px;">';
        //     content += '</td>';

        //     content += '<td>';
        //     content += '<div class="s"></div>';
        //     content += '</td>';

        //     content += '<td style="width: 50%; text-align: center;">';
        //     content += '<img src="' + img_barcode_lotno + '" style="min-width: 43px; max-width: 43px;">';
        //     content += '</td>';
        // content += '</tr>';

        // // //- lot_batch# 2
        // content += '<tr>';
        //     content += '<td style="font-family: Arial; font-size: 5px; text-align: center; vertical-align:top;">';
        //     content += '<label style="font-weight: bold;">' + lbl_po_no + '</label>';
        //     content += '<br>';
        //     content += '<label>' + lbl_device_name + '</label>';
        //     content += '</td>';

        //     content += '<td>';
        //     content += '<div class="s2"></div>';
        //     content += '</td>';

        //     content += '<td style="font-family: Arial; font-size: 5px; text-align: center; vertical-align:top;">';
        //     content += '<label>' + lbl_device_name + '</label>';
        //     content += '<br>';
        //     content += '<label>' + lbl_po_no + '</label>';
        //     content += '<br>';
        //     content += '<label style="font-weight: bold;">' + lbl_lot_batch_no + '</label>';
        //     content += '<br>';
        //     content += '<label>' + lbl_reel_lot_no + '</label>';
        //     content += '<br>';
        //     content += '<label>' + lbl_lot_qty + "/" + new_lot_qty2 + '</label>';
        //     content += '<br>';
        //     content += '<label>' + lbl_sticker_page_no2 + '</label>';
        //     content += '</td>';
        // content += '</tr>';
        // content += '</div>';

        // //- 3rd Drawing
        // content += '<div class="rotated">';
        //     content += '<table>';
        //     content += '<tr>';
        //         content += '<td style="width: 30%; text-align: center;">';
        //         content += '<img src="' + img_A_drawing + '" style="min-width: 43px; max-width: 43px;">';
        //         content += '</td>';

        //         content += '<td>';
        //         content += '<div class="s"></div>';
        //         content += '</td>';

        //         content += '<td style="width: 30%; text-align: center;">';
        //         content += '<img src="' + img_IS_drawing + '" style="min-width: 43px; max-width: 43px;">';
        //         content += '</td>';

        //         content += '<td>';
        //         content += '<div class="s"></div>';
        //         content += '</td>';

        //         content += '<td style="width: 30%; text-align: center;">';
        //         content += '<img src="' + img_R_drawing + '" style="min-width: 43px; max-width: 43px;">';
        //         content += '</td>';
        //     content += '</tr>';

        //     content += '<tr>';
        //         content += '<td style="font-family: Arial; font-size: 5px; text-align: center; vertical-align:top;">';
        //         content += '<label>' + lbl_device_name + '</label>';
        //         content += '<br>';
        //         content += '<label style="font-weight: bold;">' + lbl_adrawing + '</label>';
        //         content += '</td>';

        //         content += '<td>';
        //         content += '<div class="s3"></div>';
        //         content += '</td>';

        //         content += '<td style="font-family: Arial; font-size: 5px; text-align: center; vertical-align:top;">';
        //         content += '<label>' + lbl_device_name + '</label>';
        //         content += '<br>';
        //         content += '<label style="font-weight: bold;">' + lbl_isdrawing + '</label>';
        //         content += '</td>';

        //         content += '<td>';
        //         content += '<div class="s3"></div>';
        //         content += '</td>';

        //         content += '<td style="font-family: Arial; font-size: 5px; text-align: center; vertical-align:top;">';
        //         content += '<label>' + lbl_device_name + '</label>';
        //         content += '<br>';
        //         content += '<label style="font-weight: bold;">' + lbl_rdrawing + '</label>';
        //         content += '</td>';
        //     content += '</tr>';
        //     content += '</table>';
        // content += '</div>';



        content += '</body>';
        content += '</html>';
        popup.document.write(content);
        popup.focus(); //required for IE
        popup.print();
        popup.close();
    });

    //- Search PO
    $(document).on('click','.btn_search_POno',function(e){
      $('#txt_search_po_number').val('');
      $('#modalScan_PO').attr('data-formid', '').modal('show');
    });

    $(document).on('keypress',function(e){
      if( ($("#modalScan_PO").data('bs.modal') || {})._isShown ){
        $('#txt_search_po_number').focus();

        if( e.keyCode == 13 && $('#txt_search_po_number').val() !='' && ($('#txt_search_po_number').val().length >= 4) ){
            $('#btnOverallInspection').html('Overall Inspection');
            $('#modalScan_PO').modal('hide');
          }
        }
    });

  </script>
  @endsection
@endauth
