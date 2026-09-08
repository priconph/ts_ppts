<?php

namespace App\Http\Controllers;

use Auth;
use QrCode;
use App\User;
use DataTables;
use Carbon\Carbon;

use App\Model\Device;
use App\Model\Dlabel;

use App\Model\oqcLotApp;
use App\Model\YeuKitting;
use App\Model\DlabelBoxes;
use App\Model\PackingCode;
use App\Model\PackingList;
use App\Model\TSPTSOqcVir;
use App\Model\ModeOfDefect;
use App\Model\DlabelHistory;
use App\Model\OQCInspection;
use Illuminate\Http\Request;

use App\Model\FPDetailsQRCode;
use App\Model\OQCInspection_2;
use App\Model\ShipmentSummary;
use App\Model\ProductionRuncard;
use App\Model\PackingListDetails;
use Illuminate\Support\Facades\DB;
use App\Model\ProdRuncardAccessory;
use App\Model\FinalPackingSaveState;
// use Illuminate\Support\Facades\Cache; // added due to datatables error 04/08/2025 - BiniManoy
use App\Model\ProductionRuncardStation;
use App\Model\TSPTSPackingConfirmation;
use App\Model\MaterialIssuanceSubSystem;
use App\Model\TSPTSSupervisorValidation;
use Illuminate\Support\Facades\Validator;
use App\Model\TSPTSFinalPackingInspection;

use App\Model\TSPTSFinalPackingInspectionQC;
use App\Model\TSPTSPreliminaryPackingInspection;

use App\Model\TSPTSFinalPackingInspectionTrfficQC;
use App\Model\TSPTSFinalPackingInspectionTrfficQC_QC;

class TSPTSController extends Controller
{

    public function generate_qrcode_tspts(Request $request)
    {
        $lot_no_code = QrCode::format('png')->errorCorrection('H')->size(200)->generate($request->lot_no);

        $lot_no_code = "data:image/png;base64," . base64_encode($lot_no_code);

        $po_no_code = QrCode::format('png')->errorCorrection('H')->size(200)->generate($request->po_no);

        $po_no_code = "data:image/png;base64," . base64_encode($po_no_code);

        return response()->json(['lot_no_code' => $lot_no_code, 'po_no_code' => $po_no_code]);
    }

    public function load_inspector_user_details(Request $request)
    {
        $user_details = User::where('employee_id', $request->employee_id)->whereIn('position', [1,2,5,10])->where('status', 1)->get();

        if(count($user_details) > 0)
        {
            return response()->json(['result' => 1, 'user_details' => $user_details]);
        }
        else
        {
            return response()->json(['result' => 2]);
        }
    }

     public function load_supervisor_user_details(Request $request)
    {
        $user_details = User::where('employee_id', $request->employee_id)->whereIn('position', [1,2])->where('status', 1)->get();

        if(count($user_details) > 0)
        {
            return response()->json(['result' => 1, 'user_details' => $user_details]);
        }
        else
        {
            return response()->json(['result' => 2]);
        }
    }

    public function load_user_details(Request $request)
    {
        $user_details = User::where('employee_id', $request->employee_id)->where('status', 1)->get();


        if(count($user_details) > 0)
        {
            return response()->json(['result' => 1, 'user_details' => $user_details]);
        }
        else
        {
            return response()->json(['result' => 2]);
        }
    }

    public function load_oqcvir_pts_table(Request $request)
    {
        // return 'asd';
        // return $request->po_num;
        $subPO = substr($request->po_num, 0, 15); // added by migs and jd 11-06-2023
        $oqcvirs = ProductionRuncard::with(['prod_runcard_station_many_details' => function($query){
            $query->where('status', 1);
        },'prod_runcard_accessory_info','tspts_oqcvir_info' => function($query){
            $query->orderBy('created_at','desc');
        },'tspts_oqcvir_info.inspector_info'])
        ->where('po_no', $subPO)
        ->whereNull('deleted_at')
        ->where('status',4)
        ->get();

        // return $oqcvirs;



        for ($i=0; $i < count($oqcvirs); $i++) {
            /* 08072023 */
            if (str_contains($request->po_num, '-A')){
                $lot_no = explode('-', $oqcvirs[$i]->lot_no)[2];
                $lot_no = (int)$lot_no;

            }else if (str_contains($request->po_num, '-A1')){
                $lot_no = explode('-', $oqcvirs[$i]->lot_no)[2];
                $lot_no = (int)$lot_no;

            }else if (str_contains($request->po_num, '-B')){
                $lot_no = explode('-', $oqcvirs[$i]->lot_no)[2];
                $lot_no = (int)$lot_no;

            }else if (str_contains($request->po_num, '-B1')){
                $lot_no = explode('-', $oqcvirs[$i]->lot_no)[2];
                $lot_no = (int)$lot_no;

            }
            else if (str_contains($request->po_num, '-1')){ // 10/26/23 - UPDATE FOR DOUBLE OR SINGLE (-) DASH PO NUMBER
                $lot_no = explode('-', $oqcvirs[$i]->lot_no);
                if(count($lot_no) > 2){
                    $lot_no = explode('-', $oqcvirs[$i]->lot_no)[2];
                }else{
                    $lot_no = explode('-', $oqcvirs[$i]->lot_no)[1];
                }
                $lot_no = (int)$lot_no;
            }
            else if(str_contains($request->po_num, 'PO')){ //added 7/15/24
                // return 'qwe';

                $lot_no = explode('-', $oqcvirs[$i]->lot_no)[1];
                $lot_no = (int)$lot_no;
                // return $lot_no;
            }
            else{
                $lot_no = explode('-', $oqcvirs[$i]->lot_no)[1];
                $lot_no = (int)$lot_no;
            }

        // $lot_no = explode('-', $oqcvirs[$i]->lot_no)[1
            // $lot_no = (int)$lot_no;

            $oqcvirs[$i]->nnnnnnnn = $lot_no;
            $oqcvirs[$i]->oqc_inspec = OQCInspection::where('po_no', $oqcvirs[$i]->po_no)->where('lot_no', $lot_no)->limit(1)->get();

            // return $oqcvirs[$i]->oqc_inspect;

            $oqcvirs[$i]->oqc_inspec_2 = OQCInspection_2::where('prod_runcard_id', $oqcvirs[$i]->id)->get();
            if( count($oqcvirs[$i]->tspts_oqcvir_info)>0 )
                $oqcvirs[$i]->tspts_oqcvir_info[0]->inspector_info_2 = User::where('employee_id', $oqcvirs[$i]->tspts_oqcvir_info[0]->employee_id)->get();
        }

        //modify by Migz 04-08-25
        // $oqcvirs = Cache::remember('oqcvir', now()->addMinutes(10), function () use ($oqcvirs) {
        //     return collect($oqcvirs);
        // });

        return DataTables::of($oqcvirs)
        ->addColumn('oqc_stamp', function($oqcvir){
            if(count($oqcvir->oqc_inspec_2) > 0){
                // $user = User::where('employee_id', $oqcvir->employee_id)->get();
                // if( count($user)>0 ){
                //     if( $user[0]->oqc_stamp != null && trim($user[0]->oqc_stamp) != '' )
                //         return $user[0]->oqc_stamp;
                // }
                if( $oqcvir->oqc_inspec_2[0]->oqc_stamp == null )
                    return '---';
                return $oqcvir->oqc_inspec_2[0]->oqc_stamp;
            }
            return '---';
        })

        ->addColumn('action', function($oqcvir){

            // if(count($oqcvir->tspts_oqcvir_info) > 0)
            // {
            //     if($oqcvir->tspts_oqcvir_info[0]->result == 1)
            //     {
            //         $result = "";
            //     }
            //     else
            //     {
            //          // $result = '<button type="button" class="btn btn-sm btn-info btn-inspection-result" data-toggle="modal" data-target="#modalAddInspection" lotapp-id="'.$oqcvir->id.'"><i class="fa fa-microscope"></i></button>';
            //     }
            // }
            // else
            // {
            //      // $result = '<button type="button" class="btn btn-sm btn-info btn-inspection-result" data-toggle="modal" data-target="#modalAddInspection" lotapp-id="'.$oqcvir->id.'"><i class="fa fa-microscope"></i></button>';
            // }

            $result = "";
                // $result .= ' <button type="button" class="btn btn-sm btn-warning btn-goto-wbsoqc" title="Go to Subsystem WBS-OQC"><i class="fa fa-location-arrow"></i></button>';

            if(count($oqcvir->oqc_inspec) > 0)
                $result .= ' <button type="button" class="btn btn-sm btn-success btn-view-application" lotapp-id="'.$oqcvir->id.'"><i class="fa fa-eye"></i></button>';
            else
                $result .= ' <button type="button" class="btn btn-sm btn-warning btn-goto-wbsoqc" lotapp-id="'.$oqcvir->id.'"><i class="fa fa-location-arrow"></i></button>';

            // if( $oqcvir->id == 24058 )
            if( $oqcvir->id == 14000 )
                $result .= ' <button type="button" class="btn btn-sm btn-warning btn-goto-wbsoqc" lotapp-id="'.$oqcvir->id.'"><i class="fa fa-location-arrow"></i></button>';

                // $result .= ' <button type="button" class="btn btn-sm btn-warning btn-goto-wbsoqc" title="Go to Subsystem WBS-OQC"><i class="fa fa-location-arrow"></i></button>';
            return $result;
        })
        ->addColumn('lot_no', function($oqcvir){

            $result = $oqcvir->lot_no;

            return $result;
        })
        ->addColumn('output_qty', function($oqcvir){

            $result = 0;

            if(count($oqcvir->prod_runcard_station_many_details) > 0)
            {
                for($i = 0; $i < count($oqcvir->prod_runcard_station_many_details); $i++)
                {
                    $result += $oqcvir->prod_runcard_station_many_details[$i]->qty_output;
                }
            }

            return $result;
        })
        ->addColumn('inspected_by', function($oqcvir){

            $result = '---';

            if(count($oqcvir->tspts_oqcvir_info) > 0)
            {
                if( count($oqcvir->tspts_oqcvir_info[0]->inspector_info_2)>0 )
                    $result = $oqcvir->tspts_oqcvir_info[0]->inspector_info_2[0]->name;
            }

            return $result;
        })
        ->addColumn('status', function($oqcvir){

            $result = '---';

            if(count($oqcvir->tspts_oqcvir_info) > 0)
            {
                if($oqcvir->tspts_oqcvir_info[0]->result == 1)
                {
                    $result = "Inspection OK";
                }
                else
                {
                    $result = "Inspection NG";
                }
            }

            return $result;
        })
        ->addColumn('fy_ww', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->fy . ' - ' . $oqcvir->oqc_inspec[0]->ww;
            return '---';
        })
        ->addColumn('date_inspected', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->date_inspected;
            return '---';
        })
        ->addColumn('from', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->time_ins_from;
            return '---';
        })
        ->addColumn('to', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->time_ins_to;
            return '---';
        })
        ->addColumn('sub_lot', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->submission;
            return '---';
        })
        ->addColumn('lot_size', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->lot_qty;
            return '---';
        })
        ->addColumn('sample_size', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->sample_size;
            return '---';
        })
        ->addColumn('num_of_defects', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->num_of_defects;
            return '---';
        })
        ->addColumn('qty', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->po_qty;
            return '---';
        })
        ->addColumn('mod', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0){
                if( $oqcvir->oqc_inspec[0]->judgement == 'Accept' || $oqcvir->oqc_inspec[0]->modid == '' )
                    return 'NDF';
                else{
                    $mod = ModeOfDefect::find($oqcvir->oqc_inspec[0]->modid);
                    if( isset($mod->name) )
                        return $mod->name;
                    return 'NDF';
                }
            }
            return '---';
        })
        ->addColumn('judgement', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0){
                if( $oqcvir->oqc_inspec[0]->judgement == 'Accept' )
                    return '<span class="badge badge-pill badge-success">' . $oqcvir->oqc_inspec[0]->judgement . '</span>';
                else
                    return '<span class="badge badge-pill badge-danger">' . $oqcvir->oqc_inspec[0]->judgement . '</span>';
            }
            return '---';
        })
        ->addColumn('inspector', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->inspector;
            return '---';
        })
        ->addColumn('remarks', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->remarks;
            return '---';
        })
        ->addColumn('type', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->type;
            return '---';
        })
        ->rawColumns(['action', 'judgement'])
        ->make(true);
    }

    public function load_oqcvir_pts_table_by_id(Request $request)
    {
        $oqcvirs = ProductionRuncard::with(['prod_runcard_station_many_details' => function($query){
            $query->where('status', 1); //deleted_at
        },'prod_runcard_accessory_info','tspts_oqcvir_info' => function($query){
            $query->orderBy('created_at','desc');
        },'tspts_oqcvir_info.inspector_info'])->where('id', $request->id)->whereNull('deleted_at')->where('status',4)->get();

        for ($i=0; $i < count($oqcvirs); $i++) {

            /* 08072023 */
            if (str_contains($oqcvirs[$i]->po_no, '-A')){
                $lot_no = explode('-', $oqcvirs[$i]->lot_no)[2];
                $lot_no = (int)$lot_no;

            }else if (str_contains($oqcvirs[$i]->po_no, '-A1')){
                $lot_no = explode('-', $oqcvirs[$i]->lot_no)[2];
                $lot_no = (int)$lot_no;

            }else if (str_contains($oqcvirs[$i]->po_no, '-B')){
                $lot_no = explode('-', $oqcvirs[$i]->lot_no)[2];
                $lot_no = (int)$lot_no;

            }else if (str_contains($oqcvirs[$i]->po_no, '-B1')){
                $lot_no = explode('-', $oqcvirs[$i]->lot_no)[2];
                $lot_no = (int)$lot_no;

            }else if (str_contains($oqcvirs[$i]->po_no, '-1')){ // 10102023
                $lot_no = explode('-', $oqcvirs[$i]->lot_no);
                if(count($lot_no) > 2){
                    $lot_no = explode('-', $oqcvirs[$i]->lot_no)[2];
                }else{
                    $lot_no = explode('-', $oqcvirs[$i]->lot_no)[1];
                }
                $lot_no = (int)$lot_no;

            }else{
                $lot_no = explode('-', $oqcvirs[$i]->lot_no)[1];
                $lot_no = (int)$lot_no;
            }

            // $lot_no = explode('-', $oqcvirs[$i]->lot_no)[1];
            // $lot_no = (int)$lot_no;

            $oqcvirs[$i]->oqc_inspec = OQCInspection::where('po_no', $oqcvirs[$i]->po_no)->where('lot_no', $lot_no)->limit(1)->get();
            $oqcvirs[$i]->oqc_inspec_2 = OQCInspection_2::where('prod_runcard_id', $oqcvirs[$i]->id)->get();
            if( count($oqcvirs[$i]->tspts_oqcvir_info)>0 )
                $oqcvirs[$i]->tspts_oqcvir_info[0]->inspector_info_2 = User::where('employee_id', $oqcvirs[$i]->tspts_oqcvir_info[0]->employee_id)->get();
        }



        return DataTables::of($oqcvirs)
        ->addColumn('oqc_stamp', function($oqcvir){
            if(count($oqcvir->oqc_inspec_2) > 0){
                // $user = User::where('employee_id', $oqcvir->employee_id)->get();
                // if( count($user)>0 ){
                //     if( $user[0]->oqc_stamp != null && trim($user[0]->oqc_stamp) != '' )
                //         return $user[0]->oqc_stamp;
                // }
                if( $oqcvir->oqc_inspec_2[0]->oqc_stamp == null )
                    return '---';
                return $oqcvir->oqc_inspec_2[0]->oqc_stamp;
            }
            return '---';
        })
        ->addColumn('action', function($oqcvir){

            // if(count($oqcvir->tspts_oqcvir_info) > 0)
            // {
            //     if($oqcvir->tspts_oqcvir_info[0]->result == 1)
            //     {
            //         $result = "";
            //     }
            //     else
            //     {
            //          // $result = '<button type="button" class="btn btn-sm btn-info btn-inspection-result" data-toggle="modal" data-target="#modalAddInspection" lotapp-id="'.$oqcvir->id.'"><i class="fa fa-microscope"></i></button>';
            //     }
            // }
            // else
            // {
            //      // $result = '<button type="button" class="btn btn-sm btn-info btn-inspection-result" data-toggle="modal" data-target="#modalAddInspection" lotapp-id="'.$oqcvir->id.'"><i class="fa fa-microscope"></i></button>';
            // }

            // $result = "";

            // if(count($oqcvir->oqc_inspec) > 0)
            //     $result .= ' <button type="button" class="btn btn-sm btn-success btnView" lotapp-id="'.$oqcvir->id.'"><i class="fa fa-eye"></i></button>';
            // else
            //     $result .= ' <button type="button" class="btn btn-sm btn-warning btn-goto-wbsoqc" title="Go to Subsystem WBS-OQC"><i class="fa fa-location-arrow"></i></button>';

            return ' <button type="button" class="btn btn-sm btn-success btnView" lotapp-id="'.$oqcvir->id.'" click="viewWBSInfo('.$oqcvir->id.')"><i class="fa fa-eye"></i></button>';
        })
        ->addColumn('lot_no', function($oqcvir){

            $result = $oqcvir->lot_no;

            return $result;
        })
        ->addColumn('output_qty', function($oqcvir){

            $result = 0;

            if(count($oqcvir->prod_runcard_station_many_details) > 0)
            {
                for($i = 0; $i < count($oqcvir->prod_runcard_station_many_details); $i++)
                {
                    $result += $oqcvir->prod_runcard_station_many_details[$i]->qty_output;
                }
            }

            return $result;
        })
        ->addColumn('inspected_by', function($oqcvir){

            $result = '---';

            if(count($oqcvir->tspts_oqcvir_info) > 0)
            {
                if( count($oqcvir->tspts_oqcvir_info[0]->inspector_info_2)>0 )
                    $result = $oqcvir->tspts_oqcvir_info[0]->inspector_info_2[0]->name;
            }

            return $result;
        })
        ->addColumn('status', function($oqcvir){

            $result = '---';

            if(count($oqcvir->tspts_oqcvir_info) > 0)
            {
                if($oqcvir->tspts_oqcvir_info[0]->result == 1)
                {
                    $result = "Inspection OK";
                }
                else
                {
                    $result = "Inspection NG";
                }
            }

            return $result;
        })
        ->addColumn('fy_ww', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->fy . ' - ' . $oqcvir->oqc_inspec[0]->ww;
            return '---';
        })
        ->addColumn('date_inspected', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->date_inspected;
            return '---';
        })
        ->addColumn('from', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->time_ins_from;
            return '---';
        })
        ->addColumn('to', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->time_ins_to;
            return '---';
        })
        ->addColumn('sub_lot', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->submission;
            return '---';
        })
        ->addColumn('lot_size', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->lot_qty;
            return '---';
        })
        ->addColumn('sample_size', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->sample_size;
            return '---';
        })
        ->addColumn('num_of_defects', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->num_of_defects;
            return '---';
        })
        ->addColumn('qty', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->po_qty;
            return '---';
        })
        ->addColumn('mod', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0){
                if( $oqcvir->oqc_inspec[0]->judgement == 'Accept' || $oqcvir->oqc_inspec[0]->modid == '' )
                    return 'NDF';
                else{
                    $mod = ModeOfDefect::find($oqcvir->oqc_inspec[0]->modid);
                    if( isset($mod->name) )
                        return $mod->name;
                    return 'NDF';
                }
            }
            return '---';
        })
        ->addColumn('judgement', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0){
                if( $oqcvir->oqc_inspec[0]->judgement == 'Accept' )
                    return '<span class="badge badge-pill badge-success">' . $oqcvir->oqc_inspec[0]->judgement . '</span>';
                else
                    return '<span class="badge badge-pill badge-danger">' . $oqcvir->oqc_inspec[0]->judgement . '</span>';
            }

            // if(count($oqcvir->oqc_inspec) > 0)
            //     return $oqcvir->oqc_inspec[0]->judgement;

            return '---';
        })
        ->addColumn('inspector', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->inspector;
            return '---';
        })
        ->addColumn('remarks', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->remarks;
            return '---';
        })
        ->addColumn('type', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->type;
            return '---';
        })
        ->rawColumns(['action', 'judgement'])
        ->make(true);
    }

    public function load_oqcvir_pts_details_by_id(Request $request)
    {
        $oqcvirs = ProductionRuncard::with(['prod_runcard_station_many_details' => function($query){
            $query->where('status', 1);
        },'prod_runcard_accessory_info','oqc_inspection','tspts_oqcvir_info' => function($query){
            $query->orderBy('created_at','desc');
        },'tspts_oqcvir_info.inspector_info'])->where('id', $request->id)->where('status',4)->get();

        for ($i=0; $i < count($oqcvirs); $i++) {

            /* 08072023 */
            if (str_contains($oqcvirs[$i]->po_no, '-A')){
                $lot_no = explode('-', $oqcvirs[$i]->lot_no)[2];
                $lot_no = (int)$lot_no;

            }else if (str_contains($oqcvirs[$i]->po_no, '-A1')){
                $lot_no = explode('-', $oqcvirs[$i]->lot_no)[2];
                $lot_no = (int)$lot_no;

            }else if (str_contains($oqcvirs[$i]->po_no, '-B')){
                $lot_no = explode('-', $oqcvirs[$i]->lot_no)[2];
                $lot_no = (int)$lot_no;

            }else if (str_contains($oqcvirs[$i]->po_no, '-B1')){
                $lot_no = explode('-', $oqcvirs[$i]->lot_no)[2];
                $lot_no = (int)$lot_no;

            }else if (str_contains($oqcvirs[$i]->po_no, '-1')){ //10092023
                $lot_no = explode('-', $oqcvirs[$i]->lot_no);
                if(count($lot_no) > 2){
                    $lot_no = explode('-', $oqcvirs[$i]->lot_no)[2];
                }else{
                    $lot_no = explode('-', $oqcvirs[$i]->lot_no)[1];
                }
                $lot_no = (int)$lot_no;

            }else{
                $lot_no = explode('-', $oqcvirs[$i]->lot_no)[1];
                $lot_no = (int)$lot_no;
            }
            // $lot_no = explode('-', $oqcvirs[$i]->lot_no)[1];
            // $lot_no = (int)$lot_no;

            $oqcvirs[$i]->oqc_inspec = OQCInspection::where('po_no', $oqcvirs[$i]->po_no)->where('lot_no', $lot_no)->limit(1)->get();
            if( count($oqcvirs[$i]->tspts_oqcvir_info)>0 )
                $oqcvirs[$i]->tspts_oqcvir_info[0]->inspector_info_2 = User::where('employee_id', $oqcvirs[$i]->tspts_oqcvir_info[0]->employee_id)->get();
        }

        return DataTables::of($oqcvirs)
        ->addColumn('action', function($oqcvir){

            // if(count($oqcvir->tspts_oqcvir_info) > 0)
            // {
            //     if($oqcvir->tspts_oqcvir_info[0]->result == 1)
            //     {
            //         $result = "";
            //     }
            //     else
            //     {
            //          // $result = '<button type="button" class="btn btn-sm btn-info btn-inspection-result" data-toggle="modal" data-target="#modalAddInspection" lotapp-id="'.$oqcvir->id.'"><i class="fa fa-microscope"></i></button>';
            //     }
            // }
            // else
            // {
            //      // $result = '<button type="button" class="btn btn-sm btn-info btn-inspection-result" data-toggle="modal" data-target="#modalAddInspection" lotapp-id="'.$oqcvir->id.'"><i class="fa fa-microscope"></i></button>';
            // }

            // $result = "";

            // if(count($oqcvir->oqc_inspec) > 0)
            //     $result .= ' <button type="button" class="btn btn-sm btn-success btnView" lotapp-id="'.$oqcvir->id.'"><i class="fa fa-eye"></i></button>';
            // else
            //     $result .= ' <button type="button" class="btn btn-sm btn-warning btn-goto-wbsoqc" title="Go to Subsystem WBS-OQC"><i class="fa fa-location-arrow"></i></button>';

            return ' <button type="button" class="btn btn-sm btn-success btnView" lotapp-id="'.$oqcvir->id.'" click="viewWBSInfo('.$oqcvir->id.')"><i class="fa fa-eye"></i></button>';
        })
        ->addColumn('lot_no', function($oqcvir){

            $result = $oqcvir->lot_no;

            return $result;
        })
        ->addColumn('output_qty', function($oqcvir){

            $result = 0;

            if(count($oqcvir->prod_runcard_station_many_details) > 0)
            {
                for($i = 0; $i < count($oqcvir->prod_runcard_station_many_details); $i++)
                {
                    $result += $oqcvir->prod_runcard_station_many_details[$i]->qty_output;
                }
            }

            return $result;
        })
        ->addColumn('inspected_by', function($oqcvir){

            $result = '---';

            if(count($oqcvir->tspts_oqcvir_info) > 0)
            {
                if( count($oqcvir->tspts_oqcvir_info[0]->inspector_info_2)>0 )
                    $result = $oqcvir->tspts_oqcvir_info[0]->inspector_info_2[0]->name;
            }

            return $result;
        })
        ->addColumn('status', function($oqcvir){

            $result = '---';

            if(count($oqcvir->tspts_oqcvir_info) > 0)
            {
                if($oqcvir->tspts_oqcvir_info[0]->result == 1)
                {
                    $result = "Inspection OK";
                }
                else
                {
                    $result = "Inspection NG";
                }
            }

            return $result;
        })
        ->addColumn('fy_ww', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->fy . ' - ' . $oqcvir->oqc_inspec[0]->ww;
            return '---';
        })
        ->addColumn('date_inspected', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->date_inspected;
            return '---';
        })
        ->addColumn('from', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->time_ins_from;
            return '---';
        })
        ->addColumn('to', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->time_ins_to;
            return '---';
        })
        ->addColumn('sub_lot', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->submission;
            return '---';
        })
        ->addColumn('lot_size', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->lot_qty;
            return '---';
        })
        ->addColumn('sample_size', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->sample_size;
            return '---';
        })
        ->addColumn('num_of_defects', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->num_of_defects;
            return '---';
        })
        ->addColumn('qty', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->po_qty;
            return '---';
        })
        ->addColumn('mod', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0){
                if( $oqcvir->oqc_inspec[0]->judgement == 'Accept' || $oqcvir->oqc_inspec[0]->modid == '' )
                    return 'NDF';
                else{
                    $mod = ModeOfDefect::find($oqcvir->oqc_inspec[0]->modid);
                    if( isset($mod->name) )
                        return $mod->name;
                    return 'NDF';
                }
            }
            return '---';
        })
        ->addColumn('judgement', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->judgement;
            return '---';
        })
        ->addColumn('inspector', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->inspector;
            return '---';
        })
        ->addColumn('remarks', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->remarks;
            return '---';
        })
        ->addColumn('type', function($oqcvir){
            if(count($oqcvir->oqc_inspec) > 0)
                return $oqcvir->oqc_inspec[0]->type;
            return '---';
        })
        // ->addColumn('oqc_inspection', function($oqcvir){
        //     return OQCInspection_2::where('prod_runcard_id', $oqcvir->id)->get();
        // })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function getAllPOByPackingNo(Request $request)
    {
        $data = PackingList::where('id', $request->id)->get();
        if( count($data)>=1 )
            $data = PackingListDetails::where('packing_id', $data[0]->id)->get();
        else
            $data = [];
        return response()->json([ 'list' => $data ]);
    }

    public function tspts_view_lotapp_details(Request $request)
    { //WORKING FUNCTION
        $lotapp_details = ProductionRuncard::with(['prod_runcard_station_many_details' => function($query) {
                $query->where('status', 1);
            },'tspts_oqcvir_info','tspts_packingconfirmation_info'])->where('id', $request->lotapp_id)->get();


        // return $lotapp_details;

        if(count($lotapp_details) > 0)
        {
            $lotapp_quantity = 0;

            if(count($lotapp_details[0]->prod_runcard_station_many_details) > 0)
            {
                for($i = 0; $i < count($lotapp_details[0]->prod_runcard_station_many_details); $i++)
                {
                    $lotapp_quantity += $lotapp_details[0]->prod_runcard_station_many_details[$i]->qty_output;
                }
            }

          $oqcLotApp = oqcLotApp::where('fkid_runcard', $request->lotapp_id)->get();
            // return $oqcLotApp;
            $result = ProductionRuncardStation::where('production_runcard_id', $request->lotapp_id)->where('status', 1)->get();
            $ttl = 0;
            for ($i=0; $i < count($result); $i++)
                $ttl = $ttl + $result[$i]->qty_output;

            $po_no = ProductionRuncard::where('id', $request->lotapp_id)->get()[0]->po_no;
            // return $po_no;
            $prd_runcards = ProductionRuncard::where('po_no', $po_no)->orderBy('id')->get();
            // return $prd_runcards;
        // 04082024 by nessa
        $device_name_print = 'not found';
        if(isset($prd_runcards[0]->device_name)){
            $device_name_print = $prd_runcards[0]->device_name;
            // return $device_name_print;
            //TODO: MIGZ 09-04-24 Remove Burn-in & Test, if the Device Name in WBS Issuance & Kitting counter
           $device_name_print = CommonController::getInstance()->validate_device_name($device_name_print);
        }
        // return $device_name_print;
            // $device = Device::where('name', $prd_runcards[0]->device_name)->get();
            // $device = Device::where('name', $device_name_print)->get(); // 04082024 by nessa
            $device = Device::where('name', $device_name_print)->where('status', 1)->get(); // Add condition for status added on 07-04-2024
            // return $device_name_print;
            $prd_runcards_counter = [];
            for ($i=0; $i < count($prd_runcards); $i++)
                $prd_runcards_counter[ $prd_runcards[$i]->id ] = ($i+1);

            // $cnt = ceil($prd_runcards[0]->po_qty / $device[0]->ship_boxing);
            // return $device[0];
            // return $prd_runcards[0]->po_qty;
            $sticker_cnt = ceil($device[0]->ship_boxing / $device[0]->boxing);
            if( $device[0]->ship_boxing > $ttl )
                $sticker_cnt = ceil($ttl / $device[0]->boxing);

            $serial_no_html = "";
            if( $oqcLotApp[0]->print_lot != 'N/A' ){
                $serial_no_html = $oqcLotApp[0]->print_lot . '</b><br>';
            }

            // $lot_number = (int)(explode('-', $oqcLotApp[0]->lot_batch_no)[1]);
            // $lot_start_counter = ( $lot_number - 1 ) * (  (int)$device[0]->ship_boxing / (int)$device[0]->boxing );
            $lot_number = explode('-', $oqcLotApp[0]->lot_batch_no);
            $lot_number = (int)($lot_number[count($lot_number)-1]);
            // return $lot_number;

            /**
             * Separate the integer($lot_number) and only use ceil() function to decimal/float values as multiplier
             * Revised as of 06-20-2024 -JD
             */
            // $lot_start_counter = ceil(( $lot_number - 1 ) * (  (int)$device[0]->ship_boxing / (int)$device[0]->boxing )); // Old code
            $lot_start_counter = ($lot_number - 1 ) * ceil(  (int)$device[0]->ship_boxing / (int)$device[0]->boxing );

            // return $lot_start_counter;

            if( $prd_runcards[0]->po_qty <= 99 || $prd_runcards[0]->po_no = '450257796000010'){
                // return 'asd';
                // $sticker_cnt = 1;
                $lot_start_counter = 0;
                $current_lot_id_is_selected = false;

                for ($i=0; $i < count($prd_runcards); $i++) {

                    if( !$current_lot_id_is_selected ) {
                        if( $request->lotapp_id == $prd_runcards[$i]->id )
                            $current_lot_id_is_selected = true;

                        $_stations = ProductionRuncardStation::select('*', DB::raw("SUM(qty_output) as ttl_qtt"))
                        ->where('production_runcard_id', $prd_runcards[$i]->id)
                        ->where('status', 1)->limit(1)
                        ->get();

                        // return $_stations;

                        if( $request->lotapp_id != $prd_runcards[$i]->id ){
                            $_ttl_qtt = $_stations[0]->ttl_qtt;
                            for ($mm=0; $mm < 100; $mm++) {
                                $_ttl_qtt = $_ttl_qtt - (int)$device[0]->boxing;
                                $lot_start_counter++;
                                if( $_ttl_qtt <= 0  )
                                    break;
                            }
                        }
                    }

                }
            }


            // $lot_number = (int)(explode('-', $oqcLotApp[0]->lot_batch_no)[1]);
            // $lot_start_counter = ( $lot_number - 1 ) * (  (int)$device[0]->ship_boxing / (int)$device[0]->boxing );

            // return $lot_start_counter;

            $data = [];
            for ($i=1; $i <= $sticker_cnt; $i++) {

                $qtt_tray = $i * (int)($device[0]->boxing);
                if( $ttl >= $qtt_tray )
                    $qtt_tray = (int)($device[0]->boxing);
                else
                    $qtt_tray = (int)($device[0]->boxing) - ($qtt_tray - $ttl);

                $data[] = array(
                    'po_no' => $oqcLotApp[0]->po_no,
                    'ww' => $oqcLotApp[0]->ww,
                    'lot_no' => $oqcLotApp[0]->lot_batch_no,
                    'qtt' => $qtt_tray,
                    'counter' => ($lot_start_counter + $i) . '/' . ($lot_start_counter + $sticker_cnt), // counter
                    // 'counter' => ($lot_start_counter +3 + $i) . '/' . ($lot_start_counter +3 + $sticker_cnt), // counter
                    'count_per_tray' => $i,
                    'stt' => 0,
                );

            }

            // return $oqcLotApp[0]->lot_batch_no;

            $counter = "not set";
            $wed_edi_id = 0;
            $new_counter = "---";
            // $dlabel = Dlabel::where('po_no', $lotapp_details[0]['po_no'])->where('last_lot_no', '>=', $lot_number)->orderBy('id')->limit(1)->get();

            // if( count($dlabel) > 0 ){

            //     $dlabel_lots = DlabelHistory::where('d_label_id', $dlabel[0]['id'])->orderBy('id')->get();
            //     $index_cnt = 1;
            //     for ($i=(int)$dlabel[0]['last_lot_no']; $i > (int)$dlabel[0]['last_lot_no'] - (count($dlabel_lots)); $i--) {
            //         if( $lot_number == $i )
            //             $counter = $index_cnt . '/' . count($dlabel_lots);
            //         $index_cnt++;
            //     }

            // }

            // This query use for Packing Confirmation so need to if the unique number is exists
            $unique_number= '';
            $created_at= '';

            // 05202025 by Nessa
            $dlabel = Dlabel::where('po_no', $lotapp_details[0]['po_no'])
            ->whereNull('status')
            ->orderBy('id','DESC') //Get the latest PO / DESC order
            ->get();
            // return $dlabel[0]['id']; //The PO Exist & will get the ASC order ID

            $box = DlabelBoxes::where('lot_no', $oqcLotApp[0]->lot_batch_no)
            ->where('d_label_id', $dlabel[0]->id ?? '') // Check id is exists
            ->orWhere('lot_id', $request->lotapp_id ?? '')
            // ->where('d_label_id', $dlabel[0]->id) // 05202025 by Nessa
            ->whereNull('status')
            ->get();


            // return $box;

            if( count($box) >= 1 ){
                $counter = $box[0]->package_no;
                $wed_edi_id = $box[0]->id;
                if( isset($box[0]->unique_num) >= 1 ){
                    $unique_num = $box[0]->unique_num;
                    $created_at = $box[0]->created_at;
                    $unique_number = date('Ymd',strtotime($created_at)).$unique_num;
                }
            }

            // return $box;

            $_dlabel_ = Dlabel::where('po_no', $lotapp_details[0]['po_no'])->whereNull('status')->get();
            $_ids_ = [];
            for ($i=0; $i < count($_dlabel_); $i++)
                $_ids_[] = $_dlabel_[$i]->id;

            $_ddtt = DlabelHistory::whereIn('d_label_id', $_ids_)->where('lot_no', $lot_number)->orderBy('id')->get();
            for ($i=0; $i < count($_ddtt); $i++)
                $new_counter = $_ddtt[$i]->package_no_new;

            $packing_list = PackingListDetails::where('po', $lotapp_details[0]['po_no'])
                ->where(function($query) use ($lotapp_quantity){
                    $query->where('qty', $lotapp_quantity)
                        ->orWhere('gross_weight', 'like', "%" . $lotapp_quantity . "%");
                })
                // ->groupBy('box_no')->get();
                ->orderBy('packing_id', 'desc')
                // ->groupBy('packing_id')
                // ->limit(1)
                ->get();

                // return $packing_list;


            for ($i=0; $i < count($packing_list); $i++) {
                $packing_list[$i]->casemark = "N/A";
                $packing_list[$i]->control_no = "N/A";
                $packing_list[$i]->case_marks = "N/A";// added by Nessa
                $casemark = PackingList::find($packing_list[$i]->packing_id);
                if( isset($casemark->id) )
                    $packing_list[$i]->casemark = $casemark->ship_to;
                    $packing_list[$i]->control_no = $casemark->control_no;
                    $packing_list[$i]->case_marks = $casemark->case_marks;// added by Nessa
            }

            // return $packinglist;

            $final_packing_save_state = FinalPackingSaveState::where('lot_app_id', $request->lotapp_id)->get();
            if( count($final_packing_save_state) > 0 )
                $final_packing_save_state = $final_packing_save_state[0];
            else
                $final_packing_save_state = null;

            $device_name = $lotapp_details[0]->device_name;
            $device_name = explode(' - ', $device_name);
            $device_name = $device_name[0];

            return response()->json(['result' => 1, //wed_edi_id
                'list_of_trays' => $data,
                'list_of_trays_2' => $data,
                'lotapp_details' => $lotapp_details,
                'lotapp_quantity' => $lotapp_quantity,
                'counter' => $counter,
                'wed_edi_id' => $wed_edi_id,
                'unique_num' => $unique_number,
                'created_at' => $created_at,
                'new_counter' => $new_counter,
                'packing_list' => $packing_list,
                'final_packing_save_state' => $final_packing_save_state,
                'ww' => $oqcLotApp[0]->ww,
                'device_name' => $device_name,
                'device_name_full' => $lotapp_details[0]->device_name
            ]);
        }
        else
        {
            return response()->json(['result' => 2]);
        }
    }

    public function getPONoListByPackingID(Request $request)
    {

        $packing = PackingList::find($request->id);

        $PackingListDetails = null;
        $list_group_by_po = null;
        $list_pos = [];

        $runcard_trffic_scanned = null;
        $runcard_ids = [];

        if( isset($packing->id) ){

            $runcard_trffic_scanned = TSPTSFinalPackingInspectionTrfficQC::where('packing_id', $packing->id)->get();
            for ($i=0; $i < count($runcard_trffic_scanned); $i++)
                $runcard_ids[] = $runcard_trffic_scanned[$i]->lotapp_id;

            $PackingListDetails = PackingListDetails::select('*', DB::raw('SUM(qty) as total_qty'))->where('packing_id', $packing->id)->groupBy('po')->get();
            for ($i=0; $i < count($PackingListDetails); $i++){
                $runcards = ProductionRuncardStation::whereIn('production_runcard_id', $runcard_ids)->get();
                // $PackingListDetails[$i]->runcards = $runcards;
                $ttl = 0;
                for ($index=0; $index < count($runcards); $index++)
                    $ttl = $ttl + $runcards[$index]->qty_output;

                $PackingListDetails[$i]->prod_station_ttl = $ttl;
            }


            // $list_group_by_po = PackingListDetails::where('packing_id', $packing->id)->groupBy('po')->get();
            // for ($i=0; $i < count($list_group_by_po); $i++)
            //     $list_pos[] = $list_group_by_po[$i]->po;

        }

        // wed edi
        $shipments = ShipmentSummary::with(['wed_edi', 'lot_details.prod_runcard_station_many_details' => function($query2) {
            $query2->where('status',1);
        }])->where('packing_list_id', $request->id)->get();
        for ($i=0; $i < count($shipments); $i++) {
            $ttl_qty = 0;
            for ($j=0; $j < count($shipments[$i]->lot_details->prod_runcard_station_many_details); $j++)
                $ttl_qty += $shipments[$i]->lot_details->prod_runcard_station_many_details[$j]->qty_output;
            $shipments[$i]->total_qty = $ttl_qty;
        }

        return response()->json([

            'packing' => $packing,

            'PackingListDetails' => $PackingListDetails,
            'list_pos' => $list_pos,
            // 'list_group_by_po' => $list_group_by_po,

            'runcard_trffic_scanned' => $runcard_trffic_scanned,
            'runcard_ids' => $runcard_ids,

            'shipments' => $shipments,

        ]);
    }

    public function tspts_submit_oqc_inspection(Request $request)
    {
         date_default_timezone_set('Asia/Manila');
         $data = $request->all();

         $validator = '';

         $validator = Validator::make($request->all(), [

            'add_oqc_sample_size' => 'required',
            'add_ok_qty' => 'required',
            'add_inspection_starttime' => 'required',
            'add_inspection_datetime' => 'required',
            'add_terminal' => 'required',
            'add_yd_label' => 'required',
            'add_csh_coating' => 'required',
            'add_accessories_requirement' => 'required',
            'add_coc_requirement' => 'required',
            'add_result' => 'required',
            'add_oqc_inspector_name' => 'required',

         ]);

         if($validator->passes())
         {
            try
            {
                TSPTSOqcVir::insert([

                    'lotapp_id' => $request->add_lot_id,
                    'sample_size' => $request->add_oqc_sample_size,
                    'ok_qty' => $request->add_ok_qty,
                    'terminal_use' => $request->add_terminal,
                    'yd_label' => $request->add_yd_label,
                    'csh_coating' => $request->add_csh_coating,
                    'accessories_requirement' => $request->add_accessories_requirement,
                    'coc_requirement' => $request->add_coc_requirement,
                    'result' => $request->add_result,
                    'inspector_id' => $request->add_oqc_inspector_name,
                    'inspection_starttime' => $request->add_inspection_starttime,
                    'inspection_datetime' => $request->add_inspection_datetime,
                    'remarks' => $request->add_remarks,
                    'created_at' => date('Y-m-d H:i:s'),
                    // 'updated_at' => date('Y-m-d H:i:s'),
                    'logdel' => 0,
                ]);

                return response()->json(['result' => 1]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => $e]);
            }
         }
         else
         {
            return response()->json(['result' => 0, 'error' => $validator->messages()]);
         }
    }

    public function load_packingconfirmation_pts_table(Request $request)
    {

    /*    $oqcvirs = ProductionRuncard::with(['prod_runcard_station_many_details' => function($query){
            $query->where('status', 1);
        },'prod_runcard_accessory_info','tspts_oqcvir_info' => function($query){

            $query->where('result', 1);

        }, 'tspts_packingconfirmation_info' => function($query2)
            {
                $query2->where('logdel',0)->orderBy('created_at','desc');
            }
            ,'tspts_packingconfirmation_info.operator_info'])->where('po_no', $request->po_num)->where('status',3)->get();

        $packings = collect($oqcvirs);

        $packing_final = $packings->where('tspts_oqcvir_info', '!=', '[]')->flatten(1);

        return DataTables::of($packing_final)
        ->addColumn('action', function($packing){

            if(count($packing->tspts_packingconfirmation_info) > 0)
            {
                $result = "";
            }
            else
            {
                $result = '<button type="button" class="btn btn-sm btn-info btn-packing-confirmation" data-toggle="modal" data-target="#modalPackingConfirmation" lotapp-id="'.$packing->id.'"><i class="fa fa-edit"></i></button>';
            }

            $result .= ' <button type="button" class="btn btn-sm btn-success btn-view-application" data-toggle="modal" data-target="#modalViewApplication" lotapp-id="'.$packing->id.'"><i class="fa fa-eye"></i></button>';

            return $result;
        })
        ->addColumn('lot_no', function($packing){

            $result = $packing->runcard_no;

            return $result;
        })
        ->addColumn('device_code', function($packing){

            if(count($packing->tspts_packingconfirmation_info) > 0)
            {
                if($packing->tspts_packingconfirmation_info[0]->device_code != null)
                {
                    $result = $packing->tspts_packingconfirmation_info[0]->device_code;
                }
                else
                {
                    $result = "No Device Code Assigned!";
                }
            }
            else
            {
                $result = "---";

            }

            return $result;
        })
        ->addColumn('lot_qty', function($packing){

           $result = 0;

            if(count($packing->prod_runcard_station_many_details) > 0)
            {
                for($i = 0; $i < count($packing->prod_runcard_station_many_details); $i++)
                {
                    $result += $packing->prod_runcard_station_many_details[$i]->qty_output;
                }
            }

            return $result;
        })
        ->addColumn('packing_operator', function($packing){

            $result = "---";

            if(count($packing->tspts_packingconfirmation_info) > 0)
            {
                $result = "";

                $operators = $packing->tspts_packingconfirmation_info[0]->operator_id;
                $multipleoperators = explode(',', $operators);

                for($i = 0; $i < count($multipleoperators); $i++)
                {
                    $user = User::where('id', $multipleoperators[$i])->first();

                    if($user != null)
                    {
                        $result .= $user->name . "</br>";
                    }
                }
            }

            return $result;
        })
        ->rawColumns(['action', 'packing_operator'])
        ->make(true);
    */

        $data = ProductionRuncard::with([
            'tspts_packingconfirmation_info' => function($query2) {
                $query2->where('logdel',0)->orderBy('created_at','desc');
            }])
            ->where('po_no', $request->po_no)
            ->whereNull('deleted_at')
            ->where('status', 4)
            ->get();

            // return $data;

        $new = [];
        for ($i=0; $i < count($data); $i++) {
            $oqc_inspec = OQCInspection_2::where('prod_runcard_id', $data[$i]->id)->get();
            if( count($oqc_inspec)>0 )
                $new[] = $data[$i];
        }

        // return $new;

        return DataTables::of($new)
            ->addColumn('action', function($data){
                $btn = '';
                if(count($data->tspts_packingconfirmation_info) > 0){
                    $btn .= ' <button type="button" class="btn btn-sm btn-success btn-view-application" data-toggle="modal" data-target="#modalViewApplication" lotapp-id="'.$data->id.'" title="View Packing Confirmation"><i class="fa fa-eye"></i></button>';
                }else{
                    // $btn = '<button type="button" class="btn btn-sm btn-info btn-packing-confirmation" data-toggle="modal" data-target="#modalPackingConfirmation" lotapp-id="'.$data->id.'"><i class="fa fa-edit"></i></button>';
                    $btn = '<button type="button" class="btn btn-sm btn-info btn-packing-confirmation" lotapp-id="'.$data->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                }
                // if( $data->id === 3308 ){ //ES22-002-A
                // // if( $data->po_no === '450237958500010' ){ // FOR MAM CRIS CONCERN REGARDING YEC
                //     $btn .= '<button type="button" class="btn btn-sm btn-info btn-packing-confirmation" lotapp-id="'.$data->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                // }
                return $btn;
            })
            ->addColumn('packing_code', function($data){
                if(count($data->tspts_packingconfirmation_info) > 0)
                    return $data->tspts_packingconfirmation_info[0]->device_code;
                return '---';
            })
            ->addColumn('lot_no', function($data){
                return $data->lot_no;
            })
            ->addColumn('lot_qty', function($data){
                $cnt = 0;
                $_data = ProductionRuncardStation::where('production_runcard_id', $data->id)->where('status', 1)->get();
                for ($i=0; $i < count($_data); $i++)
                     // $cnt += $_data[$i]->qty_input; // commented 06/25/2025 change to qty_output
                        $cnt += $_data[$i]->qty_output; // mdr
                return $cnt;
            })
            ->addColumn('packing_operator', function($data){
                if(count($data->tspts_packingconfirmation_info) > 0){
                    $user = User::find($data->tspts_packingconfirmation_info[0]->operator_id);
                    if(isset($user->name))
                        return $user->name;
                }
                return '---';
            })
            ->rawColumns(['action'])
            ->make(true);

    }

    public function tspts_submit_packing_confirmation(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
         $data = $request->all();

         $validator = '';

         $validator = Validator::make($request->all(), [

            'add_series_v_label' => 'required',
            'add_label_v_actual' => 'required',
            'add_silica_gel' => 'required',
            'add_yd_label' => 'required',
            'add_packing_conf_no_of_tray_boxes' => 'required',
            'add_packing_operator_name' => 'required',
            // 'add_confirmation_datetime' => 'required',

         ]);

         if($validator->passes())
         {
            $autogen = 1;

            $series_name = explode("-",$request->add_series_name);


            $series_id = PackingCode::where('series_name', $series_name[0])->get();

            $final_series = '';


            if(count($series_id) > 0)
            {
                $device = str_pad($series_id[0]->series_id, 3, "0", STR_PAD_LEFT);
                $final_series = $device;
            }
            else
            {
                 $series_id2 = PackingCode::where('series_name', $series_name[0] . "-" . $series_name[1])->get();

                 if(count($series_id2) > 0)
                 {
                    $device = str_pad($series_id2[0]->series_id, 3, "0", STR_PAD_LEFT);
                    $final_series = $device;
                 }
                 else
                {
                    $device = "***";
                }
            }

             $month = date('m');

             $packing_lots = TSPTSPackingConfirmation::whereMonth('created_at',$month)->where('logdel', 0)->where('device_code', 'like', '%' . $final_series . '%')->orderBy('created_at','desc')->get();

            if(count($packing_lots) > 0)
            {
                if($packing_lots[0]->device_code != null)
                {
                    $autogenNum = explode("-",$packing_lots[0]->device_code)[1] + 1;
                }
                else
                {
                    $autogenNum = 1;
                }
            }
            else
            {
                $autogenNum = 1;
            }

            $device_code = $device . $month . "-" . str_pad($autogenNum, 3, "0", STR_PAD_LEFT);

            try
            {
                TSPTSPackingConfirmation::insert([

                    'lotapp_id' => $request->add_lot_id,
                    'device_code' => $device_code,
                    'series_v_label' => $request->add_series_v_label,
                    'label_v_actual' => $request->add_label_v_actual,
                    'silica_gel' => $request->add_silica_gel,
                    'yd_label' => $request->add_yd_label,
                    'no_tray_boxes' => $request->add_packing_conf_no_of_tray_boxes,
                    'operator_id' => $request->add_packing_operator_name,
                    // 'confirmation_datetime' => $request->add_confirmation_datetime,
                    'confirmation_datetime' => date('Y-m-d H:i:s'),
                    'remarks' => $request->add_packing_conf_remarks,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'logdel' => 0,
                ]);

                return response()->json(['result' => 1]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => $e]);
            }
         }
         else
         {
            return response()->json(['result' => 0, 'error' => $validator->messages()]);
         }
    }

    public function load_packinginspection_pts_table(Request $request)
    {
        // virs = ProductionRuncard::with(['prod_runcard_station_many_details' => function($query){
        //     $query->where('status', 1);
        // },'prod_runcard_accessory_info','tspts_oqcvir_info' => function($query){

        //     $query->where('result', 1);

        // }, 'tspts_packingconfirmation_info', 'tspts_packinginspection_info','tspts_packinginspection_info.inspector_info'])->where('po_no', $request->po_num)->where('status',3)->get();

        // $packings = collect($oqcvirs);

        // $packing_final = $packings->where('tspts_oqcvir_info', '!=', '[]')->where('tspts_packingconfirmation_info','!=', '[]')->flatten(1);

        // return DataTables::of($packing_final)
        // ->addColumn('action', function($packing){

        //     if(count($packing->tspts_packinginspection_info) > 0)
        //     {
        //         $result = "";
        //     }
        //     else
        //     {
        //         $result = '<button type="button" class="btn btn-sm btn-info btn-packing-inspection" data-toggle="modal" data-target="#modalPackingInspection" lotapp-id="'.$packing->id.'"><i class="fa fa-search"></i></button>';
        //     }

        //     $result .= ' <button type="button" class="btn btn-sm btn-success btn-view-application" data-toggle="modal" data-target="#modalViewApplication" lotapp-id="'.$packing->id.'"><i class="fa fa-eye"></i></button>';

        //     return $result;
        // })
        // ->addColumn('lot_no', function($packing){

        //     $result = $packing->runcard_no;

        //     return $result;
        // })
        // ->addColumn('device_code', function($packing){

        //     if(count($packing->tspts_packingconfirmation_info) > 0)
        //     {
        //         if($packing->tspts_packingconfirmation_info[0]->device_code != null)
        //         {
        //             $result = $packing->tspts_packingconfirmation_info[0]->device_code;
        //         }
        //         else
        //         {
        //             $result = "No Device Code Assigned!";
        //         }
        //     }
        //     else
        //     {
        //         $result = "---";

        //     }

        //     return $result;
        // })
        // ->addColumn('lot_qty', function($packing){

        //    $result = 0;

        //     if(count($packing->prod_runcard_station_many_details) > 0)
        //     {
        //         for($i = 0; $i < count($packing->prod_runcard_station_many_details); $i++)
        //         {
        //             $result += $packing->prod_runcard_station_many_details[$i]->qty_output;
        //         }
        //     }

        //     return $result;
        // })
        // ->addColumn('packing_operator', function($packing){

        //     $result = "---";

        //     if(count($packing->tspts_packinginspection_info) > 0)
        //     {
        //         $result = $packing->tspts_packinginspection_info[0]->inspector_info->name;
        //     }

        //     return $result;
        // })
        // ->rawColumns(['action'])
        // ->make(true);


        // $data = ProductionRuncard::with([
        //     'tspts_packingconfirmation_info' => function($query2) {
        //         $query2->where('logdel',0)->orderBy('created_at','desc');
        //     }])
        //     ->where('po_no', $request->po_no)
        //     ->where('status', 4)
        //     ->get();

        // $new = [];
        // for ($i=0; $i < count($data); $i++) {
        //     $oqc_inspec = OQCInspection_2::where('prod_runcard_id', $data[$i]->id)->get();
        //     if( count($oqc_inspec)>0 )
        //         $new[] = $data[$i];
        // }

        // return DataTables::of($new)
        //     ->addColumn('action', function($data){
        //         $btn = '';
        //         if(count($data->tspts_packingconfirmation_info) > 0){
        //             $btn .= ' <button type="button" class="btn btn-sm btn-success btn-view-application" data-toggle="modal" data-target="#modalViewApplication" lotapp-id="'.$data->id.'"><i class="fa fa-eye"></i></button>';
        //         }else{
        //             // $btn = '<button type="button" class="btn btn-sm btn-info btn-packing-confirmation" data-toggle="modal" data-target="#modalPackingConfirmation" lotapp-id="'.$data->id.'"><i class="fa fa-edit"></i></button>';
        //             $btn = '<button type="button" class="btn btn-sm btn-info btn-packing-confirmation" lotapp-id="'.$data->id.'"><i class="fa fa-edit"></i></button>';
        //         }
        //         return $btn;
        //     })

            // --------------------------------------------------------------------------------------------


        $data = ProductionRuncard::with([
            'tspts_packinginspection_info' => function($query2) {
                $query2->where('logdel',0)->orderBy('created_at','desc');
            },'tspts_packingconfirmation_info' => function($query2) {
                $query2->where('logdel',0)->orderBy('created_at','desc');
            }])
            ->where('po_no', $request->po_no)
            ->where('status', 4)
            ->get();

        $new = [];
        for ($i=0; $i < count($data); $i++) {
            $oqc_inspec = OQCInspection_2::where('prod_runcard_id', $data[$i]->id)->get();
            if( count($oqc_inspec)>0 ){
                $inspect = TSPTSPackingConfirmation::where('lotapp_id', $data[$i]->id)->get();
                if( count($inspect)>0 ){
                    $new[] = $data[$i];
                }
            }
        }

        return DataTables::of($new)
            ->addColumn('action', function($data){
                $btn = '';
                //     $btn .= ' <button type="button" class="btn btn-sm btn-success btn-view-application" data-toggle="modal" data-target="#modalViewApplication" lotapp-id="'.$data->id.'"><i class="fa fa-eye"></i></button>';
                // }else{
                //     $btn = '<button type="button" class="btn btn-sm btn-info btn-packing-confirmation" data-toggle="modal" data-target="#modalPackingConfirmation" lotapp-id="'.$data->id.'"><i class="fa fa-edit"></i></button>';
                // }

                // if( $data->po_no === '450234054600010' )
                    // return '<button type="button" class="btn btn-sm btn-success btn-view-application" data-toggle="modal" data-target="#modalViewApplication" lotapp-id="'.$data->id.'"><i class="fa fa-eye"></i></button> ';
                    // return '<button type="button" class="btn btn-sm btn-info btn-packing-inspection" title="For update" lotapp-id="'.$data->id.'"><i class="fa fa-edit"></i></button>';

                if(count($data->tspts_packinginspection_info) == 0){

                    $btn .= '<button type="button" class="btn btn-sm btn-info btn-packing-inspection" title="For update" lotapp-id="'.$data->id.'"><i class="fa fa-edit"></i></button>';
                }else{

                    $btn .= '<button type="button" class="btn btn-sm btn-success btn-view-application" data-toggle="modal" data-target="#modalViewApplication" lotapp-id="'.$data->id.'"><i class="fa fa-eye"></i></button> ';
                }

                // if( $data->po_no === 'ES22-002-A' ){
                //     $btn .= '<button type="button" class="btn btn-sm btn-info btn-packing-inspection" title="For update" lotapp-id="'.$data->id.'"><i class="fa fa-edit"></i></button>';
                // }
                return $btn;
                //     return '<button type="button" class="btn btn-sm btn-info btn-packing-inspection" title="For update" lotapp-id="'.$data->id.'"><i class="fa fa-edit"></i></button>';

                // } // for testing only 07172023
                    // <button type="button" class="btn btn-sm btn-primary btnPrintQRCode" lotapp-id="'.$data->id.'"><i class="fa fa-print"></i></button>

                    // return '<button type="button" class="btn btn-sm btn-info btn-packing-inspection" data-toggle="modal" data-target="#modalPackingInspection" lotapp-id="'.$data->id.'"><i class="fa fa-search"></i></button>';
            })
            ->addColumn('packing_code', function($data){
                if(count($data->tspts_packingconfirmation_info) > 0)
                    return $data->tspts_packingconfirmation_info[0]->device_code;
                return '---';
            })
            ->addColumn('lot_no', function($data){
                return $data->lot_no;
            })
            ->addColumn('lot_qty', function($data){
                $cnt = 0;
                $_data = ProductionRuncardStation::where('production_runcard_id', $data->id)->where('status', 1)->get();
                for ($i=0; $i < count($_data); $i++)
                    // $cnt += $_data[$i]->qty_input; // commented 06/25/2025 change to qty_output
                    $cnt += $_data[$i]->qty_output; // mdr
                return $cnt;
            })
            ->addColumn('packing_operator', function($data){
                if(count($data->tspts_packingconfirmation_info) > 0){
                    $user = User::find($data->tspts_packingconfirmation_info[0]->operator_id);
                    if(isset($user->name))
                        return $user->name;
                }
                return '---';
            })
            ->addColumn('oqc_inspector', function($data){
                if(count($data->tspts_packinginspection_info) > 0){
                    $user = User::find($data->tspts_packinginspection_info[0]->inspector_id);
                    if(isset($user->name))
                        return $user->name;
                }
                return '---';
            })
            ->rawColumns(['action'])
            ->make(true);

    }

    public function generate_qrcode_for_preliminary_packing(Request $request)
    {

        $data = ProductionRuncard::with([
            'tspts_packinginspection_info' => function($query2) {
                $query2->where('logdel',0)->orderBy('created_at','desc');
            },'tspts_packingconfirmation_info' => function($query2) {
                $query2->where('logdel',0)->orderBy('created_at','desc');
            }])
            ->where('id', $request->id)
            ->where('status', 4)
            ->get();

        $new = [];
        for ($i=0; $i < count($data); $i++) {
            $oqc_inspec = OQCInspection_2::where('prod_runcard_id', $data[$i]->id)->get();
            if( count($oqc_inspec)>0 )
                $new[] = $data[$i];
        }

        if( count($new)==0 )
            return response()->json(['error_msg' => 'QR Code generation failed. Other info is not available.']);


        // $runcards = ProductionRuncardStation::with(['ct_area_info', 'terminal_area_info','tspts_oqcvir_info' => function($query){
        //     $query->orderBy('created_at','desc');
        // }, 'tspts_oqcvir_info.inspector_info'])->where('production_runcard_id', $request->id)->get();

        // $array_batch = [];
        // if($request->array_batch != null)
        //     $array_batch = $request->array_batch;


        // // return $request->inspector_id;

        // for ($i=0; $i < count($runcards); $i++) {
        //     if( count($runcards[$i]->tspts_oqcvir_info)>0 )
        //         $runcards[$i]->tspts_oqcvir_info[0]->inspector_info_2 = User::/*where('employee_id', $runcards[$i]->tspts_oqcvir_info[0]->employee_id)->*/where('id', $request->inspector_id)->get();
        //     // break;
        // }

        // $result = 1;
        // $runcard = $runcards[0];
        // if(count($runcard->tspts_oqcvir_info) > 0)
        // {
        //     if($runcard->tspts_oqcvir_info[0]->result == 1)
        //     {
        //         // if($runcard->tspts_oqcvir_info[0]->inspector_info_2[0] != null)
        //         if($runcard->tspts_oqcvir_info[0]->inspector_info_2[0] != null)
        //         {
        //             if($runcard->tspts_oqcvir_info[0]->oqc_stamp != null)
        //             {
        //                 //year and month of created at
        //                 $yearmonth = $runcard->created_at->format('ym');

        //                 //ct area and terminal area
        //                 $ct_area = $runcard->ct_area_info;
        //                 $terminal_area = $runcard->terminal_area_info;

        //                 //generated fvi no
        //                 $fvi_no = '';

        //                 if($ct_area != null && $terminal_area != null)
        //                 {

        //                     if($ct_area->fvi_no != null && $terminal_area->fvi_no != null)
        //                     {
        //                     // return 'ok';
        //                         if($ct_area->fvi_no < 100 && $terminal_area->fvi_no < 100)
        //                         {
        //                             if($ct_area->fvi_no != $terminal_area->fvi_no)
        //                             {
        //                                 $fvi_no = $terminal_area->fvi_no . $ct_area->fvi_no;
        //                             }
        //                             else
        //                             {
        //                                 $fvi_no = str_pad($ct_area->fvi_no, 4, "0", STR_PAD_LEFT);
        //                             }
        //                         }
        //                         else
        //                         {
        //                             if($ct_area->fvi_no != null)
        //                             {
        //                                 $fvi_no = str_pad($ct_area->fvi_no, 4, "0", STR_PAD_LEFT);
        //                             }
        //                             else
        //                             {
        //                                 $fvi_no = 'XXCT';
        //                             }
        //                         }
        //                     }
        //                     else
        //                     {
        //                         $fvi_no = 'TECT';
        //                     }
        //                 }
        //                 else
        //                 {
        //                     if($ct_area->fvi_no != null)
        //                     {
        //                         $fvi_no = str_pad($ct_area->fvi_no, 4, "0", STR_PAD_LEFT);
        //                     }
        //                     else
        //                     {
        //                         $fvi_no = 'XXXX';
        //                     }
        //                 }

        //                 //oqc inspector stamp
        //                 $oqc = explode("-", $runcard->tspts_oqcvir_info[0]->oqc_stamp)[1];

        //                 $result = $yearmonth . $fvi_no . "-" . $oqc;
        //             }
        //             else
        //             {
        //                  $result = 1;
        //             }
        //         }
        //         else
        //         {
        //             $result = 1;
        //         }
        //     }
        //     else
        //     {
        //         $result = 1;
        //     }
        // }
        // else
        // {
        //     $result = 1;
        // }

        // if( $result==1 )
        //     return response()->json(['error_msg' => 'Inspector Code is generat.']);

        $result = $request->inspector_code;

        $drawings = ProductionRuncard::where('po_no', $new[0]->po_no)->get();
        $drawings = $drawings[0];
        // return $drawings;

        $drwaing_list = "";
        $drwaing_list_html = "";
        if( $drawings->a_drawing_no != "N/A" ){
            $drwaing_list .= "
" . $drawings->a_drawing_no;
            $drwaing_list_html .= "<br>" . $drawings->a_drawing_no;
        }
        if( $drawings->orig_a_drawing_no != "N/A" ){
            $drwaing_list .= "
" . $drawings->orig_a_drawing_no;
            $drwaing_list_html .= "<br>" . $drawings->orig_a_drawing_no;
        }
        if( $drawings->g_drawing_no != "N/A" ){
            $drwaing_list .= "
" . $drawings->g_drawing_no;
            $drwaing_list_html .= "<br>" . $drawings->g_drawing_no;
        }

        $doc_list = "";
        $doc_list_html = "";
        if( $drawings->pm != "N/A" ){
            $doc_list .= "
" . $drawings->pm;
            $doc_list_html .= "<br>" . $drawings->pm;
        }
        if( $drawings->j_r_dj_ks_dc_gj != "N/A" ){
            $doc_list .= "
" . $drawings->j_r_dj_ks_dc_gj;
            $doc_list_html .= "<br>" . $drawings->j_r_dj_ks_dc_gj;
        }
        if( $drawings->gp_md != "N/A" ){
            $doc_list .= "
" . $drawings->gp_md;
            $doc_list_html .= "<br>" . $drawings->gp_md;
        }

        // return response()->json(['data' => $drwaing_list]);


        $QrCode = QrCode::format('png')->errorCorrection('H')->size(200)->generate(
            'Inspector Code: ' . $result . '
Drawing #: ' . $drwaing_list . '
Packing Doc. #: ' . $doc_list
        );
        $QrCode = "data:image/png;base64," . base64_encode($QrCode);

        $lbl =  'Inspector Code: ' . $result . '<br>' .
                'Drawing #: ' . $drwaing_list_html . '<br>' .
                'Packing Doc. #: ' . $doc_list_html;

        $data = [];
        $data[] = array(
            'img' => "data:image/png;base64," . base64_encode(QrCode::format('png')->errorCorrection('H')->size(200)->generate($result . $drwaing_list . $doc_list
        )),
            'text' => $result . $drwaing_list_html . $doc_list_html
            );

        return response()->json(['QrCode' => $QrCode, 'label' => $lbl, 'label_hidden' => $data]);

//         $po_no = ProductionRuncard::where('id', $request->id)->get()[0]->po_no;
//         $prd_runcards = ProductionRuncard::where('po_no', $po_no)->orderBy('id')->get();
//         $prd_runcards_counter = [];
//         for ($i=0; $i < count($prd_runcards); $i++)
//             $prd_runcards_counter[ $prd_runcards[$i]->id ] = ($i+1);


//         $cnt = ceil($prd_runcards[0]->po_qty / $device[0]->ship_boxing);

//         $sticker_cnt = ceil($device[0]->ship_boxing / $device[0]->boxing);
//         $data = [];

//         $content = '';
//         for ($i=1; $i <= $sticker_cnt; $i++) {

//             $lcl_QrCode = QrCode::format('png')->errorCorrection('H')->size(200)->generate($oqcLotApp[0]->po_no . '
// ' . $oqcLotApp[0]->lot_batch_no . '
// ' . $oqcLotApp[0]->print_lot . '
// ' . $ttl . '
// ' . $device[0]->boxing . '/' . $device[0]->ship_boxing . '
// ' . $prd_runcards_counter[$request->id] . '/' . $cnt);
//             $lcl_QrCode = "data:image/png;base64," . base64_encode($lcl_QrCode);

//             $data[] = array('img' => $lcl_QrCode, 'text' => '<b><br>' .$oqcLotApp[0]->po_no . '</b><br>' . '<b>'. explode(' - ', $device[0]->name)[0]. '</b><br>' .
//                 $oqcLotApp[0]->lot_batch_no . '</b><br>' .
//                 $oqcLotApp[0]->print_lot . '</b><br>' .
//                 $ttl . '</b><br>' .
//                 $device[0]->boxing . '/' . $device[0]->ship_boxing . '</b><br>' .
//                 $prd_runcards_counter[$request->id] . '/' . $cnt.'</b>');

//         }

    }

    public function generate_qrcode_for_packing_confirmation(Request $request)
    {

        $data = ProductionRuncard::with([
            'tspts_packinginspection_info' => function($query2) {
                $query2->where('logdel',0)->orderBy('created_at','desc');
            },'tspts_packingconfirmation_info' => function($query2) {
                $query2->where('logdel',0)->orderBy('created_at','desc');
            }])
            ->where('id', $request->id)
            ->where('status', 4)
            ->get();

        $new = [];
        for ($i=0; $i < count($data); $i++) {
            $oqc_inspec = OQCInspection_2::where('prod_runcard_id', $data[$i]->id)->get();
            if( count($oqc_inspec)>0 )
                $new[] = $data[$i];
        }

        if( count($new)==0 )
            return response()->json(['error_msg' => 'QR Code generation failed. Other info is not available.']);

        $result = $request->inspector_code;

        $drawings = ProductionRuncard::where('po_no', $new[0]->po_no)->get();
        $drawings = $drawings[0];

        $packing_code = TSPTSPackingConfirmation::where('lotapp_id', $new[0]->id)->get();
        $packing_code = $packing_code[0];
        // return $packing_code;

        $drwaing_list = "";
        $drwaing_list_html = "";
        if( $drawings->a_drawing_no != "N/A" ){
            $drwaing_list .= "
" . $drawings->a_drawing_no;
            $drwaing_list_html .= "<br>" . $drawings->a_drawing_no;
        }
        if( $drawings->orig_a_drawing_no != "N/A" ){
            $drwaing_list .= "
" . $drawings->orig_a_drawing_no;
            $drwaing_list_html .= "<br>" . $drawings->orig_a_drawing_no;
        }
        if( $drawings->g_drawing_no != "N/A" ){
            $drwaing_list .= "
" . $drawings->g_drawing_no;
            $drwaing_list_html .= "<br>" . $drawings->g_drawing_no;
        }

        $doc_list = "";
        $doc_list_html = "";
        if( $drawings->pm != "N/A" ){
            $doc_list .= "
" . $drawings->pm;
            $doc_list_html .= "<br>" . $drawings->pm;
        }
        if( $drawings->j_r_dj_ks_dc_gj != "N/A" ){
            $doc_list .= "
" . $drawings->j_r_dj_ks_dc_gj;
            $doc_list_html .= "<br>" . $drawings->j_r_dj_ks_dc_gj;
        }
        if( $drawings->gp_md != "N/A" ){
            $doc_list .= "
" . $drawings->gp_md;
            $doc_list_html .= "<br>" . $drawings->gp_md;
        }

        $QrCode = QrCode::format('png')->errorCorrection('H')->size(200)->generate(
            '' . $result . '
' . $drwaing_list . '
' . $doc_list . '
' ."\n". $packing_code->device_code
        );
        $QrCode = "data:image/png;base64," . base64_encode($QrCode);

        $lbl =  'Inspector Code: <i>' . $result . '</i><br>' .
                'Drawing #: <i>' . $drwaing_list_html . '</i><br>' .
                'Packing Doc. #: <i>' . $doc_list_html . '</i><br>' .
                'Packing Code: <i>' . $packing_code->device_code;

        $data = [];
        $data[] = array(
            'img' => "data:image/png;base64," . base64_encode(QrCode::format('png')->errorCorrection('H')->size(200)->generate($result ."\n". $drwaing_list ."\n". $doc_list ."\n\n". $packing_code->device_code
        )),
            'text' => '<b>'.$result .'</b>'. $drwaing_list_html . $doc_list_html . '<br>'. $packing_code->device_code
            );

        return response()->json(['QrCode' => $QrCode, 'label' => $lbl, 'label_hidden' => $data]);

    }


    public function tspts_submit_packing_inspection(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
         $data = $request->all();

         $validator = '';

         $validator = Validator::make($request->all(), [

            'add_packing_type' => 'required',
            'add_unit_condition' => 'required',
            'add_series_v_label' => 'required',
            'add_label_v_actual' => 'required',
            'add_silica_gel' => 'required',
            'add_yd_label' => 'required',
            /*'add_supervisor_validation' => 'required',*/
            'add_packing_inspector_name' => 'required',
            // 'add_inspection_datetime' => 'required',

         ]);

         if($validator->passes())
         {
            try
            {
                TSPTSPreliminaryPackingInspection::insert([

                    'lotapp_id' => $request->add_lot_id,
                    'packing_type' => $request->add_packing_type,
                    'unit_condition' => $request->add_unit_condition,
                    'packing_code' => 'FOR CONFIRMATION',
                    'series_v_label' => $request->add_series_v_label,
                    'label_v_actual' => $request->add_label_v_actual,
                    'silica_gel' => $request->add_silica_gel,
                    'yd_label' => $request->add_yd_label,
                    'coc' => $request->add_coc,
                    'supervisor_conformance' => 1, /*$request->add_supervisor_validation,*/
                    // 'inspection_datetime' => $request->add_inspection_datetime,
                    'inspection_datetime' => date('Y-m-d H:i:s'),
                    'inspector_id' => $request->add_packing_inspector_name,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'logdel' => 0,
                ]);

                return response()->json(['result' => 1]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => $e]);
            }
         }
         else
         {
            return response()->json(['result' => 0, 'error' => $validator->messages()]);
         }
    }

    public function load_supervisorvalidation_pts_table(Request $request)
    {

        // $data = ProductionRuncard::with([
        //     'tspts_packinginspection_info' => function($query2) {
        //         $query2->where('logdel',0)->orderBy('created_at','desc');
        //     },'tspts_packingconfirmation_info' => function($query2) {
        //         $query2->where('logdel',0)->orderBy('created_at','desc');
        //     }])
        //     ->where('po_no', $request->po_no)
        //     ->where('status', 4)
        //     ->get();

        // $new = [];
        // for ($i=0; $i < count($data); $i++) {
        //     $oqc_inspec = OQCInspection_2::where('prod_runcard_id', $data[$i]->id)->get();
        //     if( count($oqc_inspec)>0 )
        //         $new[] = $data[$i];
        // }
        // -------------

        $data = ProductionRuncard::with(['prod_runcard_station_many_details' => function($query){
            $query->where('status', 1);
        },'prod_runcard_accessory_info','tspts_oqcvir_info' => function($query){
            $query->where('result', 1);
        }, 'tspts_packingconfirmation_info', 'tspts_packinginspection_info' => function($query2){
            $query2->where('supervisor_conformance', 1);
        }, 'tspts_supervisorvalidation_info','tspts_supervisorvalidation_info.supervisor_info'])->where('po_no', $request->po_num)->where('status',4)->get();


        // return 'qwe';
        // return 'qwe';
        // return $data;

        $new = [];
        for ($i=0; $i < count($data); $i++) {
            $oqc_inspec = OQCInspection_2::where('prod_runcard_id', $data[$i]->id)->get();
            if( count($oqc_inspec)>0 ){
                $inspect = TSPTSPackingConfirmation::where('lotapp_id', $data[$i]->id)->get();
                if( count($inspect)>0 ){
                    $prelim = TSPTSPreliminaryPackingInspection::where('lotapp_id', $data[$i]->id)->get();
                    if( count($prelim)>0 ){
                        $new[] = $data[$i];
                    }
                }
            }
        }

        // TSPTSPreliminaryPackingInspection
        // $packings = collect($oqcvirs);
        // $packing_final = $packings->where('tspts_oqcvir_info', '!=', '[]')->where('tspts_packingconfirmation_info','!=', '[]')->flatten(1)->where('tspts_packinginspection_info','!=', '[]')->flatten(1);

        return DataTables::of($new)
        ->addColumn('action', function($packing){

            $result = "";
            if(count($packing->tspts_supervisorvalidation_info) > 0)
            {
                $result .= ' <button type="button" class="btn btn-sm btn-success btn-view-application" data-toggle="modal" data-target="#modalViewApplication" lotapp-id="'.$packing->id.'"><i class="fa fa-eye"></i></button>';
            }
            else
            {
                $result .= '<button type="button" class="btn btn-sm btn-info btn-supervisor-validation" data-toggle="modal" data-target="#modalSupervisorValidation" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
            }

            if( $packing->po_no === 'ES22-002-A' )
                $result .= '<button type="button" class="btn btn-sm btn-info btn-supervisor-validation" data-toggle="modal" data-target="#modalSupervisorValidation" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';


            return $result;
        })
        ->addColumn('device_code', function($packing){

            if(count($packing->tspts_packingconfirmation_info) > 0)
            {
                if($packing->tspts_packingconfirmation_info[0]->device_code != null)
                {
                    $result = $packing->tspts_packingconfirmation_info[0]->device_code;
                }
                else
                {
                    $result = "No Device Code Assigned!";
                }
            }
            else
            {
                $result = "---";

            }

            return $result;
        })
        ->addColumn('lot_no', function($packing){

            $result = $packing->runcard_no;

            return $result;
        })
        ->addColumn('lot_qty', function($packing){

           $result = 0;

            if(count($packing->prod_runcard_station_many_details) > 0)
            {
                for($i = 0; $i < count($packing->prod_runcard_station_many_details); $i++)
                {
                    $result += $packing->prod_runcard_station_many_details[$i]->qty_output;
                }
            }

            return $result;
        })
        ->addColumn('supervisor', function($packing){

            $result = "---";

            // if(count($packing->tspts_supervisorvalidation_info) > 0)
            if( isset($packing->tspts_supervisorvalidation_info[0]->supervisor_info->name) )
            {
                $result = $packing->tspts_supervisorvalidation_info[0]->supervisor_info->name;
            }

            return $result;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function tspts_submit_supervisor_validation(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
         $data = $request->all();

         $validator = '';

         $validator = Validator::make($request->all(), [

            'add_series_v_label' => 'required',
            'add_label_v_actual' => 'required',
            'add_supervisor_name' => 'required',
            'add_label_correctness' => 'required',
            // 'add_confirmation_datetime' => 'required',
         ]);

         if($validator->passes())
         {
            try
            {
                TSPTSSupervisorValidation::insert([

                    'lotapp_id' => $request->add_lot_id,

                    'series_v_label' => $request->add_series_v_label,
                    'label_v_actual' => $request->add_label_v_actual,
                    'label_correctness' => $request->add_label_correctness,
                    'supervisor_id' => $request->add_supervisor_name,
                    // 'validation_datetime' => $request->add_confirmation_datetime,
                    'validation_datetime' => date('Y-m-d H:i:s'),

                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'logdel' => 0,
                ]);

                return response()->json(['result' => 1]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => $e]);
            }
         }
         else
         {
            return response()->json(['result' => 0, 'error' => $validator->messages()]);
         }
    }

    public function load_finalpacking_pts_table(Request $request)
    {
        $data = ProductionRuncard::with(['prod_runcard_station_many_details' => function($query){
            $query->where('status', 1);
        },'prod_runcard_accessory_info','tspts_oqcvir_info' => function($query){

            $query->where('result', 1);

        }, 'tspts_packingconfirmation_info', 'tspts_packinginspection_info', 'tspts_supervisorvalidation_info', 'tspts_finalpackinginspection_info','tspts_finalpackinginspection_info.inspector_info','oqc_details'])->where('po_no', $request->po_num)->where('status',4)->get();

        // return $data;

        $new = [];
        $new = [];
        for ($i=0; $i < count($data); $i++) {
            $oqc_inspec = OQCInspection_2::where('prod_runcard_id', $data[$i]->id)->get();
            if( count($oqc_inspec)>0 ){
                $inspect = TSPTSPackingConfirmation::where('lotapp_id', $data[$i]->id)->get();
                if( count($inspect)>0 ){
                    $prelim = TSPTSPreliminaryPackingInspection::where('lotapp_id', $data[$i]->id)->get();
                    if( count($prelim)>0 ){
                        $supervisor = TSPTSSupervisorValidation::where('lotapp_id', $data[$i]->id)->get();
                        if( count($supervisor)>0 ){
                            $data[$i]->supervisor_valid = $supervisor;
                            if( isset($new[count($new)-1]->id) ){
                                $data[$i]->id_first_data = $new[count($new)-1]->id;
                                $new[] = $data[$i];
                            }else{
                                $data[$i]->id_first_data = 0;
                                $new[] = $data[$i];
                            }
                        }
                    }
                }
            }
        }

        // TSPTSSupervisorValidation
        // $packings = collect($oqcvirs);
        // $packing_final = $packings->where('tspts_oqcvir_info', '!=', '[]')->where('tspts_packingconfirmation_info','!=', '[]')->where('tspts_packinginspection_info','!=', '[]')->where('tspts_supervisorvalidation_info','!=', '[]')->flatten(1);

        return DataTables::of($new)
        ->addColumn('action', function($packing){

            $result = "";
            if( $packing->supervisor_valid[0]->pmi_blue_packing_lbl_print != null ){
                if(count($packing->tspts_finalpackinginspection_info) > 0)
                {
                    $result .= ' <button type="button" class="btn btn-sm btn-success btn-view-application" data-toggle="modal" data-target="#modalViewApplication" lotapp-id="'.$packing->id.'"><i class="fa fa-eye"></i></button>';
                }
                else
                {
                    // $result = '<button type="button" class="btn btn-sm btn-info btn-final-inspection" data-toggle="modal" data-target="#modalFinalPackingInspection" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                    if( !in_array(Auth::user()->position, [1, 2]) || !in_array(Auth::user()->user_level_id, [2]) ){
                        if( $packing->id_first_data == 0 ){
                            $result = '<button type="button" class="btn btn-sm btn-info btn-final-inspection" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                        }else{
                            if( count( TSPTSFinalPackingInspection::where('lotapp_id', $packing->id_first_data)->get() ) == 0 ){
                                $result = '<button type="button" disabled class="btn btn-sm btn-info btn-final-inspection" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                            }else{
                                $result = '<button type="button" class="btn btn-sm btn-info btn-final-inspection" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                            }
                        }
                    }else{
                        $result = '<button type="button" class="btn btn-sm btn-info btn-final-inspection" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                    }
                }
                // 05282024 by Nessa
                // $result .= '<button type="button" class="btn btn-sm btn-info btn-final-inspection" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
            }else{
                // $result .= ' <button type="button" class="btn btn-sm btn-primary btnPrintFinalQRCode" data-toggle="modal" data-target="#modal_Final_Packing_QRcode" lotapp-id="'.$packing->id.'"><i class="fa fa-print"></i></button>';

                if( !in_array(Auth::user()->position, [1, 2]) || !in_array(Auth::user()->user_level_id, [2]) ){
                    if( $packing->id_first_data == 0 ){
                        $result .= ' <button type="button" class="btn btn-sm btn-primary btnPrintFinalQRCode" data-toggle="modal" data-target="#modal_Final_Packing_QRcode" lotapp-id="'.$packing->id.'"><i class="fa fa-print"></i></button>';
                    }else{
                        if( count( TSPTSFinalPackingInspection::where('lotapp_id', $packing->id_first_data)->get() ) == 0 ){
                            $result .= ' <button type="button" disabled class="btn btn-sm btn-primary btnPrintFinalQRCode" data-toggle="modal" data-target="#modal_Final_Packing_QRcode" lotapp-id="'.$packing->id.'"><i class="fa fa-print"></i></button>';
                        }else{
                            $result .= ' <button type="button" class="btn btn-sm btn-primary btnPrintFinalQRCode" data-toggle="modal" data-target="#modal_Final_Packing_QRcode" lotapp-id="'.$packing->id.'"><i class="fa fa-print"></i></button>';
                        }
                    }
                }else{
                    $result .= ' <button type="button" class="btn btn-sm btn-primary btnPrintFinalQRCode" data-toggle="modal" data-target="#modal_Final_Packing_QRcode" lotapp-id="'.$packing->id.'"><i class="fa fa-print"></i></button>';
                }
            }

            if( $packing->id == 1873 )
                $result .= '<button type="button" class="btn btn-sm btn-info btn-final-inspection" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
            if( in_array(Auth::user()->position, [1, 2]) || in_array(Auth::user()->user_level_id, [2]) )
                $result .= ' <button type="button" class="btn btn-sm btn-primary btnPrintFinalQRCode" data-toggle="modal" data-target="#modal_Final_Packing_QRcode" lotapp-id="'.$packing->id.'"><i class="fa fa-print"></i></button>';

            $result .= ' <button type="button" class="btn btn-sm btn-primary btnPrintFinalQRCode" data-toggle="modal" data-target="#modal_Final_Packing_QRcode" lotapp-id="'.$packing->id.'"><i class="fa fa-print"></i></button>';
            return $result;
        })
        ->addColumn('device_code', function($packing){

            if(count($packing->tspts_packingconfirmation_info) > 0)
            {
                if($packing->tspts_packingconfirmation_info[0]->device_code != null)
                {
                    $result = $packing->tspts_packingconfirmation_info[0]->device_code;
                }
                else
                {
                    $result = "No Device Code Assigned!";
                }
            }
            else
            {
                $result = "---";

            }

            return $result;
        })
        ->addColumn('lot_no', function($packing){

            $result = $packing->runcard_no;

            return $result;
        })
        ->addColumn('lot_qty', function($packing){

           $result = 0;

            if(count($packing->prod_runcard_station_many_details) > 0)
            {
                for($i = 0; $i < count($packing->prod_runcard_station_many_details); $i++)
                {
                    $result += $packing->prod_runcard_station_many_details[$i]->qty_output;
                }
            }

            return $result;
        })
        ->addColumn('packing_operator', function($packing){

            $result = "---";

            if(count($packing->tspts_finalpackinginspection_info) > 0)
            {
                // $result = $packing->tspts_finalpackinginspection_info[0]->operator_info->name .' '. $packing->tspts_finalpackinginspection_info[0]->inspection_datetime;
                $result = $packing->tspts_finalpackinginspection_info[0]->operator_info->name;
            }

            return $result;
        })
        ->addColumn('supervisor', function($packing){

            $result = "---";

            // if(count($packing->tspts_finalpackinginspection_info) > 0)
            if( isset($packing->tspts_finalpackinginspection_info[0]->inspector_info->name) )
            {
                $result = $packing->tspts_finalpackinginspection_info[0]->inspector_info->name;
            }

            return $result;
        })
        // ->addColumn('oqc_stamp', function($packing){

        //     $result = "---";

        //     if(count($packing->tspts_finalpackinginspection_info) > 0)
        //     {
        //         $result = $packing->tspts_finalpackinginspection_info[0]->inspector_info->oqc_stamp;
        //     }

        //     return $result;
        // })


        ->addColumn('ww', function($packing){

            $result = $packing->oqc_details->ww;

            return $result;
        })

        ->rawColumns(['action'])
        ->make(true);
    }

    public function load_finalpacking_pts_qc_table(Request $request)
    {
        $data = ProductionRuncard::with(['prod_runcard_station_many_details' => function($query){
            $query->where('status', 1);
        },'prod_runcard_accessory_info','tspts_oqcvir_info' => function($query){

            $query->where('result', 1);

        }, 'tspts_packingconfirmation_info', 'tspts_packinginspection_info', 'tspts_supervisorvalidation_info', 'tspts_finalpackinginspection_info_qc','tspts_finalpackinginspection_info_qc.inspector_info','oqc_details'])->where('po_no', $request->po_num)->where('status',4)->get();
        // return $data;
        $new = [];
        $new = [];
        for ($i=0; $i < count($data); $i++) {
            $oqc_inspec = OQCInspection_2::where('prod_runcard_id', $data[$i]->id)->get();
            if( count($oqc_inspec)>0 ){
                $inspect = TSPTSPackingConfirmation::where('lotapp_id', $data[$i]->id)->get();
                if( count($inspect)>0 ){
                    $prelim = TSPTSPreliminaryPackingInspection::where('lotapp_id', $data[$i]->id)->get();
                    if( count($prelim)>0 ){
                        $supervisor = TSPTSSupervisorValidation::where('lotapp_id', $data[$i]->id)->get();
                        if( count($supervisor)>0 ){
                            $f_packing = TSPTSFinalPackingInspection::where('lotapp_id', $data[$i]->id)->get();
                            if( count($f_packing)>0 ){
                                // $new[] = $data[$i];
                                if( isset($new[count($new)-1]->id) ){
                                    $data[$i]->id_first_data = $new[count($new)-1]->id;
                                    $new[] = $data[$i];
                                }else{
                                    $data[$i]->id_first_data = 0;
                                    $new[] = $data[$i];
                                }
                            }
                        }
                    }
                }
            }
        }


        return DataTables::of($new)
        ->addColumn('action', function($packing){
                $result = "";
            if(count($packing->tspts_finalpackinginspection_info_qc) > 0)
            {
                $result .= ' <button type="button" class="btn btn-sm btn-success btn-view-application" data-toggle="modal" data-target="#modalViewApplication" lotapp-id="'.$packing->id.'"><i class="fa fa-eye"></i></button>';
                $result .= '<button type="button" class="btn btn-sm btn-info btn-final-inspection-qc" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
            }
            else
            {
                // $result = '<button type="button" class="btn btn-sm btn-info btn-final-inspection-qc" data-toggle="modal" data-target="#modalFinalPackingInspection" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                if( !in_array(Auth::user()->position, [1, 2]) || !in_array(Auth::user()->user_level_id, [2]) ){
                    if( $packing->id_first_data == 0 ){
                        $result = '<button type="button" class="btn btn-sm btn-info btn-final-inspection-qc" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                    }else{
                        if( count( TSPTSFinalPackingInspectionQC::where('lotapp_id', $packing->id_first_data)->get() ) == 0 ){
                            $result = '<button type="button" disabled class="btn btn-sm btn-info btn-final-inspection-qc" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                        }else{
                            $result = '<button type="button" class="btn btn-sm btn-info btn-final-inspection-qc" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                        }
                    }
                }else{
                    $result = '<button type="button" class="btn btn-sm btn-info btn-final-inspection-qc" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                }
            }


            // $result = '<button type="button" class="btn btn-sm btn-info btn-final-inspection-qc" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
            return $result;
        })
        ->addColumn('device_code', function($packing){

            if(count($packing->tspts_packingconfirmation_info) > 0)
            {
                if($packing->tspts_packingconfirmation_info[0]->device_code != null)
                {
                    $result = $packing->tspts_packingconfirmation_info[0]->device_code;
                }
                else
                {
                    $result = "No Device Code Assigned!";
                }
            }
            else
            {
                $result = "---";

            }

            return $result;
        })
        ->addColumn('lot_no', function($packing){

            $result = $packing->runcard_no;

            return $result;
        })
        ->addColumn('lot_qty', function($packing){

           $result = 0;

            if(count($packing->prod_runcard_station_many_details) > 0)
            {
                for($i = 0; $i < count($packing->prod_runcard_station_many_details); $i++)
                {
                    $result += $packing->prod_runcard_station_many_details[$i]->qty_output;
                }
            }

            return $result;
        })
        ->addColumn('packing_operator', function($packing){

            $result = "---";

            // if(count($packing->tspts_finalpackinginspection_info_qc) > 0)
            if( isset($packing->tspts_finalpackinginspection_info_qc[0]->operator_info->name) )
            {
                $result = $packing->tspts_finalpackinginspection_info_qc[0]->operator_info->name;
            }

            return $result;
        })

        //- for comment after ng modification

        ->addColumn('supervisor', function($packing){

            $result = "---";

            // if(count($packing->tspts_finalpackinginspection_info_qc) > 0)
            if( isset($packing->tspts_finalpackinginspection_info_qc[0]->inspector_info->name) )
            {
                $result = $packing->tspts_finalpackinginspection_info_qc[0]->inspector_info->name;
            }

            return $result;
        })
        ->addColumn('oqc_stamp', function($packing){

            $result = "---";

            // if(count($packing->tspts_finalpackinginspection_info_qc) > 0)
            if( isset($packing->tspts_finalpackinginspection_info_qc[0]->inspector_info->oqc_stamp) )
            {
                $result = $packing->tspts_finalpackinginspection_info_qc[0]->inspector_info->oqc_stamp;
            }

            return $result;
        })

        ->addColumn('ww', function($packing){

            $result = $packing->oqc_details->ww;

            return $result;
        })

        ->rawColumns(['action'])
        ->make(true);
    }

    public function load_finalpacking_pts_qc_table_confirmation(Request $request)
    {
        $data = ProductionRuncard::with(['prod_runcard_station_many_details' => function($query){
            $query->where('status', 1);
        },'prod_runcard_accessory_info','tspts_oqcvir_info' => function($query){

            $query->where('result', 1);

        }, 'tspts_packingconfirmation_info', 'tspts_packinginspection_info', 'tspts_supervisorvalidation_info', 'tspts_finalpackinginspection_info_qc','tspts_finalpackinginspection_info_qc.inspector_info','oqc_details'])->where('po_no', $request->po_num)->where('status',4)->get();

        $new = [];
        for ($i=0; $i < count($data); $i++) {
            $oqc_inspec = OQCInspection_2::where('prod_runcard_id', $data[$i]->id)->get();
            if( count($oqc_inspec)>0 ){
                $inspect = TSPTSPackingConfirmation::where('lotapp_id', $data[$i]->id)->get();
                if( count($inspect)>0 ){
                    $prelim = TSPTSPreliminaryPackingInspection::where('lotapp_id', $data[$i]->id)->get();
                    if( count($prelim)>0 ){
                        $supervisor = TSPTSSupervisorValidation::where('lotapp_id', $data[$i]->id)->get();
                        if( count($supervisor)>0 ){
                            $f_packing = TSPTSFinalPackingInspection::where('lotapp_id', $data[$i]->id)->get();
                            if( count($f_packing)>0 ){
                                // $new[] = $data[$i];
                                if( isset($new[count($new)-1]->id) ){
                                    $data[$i]->id_first_data = $new[count($new)-1]->id;
                                    $qc = TSPTSFinalPackingInspectionQC::where('lotapp_id', $data[$i]->id)->get();
                                    if( count($qc)>0 ){
                                        // if( $qc[0]->inspector_id!=0 )
                                            $new[] = $data[$i];
                                    }
                                }else{
                                    $data[$i]->id_first_data = 0;
                                    $qc = TSPTSFinalPackingInspectionQC::where('lotapp_id', $data[$i]->id)->get();
                                    if( count($qc)>0 ){
                                        // if( $qc[0]->inspector_id!=0 )
                                            $new[] = $data[$i];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        // return $new;

        return DataTables::of($new)
        ->addColumn('action', function($packing){

            if(count($packing->tspts_finalpackinginspection_info_qc) > 0){
                if( $packing->tspts_finalpackinginspection_info_qc[0]->inspector_id==0 ){
                    return '<button type="button" class="btn btn-sm btn-info btn-final-inspection-qc" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                }else{
                    return ' <button type="button" class="btn btn-sm btn-success btn-view-application" data-toggle="modal" data-target="#modalViewApplication" lotapp-id="'.$packing->id.'"><i class="fa fa-eye"></i></button>';
                }
            }else{
                return "---";
            }

                $result = "";
            if(count($packing->tspts_finalpackinginspection_info_qc) > 0)
            {
                $result .= ' <button type="button" class="btn btn-sm btn-success btn-view-application" data-toggle="modal" data-target="#modalViewApplication" lotapp-id="'.$packing->id.'"><i class="fa fa-eye"></i></button>';
            }
            else
            {
                // $result = '<button type="button" class="btn btn-sm btn-info btn-final-inspection-qc" data-toggle="modal" data-target="#modalFinalPackingInspection" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                // $result = '<button type="button" class="btn btn-sm btn-info btn-final-inspection-qc" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                if( !in_array(Auth::user()->position, [1, 2]) || !in_array(Auth::user()->user_level_id, [2]) ){
                    if( $packing->id_first_data == 0 ){
                        $result = '<button type="button" class="btn btn-sm btn-info btn-final-inspection-qc" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                    }else{
                        if( count( TSPTSFinalPackingInspectionQC::where('lotapp_id', $packing->id_first_data)->get() ) == 0 ){
                            $result = '<button type="button" disabled class="btn btn-sm btn-info btn-final-inspection-qc" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                        }else{
                            $result = '<button type="button" class="btn btn-sm btn-info btn-final-inspection-qc" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                        }
                    }
                }else{
                    $result = '<button type="button" class="btn btn-sm btn-info btn-final-inspection-qc" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                }
            }


            return $result;
        })
        ->addColumn('device_code', function($packing){

            if(count($packing->tspts_packingconfirmation_info) > 0)
            {
                if($packing->tspts_packingconfirmation_info[0]->device_code != null)
                {
                    $result = $packing->tspts_packingconfirmation_info[0]->device_code;
                }
                else
                {
                    $result = "No Device Code Assigned!";
                }
            }
            else
            {
                $result = "---";

            }

            return $result;
        })
        ->addColumn('lot_no', function($packing){

            $result = $packing->runcard_no;

            return $result;
        })
        ->addColumn('lot_qty', function($packing){

           $result = 0;

            if(count($packing->prod_runcard_station_many_details) > 0)
            {
                for($i = 0; $i < count($packing->prod_runcard_station_many_details); $i++)
                {
                    $result += $packing->prod_runcard_station_many_details[$i]->qty_output;
                }
            }

            return $result;
        })
        ->addColumn('packing_operator', function($packing){

            $result = "---";

            // if(count($packing->tspts_finalpackinginspection_info_qc) > 0)
            if( isset($packing->tspts_finalpackinginspection_info_qc[0]->operator_info->name) )
            {
                $result = $packing->tspts_finalpackinginspection_info_qc[0]->operator_info->name;
            }

            return $result;
        })


        ->addColumn('supervisor', function($packing){

            $result = "---";

            // if(count($packing->tspts_finalpackinginspection_info_qc) > 0)
            if( isset($packing->tspts_finalpackinginspection_info_qc[0]->inspector_info->name) )
            {
                $result = $packing->tspts_finalpackinginspection_info_qc[0]->inspector_info->name;
            }

            return $result;
        })
        ->addColumn('oqc_stamp', function($packing){

            $result = "---";

            // if(count($packing->tspts_finalpackinginspection_info_qc) > 0)
            if( isset($packing->tspts_finalpackinginspection_info_qc[0]->inspector_info->oqc_stamp) )
            {
                $result = $packing->tspts_finalpackinginspection_info_qc[0]->inspector_info->oqc_stamp;
            }

            return $result;
        })

        ->addColumn('ww', function($packing){

            $result = $packing->oqc_details->ww;

            return $result;
        })

        ->rawColumns(['action'])
        ->make(true);
    }

    public function load_finalpacking_pts_qc_table_conf(Request $request)
    {
        $data = ProductionRuncard::with(['prod_runcard_station_many_details' => function($query){
            $query->where('status', 1);
        },'prod_runcard_accessory_info','tspts_oqcvir_info' => function($query){

            $query->where('result', 1);

        }, 'tspts_packingconfirmation_info', 'tspts_packinginspection_info', 'tspts_supervisorvalidation_info', 'tspts_finalpackinginspection_info_qc','tspts_finalpackinginspection_info_qc.inspector_info'])->where('po_no', $request->po_num)->where('status',4)->get();

        $new = [];
        $new = [];
        for ($i=0; $i < count($data); $i++) {
            $oqc_inspec = OQCInspection_2::where('prod_runcard_id', $data[$i]->id)->get();
            if( count($oqc_inspec)>0 ){
                $inspect = TSPTSPackingConfirmation::where('lotapp_id', $data[$i]->id)->get();
                if( count($inspect)>0 ){
                    $prelim = TSPTSPreliminaryPackingInspection::where('lotapp_id', $data[$i]->id)->get();
                    if( count($prelim)>0 ){
                        $supervisor = TSPTSSupervisorValidation::where('lotapp_id', $data[$i]->id)->get();
                        if( count($supervisor)>0 ){
                            $f_packing = TSPTSFinalPackingInspection::where('lotapp_id', $data[$i]->id)->get();
                            if( count($f_packing)>0 ){
                                // $new[] = $data[$i];
                                if( isset($new[count($new)-1]->id) ){
                                    $data[$i]->id_first_data = $new[count($new)-1]->id;
                                    $new[] = $data[$i];
                                }else{
                                    $data[$i]->id_first_data = 0;
                                    $new[] = $data[$i];
                                }
                            }
                        }
                    }
                }
            }
        }


        return DataTables::of($new)
        ->addColumn('action', function($packing){

                $result = "";
            if(count($packing->tspts_finalpackinginspection_info_qc) > 0)
            {
                $result .= ' <button type="button" class="btn btn-sm btn-success btn-view-application" data-toggle="modal" data-target="#modalViewApplication" lotapp-id="'.$packing->id.'"><i class="fa fa-eye"></i></button>';
            }
            else
            {
                // $result = '<button type="button" class="btn btn-sm btn-info btn-final-inspection-qc" data-toggle="modal" data-target="#modalFinalPackingInspection" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                // $result = '<button type="button" class="btn btn-sm btn-info btn-final-inspection-qc" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                if( !in_array(Auth::user()->position, [1, 2]) || !in_array(Auth::user()->user_level_id, [2]) ){
                    if( $packing->id_first_data == 0 ){
                        $result = '<button type="button" class="btn btn-sm btn-info btn-final-inspection-qc" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                    }else{
                        if( count( TSPTSFinalPackingInspectionQC::where('lotapp_id', $packing->id_first_data)->get() ) == 0 ){
                            $result = '<button type="button" disabled class="btn btn-sm btn-info btn-final-inspection-qc" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                        }else{
                            $result = '<button type="button" class="btn btn-sm btn-info btn-final-inspection-qc" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                        }
                    }
                }else{
                    $result = '<button type="button" class="btn btn-sm btn-info btn-final-inspection-qc" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                }
            }


            return $result;
        })
        ->addColumn('device_code', function($packing){

            if(count($packing->tspts_packingconfirmation_info) > 0)
            {
                if($packing->tspts_packingconfirmation_info[0]->device_code != null)
                {
                    $result = $packing->tspts_packingconfirmation_info[0]->device_code;
                }
                else
                {
                    $result = "No Device Code Assigned!";
                }
            }
            else
            {
                $result = "---";

            }

            return $result;
        })
        ->addColumn('lot_no', function($packing){

            $result = $packing->runcard_no;

            return $result;
        })
        ->addColumn('lot_qty', function($packing){

           $result = 0;

            if(count($packing->prod_runcard_station_many_details) > 0)
            {
                for($i = 0; $i < count($packing->prod_runcard_station_many_details); $i++)
                {
                    $result += $packing->prod_runcard_station_many_details[$i]->qty_output;
                }
            }

            return $result;
        })
        ->addColumn('packing_operator', function($packing){

            $result = "---";

            if(count($packing->tspts_finalpackinginspection_info_qc) > 0)
            {
                $result = $packing->tspts_finalpackinginspection_info_qc[0]->operator_info->name;
            }

            return $result;
        })


        ->addColumn('supervisor', function($packing){

            $result = "---";

            if(count($packing->tspts_finalpackinginspection_info_qc) > 0)
            {
                $result = $packing->tspts_finalpackinginspection_info_qc[0]->inspector_info->name;
            }

            return $result;
        })
        ->addColumn('oqc_stamp', function($packing){

            $result = "---";

            if(count($packing->tspts_finalpackinginspection_info_qc) > 0)
            {
                $result = $packing->tspts_finalpackinginspection_info_qc[0]->inspector_info->oqc_stamp;
            }

            return $result;
        })

        ->rawColumns(['action'])
        ->make(true);
    }
    public function load_finalpacking_pts_traffic_qc_table(Request $request)
    {
        $data = ProductionRuncard::with(['prod_runcard_station_many_details' => function($query){
            $query->where('status', 1);
        },'prod_runcard_accessory_info','tspts_oqcvir_info' => function($query){

            $query->where('result', 1);

        }, 'tspts_packingconfirmation_info', 'tspts_packinginspection_info', 'tspts_supervisorvalidation_info', 'tspts_finalpackinginspection_info_qc','tspts_finalpackinginspection_info_qc.inspector_info', 'tspts_finalpackinginspection_info_traffic_qc.traffic_info', 'tspts_finalpackinginspection_info_traffic_qc_qc.qc_info','oqc_details'])->where('po_no', $request->po_num)->where('status',4)->get();

        $new = [];
        $new = [];
        for ($i=0; $i < count($data); $i++) {
            $oqc_inspec = OQCInspection_2::where('prod_runcard_id', $data[$i]->id)->get();
            if( count($oqc_inspec)>0 ){
                $inspect = TSPTSPackingConfirmation::where('lotapp_id', $data[$i]->id)->get();
                if( count($inspect)>0 ){
                    $prelim = TSPTSPreliminaryPackingInspection::where('lotapp_id', $data[$i]->id)->get();
                    if( count($prelim)>0 ){
                        $supervisor = TSPTSSupervisorValidation::where('lotapp_id', $data[$i]->id)->get();
                        if( count($supervisor)>0 ){
                            $f_packing = TSPTSFinalPackingInspection::where('lotapp_id', $data[$i]->id)->get();
                            if( count($f_packing)>0 ){
                                $f_packing_traffic_qc = TSPTSFinalPackingInspectionQC::where('lotapp_id', $data[$i]->id)->get();
                                if( count($f_packing_traffic_qc)>0 ){
                                    // $new[] = $data[$i];

                                    if( $f_packing_traffic_qc[0]->inspector_id!=0 ){
                                        if( isset($new[count($new)-1]->id) ){
                                            $data[$i]->id_first_data = $new[count($new)-1]->id;
                                            $new[] = $data[$i];
                                        }else{
                                            $data[$i]->id_first_data = 0;
                                            $new[] = $data[$i];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }


        return DataTables::of($new)
        ->addColumn('action', function($packing){
            /* For testing Nessa */
            // return '<button type="button" class="btn btn-sm btn-info btn_final_inspection_traffic" lotapp-id="'.$packing->id.'"  data-toggle="modal" data-target="#modalFinalPackingInspection" title="For update"><i class="fa fa-edit"></i></button>';
            // return '<button type="button" class="btn btn-sm btn-info btn_final_inspection_qc" lotapp-id="'.$packing->id.'"  data-toggle="modal" data-target="#modalFinalPackingInspection" title="For update"><i class="fa fa-edit"></i></button>';

            if(count($packing->tspts_finalpackinginspection_info_traffic_qc) > 0)
            {
                $dt = TSPTSFinalPackingInspectionTrfficQC_QC::where('lotapp_id', $packing->id)->get();
                if( count($dt)>0 ){
                    return ' <button type="button" class="btn btn-sm btn-success btn-view-application" data-toggle="modal" data-target="#modalViewApplication" lotapp-id="'.$packing->id.'"><i class="fa fa-eye"></i></button>';
                }
                // return '<button type="button" class="btn btn-sm btn-info btn_final_inspection_qc" data-toggle="modal" data-target="#modalFinalPackingInspection" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                return '<button type="button" class="btn btn-sm btn-info btn_final_inspection_qc" lotapp-id="'.$packing->id.'"  data-toggle="modal" data-target="#modalFinalPackingInspection" title="For update"><i class="fa fa-edit"></i></button>';
            }
            else
            {
                // return '<button type="button" class="btn btn-sm btn-info btn_final_inspection_traffic" data-toggle="modal" data-target="#modalFinalPackingInspection" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                // return '<button type="button" class="btn btn-sm btn-info btn_final_inspection_traffic" lotapp-id="'.$packing->id.'"  data-toggle="modal" data-target="#modalFinalPackingInspection" title="For update"><i class="fa fa-edit"></i></button>';
                if( !in_array(Auth::user()->position, [1, 2]) || !in_array(Auth::user()->user_level_id, [2]) ){
                    if( $packing->id_first_data == 0 ){
                        return '<button type="button" class="btn btn-sm btn-info btn_final_inspection_traffic" lotapp-id="'.$packing->id.'"  data-toggle="modal" data-target="#modalFinalPackingInspection" title="For update"><i class="fa fa-edit"></i></button>';
                    }else{
                        if( count( TSPTSFinalPackingInspectionTrfficQC::where('lotapp_id', $packing->id_first_data)->get() ) == 0 ){
                            return '<button type="button" disabled class="btn btn-sm btn-info btn_final_inspection_traffic" lotapp-id="'.$packing->id.'"  data-toggle="modal" data-target="#modalFinalPackingInspection" title="For update"><i class="fa fa-edit"></i></button>';
                        }else{
                            return '<button type="button" class="btn btn-sm btn-info btn_final_inspection_traffic" lotapp-id="'.$packing->id.'"  data-toggle="modal" data-target="#modalFinalPackingInspection" title="For update"><i class="fa fa-edit"></i></button>';
                        }
                    }
                }else{
                    return '<button type="button" class="btn btn-sm btn-info btn_final_inspection_traffic" lotapp-id="'.$packing->id.'"  data-toggle="modal" data-target="#modalFinalPackingInspection" title="For update"><i class="fa fa-edit"></i></button>';
                }
            }
        })
        ->addColumn('_stat', function($packing){
            if(count($packing->tspts_finalpackinginspection_info_traffic_qc) > 0)
            {
                $dt = TSPTSFinalPackingInspectionTrfficQC_QC::where('lotapp_id', $packing->id)->get();
                if( count($dt)>0 )
                    return 'Done';
                return 'For QC Checking';
            }
            else
            {
                return 'For Traffic Checking';
            }
        })
        ->addColumn('device_code', function($packing){

            if(count($packing->tspts_packingconfirmation_info) > 0)
            {
                if($packing->tspts_packingconfirmation_info[0]->device_code != null)
                {
                    $result = $packing->tspts_packingconfirmation_info[0]->device_code;
                }
                else
                {
                    $result = "No Device Code Assigned!";
                }
            }
            else
            {
                $result = "---";

            }

            return $result;
        })
        ->addColumn('lot_no', function($packing){

            $result = $packing->runcard_no;

            return $result;
        })
        ->addColumn('lot_qty', function($packing){

           $result = 0;

            if(count($packing->prod_runcard_station_many_details) > 0)
            {
                for($i = 0; $i < count($packing->prod_runcard_station_many_details); $i++)
                {
                    $result += $packing->prod_runcard_station_many_details[$i]->qty_output;
                }
            }

            return $result;
        })
        // ->addColumn('packing_operator', function($packing){

        //     $result = "---";

        //     if(count($packing->tspts_finalpackinginspection_info) > 0)
        //     {
        //         $result = $packing->tspts_finalpackinginspection_info[0]->operator_info->name .' '. $packing->tspts_finalpackinginspection_info[0]->inspection_datetime;
        //     }

        //     return $result;
        // })
        ->addColumn('trffic_name', function($packing){

            $result = "---";

            if(count($packing->tspts_finalpackinginspection_info_traffic_qc) > 0)
            {
                $result = $packing->tspts_finalpackinginspection_info_traffic_qc[0]->traffic_info->name;
            }

            return $result;
        })
        ->addColumn('qc_name', function($packing){

            $result = "---";

            if(count($packing->tspts_finalpackinginspection_info_traffic_qc_qc) > 0)
            {
                $result = $packing->tspts_finalpackinginspection_info_traffic_qc_qc[0]->qc_info->name;
            }

            return $result;
        })
        // ->addColumn('oqc_stamp', function($packing){

        //     $result = "---";

        //     if(count($packing->tspts_finalpackinginspection_info_qc) > 0)
        //     {
        //         $result = $packing->tspts_finalpackinginspection_info_qc[0]->inspector_info->oqc_stamp;
        //     }

        //     return $result;
        // })

        ->addColumn('ww', function($packing){

            $result = $packing->oqc_details->ww;

            return $result;
        })

        ->rawColumns(['action'])
        ->make(true);
    }
    public function load_finalpacking_pts_traffic_qc_table_jvs(Request $request)
    {
        $data = ProductionRuncard::with(['prod_runcard_station_many_details' => function($query){
            $query->where('status', 1);
        },'prod_runcard_accessory_info','tspts_oqcvir_info' => function($query){

            $query->where('result', 1);

        }, 'tspts_packingconfirmation_info', 'tspts_packinginspection_info', 'tspts_supervisorvalidation_info', 'tspts_finalpackinginspection_info_qc','tspts_finalpackinginspection_info_qc.inspector_info', 'tspts_finalpackinginspection_info_traffic_qc.traffic_info', 'tspts_finalpackinginspection_info_traffic_qc_qc.qc_info'])->where('po_no', $request->po_num)->where('status',4)->get();

        $new = [];
        $new = [];
        for ($i=0; $i < count($data); $i++) {
            $oqc_inspec = OQCInspection_2::where('prod_runcard_id', $data[$i]->id)->get();
            if( count($oqc_inspec)>0 ){
                $inspect = TSPTSPackingConfirmation::where('lotapp_id', $data[$i]->id)->get();
                if( count($inspect)>0 ){
                    $prelim = TSPTSPreliminaryPackingInspection::where('lotapp_id', $data[$i]->id)->get();
                    if( count($prelim)>0 ){
                        $supervisor = TSPTSSupervisorValidation::where('lotapp_id', $data[$i]->id)->get();
                        if( count($supervisor)>0 ){
                            $f_packing = TSPTSFinalPackingInspection::where('lotapp_id', $data[$i]->id)->get();
                            if( count($f_packing)>0 ){
                                $f_packing_traffic_qc = TSPTSFinalPackingInspectionQC::where('lotapp_id', $data[$i]->id)->get();
                                if( count($f_packing_traffic_qc)>0 ){
                                    // $new[] = $data[$i];
                                    if( $f_packing_traffic_qc[0]->inspector_id!=0 ){
                                        if( isset($new[count($new)-1]->id) ){
                                            $data[$i]->id_first_data = $new[count($new)-1]->id;
                                            $new[] = $data[$i];
                                        }else{
                                            $data[$i]->id_first_data = 0;
                                            $new[] = $data[$i];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }


        return DataTables::of($new)
        ->addColumn('action', function($packing){
            if(count($packing->tspts_finalpackinginspection_info_traffic_qc) > 0)
            {
                $dt = TSPTSFinalPackingInspectionTrfficQC_QC::where('lotapp_id', $packing->id)->get();
                if( count($dt)>0 ){
                    return ' <button type="button" class="btn btn-sm btn-success btn-view-application" data-toggle="modal" data-target="#modalViewApplication" lotapp-id="'.$packing->id.'"><i class="fa fa-eye"></i></button>';
                }
                // return '<button type="button" class="btn btn-sm btn-info btn_final_inspection_qc" data-toggle="modal" data-target="#modalFinalPackingInspection" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                return '<button type="button" class="btn btn-sm btn-info btn_final_inspection_qc" lotapp-id="'.$packing->id.'"  data-toggle="modal" data-target="#modalFinalPackingInspection" title="For update"><i class="fa fa-edit"></i></button>';
            }
            else
            {
                // return '<button type="button" class="btn btn-sm btn-info btn_final_inspection_traffic" data-toggle="modal" data-target="#modalFinalPackingInspection" lotapp-id="'.$packing->id.'" title="For update"><i class="fa fa-edit"></i></button>';
                // return '<button type="button" class="btn btn-sm btn-info btn_final_inspection_traffic" lotapp-id="'.$packing->id.'"  data-toggle="modal" data-target="#modalFinalPackingInspection" title="For update"><i class="fa fa-edit"></i></button>';
                if( !in_array(Auth::user()->position, [1, 2]) || !in_array(Auth::user()->user_level_id, [2]) ){
                    if( $packing->id_first_data == 0 ){
                        return '<button type="button" class="btn btn-sm btn-info btn_final_inspection_traffic" lotapp-id="'.$packing->id.'"  data-toggle="modal" data-target="#modalFinalPackingInspection" title="For update"><i class="fa fa-edit"></i></button>';
                    }else{
                        if( count( TSPTSFinalPackingInspectionTrfficQC::where('lotapp_id', $packing->id_first_data)->get() ) == 0 ){
                            return '<button type="button" disabled class="btn btn-sm btn-info btn_final_inspection_traffic" lotapp-id="'.$packing->id.'"  data-toggle="modal" data-target="#modalFinalPackingInspection" title="For update"><i class="fa fa-edit"></i></button>';
                        }else{
                            return '<button type="button" class="btn btn-sm btn-info btn_final_inspection_traffic" lotapp-id="'.$packing->id.'"  data-toggle="modal" data-target="#modalFinalPackingInspection" title="For update"><i class="fa fa-edit"></i></button>';
                        }
                    }
                }else{
                    return '<button type="button" class="btn btn-sm btn-info btn_final_inspection_traffic" lotapp-id="'.$packing->id.'"  data-toggle="modal" data-target="#modalFinalPackingInspection" title="For update"><i class="fa fa-edit"></i></button>';
                }
            }
        })
        ->addColumn('_stat', function($packing){
            if(count($packing->tspts_finalpackinginspection_info_traffic_qc) > 0)
            {
                $dt = TSPTSFinalPackingInspectionTrfficQC_QC::where('lotapp_id', $packing->id)->get();
                if( count($dt)>0 )
                    return 'Done';
                return 'For QC Checking';
            }
            else
            {
                return 'For Traffic Checking';
            }
        })
        ->addColumn('device_code', function($packing){

            if(count($packing->tspts_packingconfirmation_info) > 0)
            {
                if($packing->tspts_packingconfirmation_info[0]->device_code != null)
                {
                    $result = $packing->tspts_packingconfirmation_info[0]->device_code;
                }
                else
                {
                    $result = "No Device Code Assigned!";
                }
            }
            else
            {
                $result = "---";

            }

            return $result;
        })
        ->addColumn('lot_no', function($packing){

            $result = $packing->runcard_no;

            return $result;
        })
        ->addColumn('lot_qty', function($packing){

           $result = 0;

            if(count($packing->prod_runcard_station_many_details) > 0)
            {
                for($i = 0; $i < count($packing->prod_runcard_station_many_details); $i++)
                {
                    $result += $packing->prod_runcard_station_many_details[$i]->qty_output;
                }
            }

            return $result;
        })
        // ->addColumn('packing_operator', function($packing){

        //     $result = "---";

        //     if(count($packing->tspts_finalpackinginspection_info) > 0)
        //     {
        //         $result = $packing->tspts_finalpackinginspection_info[0]->operator_info->name .' '. $packing->tspts_finalpackinginspection_info[0]->inspection_datetime;
        //     }

        //     return $result;
        // })
        ->addColumn('trffic_name', function($packing){

            $result = "---";

            if(count($packing->tspts_finalpackinginspection_info_traffic_qc) > 0)
            {
                $result = $packing->tspts_finalpackinginspection_info_traffic_qc[0]->traffic_info->name;
            }

            return $result;
        })
        ->addColumn('qc_name', function($packing){

            $result = "---";

            if(count($packing->tspts_finalpackinginspection_info_traffic_qc_qc) > 0)
            {
                $result = $packing->tspts_finalpackinginspection_info_traffic_qc_qc[0]->qc_info->name;
            }

            return $result;
        })
        // ->addColumn('oqc_stamp', function($packing){

        //     $result = "---";

        //     if(count($packing->tspts_finalpackinginspection_info_qc) > 0)
        //     {
        //         $result = $packing->tspts_finalpackinginspection_info_qc[0]->inspector_info->oqc_stamp;
        //     }

        //     return $result;
        // })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function tspts_submit_final_packing_inspection(Request $request)
    {

        // return response()->json([
        //     'packing_id' => $request->packing_id,
        //     'box_no' => $request->box_no,
        //     'po_no' => $request->po_no,
        //     'qty' => $request->qty,
        // ]);

        date_default_timezone_set('Asia/Manila');
         $data = $request->all();

         $validator = '';

        if($request->finalpacking_mode == "oqc"){
            $validator = Validator::make($request->all(), [

                // 'add_packing_operator_name' => 'required',
                //11182021
                // 'add_coc_attachment' => 'required',
                // 'add_result' => 'required',
                // 'add_confirmation_datetime' => 'required', //old
        //- for comment after ng modification
                'add_oqc_inspector_name' => 'required',
             ]);
        }else{

             $validator = Validator::make($request->all(), [

                'add_packing_operator_name' => 'required',
                //11182021
                // 'add_coc_attachment' => 'required',
                // 'add_result' => 'required',
                'add_result1' => 'required',

                // 'add_confirmation_datetime' => 'required', //old
        //- for comment after ng modification
                'add_oqc_inspector_name' => 'required',
             ]);
         }

         if($validator->passes())
         {
            try
            {
                if($request->finalpacking_mode == "oqc"){

                    TSPTSFinalPackingInspectionQC::insert([

                        'lotapp_id' => $request->add_lot_id,
                        'operator_conformance_id' => $request->add_packing_operator_name,

                        //11182021
                        'coc_requirement' => $request->add_coc_attachment,
                        'result' => $request->add_result1,
                        // 'inspection_datetime' => $request->add_confirmation_datetime, //old
                        'inspection_datetime' => date('Y-m-d H:i:s'),
        //- for comment after ng modification
                        'inspector_id' => $request->add_oqc_inspector_name,

                        'remarks' => 'GENERATED BY TSPPTS',

                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'logdel' => 0,
                    ]);

                    // ShipmentSummary::insert([
                    //     'packing_list_id' => $request->packing_id,
                    //     'box_no' => $request->box_no,
                    //     'po_no' => $request->po_no,
                    //     'qty' => $request->qty,
                    // ]);


                }else{

                    TSPTSFinalPackingInspection::insert([

                        'lotapp_id' => $request->add_lot_id,
                        'operator_conformance_id' => $request->add_packing_operator_name,

                        //11182021
                        // 'coc_requirement' => $request->add_coc_attachment,
                        'result' => $request->add_result1,
                        // 'inspection_datetime' => $request->add_confirmation_datetime, //old
                        'inspection_datetime' => date('Y-m-d H:i:s'),
        //- for comment after ng modification
                        'inspector_id' => $request->add_oqc_inspector_name,
                        'remarks' => 'GENERATED BY TSPPTS',

                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'logdel' => 0,
                    ]);

                }

                return response()->json(['result' => 1]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => $e]);
            }
         }
         else
         {
            return response()->json(['result' => 0, 'error' => $validator->messages()]);
         }
    }

    public function tspts_submit_final_packing_inspection_operator_web_edi(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
         $data = $request->all();
         $validator = '';
        $validator = Validator::make($request->all(), [
            'add_packing_operator_name' => 'required'
         ]);
         if($validator->passes())
         {
            try
            {
                TSPTSFinalPackingInspectionQC::insert([
                    'lotapp_id' => $request->add_lot_id,
                    'coc_requirement' => $request->add_coc_attachment,
                    'operator_conformance_id' => $request->add_packing_operator_name,
                    'result' => $request->add_result,
                    'inspector_id' => 0,
                    'inspection_datetime' => date('Y-m-d H:i:s'),
                    'remarks' => 'GENERATED BY TSPPTS',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'logdel' => 0,
                ]);
                return response()->json(['result' => 1]);
            }
            catch(\Exception $e) {
                DB::rollback();
                return response()->json(['result' => $e]);
            }
         }
         else
         {
            return response()->json(['result' => 0, 'error' => $validator->messages()]);
         }
    }

    public function tspts_submit_final_packing_inspection_qc_web_edi(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
         $data = $request->all();
         $validator = '';
        $validator = Validator::make($request->all(), [
            'add_oqc_inspector_name' => 'required'
         ]);
         if($validator->passes())
         {
            try
            {
                $dt = TSPTSFinalPackingInspectionQC::where('lotapp_id', $request->add_lot_id)->get();
                if( count($dt)>0 ){
                    TSPTSFinalPackingInspectionQC::where('id', $dt[0]->id)->update([
                        'inspector_id' => $request->add_oqc_inspector_name
                    ]);
                    return response()->json(['result' => 1]);
                }
                return response()->json(['result' => 'Something went wrong']);
            }
            catch(\Exception $e) {
                DB::rollback();
                return response()->json(['result' => $e]);
            }
         }
         else
         {
            return response()->json(['result' => 0, 'error' => $validator->messages()]);
         }
    }

    public function tspts_submit_final_packing_inspection_qc_conf(Request $request)
    {

        // return response()->json([
        //     'packing_id' => $request->packing_id,
        //     'box_no' => $request->box_no,
        //     'po_no' => $request->po_no,
        //     'qty' => $request->qty,
        // ]);

        date_default_timezone_set('Asia/Manila');
         $data = $request->all();

         $validator = '';

        if($request->finalpacking_mode == "oqc"){
            $validator = Validator::make($request->all(), [

                // 'add_packing_operator_name' => 'required',
                //11182021
                // 'add_coc_attachment' => 'required',
                // 'add_result' => 'required',
                // 'add_confirmation_datetime' => 'required', //old

                'add_oqc_inspector_name' => 'required',
             ]);
        }else{

             $validator = Validator::make($request->all(), [

                // 'add_packing_operator_name' => 'required',
                //11182021
                // 'add_coc_attachment' => 'required',
                // 'add_result' => 'required',
                // 'add_confirmation_datetime' => 'required', //old

                'add_oqc_inspector_name' => 'required',
             ]);
         }

         if($validator->passes())
         {
            try
            {
                if($request->finalpacking_mode == "oqc"){

                    TSPTSFinalPackingInspectionQC::insert([

                        'lotapp_id' => $request->add_lot_id,
                        // 'operator_conformance_id' => $request->add_packing_operator_name,

                        //11182021
                        // 'coc_requirement' => $request->add_coc_attachment,
                        // 'result' => $request->add_result,
                        // 'inspection_datetime' => $request->add_confirmation_datetime, //old
                        'inspection_datetime' => date('Y-m-d H:i:s'),
                        'inspector_id' => $request->add_oqc_inspector_name,

                        'remarks' => 'GENERATED BY TSPPTS',

                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'logdel' => 0,
                    ]);

                    // ShipmentSummary::insert([
                    //     'packing_list_id' => $request->packing_id,
                    //     'box_no' => $request->box_no,
                    //     'po_no' => $request->po_no,
                    //     'qty' => $request->qty,
                    // ]);


                }else{

                    TSPTSFinalPackingInspection::insert([

                        'lotapp_id' => $request->add_lot_id,
                        // 'operator_conformance_id' => $request->add_packing_operator_name,

                        //11182021
                        // 'coc_requirement' => $request->add_coc_attachment,
                        // 'result' => $request->add_result,
                        // 'inspection_datetime' => $request->add_confirmation_datetime, //old
                        'inspection_datetime' => date('Y-m-d H:i:s'),
                        'inspector_id' => $request->add_oqc_inspector_name,
                        'remarks' => 'GENERATED BY TSPPTS',

                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'logdel' => 0,
                    ]);

                }

                return response()->json(['result' => 1]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => $e]);
            }
         }
         else
         {
            return response()->json(['result' => 0, 'error' => $validator->messages()]);
         }
    }

    public function tspts_submit_final_packing_inspection_trffic_qc(Request $request)
    {

        // return response()->json([
        //     'packing_id' => $request->packing_id,
        //     'box_no' => $request->box_no,
        //     'po_no' => $request->po_no,
        //     'qty' => $request->qty,
        //     'wed_edi_id' => $request->wed_edi_id,
        // ]);

        date_default_timezone_set('Asia/Manila');
         $data = $request->all();

         $validator = '';

        if($request->mode == "qc"){
            $validator = Validator::make($request->all(), [

                'add_packing_operator_name2' => 'required',
                //11182021
                'add_coc_attachment' => 'required',
                'add_result' => 'required',
                // 'add_confirmation_datetime' => 'required', //old
                'add_oqc_inspector_name' => 'required',
                'add_accessories' => 'required',
             ]);
        }else{

             $validator = Validator::make($request->all(), [

                'add_packing_operator_name' => 'required',
                //11182021
                // 'add_coc_attachment' => 'required',
                // 'add_result' => 'required',
                // 'add_confirmation_datetime' => 'required', //old
                // 'add_oqc_inspector_name' => 'required',
                'packing_id' => 'required',
             ]);
         }

         // return $request->packing_id;

         if($validator->passes())
         {
            try
            {
                if($request->mode == "qc"){

                    TSPTSFinalPackingInspectionTrfficQC_QC::insert([

                        'lotapp_id' => $request->add_lot_id,
                        // 'operator_conformance_id' => $request->add_packing_operator_name,
                        'trffic_id' => $request->add_packing_operator_name2,
                        'accessories' => $request->add_accessories,
                        //11182021
                        'coc_requirement' => $request->add_coc_attachment,
                        'result' => $request->add_result,
                        // 'inspection_datetime' => $request->add_confirmation_datetime, //old
                        'inspection_datetime' => date('Y-m-d H:i:s'),
                        'qc_id' => $request->add_oqc_inspector_name,
                        'remarks' => 'GENERATED BY TSPPTS',

                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'logdel' => 0,
                    ]);

                    ShipmentSummary::insert([
                        'packing_list_id' => $request->packing_id,
                        'box_no' => $request->box_no,
                        'po_no' => $request->po_no,
                        'qty' => $request->qty,
                        'lot_id' => $request->add_lot_id,
                        'wed_edi_id' => $request->wed_edi_id,
                    ]);


                }else{

                    TSPTSFinalPackingInspectionTrfficQC::insert([

                        'lotapp_id' => $request->add_lot_id,
                        'trffic_id' => $request->add_packing_operator_name,

                        'packing_id' => $request->packing_id,

                        //11182021
                        // 'coc_requirement' => $request->add_coc_attachment,
                        // 'result' => $request->add_result,
                        // 'inspection_datetime' => $request->add_confirmation_datetime, //old
                        'inspection_datetime' => date('Y-m-d H:i:s'),
                        // 'inspector_id' => $request->add_oqc_inspector_name,
                        'remarks' => 'GENERATED BY TSPPTS',

                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'logdel' => 0,
                    ]);

                }

                return response()->json(['result' => 1]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => $e]);
            }
         }
         else
         {
            return response()->json(['result' => 0, 'error' => $validator->messages()]);
         }
    }

    public function final_packing_save_state(Request $request)
    {
        $data = FinalPackingSaveState::where("lot_app_id", $request->lot_app_id)->get();

        if( count($data) > 0 ){
            FinalPackingSaveState::where("lot_app_id", $request->lot_app_id)->update([
                'final_packing' => $request->final_packing,
                'trays' => $request->trays,
                'dlabel_ctr' => $request->dlabel_ctr,
                'casemark_id' => $request->casemark_id,
            ]);
        }else{
            FinalPackingSaveState::insert([
                'lot_app_id' => $request->lot_app_id,
                'final_packing' => $request->final_packing,
                'trays' => $request->trays,
                'dlabel_ctr' => $request->dlabel_ctr,
                'casemark_id' => $request->casemark_id,
            ]);
        }

        // return response()->json([
        //     'lot_app_id' => $request->lot_app_id,
        //     'final_packing' => $request->final_packing,
        //     'trays' => $request->trays,
        //     'dlabel_ctr' => $request->dlabel_ctr,
        //     'casemark_id' => $request->casemark_id,
        // ]);
        return response()->json(['result' => 1]);
    }

    public function load_accessories_pts_table(Request $request)
    {
        $accessories = ProdRuncardAccessory::where('prod_runcard_id', $request->lotapp_id)->where('status', 1)->get();

        return DataTables::of($accessories)
        ->addColumn('accessory_name', function($accessory){

            $result = $accessory->item_desc;


            return $result;
        })
        ->addColumn('quantity', function($accessory){

            $result = $accessory->quantity;


            return $result;
        })
        ->addColumn('result', function($accessory){

            $result = $accessory->id;

            return $result;
        })
        ->make(true);
    }

    public function load_oqcvir_results_table(Request $request)
    {
        $virs = TSPTSOqcVir::where('lotapp_id',$request->lotapp_id)->where('logdel',0)->orderBy('created_at','desc')->get();

        return DataTables::of($virs)
        ->addColumn('action', function($vir){

            $result = '<button type="button" class="btn btn-sm btn-primary btn-edit-inspection" data-toggle="modal" data-target="#modalEditInspection" inspection-id="'.$vir->id.'" title="Edit Inspection"><i class="fa fa-edit"></i></button>';

            return $result;

        })
        ->addColumn('result_raw', function($vir){

            switch($vir->result)
            {
                case 1:
                {
                    $result = "NO DEFECT FOUND";
                    $result = "<span class='badge badge-pill s1 badge-primary'>NO DEFECT FOUND</span>";

                    break;
                }
                case 2:
                {
                    $result = "WITH DEFECT FOUND";
                    $result = "<span class='badge badge-pill s1 badge-danger'>WITH DEFECT FOUND</span>";

                    break;
                }
                default:
                {
                    $result = "---";

                    break;
                }
            }

            return $result;
        })
        ->addColumn('inspection_starttime', function($vir){

            if($vir->inspection_starttime != null)
            {
                $result = $vir->inspection_starttime;
            }
            else
            {
                $result = "---";
            }

            return $result;
        })
        ->addColumn('inspection_datetime', function($vir){

            $result = $vir->inspection_datetime;

            return $result;
        })
        ->addColumn('inspector_id', function($vir){

            $result = $vir->inspector_info->name;

            return $result;
        })
        ->addColumn('oqc_sample', function($vir){

            $result = $vir->ok_qty . " / " . $vir->sample_size;

            return $result;
        })
        ->addColumn('terminal_use', function($vir){

            switch($vir->terminal_use)
            {
                case 1:
                {
                    $result = "YES";

                    break;
                }
                case 2:
                {
                    $result = "NO";

                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        ->addColumn('yd_label', function($vir){

            switch($vir->yd_label)
            {
                case 1:
                {
                    $result = "WITH";

                    break;
                }
                case 2:
                {
                    $result = "WITHOUT";

                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        ->addColumn('csh_coating', function($vir){

            switch($vir->csh_coating)
            {
                case 1:
                {
                    $result = "YES";

                    break;
                }
                case 2:
                {
                    $result = "NO";

                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        ->addColumn('accessory_req', function($vir){

            switch($vir->accessories_requirement)
            {
                case 1:
                {
                    $result = "YES";

                    break;
                }
                case 2:
                {
                    $result = "NO";

                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        ->addColumn('coc_req', function($vir){

            switch($vir->coc_requirement)
            {
                case 1:
                {
                    $result = "YES";

                    break;
                }
                case 2:
                {
                    $result = "NO";

                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        ->make(true);
    }

    public function load_packing_confirmation_pts_results(Request $request)
    {
        $packings = TSPTSPackingConfirmation::with(['operator_info'])->where('lotapp_id',$request->lotapp_id)->where('logdel',0)->orderBy('created_at','desc')->get();

        return DataTables::of($packings)
        ->addColumn('operator', function($packing){

            $result = "";

            $operators = $packing->operator_id;
            $multipleoperators = explode(',', $operators);

             for($i = 0; $i < count($multipleoperators); $i++)
            {
                $user = User::where('id', $multipleoperators[$i])->first();

                if($user != null)
                {
                    $result .=  $user->name . "</br>";
                }
            }

            return $result;
        })

        ->addColumn('confirmation_datetime', function($packing){

            $result = $packing->confirmation_datetime;

            return $result;
        })
        ->addColumn('series_v_label', function($packing){

            switch($packing->series_v_label)
            {
                case 1:
                {
                    $result = "YES";

                    break;
                }
                case 2:
                {
                    $result = "NO";

                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        ->addColumn('label_v_actual', function($packing){

             switch($packing->label_v_actual)
            {
                case 1:
                {
                    $result = "YES";

                    break;
                }
                case 2:
                {
                    $result = "NO";

                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        ->addColumn('silica_gel', function($packing){

            switch($packing->silica_gel)
            {
                case 1:
                {
                    $result = "WITH";

                    break;
                }
                case 2:
                {
                    $result = "WITHOUT";

                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        ->addColumn('yd_label', function($packing){

            switch($packing->yd_label)
            {
                case 1:
                {
                    $result = "YES";

                    break;
                }
                case 2:
                {
                    $result = "NO";

                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        ->rawColumns(['operator'])
        ->make(true);
    }

     public function load_packing_inspection_pts_results(Request $request)
    {
        $packings = TSPTSPreliminaryPackingInspection::with(['inspector_info'])->where('lotapp_id',$request->lotapp_id)->where('logdel',0)->orderBy('created_at','desc')->get();

        return DataTables::of($packings)
        ->addColumn('inspector', function($packing){

            $result = $packing->inspector_info->name;

            return $result;
        })
        ->addColumn('inspection_datetime', function($packing){

            $result = $packing->inspection_datetime;

            return $result;
        })
        ->addColumn('packing_type', function($packing){

            switch($packing->packing_type)
            {
                case 1:
                {
                    $result = "BOX";

                    break;
                }
                case 2:
                {
                    $result = "TRAY";

                    break;
                }
                case 3:
                {
                    $result = "CYLINDER";

                    break;
                }
                case 4:
                {
                    $result = "PALLET CASE";

                    break;
                }
                case 6:
                {
                    $result = "ESAFOAM";

                    break;
                }
                case 7:
                {
                    $result = "POLYBAG";

                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        ->addColumn('unit_condition', function($packing){

            switch($packing->unit_condition)
            {
                case 1:
                {
                    $result = "TERMINAL MOUNTED ON ESAFOAM";

                    break;
                }
                case 2:
                {
                    $result = "TERMINAL DOWN";

                    break;
                }
                case 3:
                {
                    $result = "TERMINAL UP";

                    break;
                }
                case 7:
                {
                    $result = "READABLE";

                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        ->addColumn('series_v_label', function($packing){

            switch($packing->series_v_label)
            {
                case 1:
                {
                    $result = "YES";

                    break;
                }
                case 2:
                {
                    $result = "NO";

                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        ->addColumn('label_v_actual', function($packing){

             switch($packing->label_v_actual)
            {
                case 1:
                {
                    $result = "YES";

                    break;
                }
                case 2:
                {
                    $result = "NO";

                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        ->addColumn('silica_gel', function($packing){

            switch($packing->silica_gel)
            {
                case 1:
                {
                    $result = "WITH";

                    break;
                }
                case 2:
                {
                    $result = "WITHOUT";

                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        ->addColumn('yd_label', function($packing){

             switch($packing->yd_label)
            {
                case 1:
                {
                    $result = "YES";

                    break;
                }
                case 2:
                {
                    $result = "NO";

                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        ->addColumn('supervisor_conformance', function($packing){

             switch($packing->supervisor_conformance)
            {
                case 1:
                {
                    $result = "YES";

                    break;
                }
                case 2:
                {
                    $result = "NO";

                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        ->make(true);
    }

    public function load_supervisor_validation_pts_results(Request $request)
    {
        $packings = TSPTSSupervisorValidation::with(['supervisor_info'])->where('lotapp_id',$request->lotapp_id)->where('logdel',0)->orderBy('created_at','desc')->get();

        return DataTables::of($packings)
        ->addColumn('supervisor', function($packing){

            if( isset($packing->supervisor_info->name) )
                return $packing->supervisor_info->name;

            return "---";
        })
        ->addColumn('validation_datetime', function($packing){

            $result = $packing->validation_datetime;

            return $result;
        })
        ->addColumn('series_v_label', function($packing){

            switch($packing->series_v_label)
            {
                case 1:
                {
                    $result = "YES";

                    break;
                }
                case 2:
                {
                    $result = "NO";

                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        ->addColumn('label_v_actual', function($packing){

             switch($packing->label_v_actual)
            {
                case 1:
                {
                    $result = "YES";

                    break;
                }
                case 2:
                {
                    $result = "NO";

                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        ->addColumn('label_correctness', function($packing){

             switch($packing->label_correctness)
            {
                case 0:
                {
                    $result = "---";

                    break;
                }
                case 1:
                {
                    $result = "YES";

                    break;
                }
                case 2:
                {
                    $result = "NO";

                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        ->make(true);
    }

    public function load_final_inspection_pts_results(Request $request)
    {
        $packings = TSPTSFinalPackingInspection::with(['operator_info','inspector_info'])->where('lotapp_id',$request->lotapp_id)->where('logdel',0)->orderBy('created_at','desc')->get();

        return DataTables::of($packings)
        ->addColumn('inspector', function($packing){

            $result = $packing->inspector_info->name;

            return $result;
        })
        ->addColumn('inspection_datetime', function($packing){

            $result = $packing->inspection_datetime;

            return $result;
        })
        ->addColumn('result', function($packing){

            switch($packing->result)
            {
                case 1:
                {
                    $result = "OK";
                    $result = "<span class='badge badge-pill s1 badge-success'>OK</span>";
                    break;
                }
                case 2:
                {
                    $result = "NG";
                    $result = "<span class='badge badge-pill s1 badge-danger'>NG</span>";
                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        // ->addColumn('coc_attachment', function($packing){

        //      switch($packing->coc_attachment)
        //     {
        //         case 1:
        //         {
        //             $result = "YES";

        //             break;
        //         }
        //         case 2:
        //         {
        //             $result = "NO";

        //             break;
        //         }
        //         default:
        //         {
        //             $result = "N/A";

        //             break;
        //         }
        //     }

        //     return $result;
        // })
        ->addColumn('operator_conformance', function($packing){

            $result = $packing->operator_info->name;

            return $result;
        })
        // ->addColumn('oqc_inspector', function($packing){

        //     $result = $packing->operator_info->name;

        //     return $result;
        // })
        ->rawColumns(['action', 'result'])
        ->make(true);
    }

    public function load_final_inspection_pts_qc_results(Request $request)
    {
        $packings = TSPTSFinalPackingInspectionQC::with(['inspector_info'],['operator_info'])->where('lotapp_id',$request->lotapp_id)->where('logdel',0)->orderBy('created_at','desc')->get();

        return DataTables::of($packings)
        ->addColumn('operator', function($packing){

            $result = $packing->operator_info->name;

            return $result;
        })
        //- for comment after ng modification
        ->addColumn('inspector', function($packing){

            $result = $packing->inspector_info->name;

            return $result;
        })
        ->addColumn('inspection_datetime', function($packing){

            $result = $packing->inspection_datetime;

            return $result;
        })
        ->addColumn('result', function($packing){

            switch($packing->result)
            {
                case 1:
                {
                    $result = "NO DEFECT FOUND";
                    $result = "<span class='badge badge-pill s1 badge-primary'>NO DEFECT FOUND</span>";
                    break;
                }
                case 2:
                {
                    $result = "WITH DEFECT FOUND";
                    $result = "<span class='badge badge-pill s1 badge-danger'>WITH DEFECT FOUND</span>";
                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        ->addColumn('coc_attachment', function($packing){

             switch($packing->coc_attachment)
            {
                case 1:
                {
                    $result = "YES";

                    break;
                }
                case 2:
                {
                    $result = "NO";

                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        // ->addColumn('operator_conformance', function($packing){

        //     $result = $packing->operator_info->name;

        //     return $result;
        // })
        // ->addColumn('oqc_inspector', function($packing){

        //     $result = $packing->operator_info->name;

        //     return $result;
        // })
        ->rawColumns(['action', 'result'])
        ->make(true);
    }

    public function load_final_inspection_pts_qc_results_traffic_qc(Request $request)
    {
        $packings = TSPTSFinalPackingInspectionTrfficQC_QC::with(['qc_info'])->where('lotapp_id',$request->lotapp_id)->where('logdel',0)->orderBy('created_at','desc')->get();

        return DataTables::of($packings)
        ->addColumn('trffic_name', function($packing){

            $result = $packing->qc_info->name;

            return $result;
        })
        ->addColumn('qc_name', function($packing){

            $result = $packing->qc_info->name;

            return $result;
        })
        ->addColumn('inspection_datetime', function($packing){

            $result = $packing->inspection_datetime;

            return $result;
        })
        ->addColumn('result', function($packing){

            switch($packing->result)
            {
                case 1:
                {
                    $result = "NO DEFECT FOUND";
                    $result = "<span class='badge badge-pill s1 badge-primary'>NO DEFECT FOUND</span>";
                    break;
                }
                case 2:
                {
                    $result = "WITH DEFECT FOUND";
                    $result = "<span class='badge badge-pill s1 badge-danger'>WITH DEFECT FOUND</span>";
                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        ->addColumn('coc_attachment', function($packing){

             switch($packing->coc_requirement)
            {
                case 1:
                {
                    $result = "YES";

                    break;
                }
                case 2:
                {
                    $result = "NO";

                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        ->addColumn('accessories', function($packing){

             switch($packing->coc_requirement)
            {
                case 1:
                {
                    $result = "YES";

                    break;
                }
                case 2:
                {
                    $result = "NO";

                    break;
                }
                default:
                {
                    $result = "N/A";

                    break;
                }
            }

            return $result;
        })
        // ->addColumn('operator_conformance', function($packing){

        //     $result = $packing->operator_info->name;

        //     return $result;
        // })
        // ->addColumn('oqc_inspector', function($packing){

        //     $result = $packing->operator_info->name;

        //     return $result;
        // })
        ->rawColumns(['action', 'result'])
        ->make(true);
    }


    public function load_runcards_tspts_table(Request $request)
    {

    // return $request->lotapp_id;

      $runcards = ProductionRuncardStation::with(['ct_area_info', 'terminal_area_info','tspts_oqcvir_info' => function($query){

            $query->orderBy('created_at','desc');
        //deleted_at //lot
       }, 'tspts_oqcvir_info.inspector_info'])->where('production_runcard_id', $request->lotapp_id)->where('status',1)->get();

    //    return $runcards;
    //   return $oqc = explode("-", $runcards[0]->tspts_oqcvir_info[0]->oqc_stamp);
    //   return $oqc = $runcards[0]->tspts_oqcvir_info[0];
       $array_batch = [];

       if($request->array_batch != null)
       {
            $array_batch = $request->array_batch;
       }

       // return User::where('employee_id', 'k010')->get();

       for ($i=0; $i < count($runcards); $i++) {
        // return $runcards[$i]->tspts_oqcvir_info[0]->employee_id;
            // if($runcards[$i]->tspts_oqcvir_info[0]->inspector_info_2 == null)
            if( count($runcards[$i]->tspts_oqcvir_info)>0 )
                // return 'qwe';
                $runcards[$i]->tspts_oqcvir_info[0]->inspector_info_2 = User::where('employee_id', $runcards[$i]->tspts_oqcvir_info[0]->employee_id)->get();
       }

       return DataTables::of($runcards)

        ->addColumn('packing_code', function($runcard){

            $result = "FOR GENERATION";

            if(count($runcard->tspts_oqcvir_info) > 0)
            {
                if($runcard->tspts_oqcvir_info[0]->result == 1)
                {
                    if($runcard->tspts_oqcvir_info[0]->inspector_info_2[0] != null)
                    {
                        if($runcard->tspts_oqcvir_info[0]->oqc_stamp != null)
                        {
                            //year and month of created at
                            $yearmonth = $runcard->created_at->format('ym');

                            //ct area and terminal area
                            $ct_area = $runcard->ct_area_info;
                            $terminal_area = $runcard->terminal_area_info;

                            //generated fvi no
                            $fvi_no = '';

                            if($ct_area != null && $terminal_area != null)
                            {

                                if($ct_area->fvi_no != null && $terminal_area->fvi_no != null)
                                {

                                    if($ct_area->fvi_no < 100 && $terminal_area->fvi_no < 100)
                                    {
                                        if($ct_area->fvi_no != $terminal_area->fvi_no)
                                        {
                                            $fvi_no = $terminal_area->fvi_no . $ct_area->fvi_no;
                                        }
                                        else
                                        {
                                            $fvi_no = str_pad($ct_area->fvi_no, 4, "0", STR_PAD_LEFT);
                                        }

                                    }
                                    else
                                    {
                                        if($ct_area->fvi_no != null)
                                        {
                                            $fvi_no = str_pad($ct_area->fvi_no, 4, "0", STR_PAD_LEFT);
                                        }
                                        else
                                        {
                                            $fvi_no = 'XXCT';
                                        }
                                    }

                                }
                                else
                                {
                                    $fvi_no = 'TECT';
                                }

                            }
                            else
                            {

                                if($ct_area->fvi_no != null)
                                {
                                    $fvi_no = str_pad($ct_area->fvi_no, 4, "0", STR_PAD_LEFT);

                                }
                                else
                                {
                                    $fvi_no = 'XXXX';
                                }
                            }

                            //oqc inspector stamp
                            // $oqc = explode("-", $runcard->tspts_oqcvir_info[0]->oqc_stamp)[1];
                            return 'ok';
                        }
                        else
                        {
                             $result = "OQC STAMP NOT YET REGISTERED";
                        }
                    }
                    else
                    {
                        $result = "INSPECTOR DETAILS NOT YET ADDED";
                    }
                }
                else
                {
                    $result = "NG RESULT";
                }
            }
            else
            {
                $result = "FOR GENERATION";
            }

            return $result;
        })
        ->addColumn('runcard_no', function($runcard){


            $result = $runcard->runcard_no;

            return $result;
        })
        ->addColumn('ct_area', function($runcard){


            $result = "---";

            if($runcard->ct_area_info != null)
            {
                 $result = $runcard->ct_area_info->name;
            }

            return $result;
        })
        ->addColumn('terminal_area', function($runcard){


            $result = "---";

            if($runcard->terminal_area_info != null)
            {
                 $result = $runcard->terminal_area_info->name;
            }

            return $result;
        })
        ->addColumn('output_qty', function($runcard){


            $result = $runcard->qty_output;

            return $result;
        })
        // ->rawColumns(['action_batch','action'])
        ->make(true);
    }

    public function load_vir_inspection_details(Request $request)
    {
        $inspection_details = TSPTSOqcVir::with(['lotapp_info'])->where('id',$request->inspection_id)->where('logdel',0)->get();

        if(count($inspection_details) > 0)
        {
            $total_lot_qty = ProductionRuncardStation::where('production_runcard_id', $inspection_details[0]->lotapp_id)->sum('qty_output');

            return response()->json(['result' => 1, 'inspection_details' => $inspection_details, 'total_lot_qty' => $total_lot_qty]);
        }
        else
        {
            return response()->json(['result' => 2]);
        }
    }
    // public function clear_cache(){
    //     Cache::forget('oqcvir');
    // }
    public function tspts_submit_oqc_inspection_edit(Request $request)
    {
         date_default_timezone_set('Asia/Manila');
         $data = $request->all();

         $validator = '';

         $validator = Validator::make($request->all(), [

            'edit_oqc_sample_size' => 'required',
            'edit_ok_qty' => 'required',
            'edit_inspection_starttime' => 'required',
            'edit_inspection_datetime' => 'required',
            'edit_terminal' => 'required',
            'edit_yd_label' => 'required',
            'edit_csh_coating' => 'required',
            'edit_accessories_requirement' => 'required',
            'edit_coc_requirement' => 'required',
            'edit_oqc_inspector_name' => 'required',

         ]);

         if($validator->passes())
         {
            try
            {
                // Cache::forget('oqcvir'); //modify by Migz 04-08-25
                TSPTSOqcVir::where('id', $request->edit_inspection_id)->update([

                    'sample_size' => $request->edit_oqc_sample_size,
                    'ok_qty' => $request->edit_ok_qty,
                    'terminal_use' => $request->edit_terminal,
                    'yd_label' => $request->edit_yd_label,
                    'csh_coating' => $request->edit_csh_coating,
                    'accessories_requirement' => $request->edit_accessories_requirement,
                    'coc_requirement' => $request->edit_coc_requirement,
                    'inspector_id' => $request->edit_oqc_inspector_name,
                    'inspection_datetime' => $request->edit_inspection_starttime,
                    'inspection_datetime' => $request->edit_inspection_datetime,
                    'remarks' => $request->edit_remarks,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'logdel' => 0,
                ]);

                return response()->json(['result' => 1]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => $e]);
            }
         }
         else
         {
            return response()->json(['result' => 0, 'error' => $validator->messages()]);
         }
    }

    public function get_drawingno(Request $request){
        date_default_timezone_set('Asia/Manila');
        $drawing_no = ProductionRuncard::where('id', $request->lotapp_id)
                                        ->first();

        return response()->json(['drawing_no' => $drawing_no]);
    }

    public function get_finalpacking_result_by_id(Request $request){
        // return 'true';
        // if( !in_array(Auth::user()->position, [1, 2]) || !in_array(Auth::user()->user_level_id, [1, 2]) ){
        //     $dt = TSPTSSupervisorValidation::where('lotapp_id', $request['id'])->get();
        //     if( count($dt)>0 ){
        //         if( $dt[0]->pmi_blue_packing_lbl_print != null ){
        //             if( $dt[0]->lotapp_id != 1472 )
        //                 return response()->json(['permission' => 0]);
        //         }
        //     }
        // }
        //TODO: Solve this erorr
        // Inactive / Status 2 - still appeared in prod runcard station qty -
        // runcard_id : 45140
        // station_id : 297781 | 297819
        $ins_result_by_id = ProductionRuncard::with(['tspts_finalpackinginspection_info','prod_runcard_station_many_details' => function($query){
            $query->where('status', 1);
        },
            'wbs_kitting',
            'wbs_kitting.device_info'
        ])
        ->where('id',$request['id'])
        ->get();

        // return $ins_result_by_id;


        if(is_null($ins_result_by_id[0]->wbs_kitting)){
            // return 'wala';
            $ins_result_by_id = ProductionRuncard::with([
                'yeu_kitting',
                'tspts_finalpackinginspection_info',
                'prod_runcard_station_many_details' => function($query){ // Add this to filter out passed stations - Chris 06/26/2025
                    return $query->where('status', 1);
                }
            ])
            ->where('id',$request['id'])
            // ->whereHas('prod_runcard_station_many_details',function($query) use ($request){
            //         $query->where('status',1);
            // })
            ->get();

            // return $ins_result_by_id;


            $device_name_print = 'not found';

            $device_name_print = CommonController::getInstance()->validate_device_name($ins_result_by_id[0]['yeu_kitting']);

            $device_name_print = $ins_result_by_id[0]['yeu_kitting']->product_name;

        }else{
            // return 'meron';
            $device_name_print = $ins_result_by_id[0]['wbs_kitting']->device_name;
            //TODO: MIGZ 09-04-24 Remove Burn-in & Test, if the Device Name in WBS Issuance & Kitting
            //TODO: NOTE: Burn in Memory is include only in searching Series Name in Packing
            $device_name_print = CommonController::getInstance()->validate_device_name_acdcs_packing($device_name_print);
        }

        // return $device_name_print;



        // $device_name_print = 'not found';
        // if(isset($ins_result_by_id[0]['wbs_kitting'])){
        //     $device_name_print = $ins_result_by_id[0]['wbs_kitting']->device_name;
        //     //TODO: MIGZ 09-04-24 Remove Burn-in & Test, if the Device Name in WBS Issuance & Kitting
        //     //TODO: NOTE: Burn in Memory is include only in searching Series Name in Packing
        //     $device_name_print = CommonController::getInstance()->validate_device_name_acdcs_packing($device_name_print);
        // }

        $ww = "N/A";
        if( count($ins_result_by_id)>0 ){
            $dt = oqcLotApp::where('fkid_runcard' ,$request['id'])->get();
            if( count($dt)>0 ){
                $ww = $dt[0]->ww;
            }
        }

        $lot_qty = 0;

        if(count($ins_result_by_id[0]['prod_runcard_station_many_details']) > 0)
        {
            for($i = 0; $i < count($ins_result_by_id[0]['prod_runcard_station_many_details']); $i++)
            {
                $lot_qty += $ins_result_by_id[0]['prod_runcard_station_many_details'][$i]->qty_output;
            }
        }

        $QrCode = '';
        if($ins_result_by_id->count() > 0){

//             if( $ww == "N/A" ){
//         $QrCode = QrCode::format('png')->size(200)->errorCorrection('H')->generate(
//             $ins_result_by_id[0]->po_no. '
// ' . "\n" . $device_name_print . '
// ' . "\n" . $lot_qty . '
// ' . "\n" . $ins_result_by_id[0]->lot_no
// );
//             }else{
            $QrCode = '';
            //Invalid PMI PACKING LABEL Details, Please check!
            $arr_qr_detail = [
                'oqc_lotapp_po_no' => $ins_result_by_id[0]->po_no,
                'device_name' => $device_name_print,
                'lot_qty' => $lot_qty,
                'lot_no' => $ins_result_by_id[0]->lot_no,
                'ww' => $ww
            ];
            $obj_qr_detail = json_encode($arr_qr_detail);
            $qrcode = QrCode::format('png')
            ->size(250)->errorCorrection('H')
            ->generate($obj_qr_detail);
            $QrCode = "data:image/png;base64," . base64_encode($qrcode);
        }
        // return response()->json(['ins_result_by_id' => $ins_result_by_id, 'QrCode' => $QrCode, 'lot_qty' => $lot_qty,'device_name_print' => $device_name_print,'ww' => $ww, 'permission' => 1]);
        return response()->json([
            'arr_qr_detail' => $arr_qr_detail,
            'ins_result_by_id' => $ins_result_by_id,
             'QrCode' => $QrCode, 'lot_qty' => $lot_qty,
             'device_name_print' => $device_name_print,
             'ww' => $ww, 'permission' => 1
        ]);
    }

    public function setPMIBluePackingLabelPrint(Request $request)
    {
        TSPTSSupervisorValidation::where('lotapp_id', $request['id'])
            ->update([
                'pmi_blue_packing_lbl_print' => 1
            ]);
        return response()->json(['result' => 1]);
    }

    public function getPONoDetails(Request $request)
    {
        return response()->json(['details' => MaterialIssuanceSubSystem::where('po_no', $request->po_no)->get() ]);
    }

    public function load_packinglist_scanned_summary(Request $request)
    {

        $data = ProductionRuncard::where('po_no', $request->po_no)->get();

        $packing_id = 0;

        $data = PackingListDetails::where('po', $request->po_no)->get();

        if( count($data) > 0 )
            $packing_id = $data[0]->packing_id;

        $data1 = PackingList::where('id', $packing_id)->get();
        $data1 = ShipmentSummary::select('*', DB::raw('SUM(qty) AS ttl_qty'))->where('po_no', $request->po_no)->groupBy('packing_list_id')->groupBy('po_no')->groupBy('box_no')->get();
        for ($i=0; $i < count($data1); $i++) {
            $data1[$i]->packing_dtls = PackingList::where('id', $data1[$i]->packing_list_id)->get();
            if( count($data1[$i]->packing_dtls) > 0 ){
                $data1[$i]->packing = PackingListDetails::where('packing_id', $data1[$i]->packing_dtls[0]->id)
                    ->where('po', $data1[$i]->po_no)
                    ->where('box_no', $data1[$i]->box_no)
                    ->get();
            }
        }
        return DataTables::of($data1)
        ->addColumn('balance', function($data1){
            if( isset($data1->packing) && count($data1->packing) > 0 ){
                $result = $data1->packing[0]->qty;
                $sum = 0;
                $summary = ShipmentSummary::where('packing_list_id', $data1->packing_list_id)->where('po_no', $data1->po_no)->where('box_no', $data1->box_no)->get();
                for ($i=0; $i < count($summary); $i++) {
                    $sum += $summary[$i]->qty;
                }
                if( ($result - $sum) == 0 )
                    return '<span class="badge badge-pill badge-success"> Completed</span>'; //No Balance
                return '<span class="badge badge-pill badge-warning">' . ($result - $sum) . ' left</span>';
            }
            else
                return "---";
        })
        ->addColumn('ctrl_no', function($data1){
            if( count($data1->packing_dtls) > 0 )
                return $data1->packing_dtls[0]->control_no;
            else
                return "---";
        })
        ->addColumn('dvc_name', function($data1){
            if( isset($data1->packing) && count($data1->packing) > 0 )
                return $data1->packing[0]->description;
            else
                return "---";
        })
        ->addColumn('shipment_qty', function($data1){
            if( isset($data1->packing) && count($data1->packing) > 0 )
                return $data1->packing[0]->qty;
            else
                return "---";
        })



        // $id = 0;
        // $data = PackingList::where('control_no', $request->packing_list_ctrl_no)->get();
        // if( count($data) > 0 )
        //     $id = $data[0]->id;

        // $data = PackingListDetails::where('packing_id', $id)->get();

        // return DataTables::of($data)
        // ->addColumn('balance', function($data){
        //     $result = $data->qty;
        //     $sum = 0;
        //     $summary = ShipmentSummary::where('packing_list_id', $data->packing_id)->where('po_no', $data->po)->where('box_no', $data->box_no)->get();
        //     for ($i=0; $i < count($summary); $i++) {
        //         $sum += $summary[$i]->qty;
        //     }
        //     return $result - $sum;
        // })


        // // ->addColumn('control_no', function($data){
        // //     $result = $control_no;
        // //     return $result;
        // // })


        // ->with('comments', 20)

        ->rawColumns(['balance'])

        ->make(true);
    }

    public function add_final_packing_details_qr(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'PONo' => ['required', 'string', 'max:255'],
            'DeviceName' => ['required', 'string', 'max:255'],
            'LotQty' => ['required', 'string', 'max:255'],
            'LotNumber' => ['required', 'string', 'max:255'],
            // 'ww' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                FPDetailsQRCode::insert([
                    'PONo' => $request->PONo,
                    'DeviceName' => $request->DeviceName,
                    'LotQty' => $request->LotQty,
                    'LotNumber' => $request->LotNumber,
                    'ww' => $request->ww,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                DB::commit();

                return response()->json(['result' => "1"]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => $e]);
            }
        }
    }

    public function view_final_packing_details_qr(){
        $FPDetails_QRCodes = FPDetailsQRCode::all();

        return DataTables::of($FPDetails_QRCodes)
        ->addColumn('action', function($FPDetails_QRCode){
            $result = ' <button type="button" class="btn btn-sm btn-primary EditFPDetails_QRCode" data-toggle="modal" data-target="#modalEditFPDetailsQRCode" edit-id="'.$FPDetails_QRCode->id.'"><i class="fa fa-pen"></i></button>';
            $result .= ' <button type="button" class="btn btn-sm btn-success btnPrintFinalQRCode" data-toggle="modal" data-target="#modal_Final_Packing_QRcode" id="'.$FPDetails_QRCode->id.'"><i class="fa fa-print"></i></button>';

                return $result;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function get_finalpackingdetails_result_by_id(Request $request){
        $fpqr = FPDetailsQRCode::where('id', $request->id)->get();

        $QrCode = '';
        if($fpqr->count() > 0){
        $QrCode = QrCode::format('png')->size(200)->errorCorrection('H')->generate(
            $fpqr[0]->PONo. '
' . $fpqr[0]->DeviceName. '
' . $fpqr[0]->LotQty. '
' . $fpqr[0]->LotNumber. '
' .$fpqr[0]->ww
);

            $QrCode = "data:image/png;base64," . base64_encode($QrCode);
        }

        return response()->json(['fpqr' => $fpqr, 'QrCode' => $QrCode]);
    }

    public function get_FPDetailsQRCode_by_id(Request $request){
        $fpqr = FPDetailsQRCode::where('id', $request->FPDetailsQRCodeId)->get();
        return response()->json(['fpqr' => $fpqr]);
    }

    public function edit_FPDetailsQRCode(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        // return $data;
        $validator = Validator::make($data, [
            'PONo' => ['required', 'string', 'max:255'],
            'DeviceName' => ['required', 'string', 'max:255'],
            'LotQty' => ['required', 'string', 'max:255'],
            'LotNumber' => ['required', 'string', 'max:255'],
            // 'ww' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                FPDetailsQRCode::where('id', $request->FPDetailsQRCode_id)->update([
                    'PONo' => $request->PONo,
                    'DeviceName' => $request->DeviceName,
                    'LotQty' => $request->LotQty,
                    'LotNumber' => $request->LotNumber,
                    'ww' => $request->ww,
                    'update_version' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                DB::commit();

                return response()->json(['result' => "1"]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => "0"]);
            }
        }
    }

    public function get_yeu_details(Request $request){

        // return $request['po'];

        $yeuDetails = YeuKitting::with([
            // 'wbs_kitting',
            // // 'prod_runcard_station_many_details' => function($query){
            // //     $query->orderBy('step_num', 'desc');
            // //     $query->where('status', 1);
            // //     // $query->first();
            // //     // $query->limit(1);
            // // },
            // 'prod_runcard_station_many_details' => function($query){
            //     // $query->where('has_emboss', '!=', 1);
            //     $query->where('status', 1);
            //     $query->orderBy(\DB::raw('CONVERT(SUBSTRING_INDEX(step_num,"-", 1), UNSIGNED INTEGER)', 'ASC'));
            //     $query->orderBy(\DB::raw('right(step_num,LOCATE("-",step_num) - 1)', 'ASC'));
            //     // $query->limit(1);
            // },
        ])
        ->where('po_no', $request['po'])
        ->where('product_name', '<>', NULL)
        ->orderBy('id', 'ASC')
        ->first();

        // $yeuDetails = ProductionRuncard::with([
        //     'yeu_kitting',
        //     'tspts_finalpackinginspection_info',
        //     'prod_runcard_station_many_details'
        // ])
        // ->where('po_no',$request['po'])
        // ->get();

        return $yeuDetails;

        return response()->json(['data' => $yeuDetails]);
    }


}
