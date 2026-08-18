function TSPTSViewLotAppDetails(lotapp_id)
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
        url: "tspts_view_lotapp_details",
        method: "get",
        data: {
            lotapp_id: lotapp_id
        },
        dataType: "json",
        beforeSend: function(){
            
        },
        success: function(JsonObject){
           		
        	if(JsonObject['result'] == 1)
        	{
        		let po_num = JsonObject['lotapp_details'][0].po_no;
        		let lot_number = JsonObject['lotapp_details'][0].runcard_no;
        		let lot_id = JsonObject['lotapp_details'][0].id;
        		let lotapp_quantity = JsonObject['lotapp_quantity'];

        		$('#add_po_no').val(po_num);
        		$('#add_lot_id').val(lot_id);
        		$('#add_lot_no').val(lot_number);
        		$('#add_lot_qty').val(lotapp_quantity);

                tray_check_list = JsonObject['list_of_trays']
                tray_check_list_2 = JsonObject['list_of_trays_2']
                let html = ""
                let html_2 = ""
                let html_3 = ""
                let ttl_qtt = 0
                for (var i = 0; i < tray_check_list.length; i++) {
                    html += "<tr id='tray_check_list_tr_id_" + i + "'>"
                    html +=     "<td style='padding: 5px; width: 15%;'>" + tray_check_list[i]['po_no'] + "</td>"
                    html +=     "<td style='padding: 5px; width: 15%;'>" + tray_check_list[i]['lot_no'] + "</td>"
                    html +=     "<td style='padding: 5px; width: 15%;'>" + tray_check_list[i]['qtt'] + "</td>"
                    html +=     "<td style='padding: 5px; width: 15%;'>" + tray_check_list[i]['counter'] + "</td>"
                    html +=     "<td style='padding: 5px; width: 15%;' id='tray_check_list_id_" + i + "'>pending</td>"
                    html += "</tr>"

                    html_2 += "<tr id='tray_check_list_tr_id_" + i + "_2'>"
                    html_2 +=     "<td style='padding: 5px; width: 15%;'>" + tray_check_list[i]['po_no'] + "</td>"
                    html_2 +=     "<td style='padding: 5px; width: 15%;'>" + tray_check_list[i]['lot_no'] + "</td>"
                    html_2 +=     "<td style='padding: 5px; width: 15%;'>" + tray_check_list[i]['qtt'] + "</td>"
                    html_2 +=     "<td style='padding: 5px; width: 15%;'>" + tray_check_list[i]['counter'] + "</td>"
                    html_2 +=     "<td style='padding: 5px; width: 15%;' id='tray_check_list_id_" + i + "_2'>pending</td>"
                    html_2 += "</tr>"

                    ttl_qtt += tray_check_list[i]['qtt']
                }
        
                html_3 += "<tr id='tbl_dlabel_scan'>"
                html_3 +=     "<td style='padding: 5px; width: 15%;'>" + po_num + "</td>"
                html_3 +=     "<td style='padding: 5px; width: 15%;'>" + lot_number + "</td>"
                html_3 +=     "<td style='padding: 5px; width: 15%;'>" + lotapp_quantity + "</td>"
                html_3 +=     "<td style='padding: 5px; width: 15%;'>" + JsonObject['counter'] + "</td>"
                html_3 +=     "<td style='padding: 5px; width: 15%;' id='tray_check_list_id_3'>pending</td>"
                html_3 += "</tr>"
    
                let packing_list = ""
                casemark_list = []
                let list = JsonObject['packing_list']
                for (var i = 0; i < list.length; i++) {
                    for (var j = 1; j <= 10; j++)
                        list[i]['casemark'] = list[i]['casemark'].replace("\n", " ")
                    for (var j = 1; j <= 10; j++){
                        if( list[i]['casemark'][list[i]['casemark'].length - 1] == " " )
                            list[i]['casemark'] = list[i]['casemark'].substring(0, list[i]['casemark'].length - 1)
                    }

                    let _qtt = list[i]['qty'] + ""
                    if( list[i]['box_no'].split('-').length == 2 )
                        _qtt = list[i]['gross_weight'].split('(')[1].split('/')[0] + ""

                    packing_list += "<tr id='tbl_packing_list_scan_" + i + "'>"
                    packing_list +=     "<td style='padding: 5px;'>" + list[i]['control_no'] + "</td>"
                    packing_list +=     "<td style='padding: 5px;'>" + list[i]['po'] + "</td>"
                    packing_list +=     "<td style='padding: 5px;'>" + list[i]['box_no'] + "</td>"
                    packing_list +=     "<td style='padding: 5px;'>" + _qtt + "</td>"
                    packing_list +=     "<td style='padding: 5px;'>" + list[i]['casemark'] + "</td>"
                    packing_list +=     "<td style='padding: 5px;' id='packing_list_check_list_id_" + i + "'>pending</td>"
                    packing_list += "</tr>"

                    casemark_list.push([ list[i]['po'], list[i]['item_code'], list[i]['box_no'], _qtt, list[i]['casemark'], 0 ])
                }
                $('#tblTrayChecker_casemark').html(packing_list)
                casemark_scanned = false

                dlabel_to_scan = [ po_num, lot_number, lotapp_quantity, JsonObject['counter'], 0 ]

                $('#tblTrayChecker').html(html)
                $('#tblTrayChecker_ttl_quantity').html(ttl_qtt)
                $('#tblTrayChecker_ttl_quantity_scanned').html(0)
                
                $('#tblTrayChecker_2').html(html_2)
                $('#tblTrayChecker_ttl_quantity_2').html(ttl_qtt)
                $('#tblTrayChecker_ttl_quantity_scanned_2').html(0)

                $('#tblTrayChecker_3').html(html_3)

                // dt_packing_accessories.draw();                
        	}
        	else
        	{
        		toastr.error('Error Loading Details!');
        	}

        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function TSPTSViewPackingConfirmationDetails(lotapp_id, device_name)
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
        url: "tspts_view_lotapp_details",
        method: "get",
        data: {
            lotapp_id: lotapp_id
        },
        dataType: "json",
        beforeSend: function(){
            
        },
        success: function(JsonObject){
           		
        	if(JsonObject['result'] == 1)
        	{
        		let po_num = JsonObject['lotapp_details'][0].po_no;
        		let lot_number = JsonObject['lotapp_details'][0].runcard_no;
        		let lot_id = JsonObject['lotapp_details'][0].id;
        		let lotapp_quantity = JsonObject['lotapp_quantity'];

        		$('#add_po_no').val(po_num);
        		$('#add_lot_id').val(lot_id);
        		$('#add_lot_no').val(lot_number);
        		$('#add_lot_qty').val(lotapp_quantity);

                $('#add_series_name').val(device_name);

                dt_packing_accessories.draw();
        	}
        	else
        	{
        		toastr.error('Error Loading Details!');
        	}

        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function TSPTSSubmitOQCInspection()
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
    	url: "tspts_submit_oqc_inspection",
        method: "post",
        data: $('#formAddInspection').serialize(),
        dataType: "json",
        beforeSend: function(){
            
        },
        success: function(JsonObject){
           		
        	if(JsonObject['result'] == 1)
        	{
        		$('#modalAddInspection').modal('hide');
        		$('#formAddInspection')[0].reset();
        		toastr.success('Successfully Submitted Inspection!');

        		dt_oqcvir.draw();
        	}
        	else
        	{
        		toastr.error('Error Submitting Details!');

        		if(JsonObject['error']['add_oqc_sample_size'] === undefined)
        		{
        			$('#add_oqc_sample_size').removeClass('is-invalid');
        		}
        		else
        		{
        			$('#add_oqc_sample_size').addClass('is-invalid');
        		}

        		if(JsonObject['error']['add_ok_qty'] === undefined)
        		{
        			$('#add_ok_qty').removeClass('is-invalid');
        		}
        		else
        		{
        			$('#add_ok_qty').addClass('is-invalid');
        		}

                if(JsonObject['error']['add_inspection_starttime'] === undefined)
                {
                    $('#add_inspection_starttime').removeClass('is-invalid');
                }
                else
                {
                    $('#add_inspection_starttime').addClass('is-invalid');
                }

        		if(JsonObject['error']['add_inspection_datetime'] === undefined)
        		{
        			$('#add_inspection_datetime').removeClass('is-invalid');
        		}
        		else
        		{
        			$('#add_inspection_datetime').addClass('is-invalid');
        		}

        		if(JsonObject['error']['add_terminal'] === undefined)
        		{
        			$('#add_terminal').removeClass('is-invalid');
        		}
        		else
        		{
        			$('#add_terminal').addClass('is-invalid');
        		}

        		if(JsonObject['error']['add_yd_label'] === undefined)
        		{
        			$('#add_yd_label').removeClass('is-invalid');
        		}
        		else
        		{
        			$('#add_yd_label').addClass('is-invalid');
        		}

        		if(JsonObject['error']['add_csh_coating'] === undefined)
        		{
        			$('#add_csh_coating').removeClass('is-invalid');
        		}
        		else
        		{
        			$('#add_csh_coating').addClass('is-invalid');
        		}

        		if(JsonObject['error']['add_accessories_requirement'] === undefined)
        		{
        			$('#add_accessories_requirement').removeClass('is-invalid');
        		}
        		else
        		{
        			$('#add_accessories_requirement').addClass('is-invalid');
        		}

        		if(JsonObject['error']['add_coc_requirement'] === undefined)
        		{
        			$('#add_coc_requirement').removeClass('is-invalid');
        		}
        		else
        		{
        			$('#add_coc_requirement').addClass('is-invalid');
        		}

        		if(JsonObject['error']['add_result'] === undefined)
        		{
        			$('#add_result').removeClass('is-invalid');
        		}
        		else
        		{
        			$('#add_result').addClass('is-invalid');
        		}

        		if(JsonObject['error']['add_oqc_inspector_name'] === undefined)
        		{
        			$('#add_oqc_inspector_name2').removeClass('is-invalid');
        		}
        		else
        		{
        			$('#add_oqc_inspector_name2').addClass('is-invalid');
        		}
        	}

        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function TSPTSSubmitOQCInspectionEdit()
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
        url: "tspts_submit_oqc_inspection_edit",
        method: "post",
        data: $('#formEditInspection').serialize(),
        dataType: "json",
        beforeSend: function(){
            
        },
        success: function(JsonObject){
                
            if(JsonObject['result'] == 1)
            {
                $('#modalEditInspection').modal('hide');
                $('#formEditInspection')[0].reset();
                toastr.success('Successfully Edited Inspection!');
                
                dt_oqcvir_results.draw();
            }
            else
            {
                toastr.error('Error Submitting Details!');

                if(JsonObject['error']['edit_oqc_sample_size'] === undefined)
                {
                    $('#edit_oqc_sample_size').removeClass('is-invalid');
                }
                else
                {
                    $('#edit_oqc_sample_size').addClass('is-invalid');
                }

                if(JsonObject['error']['edit_ok_qty'] === undefined)
                {
                    $('#edit_ok_qty').removeClass('is-invalid');
                }
                else
                {
                    $('#edit_ok_qty').addClass('is-invalid');
                }

                if(JsonObject['error']['edit_inspection_starttime'] === undefined)
                {
                    $('#edit_inspection_starttime').removeClass('is-invalid');
                }
                else
                {
                    $('#edit_inspection_starttime').addClass('is-invalid');
                }

                if(JsonObject['error']['edit_inspection_datetime'] === undefined)
                {
                    $('#edit_inspection_datetime').removeClass('is-invalid');
                }
                else
                {
                    $('#edit_inspection_datetime').addClass('is-invalid');
                }

                if(JsonObject['error']['edit_terminal'] === undefined)
                {
                    $('#edit_terminal').removeClass('is-invalid');
                }
                else
                {
                    $('#edit_terminal').addClass('is-invalid');
                }

                if(JsonObject['error']['edit_yd_label'] === undefined)
                {
                    $('#edit_yd_label').removeClass('is-invalid');
                }
                else
                {
                    $('#edit_yd_label').addClass('is-invalid');
                }

                if(JsonObject['error']['edit_csh_coating'] === undefined)
                {
                    $('#edit_csh_coating').removeClass('is-invalid');
                }
                else
                {
                    $('#edit_csh_coating').addClass('is-invalid');
                }

                if(JsonObject['error']['edit_accessories_requirement'] === undefined)
                {
                    $('#edit_accessories_requirement').removeClass('is-invalid');
                }
                else
                {
                    $('#edit_accessories_requirement').addClass('is-invalid');
                }

                if(JsonObject['error']['edit_coc_requirement'] === undefined)
                {
                    $('#edit_coc_requirement').removeClass('is-invalid');
                }
                else
                {
                    $('#edit_coc_requirement').addClass('is-invalid');
                }

                if(JsonObject['error']['edit_oqc_inspector_name'] === undefined)
                {
                    $('#edit_oqc_inspector_name2').removeClass('is-invalid');
                }
                else
                {
                    $('#edit_oqc_inspector_name2').addClass('is-invalid');
                }
            }

        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function TSPTSSubmitPackingConfirmation()
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
    	url: "tspts_submit_packing_confirmation",
        method: "post",
        data: $('#formPackingConfirmation').serialize(),
        dataType: "json",
        beforeSend: function(){
            
        },
        success: function(JsonObject){
           		
        	if(JsonObject['result'] == 1)
        	{
        		$('#modalPackingConfirmation').modal('hide');
        		$('#formPackingConfirmation')[0].reset();
        		toastr.success('Successfully Submitted Confirmation!');

                

        		dt_packing_confirmation.draw();
        	}
        	else
        	{
        		toastr.error('Error Submitting Details!');

        		if(JsonObject['error']['add_series_v_label'] === undefined)
        		{
        			$('#add_series_v_label').removeClass('is-invalid');
        		}
        		else
        		{
        			$('#add_series_v_label').addClass('is-invalid');
        		}

        		if(JsonObject['error']['add_label_v_actual'] === undefined)
        		{
        			$('#add_label_v_actual').removeClass('is-invalid');
        		}
        		else
        		{
        			$('#add_label_v_actual').addClass('is-invalid');
        		}

                if(JsonObject['error']['add_silica_gel'] === undefined)
                {
                    $('#add_silica_gel').removeClass('is-invalid');
                }
                else
                {
                    $('#add_silica_gel').addClass('is-invalid');
                }

                if(JsonObject['error']['add_yd_label'] === undefined)
                {
                    $('#add_yd_label').removeClass('is-invalid');
                }
                else
                {
                    $('#add_yd_label').addClass('is-invalid');
                }

                if(JsonObject['error']['add_packing_conf_no_of_tray_boxes'] === undefined)
                {
                    $('#add_packing_conf_no_of_tray_boxes').removeClass('is-invalid');
                }
                else
                {
                    $('#add_packing_conf_no_of_tray_boxes').addClass('is-invalid');
                }

        		if(JsonObject['error']['add_packing_operator_name'] === undefined)
        		{
        			$('#add_packing_operator_name').removeClass('is-invalid');
        		}
        		else
        		{
        			$('#add_packing_operator_name').addClass('is-invalid');
        		}

        		// if(JsonObject['error']['add_confirmation_datetime'] === undefined)
        		// {
        		// 	$('#add_confirmation_datetime').removeClass('is-invalid');
        		// }
        		// else
        		// {
        		// 	$('#add_confirmation_datetime').addClass('is-invalid');
        		// }

        	}

        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function TSPTSSubmitPackingInspection()
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
        url: "tspts_submit_packing_inspection",
        method: "post",
        data: $('#formPackingInspection').serialize(),
        dataType: "json",
        beforeSend: function(){
            
        },
        success: function(JsonObject){
                
            if(JsonObject['result'] == 1)
            {
                $('#modalPackingInspection').modal('hide');
                $('#formPackingInspection')[0].reset();
                toastr.success('Successfully Submitted Inspection!');

                dt_packing_inspection.draw();
            }
            else
            {
                toastr.error('Error Submitting Details!');

                if(JsonObject['error']['add_packing_type'] === undefined)
                {
                    $('#add_packing_type').removeClass('is-invalid');
                }
                else
                {
                    $('#add_packing_type').addClass('is-invalid');
                }

                if(JsonObject['error']['add_unit_condition'] === undefined)
                {
                    $('#add_unit_condition').removeClass('is-invalid');
                }
                else
                {
                    $('#add_unit_condition').addClass('is-invalid');
                }

                if(JsonObject['error']['add_series_v_label'] === undefined)
                {
                    $('#add_series_v_label').removeClass('is-invalid');
                }
                else
                {
                    $('#add_series_v_label').addClass('is-invalid');
                }

                if(JsonObject['error']['add_label_v_actual'] === undefined)
                {
                    $('#add_label_v_actual').removeClass('is-invalid');
                }
                else
                {
                    $('#add_label_v_actual').addClass('is-invalid');
                }

                if(JsonObject['error']['add_silica_gel'] === undefined)
                {
                    $('#add_silica_gel').removeClass('is-invalid');
                }
                else
                {
                    $('#add_silica_gel').addClass('is-invalid');
                }
                
                if(JsonObject['error']['add_yd_label'] === undefined)
                {
                    $('#add_yd_label').removeClass('is-invalid');
                }
                else
                {
                    $('#add_yd_label').addClass('is-invalid');
                }

                // if(JsonObject['error']['add_supervisor_validation'] === undefined)
                // {
                //     $('#add_supervisor_validation').removeClass('is-invalid');
                // }
                // else
                // {
                //     $('#add_supervisor_validation').addClass('is-invalid');
                // }

                if(JsonObject['error']['add_packing_inspector_name'] === undefined)
                {
                    $('#add_packing_inspector_name').removeClass('is-invalid');
                }
                else
                {
                    $('#add_packing_inspector_name').addClass('is-invalid');
                }

                // if(JsonObject['error']['add_inspection_datetime'] === undefined)
                // {
                //     $('#add_inspection_datetime').removeClass('is-invalid');
                // }
                // else
                // {
                //     $('#add_inspection_datetime').addClass('is-invalid');
                // }

            }

        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function TSPTSSubmitSupervisorValidation()
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
        url: "tspts_submit_supervisor_validation",
        method: "post",
        data: $('#formSupervisorValidation').serialize(),
        dataType: "json",
        beforeSend: function(){
            
        },
        success: function(JsonObject){
                
            if(JsonObject['result'] == 1)
            {
                $('#modalSupervisorValidation').modal('hide');
                $('#formSupervisorValidation')[0].reset();
                toastr.success('Successfully Submitted Validation!');

                dt_supervisor_validation.draw();
            }
            else
            {
                toastr.error('Error Submitting Details!');

                if(JsonObject['error']['add_series_v_label'] === undefined)
                {
                    $('#add_series_v_label').removeClass('is-invalid');
                }
                else
                {
                    $('#add_series_v_label').addClass('is-invalid');
                }

                if(JsonObject['error']['add_label_v_actual'] === undefined)
                {
                    $('#add_label_v_actual').removeClass('is-invalid');
                }
                else
                {
                    $('#add_label_v_actual').addClass('is-invalid');
                }

                if(JsonObject['error']['add_supervisor_name'] === undefined)
                {
                    $('#add_supervisor_name').removeClass('is-invalid');
                }
                else
                {
                    $('#add_supervisor_name').addClass('is-invalid');
                }

                // if(JsonObject['error']['add_confirmation_datetime'] === undefined)
                // {
                //     $('#add_confirmation_datetime').removeClass('is-invalid');
                // }
                // else
                // {
                //     $('#add_confirmation_datetime').addClass('is-invalid');
                // }

            }

        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function TSPTSSubmitFinalPackingInspection()
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
        url: "tspts_submit_final_packing_inspection",
        method: "post",
        data: $('#formFinalPackingInspection').serialize(),
        dataType: "json",
        beforeSend: function(){
            
        },
        success: function(JsonObject){
                
            if(JsonObject['result'] == 1)
            {
                $('#modalFinalPackingInspection').modal('hide');
                $('#formFinalPackingInspection')[0].reset();
                toastr.success('Successfully Submitted Inspection!');

                dt_final_inspection.draw();
            }
            else
            {
                toastr.error('All trays need to be scanned; Error Submitting Details!');

                if(JsonObject['error']['add_packing_operator_name'] === undefined)
                {
                    $('#add_packing_operator_name').removeClass('is-invalid');
                }
                else
                {
                    $('#add_packing_operator_name').addClass('is-invalid');
                }

                if(JsonObject['error']['add_coc_attachment'] === undefined)
                {
                    $('#add_coc_attachment').removeClass('is-invalid');
                }
                else
                {
                    $('#add_coc_attachment').addClass('is-invalid');
                }

                if(JsonObject['error']['add_result'] === undefined)
                {
                    $('#add_result').removeClass('is-invalid');
                }
                else
                {
                    $('#add_result').addClass('is-invalid');
                }

                // if(JsonObject['error']['add_confirmation_datetime'] === undefined)
                // {
                //     $('#add_confirmation_datetime').removeClass('is-invalid');
                // }
                // else
                // {
                //     $('#add_confirmation_datetime').addClass('is-invalid');
                // }

                if(JsonObject['error']['add_oqc_inspector_name'] === undefined)
                {
                    $('#add_oqc_inspector_name').removeClass('is-invalid');
                }
                else
                {
                    $('#add_oqc_inspector_name').addClass('is-invalid');
                }

            }

        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}