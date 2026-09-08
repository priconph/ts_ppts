<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Model\OqcLotappNew;
use App\Model\OqcLotappFvo;
use App\Model\OqcLotappRuncards;

use App\Model\ProductionRuncard;
use App\Model\ProductionRuncardStation;

use App\Model\DefectEscalation;
use App\Model\DEStations;

use App\Model\Device;

use App\User;

use QrCode;

use DataTables;


class OqcLotappNewController extends Controller
{
    public function submit_lot_application(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $data = $request->all();

        $validator = Validator::make($request->all(), [

            'lotapp_datetime' => 'required',
            'lotapp_device_type' => 'required',
            'lotapp_assembly_line' => 'required',
            'lotapp_applied_by' => 'required',
            'lotapp_lot_qty' => 'required|numeric|min:0|not_in:0',
        ]);

        if($validator->passes())
        {
            //oqclotapp id generation
             $oqclotapp_count = 0;
             $oqclotapp_details = OqcLotappNew::where('po_num',$request->lotapp_po_num)->where('logdel',0)->get();

             if(count($oqclotapp_details) > 0)
             {
                $oqclotapp_count = count($oqclotapp_details) + 1;
             }
             else
             {
                $oqclotapp_count = 1;
             }

             $oqclotapp_po = substr($request->lotapp_po_num, 0, 10);
             $oqclotapp_id = $oqclotapp_po . '-OQC-' . str_pad($oqclotapp_count, 3, 0, STR_PAD_LEFT);


             //array runcards
             $runcards = json_decode($request->array_runcard);

              $user_details = User::where('id',$request->lotapp_applied_by)->where('status',1)->get();

             if(count($user_details) > 0)
             {
                 try
                 {
                    OqcLotappNew::insert([

                        'po_num' => $request->lotapp_po_num,
                        'oqc_lotapp_id' => $oqclotapp_id,
                        'created_by' => $user_details[0]->id,
                        'device_type' => $request->lotapp_device_type,
                        'assembly_line_id' => $request->lotapp_assembly_line,
                        'application_datetime' => $request->lotapp_datetime,
                        'applied_by' => $user_details[0]->id,
                        'status' => 2,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'logdel' => 0

                    ]);

                    for($i = 0; $i < count($runcards); $i++)
                    {
                        OqcLotappRuncards::insert([

                            'oqc_lotapp_id' => $oqclotapp_id,
                            'item_type' => 1,
                            'runcard_no' => $runcards[$i]->runcard_id,
                            'output_qty' => $runcards[$i]->output_qty,
                            'applied_by' => $user_details[0]->id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                            'logdel' => 0

                        ]);
                    }

                    DB::commit();

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
                return response()->json(['result' => 2]);
             }
        }
        else
        {
            return response()->json(['result' => 0, 'error' => $validator->messages()]);
        }
    }




    public function add_additional_runcard(Request $request)
    {
        $runcard_details = ProductionRuncard::with(['prod_runcard_station_many_details' => function($query){

            $query->where('status',1)->orderBy('step_num','desc')->limit(1);

        }])->where('po_no',$request->po_num)->where('runcard_no', $request->runcard_no)->where('status', 3)->get();

        $oqclotapp_details = OqcLotappRuncards::where('oqc_lotapp_id', $request->oqc_lotapp_id)->where('logdel',0)->orderBy('updated_at','desc')->get();

        if(count($runcard_details) > 0)
        {

            $oqclotapp_checker = OqcLotappRuncards::where('runcard_no', $runcard_details[0]->id)->where('logdel',0)->count();

            if($oqclotapp_checker > 0)
            {
                return response()->json(['result' => 3]);
            }
            else
            {
               /* try
                {*/
                    OqcLotappRuncards::insert([

                        'item_type' => 1,
                        'oqc_lotapp_id' => $request->oqc_lotapp_id,
                        'for_submission' => 1,
                        'applied_by' => $oqclotapp_details[0]->applied_by,
                        'runcard_no' => $runcard_details[0]->id,
                        'output_qty' => $runcard_details[0]->prod_runcard_station_many_details[0]->qty_output,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'logdel' => 0

                    ]);

                    return response()->json(['result' => 1]);
                /*}
                 catch(\Exception $e) {
                    DB::rollback();
                    // throw $e;
                    return response()->json(['result' => $e]);
                }*/
            }
        }
        else
        {
            return response()->json(['result' => 2]);
        }

    }

    public function search_user_details(Request $request)
    {
        $user_details = User::where('employee_id',$request->employee_id)->where('status', 1)->get();

        if(count($user_details) > 0)
        {
            return response()->json(['result' => 1, 'user_details' => $user_details]);
        }
        else
        {
            return response()->json(['result' => 2]);
        }
    }

    public function retrieve_lotapp_details(Request $request)
    {
        $lotapp_details = OqcLotappNew::where('id',$request->oqc_lotapp_id)->where('logdel',0)->get();

        //RUNCARDS-----------------------------------------------
        $runcards = OqcLotappRuncards::with(['runcard_details','runcard_details.prod_runcard_station_many_details' => function($query){

            $query->where('status',1)->orderBy('step_num','desc');

        }])->where('oqc_lotapp_id',$lotapp_details[0]->oqc_lotapp_id)->where('item_type',1)->where('logdel',0)->get();

        //REWORKS-----------------------------------------------
        $reworks = OqcLotappRuncards::with(['rework_details','rework_details.defect_escalation_station_many_details' => function($query){

            $query->where('status',1)->orderBy('step_num','desc');

        }])->where('oqc_lotapp_id',$lotapp_details[0]->oqc_lotapp_id)->where('item_type',2)->where('logdel',0)->get();

        $lotapp_quantity = $runcards->merge($reworks);

        //return $lotapp_quantity;

        $lotapp_total_quantity = 0;

        if(count($lotapp_quantity) > 0)
        {
            for($i = 0; $i < count($lotapp_quantity); $i++)
            {
                switch($lotapp_quantity[$i]->item_type)
                {
                    case 1:
                    {
                        $lotapp_total_quantity += $lotapp_quantity[$i]->runcard_details->prod_runcard_station_many_details[0]->qty_output;
                        break;
                    }
                    case 2:
                    {
                        $lotapp_total_quantity += $lotapp_quantity[$i]->rework_details->defect_escalation_station_many_details[0]->qty_good;
                        break;
                    }
                    default:
                    {
                         $lotapp_total_quantity += 0;
                        break;
                    }
                }
            }
        }
        else
        {
             $lotapp_total_quantity = 0;
        }

        //return $lotapp_total_quantity;

        if(count($lotapp_details) > 0)
        {
            return response()->json(['result' => 1, 'lotapp_details' => $lotapp_details, 'lotapp_quantity' => $lotapp_total_quantity]);
        }
        else
        {
            return response()->json(['result' => 2]);
        }
    }

    public function load_single_application_table(Request $request)
    {
        $applications = OqcLotappNew::with(['applied_by_details','self_details','assembly_line_details','fvo_details','fvo_details.fvo_details'])->where('id', $request->application_id)->orderBy('created_at','desc')->get();

        return DataTables::of($applications)
        ->addCOlumn('submission', function($application){

            switch($application->submission)
            {
                case 1:
                {
                    $result = '<span class="badge badge-pill badge-success">1st Submission</span>';

                    break;
                }
                case 2:
                {
                    $result = '<span class="badge badge-pill badge-warning">2nd Submission</span>';

                    break;
                }
                case 3:
                {
                    $result = '<span class="badge badge-pill badge-danger">3rd Submission</span>';

                    break;
                }
                default:
                {
                    $result = '<span class="badge badge-pill badge-secondary">---</span>';

                    break;
                }
            }

            return $result;

        })
        ->addCOlumn('lotapp', function($application){

            $result = $application->oqc_lotapp_id;

            return $result;

        })
        ->addCOlumn('total_lot_qty', function($application){

            $result = OqcLotappRuncards::where('oqc_lotapp_id', $application->oqc_lotapp_id)->where('logdel',0)->sum('output_qty');

            return $result;

        })
        ->addCOlumn('device_type', function($application){

            $result = '';

            switch($application->device_type)
            {
                case 1:
                {
                    $result = '<span class="badge badge-pill badge-success">Automotive</span>';

                    break;
                }
                case 2:
                {
                    $result = '<span class="badge badge-pill badge-info">Regular</span>';

                    break;
                }
                default:
                {
                    $result = '<span class="badge badge-pill badge-secondary">---</span>';

                    break;
                }
            }

            return $result;

        })
        ->addCOlumn('assembly_line', function($application){

            if($application->assembly_line_details != null)
            {
                 $result = $application->assembly_line_details->name;
            }
            else
            {
                $result = "---";
            }



            return $result;

        })
        ->addCOlumn('fvo', function($application){

            $result = '';

            if($application->fvo_details != null)
            {
                for($i = 0; $i < count($application->fvo_details); $i++)
                {
                    $result .= '<span class="badge badge-pill badge-info">'.$application->fvo_details[$i]->fvo_details->name.'</span><br>';
                }
            }
            else
            {
                $result = '---';
            }

            return $result;

        })
         ->addCOlumn('application_datetime', function($application){

            $result = $application->application_datetime;

            return $result;

        })
          ->addCOlumn('applied_by', function($application){

            if($application->applied_by_details != null)
            {
                $result = '<span class="badge badge-pill badge-success">'.$application->applied_by_details->name.'</span>';
            }
            else
            {
                $result = "---";
            }

            return $result;

        })
        ->rawColumns(['submission','device_type','fvo','applied_by'])
        ->make(true);
    }

    public function load_oqclotapp_new_table(Request $request)
    {
        $distinct = OqcLotappNew::orderBy('submission','desc')->get();
        $distinct = $distinct->unique('oqc_lotapp_id')->pluck('id');

        $lotapps = OqcLotappNew::with(['applied_by_details','self_details'])->where('po_num',$request->po_num)->where('logdel',0)->whereIn('id',$distinct)->orderBy('created_at','desc')->get();

        return DataTables::of($lotapps)
        ->addColumn('action',function($lotapp){

            $result = '';

            switch($lotapp->status)
            {
                case 1:
                {
                    $result .= '<button type="button" class="px-2 py-1 btn btn-sm btn-info btn-oqclotapp" title="Apply Lot" data-toggle="modal" data-target="#modalOqcLotApplication" oqclotapp-id='.$lotapp->id.'><i class="fa fa-edit fa-sm"></i></button>';

                    break;
                }
                case 2:
                {
                    $result .= ' <button type="button" class="px-2 py-1 btn btn-sm btn-warning  btn-generate-sticker" title="Print QR Code" data-toggle="modal" data-target="#modal_LotApp_QRcode" oqclotapp-id='.$lotapp->id.'><i class="fa fa-print fa-sm"></i></button>';

                    break;
                }
                case 3:
                {
                     $result .= ' <button type="button" class="px-2 py-1 btn btn-sm btn-primary btn-other-submission" data-toggle="modal" data-target="#modalLotAppOtherSubmission" title="Submit Other Submission" oqclotapp-id='.$lotapp->id.'><i class="fa fa-tags"></i></button>';

                     $result .= ' <button type="button" class="px-2 py-1 btn btn-sm btn-warning  btn-generate-sticker" title="Print QR Code" data-toggle="modal" data-target="#modal_LotApp_QRcode" oqclotapp-id='.$lotapp->id.'><i class="fa fa-print fa-sm"></i></button>';


                    break;
                }
                case 4:
                {
                     $result .= ' <button type="button" class="px-2 py-1 btn btn-sm btn-warning  btn-generate-sticker" title="Print QR Code" data-toggle="modal" data-target="#modal_LotApp_QRcode" oqclotapp-id='.$lotapp->id.'><i class="fa fa-print fa-sm"></i></button>';
                    break;
                }
                case 5:
                {
                      $result .= ' <button type="button" class="px-2 py-1 btn btn-sm btn-warning  btn-generate-sticker" title="Print QR Code" data-toggle="modal" data-target="#modal_LotApp_QRcode" oqclotapp-id='.$lotapp->id.'><i class="fa fa-print fa-sm"></i></button>';
                    break;
                }
                default:
                {


                    $result = "";

                    break;
                }
            }

             $result .= ' <button type="button" class="px-2 py-1 btn btn-sm btn-info view-lot-application" title="View OQC Lot Application" data-toggle="modal" data-target="#modalViewApplication" oqclotapp-id='.$lotapp->id.'><i class="fa fa-eye fa-sm"></i></button>';

            return $result;
        })
        ->addColumn('oqclotapp_id',function($lotapp){

            $result = $lotapp->oqc_lotapp_id;

            return $result;

        })
         ->addColumn('created_at',function($lotapp){

            $result = $lotapp->created_at;

            return $result;

        })
        ->addColumn('total_lot_qty',function($lotapp){

            $result = OqcLotappRuncards::where('oqc_lotapp_id', $lotapp->oqc_lotapp_id)->where('logdel',0)->sum('output_qty');

            return $result;
        })
        ->addColumn('applied_by',function($lotapp){

            if($lotapp->applied_by != null)
            {
                $result = $lotapp->applied_by_details->name;
            }
            else
            {
                $result = "---";
            }

            return $result;
        })
        ->addColumn('status',function($lotapp){

            $result = '';

            switch($lotapp->status)
            {
                case 1:
                {
                    $result = '<span class="badge badge-pill badge-info">Runcards Added, For Application</span>';

                    break;
                }
                case 2:
                {
                    $result = '<span class="badge badge-pill badge-success">Lot Applied!</span>';
                    $result .= '<br><span class="badge badge-pill badge-primary">1st Submission</span>';

                    break;
                }
                case 3:
                {
                    $result = '<span class="badge badge-pill badge-danger">Returned by OQC VIR</span>';

                    break;
                }
                case 4:
                {
                    $result = '<span class="badge badge-pill badge-success">2nd Sub Applied</span>';

                    break;
                }
                case 5:
                {
                    $result = '<span class="badge badge-pill badge-success">Lots Accepted!</span>';

                    break;
                }
                case 6:
                {
                    $result = '<span class="badge badge-pill badge-danger">Lots Rejected/span>';

                    break;
                }
                default:
                {
                    $result = "";

                    break;
                }
            }

            return $result;
        })
        ->rawColumns(['action','status'])
        ->make(true);
    }

    public function load_oqclotapp_runcards(Request $request)
    {
        if(isset($request->array_remove_runcards))
        {
            $array_remove_runcards = $request->array_remove_runcards;
        }
        else
        {
            $array_remove_runcards = [];
        }

        //RUNCARDS-----------------------------------------------
        $runcards = OqcLotappRuncards::with(['runcard_details','runcard_details.prod_runcard_station_many_details' => function($query){

            $query->where('status',1)->orderBy('step_num','desc');

        },

        'runcard_details.prod_runcard_station_many_details.ct_area_info',
         'runcard_details.prod_runcard_station_many_details.terminal_area_info',

         'oqclotapp_details.oqcvir_details' => function($oqcvir){

                $oqcvir->orderBy('created_at','desc');

            },

    ])->where('oqc_lotapp_id',$request->oqclotapp_id)->where('item_type',1)->where('logdel',0)->get();

        //REWORKS-----------------------------------------------
        $reworks = OqcLotappRuncards::with(['rework_details','rework_details.defect_escalation_station_many_details' => function($query){

            $query->where('status',1)->orderBy('step_num','desc');

        }])->where('oqc_lotapp_id',$request->oqclotapp_id)->where('item_type',2)->where('logdel',0)->get();

        $merged = $runcards->merge($reworks);

        return DataTables::of($merged)
        ->addColumn('print_action',function($runcard){

            $result = '';
            $result2 = '';

            if(count($runcard->oqclotapp_details->oqcvir_details) > 0)
            {
                $result2 = $runcard->created_at->format('ym') . " ";

                $ct_area = $runcard->runcard_details->prod_runcard_station_many_details[0]->ct_area_info;

                $terminal_area = $runcard->runcard_details->prod_runcard_station_many_details[0]->terminal_area_info;

                if($ct_area != null && $terminal_area != null)
                {
                    if($ct_area->fvi_no < 100 && $terminal_area->fvi_no < 100)
                    {
                        $result2 .= $terminal_area->fvi_no . $ct_area->fvi_no;
                    }
                    else
                    {
                        $result2 .= str_pad($ct_area->fvi_no, 4, "0", STR_PAD_LEFT);
                    }
                }
                else
                {
                     $result2 .= str_pad($ct_area->fvi_no, 4, "0", STR_PAD_LEFT);
                }

                if($runcard->oqclotapp_details->oqcvir_details[0]->inspector_details->oqc_stamp != null)
                {
                    $result2 .= "-". explode("-", $runcard->oqclotapp_details->oqcvir_details[0]->inspector_details->oqc_stamp)[1];
                }
                else
                {
                    $result2 .= "-XXX";
                }

                $result = '<button type="button" class="px-2 py-1 btn btn-sm btn-info generate-packing-code-qr" title="Generate Packing Code" data-toggle="modal" data-target="#modalGenUserBarcode"runcard-no='.$runcard->runcard_details->runcard_no.' packing-code="'.$result2.'"><i class="fa fa-qrcode fa-sm"></i></button>';

            }
            else
            {
                $result = '';
            }

            return $result;

        })
        ->addColumn('fvi_ct',function($runcard){

            switch($runcard->item_type)
            {
                case 1:
                {
                    if($runcard->runcard_details->prod_runcard_station_many_details[0]->ct_area != null)
                    {
                          $result = $runcard->runcard_details->prod_runcard_station_many_details[0]->ct_area_info->name;
                    }
                    else
                    {
                        $result = "---";
                    }


                    break;
                }
                case 2:
                {
                    $result = $runcard->rework_details->defect_escalation_no;
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
        ->addColumn('fvi_terminal',function($runcard){

            switch($runcard->item_type)
            {
                case 1:
                {
                    if($runcard->runcard_details->prod_runcard_station_many_details[0]->terminal_area != null)
                    {
                          $result = $runcard->runcard_details->prod_runcard_station_many_details[0]->terminal_area_info->name;
                    }
                    else
                    {
                        $result = "---";
                    }

                    break;
                }
                case 2:
                {
                    $result = $runcard->rework_details->defect_escalation_no;
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
        ->addColumn('runcard_id',function($runcard){

            switch($runcard->item_type)
            {
                case 1:
                {
                    $result = $runcard->runcard_details->runcard_no;
                    break;
                }
                case 2:
                {
                    $result = $runcard->rework_details->defect_escalation_no;
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
        ->addColumn('item_type',function($runcard)
        {
            switch($runcard->item_type)
            {
                case 1:
                {
                    $result = "Production Runcard";
                    break;
                }
                case 2:
                {
                    $result = "Rework / DE";
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
        ->addColumn('lot_qty',function($runcard){

            switch($runcard->item_type)
            {
                case 1:
                {
                    $result = $runcard->runcard_details->prod_runcard_station_many_details[0]->qty_output;
                    break;
                }
                case 2:
                {
                    $result = $runcard->rework_details->defect_escalation_station_many_details[0]->qty_good;
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
        ->addColumn('remarks',function($runcard){

            switch($runcard->item_type)
            {
                case 1:
                {
                    $result = $runcard->runcard_details->remarks;
                    break;
                }
                case 2:
                {
                    $result = $runcard->rework_details->remarks;
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
        ->addColumn('from_submission',function($runcard){

            switch($runcard->for_submission)
            {
                case 1:
                {
                    $result = "1st Submission";
                    break;
                }
                case 2:
                {
                    $result = "2nd Submission";
                    break;
                }
                 case 3:
                {
                    $result = "3rd Submission";
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
        ->addColumn('created_at',function($runcard){

            switch($runcard->item_type)
            {
                case 1:
                {
                    $result = $runcard->runcard_details->created_at;
                    break;
                }
                case 2:
                {
                    $result = $runcard->rework_details->created_at;
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

        //add this for edit status and edit action
        ->addColumn('edit_status',function($runcard) use ($array_remove_runcards){

            if(in_array($runcard->id, $array_remove_runcards))
            {
                $result = "<strong style='color: red;'>For Removal</strong>";
            }
            else
            {
                $result = '';
            }

            return $result;

        })

        ->addColumn('edit_action',function($runcard) use ($array_remove_runcards){

            if(in_array($runcard->id, $array_remove_runcards))
            {
                $result = '<button type="button" class="btn btn-sm btn-warning btn-cancel-remove-runcard" runcard-id="'.$runcard->id.'" title="Cancel Remove"><i class="fa fa-retweet"></i></button>';
            }
            else
            {
                 $result = '<button type="button" class="btn btn-sm btn-danger btn-edit-remove-runcard" runcard-id="'.$runcard->id.'" title="Remove Runcard"><i class="fa fa-times"></i></button>';
            }

            return $result;

        })

         //add this for edit status and edit action (SUB)
        ->addColumn('edit_status_sub',function($runcard) use ($array_remove_runcards){

            if(in_array($runcard->id, $array_remove_runcards))
            {
                $result = "<strong style='color: red;'>For Removal</strong>";
            }
            else
            {
                $result = '';
            }

            return $result;

        })

        ->addColumn('edit_action_sub',function($runcard) use ($array_remove_runcards){

            if(in_array($runcard->id, $array_remove_runcards))
            {
                $result = '<button type="button" class="btn btn-sm btn-warning btn-cancel-remove-runcard-sub" runcard-id="'.$runcard->id.'" title="Cancel Remove"><i class="fa fa-retweet"></i></button>';
            }
            else
            {
                 $result = '<button type="button" class="btn btn-sm btn-danger btn-edit-remove-runcard-sub" runcard-id="'.$runcard->id.'" title="Remove Runcard"><i class="fa fa-times"></i></button>';
            }

            return $result;

        })

        ->rawColumns(['edit_status','edit_action','edit_status_sub','edit_action_sub','print_action'])
        ->make(true);
    }

    public function load_oqclotapp_fvo(Request $request)
    {
        $fvos = OqcLotappFvo::with(['fvo_details'])->where('oqc_lotapp_id',$request->oqclotapp_id)->where('logdel',0)->get();

        return DataTables::of($fvos)
        ->addColumn('fvo_employee_id', function($fvo)
        {
            $result = $fvo->fvo_details->employee_id;

            return $result;
        })
        ->addColumn('fvo_name', function($fvo)
        {
            $result = $fvo->fvo_details->name;

            return $result;
        })
        ->make(true);
    }

    public function load_oqlotapp_history(Request $request)
    {
        $histories = OqcLotappNew::with(['fvo_details'])->where('oqc_lotapp_id', $request->oqclotapp_id)->where('logdel',0)->get();

        return DataTables::of($histories)
        ->addColumn('submission', function($history){


            switch($history->submission)
            {
                case 1:
                {
                    $result = '<span class="badge badge-pill badge-success">1st Submission</span>';

                    break;
                }
                case 2:
                {
                    $result = '<span class="badge badge-pill badge-warning">2nd Submission</span>';

                    break;
                }
                case 3:
                {
                    $result = '<span class="badge badge-pill badge-danger">3rd Submission</span>';

                    break;
                }
                default:
                {
                     $result = '<span class="badge badge-pill badge-error">---</span>';

                    break;
                }
            }

            return $result;
        })
        ->addColumn('application_datetime',function($history){

            $result = $history->created_at;

            return $result;
        })
        ->addColumn('fvo',function($history){

            $result = '';

            for($i = 0; $i < count($history->fvo_details); $i++)
            {
                $result .= '<span class="badge badge-pill badge-info">' . $history->fvo_details[$i]->fvo_details->name . ' (' . $history->fvo_details[$i]->fvo_details->employee_id . ')</span>&nbsp;';
            }

            return $result;
        })
        ->rawColumns(['submission','fvo'])
        ->make(true);
    }

    public function get_runcard_details(Request $request)
    {
    	//real status should be 3
    	// $runcard_details = ProductionRuncard::with(['prod_runcard_station_many_details' => function($query){

    	// 	$query->where('status',1)->orderBy('step_num','desc')->limit(1);

    	// }])->where('po_no',$request->po_num)->where('runcard_no', $request->runcard_no)->where('status', 3)->get();
            // return 'get_runcard_details';
        $runcard_details = ProductionRuncard::with([
            'oqc_details' => function($query){
                $query->orderBy('submission', 'DESC');},
            'oqc_details.oqcvir_details',
            'oqc_details.user_details',
            'oqc_details.supervisor_prod_info',
            'oqc_details.supervisor_qc_info',
            'wbs_kitting',
            'prod_runcard_station_many_details' => function($query){
                $query
                // $query->where('has_emboss', '!=', 1)
                ->orderBy(\DB::raw('CONVERT(SUBSTRING_INDEX(step_num,"-", 1), UNSIGNED INTEGER)', 'ASC'))
                ->orderBy(\DB::raw('right(step_num,LOCATE("-",step_num) - 1)', 'ASC'));
            },
            'wbs_kitting.device_info',
            'yeu_kitting' // added 7/15/24 darren

        ])
        ->where('id',$request['id'])
        ->get();

        // return $runcard_details;

        //- sub lot no.
        $sub_lot_no = 0;
        if($runcard_details){

            $runcard_no = ($runcard_details[0]->runcard_no);
            $newstring = substr($runcard_no, -3);

            // added 7/15/24 darren
            if($runcard_details[0]->wbs_kitting == null ){
                $device_name = $runcard_details[0]->yeu_kitting->product_name;
            }else{
                $device_name = $runcard_details[0]->wbs_kitting->device_name;
            }

            // return $device_name;

            //TODO: MIGZ 09-04-24 Remove Burn-in & Test, if the Device Name in WBS Issuance & Kitting
            // $device_name = ($runcard_details[0]->wbs_kitting->device_name);
            $wbs_device_name = CommonController::getInstance()->validate_device_name($device_name);
            $devices = Device::where('status', 1)->where('name', $wbs_device_name)->get()->take(1); // add condition for status 1-Active
            // return $runcard_details[0]->wbs_kitting->device_name;
            $ctr = 1;
            if ( $devices ){

                $i_total = 1000;
                $q = $devices[0]->ship_boxing / $devices[0]->boxing;

                for($i=$q;$i<$i_total; $i=$i+$q){
                    if( $newstring <= $i ){
                        break;
                    }
                    $ctr++;
                }
            }
            $sub_lot_no = $ctr;
        }

        $runcard_details[0]->{'sub_lot_no'} = $sub_lot_no;
        $device = Device::where('name', $wbs_device_name)
        ->orderBy('id', 'desc')
        ->get();
        $runcard_details[0]->lot_qty = $device[0]->ship_boxing;
        $result = ProductionRuncardStation::where('production_runcard_id', $runcard_details[0]->id)->where('status', 1)->get();
        $ttl = 0;
        for ($i=0; $i < count($result); $i++)
            $ttl = $ttl + $result[$i]->qty_output;
        $runcard_details[0]->output_qty = $ttl;

        // return $runcard_details;

    	if(count($runcard_details) > 0)
    	{
    		return response()->json(['result' => 1, 'runcard_details' => $runcard_details]);
    	}
    	else
    	{
    		return response()->json(['result' => 2]);
    	}
    }

    public function get_rework_details(Request $request)
    {
        $rework_details = DefectEscalation::with(['defect_escalation_station_many_details' => function($query){

            $query->where('status',1)->orderBy('step_num','desc');

        }])->where('defect_escalation_no', $request->rework_no)->get();

        if(count($rework_details) > 0)
        {
            return response()->json(['result' => 1, 'rework_details' => $rework_details]);
        }
        else
        {
            return response()->json(['result' => 2]);
        }
    }

    public function load_oqclotapp_additional_runcards(Request $request)
    {
        if($request->array_add_runcards == null)
        {
            $runcards = [];
        }
        else
        {
            $runcards = $request->array_add_runcards;
        }

         return DataTables::of($runcards)
        ->addColumn('runcard_no',function($runcard){

            $result = $runcard['runcard_no'];

            return $result;

        })
        ->addColumn('output_qty',function($runcard){

            $result = $runcard['output_qty'];

            return $result;

        })
        ->addColumn('action',function($runcard){

            $result = '<button type="button" class="px-2 py-1 btn btn-sm btn-danger btn-remove-additional-runcard" title="Remove item from Lot Application" runcard-id='.$runcard['runcard_id'].'><i class="fa fa-times fa-sm"></i></button>';

            return $result;
        })
         ->addColumn('action_sub',function($runcard){

            $result = '<button type="button" class="px-2 py-1 btn btn-sm btn-danger btn-remove-additional-runcard-sub" title="Remove item from Lot Application" runcard-id='.$runcard['runcard_id'].'><i class="fa fa-times fa-sm"></i></button>';

            return $result;
        })
        ->rawColumns(['action','action_sub'])
        ->make(true);
    }

    public function load_oqclotapp_runcards_from_array(Request $request)
    {
        if($request->arrRuncards == null)
        {
            $runcards = [];
        }
        else
        {
            $runcards = $request->arrRuncards;
        }

        return DataTables::of($runcards)
        ->addColumn('runcard_no',function($runcard){

            $result = $runcard['runcard_no'];

            return $result;

        })
        ->addColumn('type',function($runcard){

            $result = '';

            switch($runcard['type'])
            {
                case 1:
                {
                    $result = "Runcard";
                    break;
                }
                case 2:
                {
                    $result = "Rework/DE";
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
        ->addColumn('output_qty',function($runcard){

            $result = $runcard['output_qty'];

            return $result;

        })
        ->addColumn('fvi_ct',function($runcard){

            if($runcard['fvi_ct'] != null)
            {
                $fvi_ct = User::where('id', $runcard['fvi_ct'])->get();
                $result = $fvi_ct[0]->name;
            }
            else
            {
                $result = "---";
            }

            return $result;

        })
        ->addColumn('fvi_terminal',function($runcard){

            if($runcard['fvi_terminal'] != null)
            {
                $fvi_terminal = User::where('id', $runcard['fvi_terminal'])->get();
                $result = $fvi_terminal[0]->name;
            }
            else
            {
                $result = "---";
            }

            return $result;

        })
        ->addColumn('action',function($runcard){

            $result = '<button type="button" class="px-2 py-1 btn btn-sm btn-danger btn-remove-array-runcard" title="Remove item from Lot Application" runcard-id='.$runcard['runcard_id'].'><i class="fa fa-times fa-sm"></i></button>';

            return $result;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function load_oqclotapp_fvo_from_array(Request $request)
    {
        if($request->arrFvo == null)
        {
            $fvos = [];
        }
        else
        {
            $fvos = $request->arrFvo;
        }

        return DataTables::of($fvos)
        ->addColumn('fvo_employee_id',function($fvo){

            $result = $fvo['fvo_employee_id'];

            return $result;

        })
        ->addColumn('fvo_name',function($fvo){

            $result = $fvo['fvo_name'];

            return $result;

        })
        ->addColumn('action',function($fvo){

            $result = '<button type="button" class="px-2 py-1 btn btn-sm btn-danger btn-remove-array-fvo" title="Remove FVO from List" fvo-user-id='.$fvo['fvo_user_id'].'><i class="fa fa-times fa-sm"></i></button>';

            return $result;
        })
        ->addColumn('action_sub',function($fvo){

            $result = '<button type="button" class="px-2 py-1 btn btn-sm btn-danger btn-remove-array-fvo-sub" title="Remove FVO from List" fvo-user-id='.$fvo['fvo_user_id'].'><i class="fa fa-times fa-sm"></i></button>';

            return $result;
        })
        ->rawColumns(['action','action_sub'])
        ->make(true);
    }

    public function load_device_details(Request $request)
    {
        //TODO: MIGZ 09-04-24 Remove Burn-in & Test, if the Device Name in WBS Issuance & Kitting
        $device_name = $request->current_series;
        $device_name = CommonController::getInstance()->validate_device_name($device_name);
        $device_details = Device::where('name',$device_name)->where('status',1)->get();

        if(count($device_details) > 0)
        {
            return response()->json(['result' => 1, 'device_details' => $device_details]);
        }
        else
        {
            return response()->json(['result' => 2]);
        }
    }

    public function submit_oqc_runcards(Request $request)
    {
         date_default_timezone_set('Asia/Manila');
         $data = $request->all();
         DB::beginTransaction();

         //--------------------------GENERATE OQC LOT APP ID-------------------------------

         $oqclotapp_count = 0;
         $oqclotapp_details = OqcLotappNew::where('po_num',$request->current_po_num)->where('logdel',0)->get();

         if(count($oqclotapp_details) > 0)
         {
            $oqclotapp_count = count($oqclotapp_details) + 1;
         }
         else
         {
            $oqclotapp_count = 1;
         }

         $oqclotapp_po = substr($request->current_po_num, 0, 10);
         $oqclotapp_id = $oqclotapp_po . '-OQC-' . str_pad($oqclotapp_count, 3, 0, STR_PAD_LEFT);

         //---------------------------ARRAY RUNCARDS--------------------------------------------

         $runcards = json_decode($request->array_runcard);

         //--------------------------VALIDATE ID AND SAVE DETAILS-------------------------------

         $user_details = User::where('employee_id',$request->txt_employee_id)->where('status',1)->get();

         if(count($user_details) > 0)
         {
             try
             {
                OqcLotappNew::insert([

                    'po_num' => $request->current_po_num,
                    'oqc_lotapp_id' => $oqclotapp_id,
                    'created_by' => $user_details[0]->id,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'logdel' => 0

                ]);

                for($i = 0; $i < count($runcards); $i++)
                {
                    OqcLotappRuncards::insert([

                        'oqc_lotapp_id' => $oqclotapp_id,
                        'item_type' => $runcards[$i]->type,
                        'runcard_no' => $runcards[$i]->runcard_id,
                        'output_qty' => $runcards[$i]->output_qty,
                        'applied_by' => $user_details[0]->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'logdel' => 0

                    ]);
                }

                DB::commit();

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
            return response()->json(['result' => 2]);
         }
    }

    public function submit_oqc_lot_application(Request $request)
    {
         date_default_timezone_set('Asia/Manila');
         $data = $request->all();
         DB::beginTransaction();

         $applied_by = User::where('employee_id', $request->txt_applied_by_id)->where('status',1)->get();

         $fvos = json_decode($request->array_fvo);

         if(count($applied_by) > 0)
         {
            try
            {
                OqcLotappNew::where('oqc_lotapp_id',$request->lotapp_id)->update([

                    'device_type' => $request->lotapp_device_type,
                    'assembly_line_id' => $request->lotapp_assembly_line,
                    'applied_by' => $applied_by[0]->id,
                    'application_datetime' => $request->lotapp_datetime,
                    'status' => 2,
                    'updated_at' => date('Y-m-d H:i:s'),

                ]);

                for($i = 0; $i < count($fvos); $i++)
                {
                    OqcLotappFvo::insert([
                        'oqc_lotapp_id' => $request->lotapp_id,
                        'fvo_user_id' => $fvos[$i]->fvo_user_id,
                        'applied_by' => $applied_by[0]->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'logdel' => 0

                    ]);
                }

                DB::commit();

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
            return response()->json(['result' => 2]);
         }
    }

    public function load_qr_code_details(Request $request)
    {
        $po_num = QrCode::format('png')->size(200)->errorCorrection('H')->generate($request->po_num);

        $po_num = "data:image/png;base64," . base64_encode($po_num);

        $lotapp_id = QrCode::format('png')->size(200)->errorCorrection('H')->generate($request->lotapp_id);

        $lotapp_id = "data:image/png;base64," . base64_encode($lotapp_id);

        return response()->json(['result' => 1, 'po_num' => $po_num, 'lotapp_id' => $lotapp_id]);
    }

    public function edit_add_runcard(Request $request)
    {

        //get runcard_details
        $runcard_details = ProductionRuncard::with(['prod_runcard_station_many_details' => function($query){

            $query->where('status',1)->orderBy('step_num','desc')->limit(1);

        }])->where('po_no',$request->po_num)->where('runcard_no', $request->runcard_no)->where('status', 3)->get();

        if(count($runcard_details) > 0)
        {
            //get lotapp_details
             $oqclotapp_details = OqcLotappRuncards::where('runcard_no',$runcard_details[0]->id)->where('logdel',0)->get();

            if(count($oqclotapp_details) > 0)
            {
                return response()->json(['result' => 2]);
            }
            else
            {
                return response()->json(['result' => 1, 'runcard_details' => $runcard_details]);
            }
        }
        else
        {
            return response()->json(['result' => 3]);
        }
    }

    public function edit_add_rework(Request $request)
    {

        //get rework_details
       $rework_details = OqcLotappRuncards::with(['rework_details','rework_details.defect_escalation_station_many_details' => function($query){

            $query->where('status',1)->orderBy('step_num','desc');

        }])->where('po_no',$request->po_num)->where('defect_escalation_no', $request->rework_no)->get();


        if(count($rework_details) > 0)
        {
            //get lotapp_details
             $oqclotapp_details = OqcLotappRuncards::where('runcard_no',$rework_details[0]->id)->where('logdel',0)->get();

            if(count($oqclotapp_details) > 0)
            {
                return response()->json(['result' => 2]);
            }
            else
            {
                return response()->json(['result' => 1, 'rework_details' => $rework_details]);
            }
        }
        else
        {
            return response()->json(['result' => 3]);
        }
    }

    public function save_runcard_changes(Request $request)
    {
         date_default_timezone_set('Asia/Manila');
         $data = $request->all();

         $edited_by = User::where('employee_id', $request->emp_no)->where('status',1)->get();

         $array_remove_runcards = $request->array_runcard_remove;
         $array_add_runcards = $request->array_runcard_add;

         try
         {
            if(count($edited_by) > 0)
             {
                if(isset($request->array_runcard_remove))
                {
                    //step 1, remove all the existing by changing logdel = 1
                    for($i = 0; $i < count($array_remove_runcards); $i++)
                    {
                        OqcLotappRuncards::where('id',$array_remove_runcards[$i])->update([

                            'logdel' => 1,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'applied_by' => $edited_by[0]->id
                        ]);
                    }
                }

                if(isset($request->array_runcard_add))
                {
                    //step 2, add new runcards
                    for($x = 0; $x < count($array_add_runcards); $x++)
                    {
                        OqcLotappRuncards::insert([

                            'oqc_lotapp_id' => $request->oqc_lotapp_id,
                            'runcard_no' => $array_add_runcards[$x]['runcard_id'],
                            'item_type' => $array_add_runcards[$x]['type'],
                            'output_qty' => $array_add_runcards[$x]['output_qty'],
                            'applied_by' => $edited_by[0]->id,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'created_at' => date('Y-m-d H:i:s'),
                            'logdel' => 0
                        ]);
                    }
                }

                return response()->json(['result' => 1]);
             }
             else
             {
                return response()->json(['result' => 2]);
             }
         }
         catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => $e]);
            }

    }

    public function submit_oqc_lot_application_sub(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
         $data = $request->all();
         DB::beginTransaction();

         $get_status = OqcLotappNew::where('oqc_lotapp_id',$request->lotapp_id_sub)->get();

         //return $get_status;

         if($get_status[0]->status == 3)
         {
            $status = 4;
         }
         else if($get_status[0]->status == 6)
         {
            $status = 7;
         }

         $applied_by = User::where('employee_id', $request->txt_applied_by_id_sub)->where('status',1)->get();

         $fvos = json_decode($request->array_fvo);

         if(count($applied_by) > 0)
         {
            try
            {
                OqcLotappNew::insert([

                    'oqc_lotapp_id' => $request->lotapp_id_sub,
                    'po_num' => $request->lotapp_po_num_sub,
                    'device_type' => $request->lotapp_device_type_sub,
                    'assembly_line_id' => $request->lotapp_assembly_line_sub,
                    'applied_by' => $applied_by[0]->id,
                    'application_datetime' => $request->lotapp_datetime_sub,
                    'submission' => $request->lotapp_submission,
                    'status' => $status,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_by' => $applied_by[0]->id,
                    'logdel' => 0
                ]);

                for($i = 0; $i < count($fvos); $i++)
                {
                    OqcLotappFvo::insert([
                        'oqc_lotapp_id' => $request->lotapp_id_sub,
                        'fvo_user_id' => $fvos[$i]->fvo_user_id,
                        'applied_by' => $applied_by[0]->id,
                        'for_submission' => $fvos[$i]->submission,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'logdel' => 0
                    ]);
                }

                DB::commit();

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
            return response()->json(['result' => 2]);
         }
    }




    //BULK RUNCARDS FUNCTION------------------------------
    public function load_bulk_runcards(Request $request)
    {
        $array_runcards = [];

        if(isset($request->array_runcards))
        {
            for($i = 0; $i < count($request->array_runcards); $i++)
            {
                if($request->array_runcards[$i]['type'] == 1)
                {
                    $array_runcards[] = $request->array_runcards[$i]['runcard_id'];
                }
            }
        }

        $oqc_runcards = OqcLotappRuncards::where('logdel',0)->where('item_type',1)->pluck('runcard_no')->toArray();

        $runcards = ProductionRuncard::with(['prod_runcard_station_many_details' => function($query){

            $query->where('status',1)->orderBy('step_num','desc');

        }, 'prod_runcard_station_many_details.ct_area_info', 'prod_runcard_station_many_details.terminal_area_info'])->where('po_no',$request->po_num)->where('status', 3)->orderBy('created_at','desc')->whereNotIn('id', $oqc_runcards)->get();

        return DataTables::of($runcards)
        ->addColumn('action', function($runcard) use($array_runcards){

            if(in_array($runcard->id,array_column($array_runcards, 'affected_doc_id')))
            {
                $result = '<input type="checkbox" class="bulk-runcard" fvi-ct="'.$runcard->prod_runcard_station_many_details[0]->ct_area.'" fvi-terminal="'.$runcard->prod_runcard_station_many_details[0]->terminal_area.'" output-qty='.$runcard->prod_runcard_station_many_details[0]->qty_output.' runcard-no="'.$runcard->runcard_no.'" runcard-id="'.$runcard->id.'" checked>';
            }
            else
            {
                $result = '<input type="checkbox" class="bulk-runcard" fvi-ct="'.$runcard->prod_runcard_station_many_details[0]->ct_area.'" fvi-terminal="'.$runcard->prod_runcard_station_many_details[0]->terminal_area.'"  output-qty='.$runcard->prod_runcard_station_many_details[0]->qty_output.' runcard-no="'.$runcard->runcard_no.'" runcard-id="'.$runcard->id.'">';
            }


            return $result;
        })
        ->addColumn('runcard_no', function($runcard){

            $result = $runcard->runcard_no;

            return $result;
        })
        ->addColumn('fvi_ct', function($runcard){

            if($runcard->prod_runcard_station_many_details[0]->ct_area_info != null)
            {
                $result = $runcard->prod_runcard_station_many_details[0]->ct_area_info->name;
            }
            else
            {
                $result = "---";
            }



            return $result;
        })
        ->addColumn('fvi_terminal', function($runcard){

            if($runcard->prod_runcard_station_many_details[0]->terminal_area_info != null)
            {
                $result = $runcard->prod_runcard_station_many_details[0]->terminal_area_info->name;
            }
            else
            {
                $result = "---";
            }


            return $result;
        })
        ->addColumn('lot_qty', function($runcard){

            if(count($runcard->prod_runcard_station_many_details) > 0)
            {
                 $result = $runcard->prod_runcard_station_many_details[0]->qty_output;
            }
            else
            {
                 $result = "---";
            }


            return $result;
        })
        ->addColumn('remarks', function($runcard){

            $result = $runcard->remarks;

            return $result;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function load_bulk_reworks(Request $request)
    {
        $array_rework = [];

        if(isset($request->array_runcards))
        {
            for($i = 0; $i < count($request->array_runcards); $i++)
            {
                if($request->array_runcards[$i]['type'] == 2)
                {
                    $array_rework[] = $request->array_runcards[$i]['runcard_id'];
                }
            }
        }

         $oqc_rework = OqcLotappRuncards::where('logdel',0)->where('item_type',2)->pluck('runcard_no')->toArray();


         $runcards = DefectEscalation::with(['defect_escalation_station_many_details' => function($query){

            $query->where('status',1)->orderBy('step_num','desc');

        }])->where('po_no', $request->po_num)->where('status',3)->orderBy('created_at','desc')->whereNotIn('id', $oqc_rework)->get();


        return DataTables::of($runcards)
        ->addColumn('action', function($runcard) use($array_rework){

            if(in_array($runcard->id, $array_rework))
            {
                $result = '<input type="checkbox" class="bulk-rework" output-qty='.$runcard->defect_escalation_station_many_details[0]->qty_good.' runcard-no="'.$runcard->defect_escalation_no.'" runcard-id="'.$runcard->id.'" checked>';
            }
            else
            {
                $result = '<input type="checkbox" class="bulk-rework"  output-qty='.$runcard->defect_escalation_station_many_details[0]->qty_good.' runcard-no="'.$runcard->defect_escalation_no.'" runcard-id="'.$runcard->id.'">';
            }


            return $result;
        })
        ->addColumn('runcard_no', function($runcard){

            $result = $runcard->defect_escalation_no;

            return $result;
        })
        ->addColumn('lot_qty', function($runcard){

            if(count($runcard->defect_escalation_station_many_details) > 0)
            {
                 $result = $runcard->defect_escalation_station_many_details[0]->qty_good;
            }
            else
            {
                 $result = "---";
            }


            return $result;
        })
        ->addColumn('remarks', function($runcard){

            $result = $runcard->remarks;

            return $result;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
}
