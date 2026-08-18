@extends('layouts.super_user_layout')

@section('title', 'Blank')

@section('content_page')
  <!-- Content Header (Page header) -->
  <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Preliminary Packing Inspection</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Preliminary Packing Inspection</li>
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

              <div class="float-sm-right">
                <button type="button" data-toggle="modal" data-target="#modalPackingInspection">test</button>
              </div>

            </div>

            <!-- Start Page Content -->
              <div class="card-body">
                  <div class="row">
                    <div class="col-sm-2">
                      <label>Search PO Number</label>
                      <div class="input-group">
                       <!--  <div class="input-group-prepend">
                            <button type="button" class="btn btn-primary btn_search_POno" title="Click to Scan PO Code"><i class="fa fa-qrcode"></i></button>
                        </div> -->

                         <input type="text" id="txt_search_po_number" class="form-control" autocomplete="off">

                        <input type="hidden" class="form-control" id="id_po_no" readonly="">
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
                      <table id="tbl_packing_confirmation" class="table table-bordered table-striped table-hover" style="width: 100%;">
                          <thead>
                            <tr>
                              <th>Action</th>
                              <th>Status</th>
                              <th>Lot Application</th>
                              <th>Lot Numbers</th>
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


<div class="modal fade" id="modalPackingInspection">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">Preliminary Packing Inspection (Responsible: OQC Inspector)</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </div>

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
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div class="input-group input-group-sm mb-3">
              <div class="input-group-prepend w-50">
                <span class="input-group-text w-100" id="basic-addon1">TOTAL LOT QTY</span>
              </div>
              <input type="text" class="form-control form-control-sm" id="add_lot_no" name="add_lot_no" readonly>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div class="input-group input-group-sm mb-3">
              <div class="input-group-prepend w-50">
                <span class="input-group-text w-100" id="basic-addon1">SERIES NAME</span>
              </div>
              <input type="text" class="form-control form-control-sm" id="add_series_name" name="add_series_name" readonly>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div class="input-group input-group-sm mb-3">
              <div class="input-group-prepend w-50">
                <span class="input-group-text w-100" id="basic-addon1">SERIES NAME ON QA APPLICATION VS. LABEL TALLY?</span>
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
                  <span class="input-group-text w-100" id="basic-addon1">SERIES NAME ON LABEL VS. ACTUAL PRODUCT TALLY?</span>
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
                  <span class="input-group-text w-100" id="basic-addon1">SILICA GEL / ANTI-RUST REQUIREMENT:</span>
                </div>
                <select class="form-control form-control-sm" id="add_csh_coating" name="add_csh_coating">
                  <option selected disabled>-- Choose One --</option>
                  <option value='1'>WITH</option>
                  <option value='2'>WITHOUT</option>
                  <option value='3'>N/A</option>
                </select>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col">
               <div class="table-responsive dt-responsive">
                  <table id="tbl_packing_confirmation_accessories" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 75%">
                      <thead>
                        <tr>
                          <th>Accessory Name</th>
                          <th>Quantity</th>
                          <th>Result</th>
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
                  <span class="input-group-text w-100" id="basic-addon1">PACKING OPERATOR NAME:</span>
                </div>
                <select class="form-control form-control-sm" id="add_packing_operator_name" name="add_packing_operator_name">
                  <option selected disabled>-- Choose One --</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
          <div class="col">
            <div class="input-group input-group-sm mb-3">
              <div class="input-group-prepend w-50">
                <span class="input-group-text w-100" id="basic-addon1">CONFIRMATION DATE/TIME</span>
              </div>
              <input type="datetime-local" class="form-control form-control-sm" id="add_confirmation_datetime" name="add_confirmation_datetime">
            </div>
          </div>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-success" id="btnSubmitConfirmation">Submit Confirmation</button>
      </div>

    </div>
  </div>
</div>


@endsection

@section('js_content')
<script type="text/javascript">
  $(document).ready(function () {
    bsCustomFileInput.init();
  });
</script>
@endsection