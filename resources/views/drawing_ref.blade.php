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

  @section('title', 'Drawing')

  @section('content_page')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Drawing Reference</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Drawing Reference</li>
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
                <h3 class="card-title">Drawing Ref</h3>
              </div>
 -->
              <!-- Start Page Content -->
              <div class="card-body">
                  <div style="float: right;">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddDrawingRef" id="btnShowAddDrawingRefModal"><i class="fa fa-plus"></i> Add Drawing Ref</button>
                  </div> <br><br>
                  <div class="table responsive">
                    <table id="tblDrawingRef" class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                      <thead>
                        <tr>
                          <th>Document Code</th>
                          <th>Document #</th>
                          <th>Series</th>
                          <th>Station</th>
                          <th>Process</th>
                          <th>Revision</th>
                          <th>Remarks</th>
                          <th>Status</th>
                          <th>Action</th>
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
  <div class="modal fade" id="modalAddDrawingRef">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-plus"></i> Add Drawing Ref</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formAddDrawingRef">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Document Code</label>
                    <input type="text" class="form-control" name="document_code" id="txtAddDocumentCode">
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Document No</label>
                    <input type="text" class="form-control" name="document_no" id="txtAddDocumentNo">
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Series</label>
                    <input type="text" class="form-control" name="series" id="txtAddSeries">
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Station</label>
                    <input type="text" class="form-control" name="station" id="txtAddStation">
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Process</label>
                    <input type="text" class="form-control" name="process" id="txtAddProcess">
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Revision</label>
                    <input type="text" class="form-control" name="revision" id="txtAddRevision">
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Remarks</label>
                    <input type="text" class="form-control" name="remarks" id="txtAddRemarks">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnAddDrawingRef" class="btn btn-primary"><i id="iBtnAddDrawingRefIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalEditDrawingRef">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-default"></i> Edit Drawing Ref</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formEditDrawingRef">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <input type="hidden" class="form-control" name="drawing_ref_id" id="txtEditDrawingRefId">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Document Code</label>
                      <input type="text" class="form-control" name="document_code" id="txtEditDocumentCode">
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Document No</label>
                      <input type="text" class="form-control" name="document_no" id="txtEditDocumentNo">
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Series</label>
                      <input type="text" class="form-control" name="series" id="txtEditSeries">
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Station</label>
                      <input type="text" class="form-control" name="station" id="txtEditStation">
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Process</label>
                      <input type="text" class="form-control" name="process" id="txtEditProcess">
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Revision</label>
                      <input type="text" class="form-control" name="revision" id="txtEditRevision">
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Remarks</label>
                      <input type="text" class="form-control" name="remarks" id="txtEditRemarks">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnAddDrawingRef" class="btn btn-primary"><i id="iBtnAddDrawingRefIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalChangeDrawingRefStat">
    <div class="modal-dialog">
      <div class="modal-content modal-sm">
        <div class="modal-header">
          <h4 class="modal-title" id="h4ChangeDrawingRefTitle"><i class="fa fa-default"></i> Change Status</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formChangeDrawingRefStat">
          @csrf
          <div class="modal-body">
            <label id="lblChangeDrawingRefStatLabel">Are you sure to ?</label>
            <input type="hidden" name="drawing_ref_id" placeholder="DrawingRef Id" id="txtChangeDrawingRefStatDrawingRefId">
            <input type="hidden" name="status" placeholder="Status" id="txtChangeDrawingRefStatDrawingRefStatus">
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            <button type="submit" id="btnChangeDrawingRefStat" class="btn btn-primary"><i id="iBtnChangeDrawingRefStatIcon" class="fa fa-check"></i> Yes</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
  @endsection

  @section('js_content')
  <script type="text/javascript">
    let dataTableAssemblyLines;
    $(document).ready(function () {
      //Initialize Select2 Elements
      $('.select2').select2();

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      dataTableDrawingRef = $("#tblDrawingRef").DataTable({
        "processing" : false,
          "serverSide" : true,
          "ajax" : {
            url: "view_drawing_ref",
            // data: function (param){
            //     param.status = $("#selEmpStat").val();
            // }
          },
          
          "columns":[
            { "data" : "document_code" },
            { "data" : "document_no" },
            { "data" : "series" },
            { "data" : "station" },
            { "data" : "process" },
            { "data" : "rev" },
            { "data" : "remarks" },
            { "data" : "status" },
            { "data" : "action", orderable:false, searchable:false }
          ],
        });//end of dataTableDrawingRef

        $(document).on('click','#tblDrawingRef tbody tr',function(e){
          $(this).closest('tbody').find('tr').removeClass('table-active');
          $(this).closest('tr').addClass('table-active');
        });


        $("#formAddDrawingRef").submit(function(event){
          event.preventDefault();
          AddDrawingRef();
        });

        $("#btnShowAddDrawingRefModal").click(function(){
          // $("#txtAddAssemblyLineName").removeClass('is-invalid');
          // $("#txtAddAssemblyLineName").attr('title', '');
        });


        // Edit
        $(document).on('click', '.modalEditDrawingRef', function(){
          let drawingRefId = $(this).attr('drawing-ref-id');
          $("#txtEditDrawingRefId").val(drawingRefId);
          GetDrawingRefByIdToEdit(drawingRefId);
          // $("#txtEditAssemblyLineName").removeClass('is-invalid');
          // $("#txtEditAssemblyLineName").attr('title', '');
        });


        $("#formEditDrawingRef").submit(function(event){
          event.preventDefault();
          EditDrawingRef();
        });


        $(document).on('click', '.actionChangeDrawingRefStat', function(){
          let drawingRefId = $(this).attr('drawing-ref-id');
          let drawingRefStat = $(this).attr('status');

          $("#txtChangeDrawingRefStatDrawingRefId").val(drawingRefId);
          $("#txtChangeDrawingRefStatDrawingRefStatus").val(drawingRefStat);

          if(drawingRefStat == 1){
            $("#lblChangeDrawingRefStatLabel").text('Are you sure to activate?'); 
            $("#h4ChangeDrawingRefTitle").html('<i class="fa fa-default"></i> Activate Drawing Ref');
          }
          else{
            $("#lblChangeDrawingRefStatLabel").text('Are you sure to deactivate?');
            $("#h4ChangeDrawingRefTitle").html('<i class="fa fa-default"></i> Deactivate Drawing Ref');
          }
        });

        $("#formChangeDrawingRefStat").submit(function(event){
          event.preventDefault();
          ChangeDrawingRefStatus();
        });

        // $("#formImportAssemblyLine").submit(function(event){
        //     event.preventDefault();
        //     $.ajax({
        //         url: 'import_assembly_line',
        //         method: 'post',
        //         data: new FormData(this),
        //         dataType: 'json',
        //         cache: false,
        //         contentType: false,
        //         processData: false,
        //         beforeSend: function(){
        //             // alert('Loading...');
        //         },
        //         success: function(JsonObject){
        //             if(JsonObject['result'] == 1){
        //               toastr.success('Importing Success!');
        //               dataTableDrawingRef.draw();
        //               $("#modalImportAssemblyLine").modal('hide');
        //             }
        //             else{
        //               toastr.error('Importing Failed!');
        //             }
        //         },
        //         error: function(data, xhr, status){
        //             console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        //         }
        //     });
        // });

      });
  </script>
  @endsection
@endauth