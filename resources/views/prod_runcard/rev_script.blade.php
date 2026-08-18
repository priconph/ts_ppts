<script type="text/javascript">	
	$(function(){
		GetCboSubStationByStat($(".selSubStation"), 1);
		GetCboMaterialByStat($(".selMaterial"), 1);

		$(document).on('click','#btnAddProcess', function(e){
			// alert('wew');
			  $('#mdl_edit_prod_runcard_station_details').modal('show');
	        var data_arr = [];
	        data_arr['col_prod_runcard_station_id']     = $(this).closest('tr').find('.col_prod_runcard_station_id').val();
	        $("#txt_edit_prod_runcard_operator").val("0").trigger('change');

	        $("#tblEditProdRunStaMOD tbody").html('');
	        $("#pRCStatTotNoOfNG").css({color: 'green'});
	        $("#pRCStatTotNoOfNG").text('0');

	        // let lastStepNum1 = $('#tbl_prod_runcard_stations tr:last').find('td:eq(0)').html();
	        // let lastStepNum = $('#tbl_prod_runcard_stations tr:last').find('td:eq(1)').html();

	        // console.log(lastStepNum1);
	        let lastOutputQty = $('#tbl_prod_runcard_stations tr:last').find('td:eq(7)').html();
	        let lastInputQty = $('#tbl_prod_runcard_stations tr:last').find('td:eq(8)').html();

	        // if(lastStepNum1 == 'No data available in table'){
	        // 	lastStepNum = 1;
	        // 	lastOutputQty = $("#txt_lot_qty").val();
	        // }
	        // else{
	        // 	lastStepNum = parseInt(lastStepNum) + 1;
	        // }
	        // $("#txt_edit_prod_runcard_station_step").val(lastStepNum);
	        $("#txt_edit_prod_runcard_station_input").val(lastOutputQty);
	        $("#txt_edit_prod_runcard_station_output").val(0);
	        $("#txt_edit_prod_runcard_station_ng").val(0);
	        $("#txt_edit_prod_runcard_substation").val(0).trigger('change');
	        $("#txt_edit_prod_runcard_operator").val(0).trigger('change');
	        $("#txt_edit_prod_runcard_station_machine").val(0).trigger('change');

	        $("#btn_save_prod_runcard_station_stations").prop('disabled', false);
	        $('#txt_prod_runcard_station_id_query').val('');
      	});

      	$(document).on('click','#btnDEAddProcess', function(e){
			// alert('wew');
			  $('#mdl_edit_prod_runcard_station_details').modal('show');
	        var data_arr = [];
	        data_arr['col_prod_runcard_station_id']     = $(this).closest('tr').find('.col_prod_runcard_station_id').val();
	        $("#txt_edit_prod_runcard_operator").val("0").trigger('change');

	        $("#tblEditProdRunStaMOD tbody").html('');
	        $("#pRCStatTotNoOfNG").css({color: 'green'});
	        $("#pRCStatTotNoOfNG").text('0');

	        let lastStepNum1 = $('#tbl_prod_runcard_stations tr:last').find('td:eq(0)').html();
	        let lastStepNum = $('#tbl_prod_runcard_stations tr:last').find('td:eq(1)').html();

	        console.log(lastStepNum1);
	        let lastOutputQty = $('#tbl_prod_runcard_stations tr:last').find('td:eq(7)').html();
	        let lastInputQty = $('#tbl_prod_runcard_stations tr:last').find('td:eq(8)').html();

	        if(lastStepNum1 == 'No data available in table'){
	        	lastStepNum1 = 1;
	        	lastOutputQty = $("#txt_lot_qty").val();
	        }
	        else{
	        	lastStepNum1 = parseInt(lastStepNum1) + 1;
	        }
	        console.log(lastStepNum1);
	        $("#txt_edit_prod_runcard_station_step").val(lastStepNum1);
	        $("#txt_edit_prod_runcard_station_input").val(lastOutputQty);
	        $("#txt_edit_prod_runcard_station_output").val(0);
	        $("#txt_edit_prod_runcard_station_ng").val(0);
	        $("#txt_edit_prod_runcard_substation").val(0).trigger('change');
	        $("#txt_edit_prod_runcard_operator").val(0).trigger('change');
	        $("#txt_edit_prod_runcard_station_machine").val(0).trigger('change');

	        $("#btn_save_prod_runcard_station_stations").prop('disabled', false);
      	});

      	$("#btnAddMaterial").click(function(){
      		$("#mdlSaveMaterial").modal('show');
      		$("#frmSaveMaterial")[0].reset();
      		// 
      	});

      	$("#frmSaveMaterial").submit(function(e){
      		e.preventDefault();
      	});

      	$("#btnSaveMaterial").click(function(){
      		$('#txt_employee_number_scanner').val('');
        	$('#mdl_employee_number_scanner').attr('data-formid','save_material').modal('show');
      	});
	});
</script>