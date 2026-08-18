<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use DataTables;
use Carbon\Carbon;

use App\Model\OQCInspection;
use App\Model\OQCInspection_2;
use App\Model\OQCStamp;
use App\User;

use App\Model\ProductionRuncard;
use App\Model\oqcLotApp;
use App\Model\OQCInspection_2_ViewChecker;

class OQCInspectionController extends Controller
{
    public function wbs_getLotDetails_try_url(Request $request)
    {
        return response()->json([
            'po_no' => $request->po_no,
            'po_qty' => $request->po_qty,
            'lot_no' => $request->lot_no,

            'ww' => $request->ww,

            'app_date_time' => $request->app_date_time,

            'user_id' => $request->user_id,
            'username' => $request->username,
            'password' => $request->password,
        ]);
    }

    public function wbs_getLotDetails(Request $request)
    {
        // $prod_runcard = ProductionRuncard::where('lot_no', $request->lot_no)->orderBy('id', 'desc')->limit(1)->get();
        $prod_runcard = ProductionRuncard::where('id', $request->id)->orderBy('id', 'desc')->limit(1)->get();

        // return $prod_runcard;
        if( count($prod_runcard) == 0 )
            return response()->json([ 'error' => 'Lot # is not existing.' ]);

        // OQCInspection_2_ViewChecker
        // OQCInspection_2

        $prod_runcard = $prod_runcard[0];
        $oqc_inspection = OQCInspection_2_ViewChecker::where('runcard_id', $prod_runcard->id)->orderBy('id', 'desc')->limit(1)->get();
        if( count($oqc_inspection) == 0 )
            return response()->json([ 'error' => 'Lot # is not scanned the tray\'s.' ]);
            // return response()->json([ 'error' => 'Lot # is not set in oqc inspection.' ]);

        $oqc_inspection = $oqc_inspection[0];
        $oqc_lotapp = oqcLotApp::where('fkid_runcard', $prod_runcard->id)->orderBy('id', 'desc')->limit(1)->get();
        if( count($oqc_lotapp) == 0 )
            return response()->json([ 'error' => 'Lot # is not set in oqc lot app.' ]);
        $oqc_lotapp = $oqc_lotapp[0];

        // ? NOTE : Check the employee number then get the username that match WBS
        //? NOTE: check the table oqc_inspection_view_scan_checker - employee_id column
        // return $oqc_inspection->employee_id;

        // return $oqc_inspection->employee_id;

        $user = User::where('employee_id', $oqc_inspection->employee_id)->orderBy('id', 'desc')->limit(1)->get();
        if( count($user) == 0 )
            return response()->json([ 'error' => 'Employee ID not found.' ]);
        $user = $user[0];

        $date = date_create($oqc_inspection->created_at);
        $date = date_format($date, 'Y-m-d-H-i-s');
        return response()->json([
            'po_no' => $prod_runcard->po_no,
            'po_qty' => $prod_runcard->po_qty,
            'lot_no' => $prod_runcard->lot_no,

            'ww' => $oqc_lotapp->ww,

            'app_date_time' => $date,

            'user_id' => $oqc_inspection->employee_id,
            'username' => $user->username,
            'password' => $user->password,
        ]);
    }

    public function getAll_OQCInspection(Request $request)
    {
    	return response()->json(['data' => OQCInspection::get()]);
    }

    public function saveOQCInspection_2(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        if( count( OQCInspection_2::where('prod_runcard_id', $request->prod_runcard_id)->get() ) == 0 ){
        	$data = new OQCInspection_2();
        	$data->prod_runcard_id = $request->prod_runcard_id;
			// $data->coc = $request->coc;
			$data->guage = $request->guage;
			$data->accessory = $request->accessory;
			$data->yd_lbl_req = $request->yd_lbl_req;
			$data->chs_coating = $request->chs_coating;
            $data->employee_id = $request->employee_id;
            $data->created_at = date('Y-m-d H:i:s'); // Added on 05-15-2024 -JD
            $result = 0;
            if(  in_array($request->judgement, ["Accept", "Accepted"]) )
                $result = 1;
            $data->result = $result;
            $user = User::where('employee_id', $data->employee_id)->get();
            if( count($user)>0 ){
                $oqc_stamp = OQCStamp::where('user_id', $user[0]->id)->get();
                if( count($oqc_stamp)>0 )
                    $data->oqc_stamp = $oqc_stamp[0]->oqc_stamp;
            }
			$data->save();
			return response()->json(['result' => 1]);
        }else{
        	return response()->json(['error_msg' => 'There is OQC Inspection Result already existing.']);
        }
    }
}
