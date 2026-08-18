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

  @section('title', 'Final Packing Details')

  @section('content_page')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Final Packing Details</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Final Packing Details</li>
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
                <h3 class="card-title">Assembly Lines</h3>
              </div>
 -->
              <!-- Start Page Content -->
              <div class="card-body">
                  <div style="float: right;">

                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddFPDetailsQRCode" id="btnShowAddFPDetailsQRCodeModal"><i class="fa fa-plus"></i> Add</button>
                  </div> <br><br>
                  <div class="table responsive">
                    <table id="tblFPDetailsQRCode" class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                      <thead>
                        <tr>
                          <th>Action</th>
                          <th>PO Number</th>
                          <th>Series Name</th>
                          <th>Lot Number</th>
                          <th>Lot Qty</th>
                          <th>WW</th>
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
  <!-- /.content-wrapper -->

  <!-- MODALS -->
  <div class="modal fade" id="modalAddFPDetailsQRCode">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-plus"></i> Add Final Packing Details</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formAddFPDetailsQRCode">
          @csrf
          <div class="modal-body">
            <div class="row">

              <div class="col-sm-12">
                <div class="form-group">
                  <label>PO Number</label>
                    <input type="text" class="form-control" name="PONo" id="txtAddPONo" autocomplete="off" required>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Series Name</label>
                    <input type="text" class="form-control" name="DeviceName" id="txtAddDeviceName" autocomplete="off" required>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Lot Qty</label>
                    <input type="text" class="form-control" name="LotQty" id="txtAddLotQty" autocomplete="off" required>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Lot Number</label>
                    <input type="text" class="form-control" name="LotNumber" id="txtAddLotNumber" autocomplete="off" required>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label>WW</label>
                    <input type="number" class="form-control" name="ww" id="txtAddWW" style="text-transform:uppercase" autocomplete="off">
                </div>
              </div>

            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnAddFPDetailsQRCode" class="btn btn-primary"><i id="iBtnAddFPDetailsQRCodeIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalEditFPDetailsQRCode">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-default"></i> Edit Final Packing Details</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formEditFPDetailsQRCode">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <input type="hidden" class="form-control" name="FPDetailsQRCode_id" id="txtEditFPDetailsQRCodeId">

              <div class="col-sm-12">
                <div class="form-group">
                  <label>PO Number</label>
                    <input type="text" class="form-control" name="PONo" id="txtEditPONo" autocomplete="off">
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Series Name</label>
                    <input type="text" class="form-control" name="DeviceName" id="txtEditDeviceName" autocomplete="off">
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Lot Qty</label>
                    <input type="text" class="form-control" name="LotQty" id="txtEditLotQty" autocomplete="off">
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Lot Number</label>
                    <input type="text" class="form-control" name="LotNumber" id="txtEditLotNumber" autocomplete="off">
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label>WW</label>
                    <input type="number" class="form-control" name="ww" id="txtEditWW" style="text-transform:uppercase" autocomplete="off">
                </div>
              </div>

              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnEditFPDetailsQRCode" class="btn btn-primary"><i id="iBtnEditFPDetailsQRCodeIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div class="modal fade" id="modal_Final_Packing_QRcode">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-qrcode"></i> Final Packing - QR Code</h4>
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
                              ->generate('0')) !!}" id="img_barcode_po_no" style="max-width: 200px;">
                    </center>
                    <br>
                    <label>PO Number: </label>
                    <label id="lbl_po_no"></label> <br>
                    <label>Device Name: </label>
                    <label id="lbl_device_name"></label> <br>
                    <label>Lot Number: </label>
                    <label id="lbl_lot_no"></label> <br>
                    <label>Lot Qty: </label>
                    <label id="lbl_lot_qty"></label> <br>
                    <label>WW: </label>
                    <label id="lbl_ww"></label> <br>

                </div>

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

  
  @endsection

  @section('js_content')
  <script type="text/javascript">
    let dataTableFPDetailsQRCode;
    $(document).ready(function () {
      //Initialize Select2 Elements
      $('.select2').select2();

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      dataTableFPDetailsQRCode = $("#tblFPDetailsQRCode").DataTable({
        "processing" : false,
          "serverSide" : true,
          "ajax" : {
            url: "view_final_packing_details_qr",
          },
          
          "columns":[
            { "data" : "action", orderable:false, searchable:false },
            { "data" : "PONo" },
            { "data" : "DeviceName" },
            { "data" : "LotNumber" },
            { "data" : "LotQty" },
            { "data" : "ww" }

          ],
        });//end of dataTableFPDetailsQRCode

        $(document).on('click','#tblFPDetailsQRCode tbody tr',function(e){
          $(this).closest('tbody').find('tr').removeClass('table-active');
          $(this).closest('tr').addClass('table-active');
        });

        // Add Assembly Line 
        $("#formAddFPDetailsQRCode").submit(function(event){
          event.preventDefault();
          AddFPDetailsQRCode();
        });

        $("#btnShowAddFPDetailsQRCodeModal").click(function(){
          $("#txtAddPONo").removeClass('is-invalid');
          $("#txtAddPONo").attr('title', '');
          $("#txtAddDeviceName").removeClass('is-invalid');
          $("#txtAddDeviceName").attr('title', '');
          $("#txtAddLotNumber").removeClass('is-invalid');
          $("#txtAddLotNumber").attr('title', '');
          $("#txtAddLotQty").removeClass('is-invalid');
          $("#txtAddLotQty").attr('title', '');
          // $("#txtAddWW").removeClass('is-invalid');
          // $("#txtAddWW").attr('title', '');
        });

        // Edit Assembly Line
        $(document).on('click', '.EditFPDetails_QRCode', function(){
          let FPDetailsQRCodeId = $(this).attr('edit-id');
          $("#txtEditFPDetailsQRCodeId").val(FPDetailsQRCodeId);
          GetFPDetailsQRCodeByIdToEdit(FPDetailsQRCodeId);
          $("#txtEditPONo").removeClass('is-invalid');
          $("#txtEditPONo").attr('title', '');
          $("#txtEditDeviceName").removeClass('is-invalid');
          $("#txtEditDeviceName").attr('title', '');
          $("#txtEditLotNumber").removeClass('is-invalid');
          $("#txtEditLotNumber").attr('title', '');
          $("#txtEditLotQty").removeClass('is-invalid');
          $("#txtEditLotQty").attr('title', '');
          // $("#txtEditWW").removeClass('is-invalid');
          // $("#txtEditWW").attr('title', '');
        });

        $("#formEditFPDetailsQRCode").submit(function(event){
          event.preventDefault();
          EditFPDetailsQRCode();
        });

      });


    $(document).on('click', '.btnPrintFinalQRCode', function(){

      let id = $(this).attr('id');

      get_finalpackingdetails_result_by_id(id);
      return;

    });

    function get_finalpackingdetails_result_by_id (id){

    var data = {
        "id"          : id
    }

    data = $.param(data);
    $.ajax({
        data        : data,
        type        : 'get',
        dataType    : 'json',
        url         : "get_finalpackingdetails_result_by_id",
        success     : function (data) {
          console.log(data);

            $("#img_barcode_po_no").attr('src', data['QrCode']);
            $('#lbl_po_no').text( data['fpqr'][0]['PONo'] );
            $('#lbl_device_name').text( data['fpqr'][0]['DeviceName'] );
            $('#lbl_lot_no').text( data['fpqr'][0]['LotNumber'] );
            $('#lbl_lot_qty').text( data['fpqr'][0]['LotQty'] );

            if (data['fpqr'][0]['ww'] == 'N/A' || data['fpqr'][0]['ww'] == '' || data['fpqr'][0]['ww'] == null){
              $('#lbl_ww').text( 'N/A' );
            }else{
              $('#lbl_ww').text( 'WW' + data['fpqr'][0]['ww'] );
            }
            
            img_barcode_po_no    = data['QrCode'];
            lbl_po_no            = data['fpqr'][0]['PONo'];
            lbl_device_name      = data['fpqr'][0]['DeviceName'];
            lbl_lot_no           = data['fpqr'][0]['LotNumber'];
            lbl_lot_qty          = data['fpqr'][0]['LotQty'];

            if (data['fpqr'][0]['ww'] == 'N/A' || data['fpqr'][0]['ww'] == '' || data['fpqr'][0]['ww'] == null){
              lbl_ww             = 'WW N/A';
            }else{
              lbl_ww             = 'WW' + data['fpqr'][0]['ww'];
            }
 

            $('#modal_Final_Packing_QRcode').modal({
              backdrop: 'static',
              keyboard: false, 
              show: true
            });

            
        }, error    : function (data) {
        alert('ERROR: '+data);
        }
    });

  }

    $("#btn_print_barcode").click(function(){
      popup = window.open();
        let content = '';
        
        content += '<html>';
        content += '<head>';
        content += '<title></title>';
        content += '<style type="text/css">';
        
        content += '@media print { .pagebreak { page-break-before: always; } }';
        
        content += '.rotated {';
        content += 'width: 150px;';

        content += '}';

        content += '</style>';
        content += '</head>';
        content += '<body>';

          content += '<table>';
          content += '<br>';
          content += '<tr style="width: 150px;">';
              content += '<td style="text-align: left;">';
              content += '<img src="' + img_barcode_po_no + '" style="min-width: 45px; max-width: 45px;">';
              content += '</td>';
              content += '<td style="font-size: 10px; font-family: Arial;">';
              content += '<label><b>' + lbl_po_no + '</label><br>';
              content += '<label><b>' + lbl_device_name + '</label><br>';
              content += '<label><b>' + lbl_lot_no + '</label><br>';
              content += '<label><b>' + lbl_lot_qty + '</label><br>';
              content += '<label><b>' + lbl_ww + '</label>';
              content += '</td>';
          content += '</tr>';
          content += '</table>';
          // content += '<div class="pagebreak"> </div>';

        content += '</body>';
        content += '</html>';
        popup.document.write(content);
        popup.focus(); //required for IE
        popup.print();
        popup.close();
    });

  </script>
  @endsection
@endauth