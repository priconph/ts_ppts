function GetProductionRuncards(cboElement, status){
    let result = '<option value="0" selected> --- </option>';
    $.ajax({
        url: 'get_prod_runcards',
        method: 'get',
        data: {
            status: status
        },
        dataType: 'json',
        beforeSend: function(){
            result = '<option value="0" selected disabled> -- Loading -- </option>';
            cboElement.html(result);
        },
        success: function(JsonObject){
            result = '';
            if(JsonObject['prod_runcards'].length > 0){
                result = '<option value="0" selected> --- </option>';
                for(let index = 0; index < JsonObject['prod_runcards'].length; index++){
                    result += '<option value="' + JsonObject['prod_runcards'][index].id + '">' + JsonObject['prod_runcards'][index].runcard_no + '</option>';
                }
            }
            else{
                result = '<option value="0" selected> --- </option>';
            }

            cboElement.html(result);
        },
        error: function(data, xhr, status){
            result = '<option value="0" selected> --- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function GetWHMatIssuIdToPrint(matWHMatIssuId, qrCodeType){
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
        url: "fn_get_warehouse_view_batches_by_id",
        method: "get",
        data: {
            material_issuance_id: matWHMatIssuId,
            qr_code_type: qrCodeType,
        },
        dataType: "json",
        beforeSend: function(){
                $("#lblGenWHMatIssuPoNo").text('...');
                $("#lblGenWHMatIssuPoDevName").text('...');
                $("#lblGenWHMatIssuPoKitNo").text('...');
                $("#lblGenWHMatIssuPoKitQty").text('...');
                $("#lblGenWHMatIssuTransSlip").text('...');
        },
        success: function(JsonObject){
            let result = JsonObject['tbl_wbs_material_kitting'];
            let poQRCode = JsonObject['po_qrcode'];
            let whsSlipQRcode = JsonObject['whs_slip_qrcode'];

            if(result.length > 0){
                $("#modalGenWHMatIssuIdToPrint").modal('show');
                $("#imgGenWHMatIssuIdPoNoBarcode").attr('src', poQRCode);
                $("#imgGenWHMatIssuIdBarcode").attr('src', whsSlipQRcode);
                $("#lblGenWHMatIssuPoNo").text(result[0].po_no);
                $("#lblGenWHMatIssuPoDevName").text(result[0].device_name);
                $("#lblGenWHMatIssuPoKitNo").text(result[0].kit_no);
                $("#lblGenWHMatIssuPoKitQty").text(result[0].kit_qty);
                $("#lblGenWHMatIssuTransSlip").text(result[0].issuance_no);

                imgResultMatIssuePoNoQrCode = poQRCode;
                imgResultMatIssueQrCode = whsSlipQRcode;
                lblGenWHMatIssuPoNo = result[0].po_no;
                lblGenWHMatIssuPoDevName = result[0].device_name;
                lblGenWHMatIssuPoKitNo = result[0].kit_no;
                lblGenWHMatIssuPoKitQty = result[0].kit_qty;
                lblGenWHMatIssuTransSlip = result[0].issuance_no;
            }
            else{
                toastr.error('QR Code Generation Failed!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function GetWHSakIssuIdToPrint(matWHSakIssuId){
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
        url: "get_warehouse_sakidashi_view_batches_by_id",
        method: "get",
        data: {
            sakidashi_issuance_id: matWHSakIssuId
        },
        dataType: "json",
        beforeSend: function(){
                $("#lblGenWHSakIssuPoNo").text('...');
                $("#lblGenWHSakIssuTransSlip").text('...');
        },
        success: function(JsonObject){
            let result = JsonObject['tbl_sakidashi_kitting'];
            let poQRCode = JsonObject['po_qrcode'];
            let ctrlNoQRcode = JsonObject['ctrl_no_qrcode'];

            if(result.length > 0){
                $("#modalGenWHSakIssuIdToPrint").modal('show');
                $("#imgGenWHSakIssuIdPoNoBarcode").attr('src', poQRCode);
                $("#imgGenWHSakIssuIdBarcode").attr('src', ctrlNoQRcode);
                $("#lblGenWHSakIssuPoNo").text(result[0].po_no);
                $("#lblGenWHSakIssuTransSlip").text(result[0].issuance_no);

                imgResultSakIssuePoNoQrCode = poQRCode;
                imgResultSakIssueQrCode = ctrlNoQRcode;
                lblGenWHSakIssuPoNo = result[0].po_no;
                lblGenWHSakIssuCtrlNo = result[0].issuance_no;
            }
            else{
                toastr.error('QR Code Generation Failed!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function GetRuncardNoToPrint(runcardId, runcardNo){
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
        url: "generate_prod_runcard_qrcode",
        method: "get",
        data: {
            runcard_id: runcardId,
            runcard_no: runcardNo,
            // qr_code_type: qrCodeType,
        },
        dataType: "json",
        beforeSend: function(){
                // $("#lblGenWHMatIssuPoNo").text('...');
                // $("#lblGenWHMatIssuPoDevName").text('...');
                // $("#lblRuncardPoKitNo").text('...');
                // $("#lblRuncardPoKitQty").text('...');
                // $("#lblRuncardTransSlip").text('...');
        },
        success: function(JsonObject){
            let result = JsonObject['data'];
            let poQRCode = JsonObject['po_qrcode'];
            let runcardQRCode = JsonObject['runcard_qrcode'];

            if(result != null){
                $("#modalGenRuncardToPrint").modal('show');
                $("#imgGenRuncardPoNoBarcode").attr('src', poQRCode);
                $("#imgGenRuncardBarcode").attr('src', runcardQRCode);
                $("#lblRuncardPoNo").text(result.po_no);
                $("#lblRuncardNo").text(result.runcard_no);
                // $("#lblRuncardPoDevName").text(result[0].device_name);
                // $("#lblRuncardPoKitNo").text(result[0].kit_no);
                // $("#lblRuncardPoKitQty").text(result[0].kit_qty);

                // imgResultMatIssuePoNoQrCode = poQRCode;
                // imgResultMatIssueQrCode = whsSlipQRcode;
                // lblRuncardPoNo = result[0].po_no;
                // lblRuncardPoDevName = result[0].device_name;
                // lblRuncardPoKitNo = result[0].kit_no;
                // lblRuncardPoKitQty = result[0].kit_qty;
                // lblRuncardTransSlip = result[0].issuance_no;
            }
            else{
                toastr.error('QR Code Generation Failed!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function GetDefectEscalationNoToPrint(runcardId, runcardNo){
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
        url: "generate_defect_escalation_qrcode",
        method: "get",
        data: {
            runcard_id: runcardId,
            runcard_no: runcardNo,
            // qr_code_type: qrCodeType,
        },
        dataType: "json",
        beforeSend: function(){
                // $("#lblGenWHMatIssuPoNo").text('...');
                // $("#lblGenWHMatIssuPoDevName").text('...');
                // $("#lblRuncardPoKitNo").text('...');
                // $("#lblRuncardPoKitQty").text('...');
                // $("#lblRuncardTransSlip").text('...');
        },
        success: function(JsonObject){
            let result = JsonObject['data'];
            let poQRCode = JsonObject['po_qrcode'];
            let runcardQRCode = JsonObject['runcard_qrcode'];

            if(result != null){
                $("#modalGenRuncardToPrint").modal('show');
                $("#imgGenRuncardPoNoBarcode").attr('src', poQRCode);
                $("#imgGenRuncardBarcode").attr('src', runcardQRCode);
                $("#lblRuncardPoNo").text(result.po_no);
                $("#lblRuncardNo").text(result.defect_escalation_no);
                // $("#lblRuncardPoDevName").text(result[0].device_name);
                // $("#lblRuncardPoKitNo").text(result[0].kit_no);
                // $("#lblRuncardPoKitQty").text(result[0].kit_qty);

                // imgResultMatIssuePoNoQrCode = poQRCode;
                // imgResultMatIssueQrCode = whsSlipQRcode;
                // lblRuncardPoNo = result[0].po_no;
                // lblRuncardPoDevName = result[0].device_name;
                // lblRuncardPoKitNo = result[0].kit_no;
                // lblRuncardPoKitQty = result[0].kit_qty;
                // lblRuncardTransSlip = result[0].issuance_no;
            }
            else{
                toastr.error('QR Code Generation Failed!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}