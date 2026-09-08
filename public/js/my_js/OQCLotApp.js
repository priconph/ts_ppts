// Add AddOQCLotApp
function AddOQCLotApp(){
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
        url: "add_oqc_lot_app",
        method: "post",
        data: $('#formOQCLotApp').serialize(),
        dataType: "json",
        beforeSend: function(){
        //
        },
        success: function(JsonObject){

            if(JsonObject['result'] == 1){
            	$("#modalOQCLotApp").modal('hide');
            	$("#formOQCLotApp")[0].reset();

                dataTableOQCLotApp.draw();
                toastr.success('Lot Application was successfully saved!');

            } else if (JsonObject['result'] == '2'){
                toastr.error('Invalid Employee No. for final visual operator!');
            } else if (JsonObject['result'] == '3'){
                toastr.error('Max of 3rd submission only!');
            }
            else if (JsonObject['result'] == 'partial_lot') { // boss da
                showPartialLotAlert(JsonObject);
            }

            else{
                if(JsonObject['result'] == 'x'){
                    toastr.error('Check Partial Qty.');
                    showPartialLotAlert(JsonObject);
                }
                toastr.error('Saving lot application failed!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            // console.log(data);
        }
    });
}

// boss da
function showPartialLotAlert(JsonObject) {

    Swal.fire({
        title: 'Partial Lot',
        text: JsonObject['message'] || 'This is a partial lot.',
        input: 'number',
        inputLabel: 'Enter Partial Quantity',

        inputValue: '',

        inputAttributes: {
            min: 1,
            max: parseInt(JsonObject['remainder']),
            step: 1
        },

        showCancelButton: true,
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel',

        inputValidator: function(value) {

            if (!value) {
                return 'Please enter the partial quantity.';
            }

            let qty = parseInt(value);
            let remainder = parseInt(JsonObject['remainder']);

            if (qty <= 0) {
                return 'Quantity must be greater than 0.';
            }

            if (qty > remainder) {
                return 'Partial quantity cannot exceed ' + remainder + '.';
            }
        }

    }).then(function(result) {

        if (result.value !== undefined) {

            let partialQty = parseInt(result.value);

            console.log('User entered partial quantity:', partialQty);

            $('#partial_qty').val(partialQty);

            AddOQCLotApp();
        }

    });
}

function ApprovedProd_OQCLotApp(){
  alert();
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
        url: "update_approved_prod",
        method: "post",
        data: $('#formApprovedByProd').serialize(),
        dataType: "json",
        beforeSend: function(){
        //
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
              $("#modalApprovedByProd").modal('hide');
              $("#modalOQCLotApp").modal('hide');
              $("#formApprovedByProd")[0].reset();

              // $("#formOQCLotApp").submit();

              dataTableOQCLotApp.draw();
              toastr.success('Lot Application was succesfully approved by Prodn Supervisor!');
            }else if (JsonObject['result'] == 2){
                toastr.error('You are not registered as Prodn. Supervisor');
            } else{
                toastr.error('Failed!');
            }

        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function ApprovedOQC_OQCLotApp(){
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
        url: "update_approved_oqc",
        method: "post",
        data: $('#formApprovedByOQC').serialize(),
        dataType: "json",
        beforeSend: function(){
        //
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
              $("#modalApprovedByOQC").modal('hide');
              $("#modalOQCLotApp").modal('hide');
              $("#formApprovedByOQC")[0].reset();

              dataTableOQCLotApp.draw();
              toastr.success('Lot Application was succesfully approved by OQC Supervisor!');
            }else if (JsonObject['result'] == 2){
                toastr.error('You are not registered as OQC Supervisor');
            } else{
                toastr.error('Failed!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

//----------------------------------------NEW FUNCTIONS FOR TS-------------------------

function ViewOQCApplicationTS(batch,po_num)
{
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
      url: "view_oqclotapp_ts",
      method: "get",
      data:
      {
        batch: batch,
        po_num: po_num
      },
      dataType: "json",
      beforeSend: function()
      {

      },
      success: function(JsonObject)
      {
        $('#id_submission').val(JsonObject['sub_count']);

        $('#id_currentPONo').val(JsonObject['oqclotapp'][0].po_no);
        $('#id_assemblyLine').val('');
        // $('#id_seriesName').val(JsonObject['series'][0].device_name);
        $('#id_lotBatchNo').val(JsonObject['oqclotapp'][0].runcard_no);
        $('#id_lotQuantity').val(JsonObject['oqclotapp'][0].lot_qty);
        $('#id_deviceClassification').val('');

        dt_oqclotapp_history.draw();
      },
      error: function(data,xhr,status)
      {
        toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }
    });
}

function SubmitOQCApplicationTS()
{
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
      url: 'add_oqc_lotapplication_ts',
      method: 'post',
      data: $('#formOQCLotApp').serialize(),
      dataType: 'json',
      beforeSend: function()
      {

      },
      success: function(JsonObject)
      {
        if(JsonObject['result'] == "1")
        {
            $('#formOQCLotApp')[0].reset();
            $('#modalAddApplication').modal('hide');

            dt_oqclotapp.draw();

            toastr.success('OQC Lot Application Submitted!');
        }
        else
        {
          toastr.error('OQC Lot Application Submission Failed');
        }

      },
      error: function(data,xhr,status)
      {
        toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }
    });
}
