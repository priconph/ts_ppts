//  AddFPDetailsQRCode
function AddFPDetailsQRCode(){
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

    $.ajax({
        url: "add_final_packing_details_qr",
        method: "post",
        data: $('#formAddFPDetailsQRCode').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnAddFPDetailsQRCodeIcon").addClass('fa fa-spinner fa-pulse');
            // $("#txtAddPONo").prop('disabled', 'disabled');
            // $("#txtAddDeviceName").prop('disabled', 'disabled');
            // $("#txtAddLotQty").prop('disabled', 'disabled');
            // $("#txtAddLotNumber").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
            	$("#modalAddFPDetailsQRCode").modal('hide');
            	$("#formAddFPDetailsQRCode")[0].reset();

            	dataTableFPDetailsQRCode.draw();
              toastr.success('Data was succesfully saved!');
            }
            else{
                toastr.error('Saving Failed!');

                if(JsonObject['error']['PONo'] === undefined){
                    $("#txtAddPONo").removeClass('is-invalid');
                    $("#txtAddPONo").attr('title', '');
                }
                else{
                    $("#txtAddPONo").addClass('is-invalid');
                    $("#txtAddPONo").attr('title', JsonObject['error']['PONo']);
                }
                if(JsonObject['error']['DeviceName'] === undefined){
                    $("#txtAddDeviceName").removeClass('is-invalid');
                    $("#txtAddDeviceName").attr('title', '');
                }
                else{
                    $("#txtAddDeviceName").addClass('is-invalid');
                    $("#txtAddDeviceName").attr('title', JsonObject['error']['DeviceName']);
                }
                if(JsonObject['error']['LotQty'] === undefined){
                    $("#txtAddLotQty").removeClass('is-invalid');
                    $("#txtAddLotQty").attr('title', '');
                }
                else{
                    $("#txtAddLotQty").addClass('is-invalid');
                    $("#txtAddLotQty").attr('title', JsonObject['error']['LotQty']);
                }
                if(JsonObject['error']['LotNumber'] === undefined){
                    $("#txtAddLotNumber").removeClass('is-invalid');
                    $("#txtAddLotNumber").attr('title', '');
                }
                else{
                    $("#txtAddLotNumber").addClass('is-invalid');
                    $("#txtAddLotNumber").attr('title', JsonObject['error']['LotNumber']);
                }
                // if(JsonObject['error']['ww'] === undefined){
                //     $("#txtAddWW").removeClass('is-invalid');
                //     $("#txtAddWW").attr('title', '');
                // }
                // else{
                //     $("#txtAddWW").addClass('is-invalid');
                //     $("#txtAddWW").attr('title', JsonObject['error']['ww']);
                // }
            }

            $("#iBtnAddFPDetailsQRCodeIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddFPDetailsQRCode").removeAttr('disabled');
            $("#iBtnAddFPDetailsQRCodeIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnAddFPDetailsQRCodeIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddFPDetailsQRCode").removeAttr('disabled');
            $("#iBtnAddFPDetailsQRCodeIcon").addClass('fa fa-check');
        }
    });
}

function GetFPDetailsQRCodeByIdToEdit(FPDetailsQRCodeId){
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

    $.ajax({
        url: "get_FPDetailsQRCode_by_id",
        method: "get",
        data: {
            FPDetailsQRCodeId: FPDetailsQRCodeId
        },
        dataType: "json",
        beforeSend: function(){
            $("#txtEditPONo").val("");
            $("#txtEditDeviceName").val("");
            $("#txtEditLotNumber").val("");
            $("#txtEditLotQty").val("");
            $("#txtEditWW").val("");
        },
        success: function(JsonObject){
            let result = JsonObject['fpqr'];
            if(result.length > 0){
              $("#txtEditPONo").val(result[0].PONo);
              $("#txtEditDeviceName").val(result[0].DeviceName);
              $("#txtEditLotNumber").val(result[0].LotNumber);
              $("#txtEditLotQty").val(result[0].LotQty);
              $("#txtEditWW").val(result[0].ww);

              $.ajax({
                type      : "get",
                dataType  : "json",
                data      : { po_no: $("#txtEditPONo").val() },
                url       : "getPONoDetails",
                success : function(data){
                  if( data['details'].length > 0 ){
                    $("#txtEditDeviceName_holder").val( data['details'][0]['device_name'].split(' - ')[0] )
                    $("#txtEditDeviceName_holder").attr('disabled', true)
                  }
                  else{
                    $("#txtEditDeviceName_holder").val( result[0].DeviceName )
                    $("#txtEditDeviceName_holder").attr('disabled', false)
                  }
                }
              });
            }
            else{
                toastr.warning('No Final Packing Details Record Found!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function EditFPDetailsQRCode(){
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

    $.ajax({
        url: "edit_FPDetailsQRCode",
        method: "post",
        data: $('#formEditFPDetailsQRCode').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnEditFPDetailsQRCodeIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnEditFPDetailsQRCode").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalEditFPDetailsQRCode").modal('hide');
                $("#formEditFPDetailsQRCode")[0].reset();

                dataTableFPDetailsQRCode.draw();
                toastr.success('Data was succesfully updated!');
            }
            else{
                toastr.error('Updating data Failed!');

                if(JsonObject['error']['PONo'] === undefined){
                    $("#txtEditPONo").removeClass('is-invalid');
                    $("#txtEditPONo").attr('title', '');
                }
                else{
                    $("#txtEditPONo").addClass('is-invalid');
                    $("#txtEditPONo").attr('title', JsonObject['error']['PONo']);
                }
                if(JsonObject['error']['DeviceName'] === undefined){
                    $("#txtEditDeviceName").removeClass('is-invalid');
                    $("#txtEditDeviceName").attr('title', '');
                }
                else{
                    $("#txtEditDeviceName").addClass('is-invalid');
                    $("#txtEditDeviceName").attr('title', JsonObject['error']['DeviceName']);
                }
                if(JsonObject['error']['LotQty'] === undefined){
                    $("#txtEditLotQty").removeClass('is-invalid');
                    $("#txtEditLotQty").attr('title', '');
                }
                else{
                    $("#txtEditLotQty").addClass('is-invalid');
                    $("#txtEditLotQty").attr('title', JsonObject['error']['LotQty']);
                }
                if(JsonObject['error']['LotNumber'] === undefined){
                    $("#txtEditLotNumber").removeClass('is-invalid');
                    $("#txtEditLotNumber").attr('title', '');
                }
                else{
                    $("#txtEditLotNumber").addClass('is-invalid');
                    $("#txtEditLotNumber").attr('title', JsonObject['error']['LotNumber']);
                }
                // if(JsonObject['error']['ww'] === undefined){
                //     $("#txtEditWW").removeClass('is-invalid');
                //     $("#txtEditWW").attr('title', '');
                // }
                // else{
                //     $("#txtEditWW").addClass('is-invalid');
                //     $("#txtEditWW").attr('title', JsonObject['error']['ww']);
                // }
            }

            $("#iBtnEditFPDetailsQRCodeIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditFPDetailsQRCode").removeAttr('disabled');
            $("#iBtnEditFPDetailsQRCodeIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnEditFPDetailsQRCodeIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditFPDetailsQRCode").removeAttr('disabled');
            $("#iBtnEditFPDetailsQRCodeIcon").addClass('fa fa-check');
        }
    });
}

