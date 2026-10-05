function GetMaterialKittingList(cboElement){
    let result = '<option value="">N/A</option>';
    $.ajax({
        url: 'get_wbs_kitting_details',
        method: 'get',
        dataType: 'json',
        beforeSend: function(){
            result = '<option disabled value=""> -- Loading -- </option>';
            cboElement.html(result);
        },
        success: function(JsonObject){
            result = '';
            if(JsonObject['kitting_details'].length > 0){
                // result = '<option value="">N/A</option>';
                for(let index = 0; index < JsonObject['kitting_details'].length; index++){
                    result += '<option data-code="' + JsonObject['kitting_details'][index].item + '" value="' + JsonObject['kitting_details'][index].item + '--' + JsonObject['kitting_details'][index].item_desc + '" ' + '>' + JsonObject['kitting_details'][index].item_desc + '</option>';
                }
            }
            else{
                result = '<option value=""> -- No record found -- </option>';
            }

            cboElement.html(result);
        },
        error: function(data, xhr, status){
            result = '<option value=""> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function GetMaterialKittingListByPoNo(cboElement, poNo){
    let result = '<option value="">N/A</option>';
    $.ajax({
        url: 'get_wbs_kitting_details_by_po_no',
        method: 'get',
        dataType: 'json',
        data: {
            po_no: poNo,
        },
        beforeSend: function(){
            result = '<option disabled value=""> -- Loading -- </option>';
            cboElement.html(result);
        },
        success: function(JsonObject){
            result = '';
            // let itemDesc = $('#selSaveAccessoryName option:selected').attr('item-desc');
            let usage = '';
            let issuedQty = '';

            if(JsonObject['kitting_details'].length > 0){

                // result = '<option value="">N/A</option>';
                for(let index = 0; index < JsonObject['kitting_details'].length; index++){
                    result += '<option data-code="' + JsonObject['kitting_details'][index].item + '" item-desc="' + JsonObject['kitting_details'][index].item_desc + '" usage="' + JsonObject['kitting_details'][index].usage + '" issued-qty="' + JsonObject['kitting_details'][index].issued_qty + '" value="' + JsonObject['kitting_details'][index].item + '--' + JsonObject['kitting_details'][index].item_desc + '--' + JsonObject['kitting_details'][index].id + '" ' + '>' + JsonObject['kitting_details'][index].item_desc + '</option>';
                    if(index <= 0){
                        usage = JsonObject['kitting_details'][index].usage;
                        issuedQty = JsonObject['kitting_details'][index].issued_qty;
                    }
                }

                for(let index = 0; index < JsonObject['yeu_kitting_details'].length; index++){
                    result += '<option data-code="' + JsonObject['yeu_kitting_details'][index].item_code + '" item-desc="' + JsonObject['yeu_kitting_details'][index].item_name + '" usage="' + JsonObject['yeu_kitting_details'][index].usg + '" issued-qty="' + JsonObject['yeu_kitting_details'][index].qty + '" value="' + JsonObject['yeu_kitting_details'][index].item_code + '--' + JsonObject['yeu_kitting_details'][index].item_name + '--' + JsonObject['yeu_kitting_details'][index].id + '" ' + '>' + JsonObject['yeu_kitting_details'][index].item_name + '</option>';
                    if(index <= 0){
                        usage = JsonObject['yeu_kitting_details'][index].usg;
                        issuedQty = JsonObject['yeu_kitting_details'][index].qty;
                    }
                }

            }
            else if(JsonObject['kitting_details'].length === 0 && JsonObject['yeu_kitting_details'].length > 0){
                console.log('JsonObject',JsonObject['yeu_kitting_details']);

                for(let index = 0; index < JsonObject['yeu_kitting_details'].length; index++){
                    result += '<option data-code="' + JsonObject['yeu_kitting_details'][index].item_code + '" item-desc="' + JsonObject['yeu_kitting_details'][index].item_name + '" usage="' + JsonObject['yeu_kitting_details'][index].usg + '" issued-qty="' + JsonObject['yeu_kitting_details'][index].qty + '" value="' + JsonObject['yeu_kitting_details'][index].item_code + '--' + JsonObject['yeu_kitting_details'][index].item_name + '--' + JsonObject['yeu_kitting_details'][index].id + '" ' + '>' + JsonObject['yeu_kitting_details'][index].item_name + '</option>';
                    if(index <= 0){
                        usage = JsonObject['yeu_kitting_details'][index].usg;
                        issuedQty = JsonObject['yeu_kitting_details'][index].qty;
                    }
                }
            }
            else{
                result = '<option value=""> -- No record found -- </option>';
            }

            cboElement.html(result);


            // $("input[name='accessory_name']", $("#frmSaveAccessory")).val(itemDesc);
            $("input[name='quantity']", $("#frmSaveAccessory")).val(usage);
            $("input[name='usage_per_socket']", $("#frmSaveAccessory")).val(issuedQty);
        },
        error: function(data, xhr, status){
            result = '<option value=""> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}
