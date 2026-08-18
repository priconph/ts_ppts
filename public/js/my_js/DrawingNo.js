
function GetDrawingNo(cboElement){
    let result = '<option value="0" selected disabled> --- </option>';

        var data = 'device_name='+ $("#txt_device_name_lbl").val() + '&str=' + $(cboElement).val() 

    $.ajax({
        url: 'get_drawing_no',
        data: data,
        method: 'get',
        dataType: 'json',
        beforeSend: function(){
            result = '<option value="0" selected disabled> -- Loading -- </option>';
            cboElement.html(result);
        },
        success: function(JsonObject){
            result = '';
            if(JsonObject['doc'].length > 0){
                result = '<option value="0" selected disabled> --- </option>';
                for(let index = 0; index < JsonObject['doc'].length; index++){
                    result += '<option value="' + JsonObject['doc'][index]['doc_no'] + '" data-revision="' + JsonObject['doc'][index]['rev_no'] + '">' + JsonObject['doc'][index]['doc_no'] + ' ('+JsonObject['doc'][index]['doc_title']+')' + '</option>';
                }
            }
            else{
                result = '<option value="0" selected disabled> -- No record found -- </option>';
            }

            cboElement.html(result);
        },
        error: function(data, xhr, status){
            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}
