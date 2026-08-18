// Add Device
function AddDevice(){
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
        url: "add_device",
        method: "post",
        data: $('#formAddDevice').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnAddDeviceIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnAddDevice").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
            	$("#modalAddDevice").modal('hide');
            	$("#formAddDevice")[0].reset();

            	dataTableDevices.draw();
                toastr.success('Device was succesfully saved!');
            }
            else{
                toastr.error('Saving Device Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtAddDeviceName").removeClass('is-invalid');
                    $("#txtAddDeviceName").attr('title', '');
                }
                else{
                    $("#txtAddDeviceName").addClass('is-invalid');
                    $("#txtAddDeviceName").attr('title', JsonObject['error']['name']);
                }

                if(JsonObject['error']['[process]'] === undefined){
                    $("#txtAddPOI").removeClass('is-invalid');
                    $("#txtAddPOI").attr('title', '');
                }
                else{
                    $("#txtAddPOI").addClass('is-invalid');
                    $("#txtAddPOI").attr('title', JsonObject['error']['process']);
                }

                if(JsonObject['error']['barcode'] === undefined){
                    $("#txtAddDeviceBarcode").removeClass('is-invalid');
                    $("#txtAddDeviceBarcode").attr('title', '');
                }
                else{
                    $("#txtAddDeviceBarcode").addClass('is-invalid');
                    $("#txtAddDeviceBarcode").attr('title', JsonObject['error']['barcode']);
                }

                if(JsonObject['error']['boxing'] === undefined){
                    $("#txtAddDeviceBoxing").removeClass('is-invalid');
                    $("#txtAddDeviceBoxing").attr('title', '');
                }
                else{
                    $("#txtAddDeviceBoxing").addClass('is-invalid');
                    $("#txtAddDeviceBoxing").attr('title', JsonObject['error']['boxing']);
                }

                if(JsonObject['error']['ship_boxing'] === undefined){
                    $("#txtAddDeviceShipBoxing").removeClass('is-invalid');
                    $("#txtAddDeviceShipBoxing").attr('title', '');
                }
                else{
                    $("#txtAddDeviceShipBoxing").addClass('is-invalid');
                    $("#txtAddDeviceShipBoxing").attr('title', JsonObject['error']['ship_boxing']);
                }

                if(JsonObject['error']['type'] === undefined){
                    $("#txtAddType").removeClass('is-invalid');
                    $("#txtAddType").attr('title', '');
                }
                else{
                    $("#txtAddType").addClass('is-invalid');
                    $("#txtAddType").attr('title', JsonObject['error']['type']);
                }

                if(JsonObject['error']['label'] === undefined){
                    $("#txtAddLabel").removeClass('is-invalid');
                    $("#txtAddLabel").attr('title', '');
                }
                else{
                    $("#txtAddLabel").addClass('is-invalid');
                    $("#txtAddLabel").attr('title', JsonObject['error']['label']);
                }
            }

            $("#iBtnAddDeviceIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddDevice").removeAttr('disabled');
            $("#iBtnAddDeviceIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error(data.responseJSON.error);
            $("#iBtnAddDeviceIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddDevice").removeAttr('disabled');
            $("#iBtnAddDeviceIcon").addClass('fa fa-check');
        }
    });
}

// Edit Device
function GetDeviceByIdToEdit(deviceId){
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
        url: "get_device_by_id",
        method: "get",
        data: {
            device_id: deviceId
        },
        dataType: "json",
        beforeSend: function(){
            // $("#txtEditDeviceName").val("");
            // $("#txtEditDeviceBarcode").val("");
        },
        success: function(JsonObject){
            let result = JsonObject['device'];
            if(result.length > 0){
                // $("#txtEditDeviceName").val(result[0].name);
                // $("#txtEditPOI").val(result[0].process);
                // $("#txtEditDeviceBarcode").val(result[0].barcode);
                // $("#txtEditDeviceBoxing").val(result[0].boxing);
                // $("#txtEditDeviceShipBoxing").val(result[0].ship_boxing);
                // $("#txtEditType").val(result[0].type);
                // $("#txtEditLabel").val(result[0].label);
                $("#txtEditDeviceName").val(result[0].name != "" ? result[0].name : 0);
                $("#txtEditPOI").val(result[0].process != "" ? result[0].process : 0);
                $("#txtEditDeviceBarcode").val(result[0].barcode != "" ? result[0].barcode : 0 );
                $("#txtEditDeviceBoxing").val(result[0].boxing != "" ? result[0].boxing : 0 );
                $("#txtEditDeviceShipBoxing").val(result[0].ship_boxing != "" ? result[0].ship_boxing : 0 );
                $("#txtEditType").val(result[0].type != "" ? result[0].type : 0 );
                $("#txtEditLabel").val(result[0].label != "" ? result[0].label : 0 );
            }
            else{
                toastr.warning('No Device Record Found!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function EditDevice(){
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
        url: "edit_device",
        method: "post",
        data: $('#formEditDevice').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnEditDeviceIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnEditDevice").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalEditDevice").modal('hide');
                $("#formEditDevice")[0].reset();

                dataTableDevices.draw();
                toastr.success('Device was succesfully saved!');
            }
            else{
                toastr.error('Updating Device Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtEditDeviceName").removeClass('is-invalid');
                    $("#txtEditDeviceName").attr('title', '');
                }
                else{
                    $("#txtEditDeviceName").addClass('is-invalid');
                    $("#txtEditDeviceName").attr('title', JsonObject['error']['name']);
                }

                if(JsonObject['error']['process'] === undefined){
                    $("#txtEditPOI").removeClass('is-invalid');
                    $("#txtEditPOI").attr('title', '');
                }
                else{
                    $("#txtEditPOI").addClass('is-invalid');
                    $("#txtEditPOI").attr('title', JsonObject['error']['process']);
                }

                if(JsonObject['error']['barcode'] === undefined){
                    $("#txtEditDeviceBarcode").removeClass('is-invalid');
                    $("#txtEditDeviceBarcode").attr('title', '');
                }
                else{
                    $("#txtEditDeviceBarcode").addClass('is-invalid');
                    $("#txtEditDeviceBarcode").attr('title', JsonObject['error']['barcode']);
                }

                if(JsonObject['error']['boxing'] === undefined){
                    $("#txtEditDeviceBoxing").removeClass('is-invalid');
                    $("#txtEditDeviceBoxing").attr('title', '');
                }
                else{
                    $("#txtEditDeviceBoxing").addClass('is-invalid');
                    $("#txtEditDeviceBoxing").attr('title', JsonObject['error']['boxing']);
                }

                if(JsonObject['error']['ship_boxing'] === undefined){
                    $("#txtEditDeviceShipBoxing").removeClass('is-invalid');
                    $("#txtEditDeviceShipBoxing").attr('title', '');
                }
                else{
                    $("#txtEditDeviceShipBoxing").addClass('is-invalid');
                    $("#txtEditDeviceShipBoxing").attr('title', JsonObject['error']['ship_boxing']);
                }

                if(JsonObject['error']['type'] === undefined){
                    $("#txtEditType").removeClass('is-invalid');
                    $("#txtEditDeviceShipBoxing").attr('title', '');
                }
                else{
                    $("#txtEditDeviceShipBoxing").addClass('is-invalid');
                    $("#txtEditDeviceShipBoxing").attr('title', JsonObject['error']['type']);
                }

                if(JsonObject['error']['label'] === undefined){
                    $("#txtEditDeviceShipBoxing").removeClass('is-invalid');
                    $("#txtEditDeviceShipBoxing").attr('title', '');
                }
                else{
                    $("#txtEditDeviceShipBoxing").addClass('is-invalid');
                    $("#txtEditDeviceShipBoxing").attr('title', JsonObject['error']['label']);
                }
            }

            $("#iBtnEditDeviceIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditDevice").removeAttr('disabled');
            $("#iBtnEditDeviceIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnEditDeviceIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditDevice").removeAttr('disabled');
            $("#iBtnEditDeviceIcon").addClass('fa fa-check');
        }
    });
}

// Change Device Status
function ChangeDeviceStatus(){
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
        url: "change_device_stat",
        method: "post",
        data: $('#formChangeDeviceStat').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnChangeDeviceStatIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnChangeDeviceStat").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalChangeDeviceStat").modal('hide');
                $("#formChangeDeviceStat")[0].reset();

                dataTableDevices.draw();

                if($("#txtChangeDeviceStatDeviceStat").val() == 1){
                    toastr.success('Device Activation Success!');
                }
                else{
                    toastr.success('Device Deactivation Success!');
                }
            }
            else{
                if($("#txtChangeDeviceStatDeviceStat").val() == 1){
                    toastr.error('Device Activation Failed!');
                }
                else{
                    toastr.error('Device Deactivation Failed!');
                }
            }

            $("#iBtnChangeDeviceStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeDeviceStat").removeAttr('disabled');
            $("#iBtnChangeDeviceStatIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnChangeDeviceStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeDeviceStat").removeAttr('disabled');
            $("#iBtnChangeDeviceStatIcon").addClass('fa fa-check');
        }
    });
}
