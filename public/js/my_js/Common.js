let qrcode = "";
let imgResultQrCode = '';
let qrCodeTitle = '';
let genQRCodeName = '';

    //re_initialize_select2_server_side('#'+mdl_prdn_new+' #prdn_first_take_trained_by','#'+mdl_prdn_new+' #'+frm_prdn_new,[],"server_side_scripts/dropdown/common/dd_hris_all_list.php");

function re_initialize_select2_server_side(combo_id,dropdown_parent='',data_value,ajax_url){
	if($(combo_id).hasClass('select2-hidden-accessible')){
		$(combo_id).select2('destroy');
	}

	$(combo_id).select2({
        // theme: 'bootstrap-4',
		dropdownParent	: $(dropdown_parent),
		minimumInputLength: 2,
		// triggerChange: true,
		// allowClear: true,
		placeholder: {
			id: "",
			placeholder: "Leave blank to ..."
		},
		ajax: {
		url: "http://rapid/NAAYES/api/ypics_po_details_for_dlabel_ppts_f3.php",
			dataType: 'json',
			delay: 100,
			data: function (params) {
				return {
					q: params.term || '', // common search parameter
					term: params.term || '', // fallback for APIs expecting term
					po: params.term || '' // fallback for PO look-up APIs (e.g. ypics_po_details_for_dlabel_*.php)
				};
			},
			processResults: function (data) {

                let parsedData = data;
                console.log('Parsed Data:', parsedData);
                return;
				if(typeof parsedData === 'string'){
					try{
						parsedData = JSON.parse(parsedData);
					}
					catch(err){
						parsedData = [];
					}
				}

				let items = [];

				// Handle PO look-up API shape: { po_details: [ { wbs_kitting: {...} } | { yeu_kitting: {...} } ] }
				if(parsedData && Array.isArray(parsedData.po_details)){
					items = parsedData.po_details.map(function(detail){
						let info = (detail && (detail.wbs_kitting || detail.yeu_kitting)) || {};
						let poNo = info.po_no || info.po || '';
						let deviceName = info.device_name || info.product_name || '';
						return {
							id: poNo,
							text: deviceName ? (poNo + ' - ' + deviceName) : poNo
						};
					}).filter(function(item){
						return item.id !== '' && item.id !== null && item.id !== undefined;
					});
				}
				else if(Array.isArray(parsedData)){
					items = parsedData;
				}
				else if(parsedData && Array.isArray(parsedData.results)){
					items = parsedData.results;
				}
				else if(parsedData && typeof parsedData === 'object'){
					items = Object.keys(parsedData).map(function(key){
						return {
							id: key,
							text: parsedData[key]
						};
					});
				}

				const normalized = items.map(function(item){
					if(item && typeof item === 'object'){
						if(item.id !== undefined && item.text !== undefined){
							return item;
						}
						const id = item.id || item.value || item.po_no || item.po || item.code || item.text || '';
						const text = item.text || item.label || item.name || item.po_no || item.po || item.device_name || id;
						return {
							id: id,
							text: text
						};
					}

					return {
						id: item,
						text: item
					};
				}).filter(function(item){
					return item.id !== '' && item.id !== null && item.id !== undefined;
				});

				return {
					results: normalized
				};
			},
			cache: true
		}
	});
}

$(document).ready(function(){
	$(document).on('click', '.aGenerateBarcode', function(){
		let barcode = $(this).attr('barcode');
		qrCodeTitle = $(this).attr('title');
		genQRCodeName = $(this).attr('name');
		qrcode = barcode;
	    $.ajax({
	        url: "generate_qrcode",
	        method: "get",
	        data: {
	        	qrcode: barcode
	        },
	        // dataType: "json",
	        beforeSend: function(){

	        },
	        success: function(JsonObject){
				if(JsonObject['result'] == 1){
					$("#imgGenBarcode").attr("src", JsonObject['qrcode']);
					imgResultQrCode = JsonObject['qrcode'];
				}
				$("#lblGenBarcodeVal").text(barcode);
	        },
	        error: function(data, xhr, status){
	            alert('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);

	        }
	    });
	});

	$("#btnPrintBarcode").click(function(){
		// popup = window.open();
		// // popup.document.write('<br><br><div style="border: 2px solid black; padding: 1px 1px; max-width: 100px;" class="rotated"><img src="' + imgResultQrCode + '" style="max-width: 100px;"><br><center><label style="text-align: center; font-weight: bold; font-family: Arial;">' + qrcode + '</label></center></div>');
		// let content = '';
		// content += '<html>';
		// content += '<head>';
		// 	content += '<title></title>';
		// 	content += '<style type="text/css">';
		// 		content += '.rotated {';
		// 		content += '  transform: rotate(90deg); /* Equal to rotateZ(45deg) */';
		// 		content += '}';
		// 	content += '</style>';
		// content += '</head>';
		// content += '<body>';
		// 	//content += '<br><br><br>';
		// 	content += '<center>';
		// 	content += '<div class="rotated">';
		// 	content += '<table>';
		// 	content += '<tr>';
		// 	content += '<td>';
		// 	content += '<center>';
		// 	content += '<img src="' + imgResultQrCode + '" style="max-width: 100px;">';
		// 	content += '<br>';
		// 	content += '<label style="text-align: center; font-weight: bold; font-family: Arial;">' + qrcode + '</label>';
		// 	content += '</center>';
		// 	content += '</td>';
		// 	// content += '<td>';
		// 	// content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 14px;">' + genQRCodeName + ' <br> </label>';
		// 	// content += '</td>';
		// 	content += '</tr>';
		// 	content += '</table>';
		// 	content += '</div>';
		// 	content += '</center>';
		// content += '</body>';
		// content += '</html>';
		// popup.document.write(content);
		// popup.focus(); //required for IE
		// popup.print();
		// popup.close();

		popup = window.open();
        // popup.document.write('<br><br><div style="border: 2px solid black; padding: 1px 1px; max-width: 100px;" class="rotated"><img src="' + imgResultUserQrCode + '" style="max-width: 100px;"><br><center><label style="text-align: center; font-weight: bold; font-family: Arial;">' + qrcode + '</label></center></div>');
        let content = '';
        content += '<html>';
        content += '<head>';
          content += '<title></title>';
          content += '<style type="text/css">';
            content += '.rotated {';
              // content += 'transform: rotate(270deg); /* Equal to rotateZ(45deg) */';
              content += 'border: 2px solid black;';
              content += 'width: 150px;';
              content += 'position: absolute;';
              content += 'left: 15px;';
              content += 'top: 15px;';
            content += '}';
          content += '</style>';
        content += '</head>';
        content += '<body>';
          //content += '<br><br><br>';
          content += '<center>';
          content += '<div class="rotated">';
          content += '<table>';
          content += '<tr>';
          content += '<td>';
          content += '<center>';
          content += '<img src="' + imgResultQrCode + '" style="max-width: 70px;">';
          // content += '<br>';
          // content += '<label style="text-align: center; font-weight: bold; font-family: Arial;">' + genUserqrcode + '</label>';
          content += '</center>';
          content += '</td>';
          content += '<td>';
          content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">' + qrCodeTitle + '</label>';
          // content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">PO NO.:</label>';
          content += '<br>';
          content += '<label style="text-align: center; font-weight: bold; font-family: Arial Narrow; font-size: 10px;">' + genQRCodeName + '</label>';
          // content += '<label style="text-align: center; font-weight: bold; font-family: Arial Narrow; font-size: 10px;">450198990900010</label>';
          content += '</td>';
          content += '</tr>';
          content += '</table>';
          content += '</div>';
          content += '</center>';
        content += '</body>';
        content += '</html>';
        popup.document.write(content);
        popup.focus(); //required for IE
        popup.print();
        popup.close();
	});
});


const getWbsPoDetails = function (data){
    $.ajax({
        type      : "get",
        dataType  : "json",
        data      : {'po' : data},
        url       : "get_po_details",
        beforeSend: function(){
            $('#id_po_no').val('');
            $('#id_device_name').val('-- Data Loading --');
            $('#txt_device_code_lbl').val('-- Data Loading --');
            $('#id_po_qty').val('-- Data Loading --');
            $('#id_device_name').attr('device_name_print', 'not found' );
        },
        success : function(data){
            let po_details = data['po_details'][0].wbs_kitting;
            let device_name_print = data['device_name_print'];

            if(po_details != null){
              console.log('wbs_kitting',po_details);
                $('#id_po_no').val( po_details.po_no );
                $('#id_device_name').val( po_details.device_name);
                $('#txt_device_code_lbl').val( po_details.device_code);
                $('#add_series_name').val(device_name_print.device_name );
                $('#id_po_qty').val( po_details.po_qty);

              }else{
                let po_details = data['po_details'][0].yeu_kitting;
                console.log('yeu_kitting',po_details);
                $('#id_po_no').val( po_details.po_no );
                $('#id_device_name').val(po_details.product_name);
                $('#txt_device_code_lbl').val(po_details.item_code);
                $('#id_po_qty').val(po_details.po_qty );
                $('#add_series_name').val(po_details.product_name);
              }
        },error : function(data){
            $('#id_po_no').val('');
            $('#id_device_name').val('-- Data Error, Please Refresh --');
            $('#txt_device_code_lbl').val('-- Data Error, Please Refresh --');
            $('#id_po_qty').val('-- Data Error, Please Refresh --');
        }
    });
}
