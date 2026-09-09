<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Model\oqcLotApp;
use App\Model\OQCDBM;
use App\Model\AssemblyLine;
use App\Model\MaterialIssuanceSubSystem;
use App\Model\MaterialIssuanceDetails;
use App\Model\ProductionRuncard;
use App\Model\ProductionRuncardStation;
use App\Model\RapidActiveDocs;
use App\Model\Device;
use App\Model\Series;

use App\Model\OverallInspection;
use App\Model\OverallInspectionMOD;

use App\User;
use QrCode;


use DataTables;

use Excel;
use App\Exports\PartsLotReportExport;

class OverAllInspectionController extends Controller
{

    public function getTotalLotQuantityInOverallInspect(Request $request){
        $cnt = 0;
        $data = ProductionRuncardStation::where('status', 1)
            ->where('production_runcard_id', $request->prod_runcard_id )->get();
        for ($i=0; $i < count($data); $i++)
            $cnt += $data[$i]->qty_input;

        $overall_cnt = 0;
        $data = OverallInspection::where('prod_runcard_id', $request->prod_runcard_id)->get();
        for ($i=0; $i < count($data); $i++)
            $overall_cnt += $data[$i]->input;

        $array = array(
            'ttl_lot_qtt' => $cnt,
            'overall_qtt' => $overall_cnt,
            'need_qtt' => $cnt - $overall_cnt,
        );

        return response()->json(['data' => $array]);
    }

	public function saveOverallInspection(Request $request){
        date_default_timezone_set('Asia/Manila');
        $cnt = 0;
        $data = ProductionRuncardStation::where('status', 1)
            ->where('production_runcard_id', $request->prod_runcard_id )->get();
        for ($i=0; $i < count($data); $i++){
            $cnt += $data[$i]->qty_input;
        }
        $overall_cnt = 0;
        $data = OverallInspection::where('prod_runcard_id', $request->prod_runcard_id)->get();
        for ($i=0; $i < count($data); $i++)
            $overall_cnt += $data[$i]->input;

        if( $cnt < ($overall_cnt + $request->input) )
            return response()->json(['error_msg' => 'You exceeding the total lot quantity.']);

        // return '' . (json_encode($request->ng_qtt_mod) == 'null');

		$overall = new OverallInspection();
        // $overall->po_no = $request->po_no;
		$overall->prod_runcard_id = $request->prod_runcard_id;
		$overall->process = 'Overall Inspection';
		$overall->input = $request->input;
		$overall->output = $request->output;
		$overall->ng = $request->ng;
		$overall->date = $request->date;
		$overall->employee_id = $request->employee_id;
		$overall->updated_at = date('Y-m-d H:i:s'); // Added on 05-15-2024 -JD
		$overall->save();

		// $ids = substr($request->ids, 0, -1);
		// $ids = explode(',', $ids);

        if( (json_encode($request->ng_qtt_mod) == 'null') != 1 ){
            $ng_qtt_mod = $request->ng_qtt_mod;
            $select_mod = $request->select_mod;
            for ($i=0; $i < count($ng_qtt_mod); $i++) {
                $tbl = new OverallInspectionMOD();
                $tbl->overall_inspection_id = $overall->id;
                $tbl->mod_id = $select_mod[$i];
                $tbl->ng_qtt = $ng_qtt_mod[$i];
                $tbl->updated_at = date('Y-m-d H:i:s'); // Added on 05-15-2024 -JD
                $tbl->save();
            }
        }

        $overall_cnt = 0;
        $data = OverallInspection::where('prod_runcard_id', $request->prod_runcard_id)->get();
        for ($i=0; $i < count($data); $i++)
            $overall_cnt += $data[$i]->input;

        /**
         * This will only execute if the Process - Overall Inspection(column) in the Matrix(Module) is updated
         * Else this will not update the Production Runcard status(column) to 4 that is needed to the next process
         * Since the next process OQC Inspection(Module) pre-requisites the Production Runcard status(column) value that needs to be 4
         * ? -JD
         */
        if( $cnt == $overall_cnt ){
            // ProductionRuncard::whereIn('id', $ids)->update([
            ProductionRuncard::where('id', $request->prod_runcard_id)->update([
                'status' => 4,
            ]);
        }

		return response()->json(['result' => 1]);
	}

    public function get_oqc_lot_app_data_for_overAllInspect(Request $request){
        $oqc_inspections = ProductionRuncard::with([
            'oqc_details' => function($query){
                $query->orderBy('submission', 'DESC');},
            'oqc_details.user_details',
            'oqc_details.supervisor_prod_info',
            'oqc_details.supervisor_qc_info',
            'prod_runcard_station_many_details' => function($query){
                $query
                ->orderBy(\DB::raw('CONVERT(SUBSTRING_INDEX(step_num,"-", 1), UNSIGNED INTEGER)', 'ASC'))
                ->orderBy(\DB::raw('right(step_num,LOCATE("-",step_num) - 1)', 'ASC'));
            },
            'wbs_kitting',
            'wbs_kitting.device_info',
            'yeu_kitting'

        ])
        ->where('po_no',$request['po_no'])
        // ->whereIn('status', [3,4])
        ->whereIn('status', [3])
        // ->orWhere('id', 315)
        ->get();

        // if(is_null($oqc_inspections[0]->wbs_kitting)){
        //     $oqc_inspections = ProductionRuncard::with([
        //         'yeu_kitting'
        //     ])
        //     ->where('po_no', $request['po_no'])
        //     // ->whereNotNull('product_name')
        //     ->get();

        //     $device_name_print = 'not found';
        // }else{
        //     $device_name_print = 'not found';
        // }


        // return $oqc_inspections;
        if( count($oqc_inspections) > 0 )
            $device = Device::where('name', $request['device_name'])->get();
        else
            $device = null;

        $data = [];
        if( $device!=null && count($device) ){
            if( $device[0]->process==1 ){
                for ($i=0; $i < count($oqc_inspections); $i++) {
                	if ($oqc_inspections[$i]->oqc_details != null)
                		$data[] = $oqc_inspections[$i];
                }
            }
        }

        return DataTables::of($data)
        ->addColumn('select', function($oqc_inspection){
        	return '<input type="checkbox" class="select_runcard_id" data="'.$oqc_inspection['id'].'">';
        })
        ->addColumn('action', function($oqc_inspection){

            $cnt = 0;
            $data = ProductionRuncardStation::where('status', 1)
                ->where('production_runcard_id', $oqc_inspection['id'] )->get();
            for ($i=0; $i < count($data); $i++) {
                $cnt += $data[$i]->qty_input;
            }
            // return $cnt;

            $result = "";
            $data = OverallInspection::where('prod_runcard_id', $oqc_inspection['id'])->get();
            $overall_cnt = 0;
            for ($i=0; $i < count($data); $i++) {
                $overall_cnt += $data[$i]->input;
            }

            if( $cnt == $overall_cnt )
                $result.='<button type="button" class="btn btn-sm btn-primary btnView" value="'.$oqc_inspection['id'].'" title="View/Update Details"><i class="fa fa-file fa-sm"></i></button>';
            else
                $result.='<button type="button" class="btn btn-sm btn-primary btnOverall" value="'.$oqc_inspection['id'].'" title="View/Update Details"><i class="fa fa-edit fa-edit"></i></button>';
            // if ($oqc_inspection->oqc_details != null){
            //   $result.=' <button type="button" class="btn btn-sm btn-primary btn_print_lot" id="btn_print" data-toggle="modal" value="'.$oqc_inspection->oqc_details['fkid_runcard'].'" title="Print barcode"><i class="fa fa-print fa-sm"></i></button>';
            // }else{
            //   $result.=' <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" title="No Lot App Details" disabled><i class="fa fa-print fa-sm"></i></button>';
            // }
            return $result;
        })
        ->addColumn('lot_qty', function($oqc_inspection) use ($device){
            // if( isset( $device[0]->ship_boxing ) )
            //     return $device[0]->ship_boxing;
            // else
            //     return 0;
            $cnt = 0;
			$ng_cnt = 0;
			$data = ProductionRuncardStation::where('status', 1)
            	->where('production_runcard_id', $oqc_inspection['id'] )->get();
            for ($i=0; $i < count($data); $i++) {
                $cnt += $data[$i]->qty_input;
            	$ng_cnt += $data[$i]->qty_ng;
            }
            // return $cnt - $ng_cnt;
            return $cnt;
        })
        ->addColumn('output_qty_raw', function($oqc_inspection){
            $result = ProductionRuncardStation::where('production_runcard_id', $oqc_inspection->id)->where('status', 1)->get();
            $ttl = 0;
            for ($i=0; $i < count($result); $i++)
                $ttl = $ttl + $result[$i]->qty_output;
            return $ttl;
        })

        ->addColumn('fvo_raw', function($oqc_inspection){
            $result = '';
            if ( $oqc_inspection['oqc_details'] ){
                $empno_arr = array();
                $empno_arr = $oqc_inspection['oqc_details']->FVO_empid;
                $empno_arr = explode(',', $empno_arr);
                $user_details = array();
                if(count( $empno_arr )){
                    for ($i=0; $i < count($empno_arr) ; $i++) {
                        $user_details_temp = array();
                        $user_details_temp = User::where('id',$empno_arr[$i])->get();
                        array_push($user_details, $user_details_temp);
                    }
                }
                if( count($user_details) ){
                    foreach ($user_details as $key => $value) {
                        $result .= '<span class="badge badge-pill badge-info"> '.$value[0]['name'].'</span> ';
                    }
                }
            }
            return $result;
        })
        ->addColumn('status_raw', function($oqc_inspection){
            $result = "pending";
            // if ($oqc_inspection->has_emboss == 1){
            //     if ($oqc_inspection->require_oqc_before_emboss == 1){
            //         if($oqc_inspection->status == 7) {
            //             $result ='<span class="badge badge-pill badge-info">For Lot Application</span>';
            //             if ($oqc_inspection->oqc_details != null){
            //                 switch ($oqc_inspection->oqc_details->status) {
            //                     case 1:
            //                         $result ='<span class="badge badge-pill badge-warning">For OQC Supv. Approval</span>';
            //                         break;
            //                     case 2:
            //                         $result ='<span class="badge badge-pill badge-success">Done; Closed Lot</span>';
            //                         break;
            //                     default:
            //                         $result ='<span class="badge badge-pill badge-primary">For Prod`n Supv. Approval</span>';
            //                         break;
            //                 }
            //             } else{
            //                 $result ='<span class="badge badge-pill badge-info">For Lot Application</span>';
            //             }
            //         }
            //         else if($oqc_inspection->status == 8) {
            //             $result ='<span class="badge badge-pill badge-info">Emboss Done</span>';
            //             if ($oqc_inspection->oqc_details != null){
            //                 switch ($oqc_inspection->oqc_details->status) {
            //                     case 1:
            //                         $result ='<span class="badge badge-pill badge-warning">For OQC Supv. Approval</span>';
            //                         break;
            //                     case 2:
            //                         $result ='<span class="badge badge-pill badge-success">Done; Closed Lot</span>';
            //                         break;
            //                     default:
            //                         $result ='<span class="badge badge-pill badge-primary">For Prod`n Supv. Approval</span>';
            //                         break;
            //                 }
            //             } else {
            //                 $result ='<span class="badge badge-pill badge-info">Emboss Done | For Lot Application</span>';
            //             }
            //         }
            //         else{
            //             $result ='<span class="badge badge-pill badge-info">Pending in Runcard</span>';
            //         }
            //     }
            //     else{
            //         if($oqc_inspection->status == 8) {
            //             $result ='<span class="badge badge-pill badge-info">Emboss Done | For Lot Application</span>';
            //             if ($oqc_inspection->oqc_details != null){
            //                 switch ($oqc_inspection->oqc_details->status) {
            //                     case 1:
            //                         $result ='<span class="badge badge-pill badge-warning">For OQC Supv. Approval</span>';
            //                         break;
            //                     case 2:
            //                         $result ='<span class="badge badge-pill badge-success">Done; Closed Lot</span>';
            //                         break;
            //                     default:
            //                         $result ='<span class="badge badge-pill badge-primary">For Prod`n Supv. Approval</span>';
            //                         break;
            //                 }

            //             }
            //         }
            //         else if($oqc_inspection->status == 7) {
            //             $result ='<span class="badge badge-pill badge-info">Done Runcard | Pending Emboss</span>';
            //         }
            //         else{
            //             $result ='<span class="badge badge-pill badge-info">Pending in Runcard</span>';
            //         }
            //     }
            // }

            $cnt = 0;
            $data = ProductionRuncardStation::where('status', 1)
                ->where('production_runcard_id', $oqc_inspection['id'] )->get();
            for ($i=0; $i < count($data); $i++) {
                $cnt += $data[$i]->qty_input;
            }

            // return $data;

            // $result = "";
            $data = OverallInspection::where('prod_runcard_id', $oqc_inspection['id'])->get();
            $overall_cnt = 0;
            for ($i=0; $i < count($data); $i++) {
                $overall_cnt += $data[$i]->input;
            }
            // return $data;


            if( $cnt == $overall_cnt )
                $result = "Done.";

            if( $cnt > $overall_cnt && $overall_cnt > 0 )
                // $result = ($cnt - $overall_cnt) . " needed balance.";
                $result = 'On-going inspection';

            return $result;
        })
        ->addColumn('balance', function($oqc_inspection){
            $result = "";
            $data = OverallInspection::where('prod_runcard_id', $oqc_inspection['id'])->get();

            if( count($data)==0 )
                return '---';

            $overall_cnt = 0;
            for ($i=0; $i < count($data); $i++)
                $overall_cnt += $data[$i]->input;

            $cnt = 0;
            $ng_cnt = 0;
            $data = ProductionRuncardStation::where('status', 1)
                ->where('production_runcard_id', $oqc_inspection['id'] )->get();
            for ($i=0; $i < count($data); $i++){
                $cnt += $data[$i]->qty_input;
                $ng_cnt += $data[$i]->qty_ng;
            }
            // $cnt = $cnt - $ng_cnt;

            if( $cnt == $overall_cnt )
                $result = "---";

            if( $cnt > $overall_cnt && $overall_cnt > 0 )
                $result = ($cnt - $overall_cnt) ;

            return $result;
        })

        ->addColumn('subm_raw', function($oqc_inspection){
            $result = "";

            if ($oqc_inspection->oqc_details != null){
                switch ($oqc_inspection->oqc_details->submission) {
                    case 1:
                        $result ='<span class="badge badge-pill s1 badge-success">1st Sub</span>';
                        break;
                    case 2:
                        $result ='<span class="badge badge-pill s2 badge-warning">2nd Sub</span>';
                        break;
                    case 3:
                        $result ='<span class="badge badge-pill s3 badge-danger">3rd Sub</span>';
                        break;
                }

            }else{
                $result ='---';
            }
            return $result;
        })
        ->addColumn('sub_lot_raw', function($oqc_inspection)use($device){
            $result = "";
            $runcard_no = ($oqc_inspection->runcard_no);
            $newstring = substr($runcard_no, -3);

            // if(is_null($oqc_inspection->wbs_kitting)){
            //     $device_name = ($oqc_inspection->yeu_kitting->device_name);
            // }else{
            //     $device_name = ($oqc_inspection->wbs_kitting->device_name);
            // }
            // $devices = Device::where('name', $device_name)->get()->take(1);
            $ctr = 1;
            if ( isset( $device[0]->ship_boxing ) ){
                $i_total = 1000;
                $q = $device[0]->ship_boxing / $device[0]->boxing;
                for($i=$q;$i<$i_total; $i=$i+$q){
                    if( $newstring <= $i ){
                        break;
                    }
                    $ctr++;
                }
            }
            return $ctr;
        })
        ->rawColumns(['select','action','status_raw','fvo_raw','output_qty_raw','subm_raw','sub_lot_raw'])
        ->make(true);
    }

    public function view_prod_runcard_stations_for_overAllInspect(Request $request){
        date_default_timezone_set('Asia/Manila');
        // $has_emboss = $request->has_emboss;
        // $prod_runcard_no = "";
        // $prod_runcard_status = "";
        // if(isset($request->prod_runcard_status)){
        //     $prod_runcard_status = $request->prod_runcard_status;
        // }
        // if(isset($request->prod_runcard_no)){
        //     $prod_runcard_no = $request->prod_runcard_no;
        // }

        $prod_runcardstations = ProductionRuncardStation::with([
                'station' => function($query){
                    $query->where('status', 1);
                },
                'sub_station' => function($query){
                    $query->where('status', 1);
                },
                'ct_area_info',
                'terminal_area_info',
                // 'operator_info',
                // 'machine_info',
                // 'machine_details',
                'prod_runcard_station_machine_details',
                'prod_runcard_station_machine_details.machine_info',
                'prod_runcard_station_operator_details',
                'prod_runcard_station_operator_details.operator_info',
                // 'material_process_info',
                // 'material_process_info.machine_info'
                'prod_runcard_station_mod_details' => function($query) use($request){
                    $query->where('status', 1);
                },
                'prod_runcard_station_mod_details.mod_details',
                // 'prod_runcard_station_material_details',
            ])
            ->where('status', 1)
            ->where('production_runcard_id', $request->id )
            ->get();

        return DataTables::of($prod_runcardstations)
            ->setRowId('id')
            // ->addColumn('raw_action', function($prod_runcardstation) use ($prod_runcard_status, $prod_runcard_no){
            //     $disabled = '';
            //     $prod_runcard_id = $prod_runcardstation->production_runcard_id;

            //         $disabled = '';
            //                 if($prod_runcard_status == 3){
            //                     $disabled = 'disabled';
            //                 }
            //                 else{
            //                     $disabled = '';
            //                 }

            //     $disabled = 'disabled';
            //     $disabled = ''; // Comment this
            //     if(Auth::user()->position == 1){
            //         $disabled = '';
            //     }

            //     $result='<button '.$disabled.' style="width:30px; margin-top: 3px;" title="Edit" class="btn_material_action btn_edit_prod_runcard_station btn btn-info btn-sm py-0" mat-proc-id="' . $prod_runcardstation->mat_proc_id . '"><i class="fa fa-edit"></i></button>';
            //     $result.='<input type="hidden" class="col_prod_runcard_station_id" value="'.$prod_runcardstation->id.'">';

            //     $result.=' <button '.$disabled.' style="width:30px; margin-top: 3px;" title="Delete" class=" btn_scan_delete_process btn btn-danger btn-sm py-0" mat-proc-id="' . $prod_runcardstation->mat_proc_id . '" runcard-station-id="'.$prod_runcardstation->id.'"><i class="fa fa-times"></i></button>';

            //     $result.='<input type="hidden" class="col_prod_runcard_station_id" value="'.$prod_runcardstation->id.'">';

            //     return $result;
            // })
            ->addColumn('operators_info', function($prod_runcardstation){
                $operatorsName = "";

                if($prod_runcardstation->operator != null){
                    $operators = explode(",", $prod_runcardstation->operator);
                    $users = User::whereIn('id', $operators)->get();

                    $operatorsName = implode(" / ", $users->pluck('name')->toArray());
                }
                return $operatorsName;
            })
            ->addColumn('txt_qty_ng', function($prod_runcardstation){
                $result = '';
                if($prod_runcardstation->status == 0){
                }
                else{
                    $result = $prod_runcardstation->qty_ng;
                }

                    $result = '<center><input type="text" class="form-control form-control-sm txtEditProcessQtyNG" name="txt_qty_ng" style="max-width: 50px; margin: 1px 1px; padding: 1px 1px;"></center>';
                return $result;
            })
            ->addColumn('machines_info', function($prod_runcardstation){
                $machinesName = null;

                if($prod_runcardstation->machines != null){
                    $machines = explode(",", $prod_runcardstation->machines);
                    $machines = Machine::whereIn('id', $machines)->get();
                    $machinesName = implode(" / ", $machines->pluck('name')->toArray());
                }
                return $machinesName;
            })
            ->rawColumns([/*'raw_action', */'operators_info', 'machines_info', 'txt_qty_ng'])
            ->make(true);
    }
}
