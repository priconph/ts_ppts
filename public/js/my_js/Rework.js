// VARIABLES

// FUNCTIONS
// Save Rework
function SaveRework(){
    // let url = globalLink.replace('link', 'save_rework');
    // let login = globalLink.replace('link', 'login');

    $.ajax({
        url: 'save_rework',
        method: 'post',
        data: frmSaveRework.serialize(),
        dataType: 'json',
        beforeSend() {
            btnSaveRework.prop('disabled', true);
            btnSaveRework.html('Saving...');
            $(".input-error", frmSaveRework).text('');
            $(".form-control", frmSaveRework).removeClass('is-invalid');
            // cnfrmLoading.open();
        },
        success(data){
            btnSaveRework.prop('disabled', false);
            btnSaveRework.html('Save');
            // cnfrmLoading.close();
            $("input[name='unit_no']", frmSaveRework).focus();

            if(data['auth'] == 1){
                if(data['result'] == 1){
                    toastr.success('Record Saved!');
                    frmSaveRework[0].reset();
                    mdlSaveRework.modal('hide');
                    $(".input-error", frmSaveRework).text('');
                    $(".form-control", frmSaveRework).removeClass('is-invalid');
                    dtReworks.draw();
                }
                else{
                    toastr.error('Saving Failed!');
                    if(data['error'] != null){
                        if(data['error']['unit_no'] != null){
                            $("input[name='unit_no']", frmSaveRework).addClass('is-invalid');
                        }
                        else{
                            $("input[name='unit_no']", frmSaveRework).removeClass('is-invalid');
                        }
                    }
                }
            }
            else{ // if session expired
                // cnfrmAutoLogin.open();
                toastr.error('Session Expired!');
            }
        },
        error(xhr, data, status){
            // cnfrmLoading.close();
            btnSaveRework.prop('disabled', false);
            btnSaveRework.html('Save');
            toastr.error('Saving Failed!');
        }
    });
}

function SaveReworkVerification(employeeNo, token){
    // let url = globalLink.replace('link', 'save_rework');
    // let login = globalLink.replace('link', 'login');

    $.ajax({
        url: 'save_rework_verification',
        method: 'post',
        data: {
            _token: token,
            rework_id: arrSelectedRework,
            employee_no: employeeNo,
            verification_type: $('select[name="verification_type"]', $("#frmSaveReworkVerification")).val(),
            verification_result: $('select[name="verification_result"]', $("#frmSaveReworkVerification")).val(),
        },
        dataType: 'json',
        beforeSend() {
            $('.btnSaveRework').prop('disabled', true);
            $('.btnSaveRework').html('Saving...');
            $(".input-error", $('#frmSaveReworkVerification')).text('');
            $(".form-control", $('#frmSaveReworkVerification')).removeClass('is-invalid');
            // cnfrmLoading.open();
        },
        success(data){
            $('.btnSaveRework').prop('disabled', false);
            $('.btnSaveRework').html('Save');
            // cnfrmLoading.close();
            $("input[name='unit_no']", $('#frmSaveReworkVerification')).focus();

            if(data['auth'] == 1){
                if(data['result'] == 1){
                    toastr.success('Record Saved!');
                    $('#frmSaveReworkVerification')[0].reset();
                    $('#mdlSaveReworkVerification').modal('hide');
                    arrSelectedRework = [];
                    dtReworks.draw();
                }
                else if(data['result'] == 2){
                    toastr.warning('Invalid User!');
                }
                else{
                    toastr.error('Saving Failed!');
                }
            }
            else{ // if session expired
                // cnfrmAutoLogin.open();
                toastr.error('Session Expired!');
            }
        },
        error(xhr, data, status){
            // cnfrmLoading.close();
            $('.btnSaveRework').prop('disabled', false);
            $('.btnSaveRework').html('Save');
            toastr.error('Saving Failed!');
        }
    });
}

function GetReworkById(reworkId){
    // let url = globalLink.replace('link', 'get_rework_by_id');
    // let login = globalLink.replace('link', 'login');

    $.ajax({
        url: 'get_rework_by_id',
        method: 'get',
        data: {
            rework_id: reworkId
        },
        dataType: 'json',
        beforeSend() {
            btnSaveRework.prop('disabled', true);
            btnSaveRework.html('Saving...');
            $(".input-error", frmSaveRework).text('');
            $(".form-control", frmSaveRework).removeClass('is-invalid');
            // cnfrmLoading.open();
            frmSaveRework[0].reset();
            $('input[name="rework_id"]', frmSaveRework).val('');
        },
        success(data){
            btnSaveRework.prop('disabled', false);
            btnSaveRework.html('Save');
            // cnfrmLoading.close();
            $("input[name='description']", frmSaveRework).focus();

            if(data['auth'] == 1){
                if(data['rework_info'] != null){
                    $("#mdlSaveRework").modal('show');
                    $('#select_runcard_no').attr('disabled', true)
                    $('#frmSaveRework_unit_no').attr('readonly', true)
                    $('input[name="rework_id"]', frmSaveRework).val(data['rework_info']['id']);
                    $('input[name="unit_no"]', frmSaveRework).val(data['rework_info']['unit_no']);
                    $('select[name="mode_of_defect_id"]', frmSaveRework).val(data['rework_info']['mode_of_defect_id']).trigger('change');
                    $('input[name="location_of_ng"]', frmSaveRework).val(data['rework_info']['location_of_ng']);
                    $('input[name="ng_qty"]', frmSaveRework).val(data['rework_info']['ng_qty']);
                    $('select[name="scrap"]', frmSaveRework).val(data['rework_info']['scrap']);
                    $('select[name="for_rework"]', frmSaveRework).val(data['rework_info']['for_rework']);
                    $('select[name="for_verification"]', frmSaveRework).val(data['rework_info']['for_verification']);
                    $('select[name="prod"]', frmSaveRework).val(data['rework_info']['prod']);
                    $('select[name="engg"]', frmSaveRework).val(data['rework_info']['engg']);
                    $('select[name="qc"]', frmSaveRework).val(data['rework_info']['qc']);
                    $('input[name="rework_qty"]', frmSaveRework).val(data['rework_info']['rework_qty']);
                    $('input[name="result_qty_ok"]', frmSaveRework).val(data['rework_info']['result_qty_ok']);
                    $('input[name="result_qty_scrap"]', frmSaveRework).val(data['rework_info']['result_qty_scrap']);
                    $('input[name="rework_code"]', frmSaveRework).val(data['rework_info']['rework_code']);
                    $('select[name="terminal_gauge"]', frmSaveRework).val(data['rework_info']['terminal_gauge']);
                    $('select[name="dummy_lo"]', frmSaveRework).val(data['rework_info']['dummy_lo']);
                    $('select[name="dummy_mo"]', frmSaveRework).val(data['rework_info']['dummy_mo']);
                    $('select[name="operator"]', frmSaveRework).val(data['rework_info']['operator']).trigger('change');
                    $('input[name="date"]', frmSaveRework).val(data['rework_info']['date']);

                    if(data['rework_info']['for_rework'] == 1){
                        $('.divForRework').show();
                    }
                    else{
                        $('.divForRework').hide();   
                    }
                }
                else{
                    toastr.error('No record found.');
                }
            }
            else{ // if session expired
                // cnfrmAutoLogin.open();
                toastr.error('Session Expired!');
            }
        },
        error(xhr, data, status){
            // cnfrmLoading.close();
            btnSaveRework.prop('disabled', false);
            btnSaveRework.html('Save');
            toastr.error('An error occured!');
        }
    });
}

function ReworkAction(reworkId, action, status){
    let url = globalLink.replace('link', 'rework_action');
    let login = globalLink.replace('link', 'login');

    $.ajax({
        url: url,
        method: 'post',
        data: {
            _token: _token,
            rework_id: reworkId,
            action: action,
            status: status,
        },
        dataType: 'json',
        beforeSend() {
            cnfrmLoading.open();
        },
        success(data){
            cnfrmLoading.close();
            dtReworks.draw();

            if(data['auth'] == 1){
                if(data['result'] == 1){
                    toastr.success('Record Saved!');
                }
                else{
                    toastr.error('Saving Failed!');
                }
            }
            else{ // if session expired
                cnfrmAutoLogin.open();
            }
        },
        error(xhr, data, status){
            cnfrmLoading.close();
            toastr.error('An error occured!');
        }
    });
}