function AddDrawingRef(){
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
        url: "add_drawing_ref",
        method: "post",
        data: $('#formAddDrawingRef').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnAddDrawingRefIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnAddDrawingRef").prop('disabled', 'disabled');
        },
        success: function(result){
            if(result['result'] == 1){
            	$("#modalAddDrawingRef").modal('hide');
            	$("#formAddDrawingRef")[0].reset();

            	dataTableDrawingRef.draw();
                toastr.success('Drawing Ref was succesfully saved!');
            }
            else{
                toastr.error('Saving Drawing Ref Failed!');

                if(result['error']['document_code'] === undefined){
                    $("#txtAddDocumentCode").removeClass('is-invalid');
                    $("#txtAddDocumentCode").attr('title', '');
                }
                else{
                    $("#txtAddDocumentCode").addClass('is-invalid');
                    $("#txtAddDocumentCode").attr('title', result['error']['document_code']);
                }

                if(result['error']['document_no'] === undefined){
                    $("#txtAddDocumentNo").removeClass('is-invalid');
                    $("#txtAddDocumentNo").attr('title', '');
                }
                else{
                    $("#txtAddDocumentNo").addClass('is-invalid');
                    $("#txtAddDocumentNo").attr('title', result['error']['document_no']);
                }

                if(result['error']['series'] === undefined){
                    $("#txtAddSeries").removeClass('is-invalid');
                    $("#txtAddSeries").attr('title', '');
                }
                else{
                    $("#txtAddSeries").addClass('is-invalid');
                    $("#txtAddSeries").attr('title', result['error']['series']);
                }

                if(result['error']['station'] === undefined){
                    $("#txtAddStation").removeClass('is-invalid');
                    $("#txtAddStation").attr('title', '');
                }
                else{
                    $("#txtAddStation").addClass('is-invalid');
                    $("#txtAddStation").attr('title', result['error']['station']);
                }

                if(result['error']['process'] === undefined){
                    $("#txtAddProcess").removeClass('is-invalid');
                    $("#txtAddProcess").attr('title', '');
                }
                else{
                    $("#txtAddProcess").addClass('is-invalid');
                    $("#txtAddProcess").attr('title', result['error']['process']);
                }

                if(result['error']['rev'] === undefined){
                    $("#txtAddRevision").removeClass('is-invalid');
                    $("#txtAddRevision").attr('title', '');
                }
                else{
                    $("#txtAddRevision").addClass('is-invalid');
                    $("#txtAddRevision").attr('title', result['error']['rev']);
                }

                if(result['error']['remarks'] === undefined){
                    $("#txtAddRemarks").removeClass('is-invalid');
                    $("#txtAddRemarks").attr('title', '');
                }
                else{
                    $("#txtAddRemarks").addClass('is-invalid');
                    $("#txtAddRemarks").attr('title', result['error']['remarks']);
                }
            }

            $("#iBtnAddDrawingRefIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddDrawingRef").removeAttr('disabled');
            $("#iBtnAddDrawingRefIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnAddDrawingRefIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddDrawingRef").removeAttr('disabled');
            $("#iBtnAddDrawingRefIcon").addClass('fa fa-check');
        }
    });
}


function GetDrawingRefByIdToEdit(drawingRefId){
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
        url: "get_drawing_ref_by_id",
        method: "get",
        data: {
            drawing_ref_id: drawingRefId
        },
        dataType: "json",
        beforeSend: function(){
            // $("#txtEditDocumentCode").val("");
        },
        success: function(result){
            let drawingRefRow = result['drawing_ref'];
            console.log(result['drawing_ref']);
            if(result['drawing_ref'].length > 0){
                $("#txtEditDocumentCode").val(drawingRefRow[0].document_code);
                $("#txtEditDocumentNo").val(drawingRefRow[0].document_no);
                $("#txtEditSeries").val(drawingRefRow[0].series);
                $("#txtEditStation").val(drawingRefRow[0].station);
                $("#txtEditProcess").val(drawingRefRow[0].process);
                $("#txtEditRevision").val(drawingRefRow[0].rev);
                $("#txtEditRemarks").val(drawingRefRow[0].remarks);
            }
            else{
                toastr.warning('No Drawing Ref Record Found!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function EditDrawingRef(){
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
        url: "edit_drawing_ref",
        method: "post",
        data: $('#formEditDrawingRef').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnAddDrawingRefIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnAddDrawingRef").prop('disabled', 'disabled');
        },
        success: function(result){
            if(result['result'] == 1){
                $("#modalEditDrawingRef").modal('hide');
            	$("#formEditDrawingRef")[0].reset();

                dataTableDrawingRef.draw();
                toastr.success('Drawing Ref was succesfully saved!');
            }
            else{
                toastr.error('Updating Assembly Line Failed!');
                if(result['error']['document_code'] === undefined){
                    $("#txtEditDocumentCode").removeClass('is-invalid');
                    $("#txtEditDocumentCode").attr('title', '');
                }
                else{
                    $("#txtEditDocumentCode").addClass('is-invalid');
                    $("#txtEditDocumentCode").attr('title', result['error']['document_code']);
                }

                if(result['error']['document_no'] === undefined){
                    $("#txtEditDocumentNo").removeClass('is-invalid');
                    $("#txtEditDocumentNo").attr('title', '');
                }
                else{
                    $("#txtEditDocumentNo").addClass('is-invalid');
                    $("#txtEditDocumentNo").attr('title', result['error']['document_no']);
                }

                if(result['error']['series'] === undefined){
                    $("#txtEditSeries").removeClass('is-invalid');
                    $("#txtEditSeries").attr('title', '');
                }
                else{
                    $("#txtEditSeries").addClass('is-invalid');
                    $("#txtEditSeries").attr('title', result['error']['series']);
                }

                if(result['error']['station'] === undefined){
                    $("#txtEditStation").removeClass('is-invalid');
                    $("#txtEditStation").attr('title', '');
                }
                else{
                    $("#txtEditStation").addClass('is-invalid');
                    $("#txtEditStation").attr('title', result['error']['station']);
                }

                if(result['error']['process'] === undefined){
                    $("#txtEditProcess").removeClass('is-invalid');
                    $("#txtEditProcess").attr('title', '');
                }
                else{
                    $("#txtEditProcess").addClass('is-invalid');
                    $("#txtEditProcess").attr('title', result['error']['process']);
                }

                if(result['error']['rev'] === undefined){
                    $("#txtEditRevision").removeClass('is-invalid');
                    $("#txtEditRevision").attr('title', '');
                }
                else{
                    $("#txtEditRevision").addClass('is-invalid');
                    $("#txtEditRevision").attr('title', result['error']['rev']);
                }

                if(result['error']['remarks'] === undefined){
                    $("#txtEditRemarks").removeClass('is-invalid');
                    $("#txtEditRemarks").attr('title', '');
                }
                else{
                    $("#txtEditRemarks").addClass('is-invalid');
                    $("#txtEditRemarks").attr('title', result['error']['remarks']);
                }
            }

            $("#iBtnAddDrawingRefIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddDrawingRef").removeAttr('disabled');
            $("#iBtnAddDrawingRefIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnAddDrawingRefIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddDrawingRef").removeAttr('disabled');
            $("#iBtnAddDrawingRefIcon").addClass('fa fa-check');
        }
    });
}


function ChangeDrawingRefStatus(){
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
        url: "change_drawing_ref_stat",
        method: "post",
        data: $('#formChangeDrawingRefStat').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnChangeDrawingRefStatIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnChangeDrawingRefStat").prop('disabled', 'disabled');
        },
        success: function(result){
            if(result['result'] == 1){
                $("#modalChangeDrawingRefStat").modal('hide');
                $("#formChangeDrawingRefStat")[0].reset();

                dataTableDrawingRef.draw();

                if($("#txtChangeDrawingRefStatDrawingRefStatus").val() == 1){
                    toastr.success('Drawing Ref Activation Success!');
                }
                else{
                    toastr.success('Drawing Ref Deactivation Success!');
                }
            }
            else{
                if($("#txtChangeDrawingRefStatDrawingRefStatus").val() == 1){
                    toastr.error('Drawing Ref Activation Failed!');
                }
                else{
                    toastr.error('Drawing Ref Deactivation Failed!');
                }
            }

            $("#iBtnChangeDrawingRefStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeDrawingRefStat").removeAttr('disabled');
            $("#iBtnChangeDrawingRefStatIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnChangeDrawingRefStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeDrawingRefStat").removeAttr('disabled');
            $("#iBtnChangeDrawingRefStatIcon").addClass('fa fa-check');
        }
    });
}

// function GetAssemblyLines(cboElement){
//     let result = '<option value="0" selected disabled> --- </option>';
//     $.ajax({
//         url: 'get_assembly_lines',
//         method: 'get',
//         dataType: 'json',
//         beforeSend: function(){
//             result = '<option value="0" selected disabled> -- Loading -- </option>';
//             cboElement.html(result);
//         },
//         success: function(JsonObject){
//             result = '';
//             if(JsonObject['assembly_lines'].length > 0){
//                 result = '<option value="0" selected disabled> --- </option>';
//                 for(let index = 0; index < JsonObject['assembly_lines'].length; index++){
//                     result += '<option value="' + JsonObject['assembly_lines'][index].id + '">' + JsonObject['assembly_lines'][index].name + '</option>';
//                 }
//             }
//             else{
//                 result = '<option value="0" selected disabled> -- No record found -- </option>';
//             }

//             cboElement.html(result);
//         },
//         error: function(data, xhr, status){
//             result = '<option value="0" selected disabled> -- Reload Again -- </option>';
//             cboElement.html(result);
//             console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
//         }
//     });
// }