<?php

namespace App\Http\Controllers;

use App\Model\ACDCSDrawingNo;
use App\Model\DrawingNo;
use App\Model\RapidActiveDocs;
use Illuminate\Http\Request;


class DrawingNoController extends Controller
{
    public function get_drawing_no(Request $request){
        // $device_name = explode('-', $request['device_name']);
        // $device_name_str = $device_name[0].'-'.$device_name[1];

        // return $device_name_str;

        // return $device_name_str;
        // return 'qwe';

        $request_str = $request->str;

        // $DrawingNo = DrawingNo::where('logdel', 0)
        //             ->where(function($query) use ($device_name_str, $request_str) {
        //                 $query->where('document_no', "like",'%'.$device_name_str.'%')
        //                 ->orWhere('document_no', "like",'%'.$request_str.'%');
        //             })
        //             ->limit('200')
        //             ->get();
        $device_name = $request['device_name'];
        $device_name = CommonController::getInstance()->validate_device_name_acdcs_packing($device_name);
        $DrawingNo = RapidActiveDocs::where('doc_no','LIKE', '%'.$request_str.'%')
        // ->where('series', 'LIKE', '%' . $device_name . '%')
        ->limit(300)
        ->get();
        $doc = array();
        $doc['doc'] = $DrawingNo;

        return ($doc);
    }


    public function get_adrawing_no(Request $request){
        // $device_name = explode('-', $request['device_name']);
        // $device_name_str = $device_name[0].'-'.$device_name[1];
        $request_str = $request->str;

        // $DrawingNo = DrawingNo::where('logdel', 0)
        //             ->where(function($query) use ($device_name_str, $request_str) {
        //                 $query->where('document_no', "like",'%'.$device_name_str.'%')
        //                 ->orWhere('document_no', "like",'%'.$request_str.'%');
        //             })
        //             ->where(function($query) {
        //                 $query->where('document_code', 'A Drawing')
        //                 ->where('document_code', 'AA Drawing');
        //             })
        //             ->limit('300')
        //             ->get();
        $device_name = $request['device_name'];
        $device_name = CommonController::getInstance()->validate_device_name_acdcs_packing($device_name);
        $DrawingNo = RapidActiveDocs::where('doc_no','LIKE', '%'.$request_str.'%')
        // ->where('series', 'LIKE', '%' . $device_name . '%')
        ->limit(300)
        ->get();
        $doc = array();
        $doc['doc'] = $DrawingNo;

        return ($doc);
    }

    public function get_gdrawing_no(Request $request){
        // $device_name = explode('-', $request['device_name']);
        // $device_name_str = $device_name[0].'-'.$device_name[1];
        $device_name = $request['device_name'];
        $device_name = CommonController::getInstance()->validate_device_name_acdcs_packing($device_name);
        $request_str = $request->str;
        // $device_name_str = $device_name[0];
        // if( strlen($request_str)<3 ){
        //     $request_str = 'show nothing';
        // }
        // $DrawingNo = DrawingNo::where('logdel', 0)
        //             ->where(function($query) use ($device_name_str, $request_str) {
        //                 $query->where('document_no', "like",'%'.$device_name_str.'%')
        //                 ->orWhere('document_no', "like",'%'.$request_str.'%');
        //             })
        //             ->where(function($query) {
        //                 $query->where('document_code', 'G Drawing')
        //                 ->where('document_code', 'AG Drawing');
        //             })
        //             ->limit('300')
        //             ->get();
        $device_name = $request['device_name'];
        $device_name = CommonController::getInstance()->validate_device_name_acdcs_packing($device_name);
        $request_str = $request->str;
        $DrawingNo = RapidActiveDocs::where('doc_no','LIKE', '%'.$request_str.'%')
        ->where('doc_type', 'G Drawing')
        ->where('doc_type', 'AG Drawing')
        ->limit(300)
        ->get();
        $doc = array();
        $doc['doc'] = $DrawingNo;

        return ($doc);
    }

    public function get_odrawing_no(Request $request){
        // $device_name = explode('-', $request['device_name']);
        // $device_name_str = $device_name[0];
        // $request_str = $request->str;
        // $DrawingNo = DrawingNo::where('logdel', 0)
        //             // ->where('doc_title', "like",'%'.$device_name_str.'%')
        //             // ->Orwhere('doc_title', "like",'%'.$request_str.'%')
        //             ->where(function($query) use ($device_name_str, $request_str) {
        //                 $query->where('document_no', "like",'%'.$device_name_str.'%')
        //                 ->orWhere('document_no', "like",'%'.$request_str.'%');
        //             })
        //             ->where(function($query) {
        //                 $query->where('document_code', 'KL Drawing')
        //                 ->where('document_code', 'AC Drawing');
        //             })
        //             ->limit('300')
        //             ->get();
        $device_name = $request['device_name'];
        $device_name = CommonController::getInstance()->validate_device_name_acdcs_packing($device_name);
        $request_str = $request->str;
        $DrawingNo = RapidActiveDocs::where('doc_no','LIKE', '%'.$request_str.'%')
        ->where('doc_type', 'KL Drawing')
        ->where('doc_type', 'AC Drawing')
        ->limit(300)
        ->get();
        $doc = array();
        $doc['doc'] = $DrawingNo;

        return ($doc);
    }

    public function get_WIDoc(Request $request){
        $device_name = $request['device_name'];
        $device_name = CommonController::getInstance()->validate_device_name_acdcs_packing($device_name);
        $request_str = $request->str;

        // $device_name_str = $device_name[0].'-'.$device_name[1];
        $device_name_str = $device_name[0];
        // if( strlen($request_str)<3 ){
        //     $request_str = 'show nothing';
        // }
        // $DrawingNo = DrawingNo::where('logdel', 0)
        //             // ->where(function($query) use ($device_name_str, $request_str) {
        //             //     $query->where('document_no', "like",'%'.$device_name_str.'%')
        //             //     ->orWhere('document_no', "like",'%'.$request_str.'%');
        //             // })
        //             ->where('document_code', 'WI')
        //             ->where('station', 'like', '%Final Visual%')
        //             // ->where('document_code', 'Work Instruction')
        //             ->limit('300')
        //             ->get();

                    // ->toSql();
        $request_str = $request->str;
        $DrawingNo = RapidActiveDocs::where('doc_no','LIKE', '%'.$request_str.'%')
        // ->where('doc_no','LIKE', '%'.WI.'%')
        // ->where('series', 'LIKE', '%' . $device_name . '%')
        ->limit(300)
        ->get();
        $doc = array();
        $doc['doc'] = $DrawingNo;

        return ($doc);
    }

    public function get_OGM_VIG_IGDoc(Request $request){


        // if( strlen($request_str)<3 ){
        //     $request_str = 'show nothing';
        // }
        // $DrawingNo = DrawingNo::where('logdel', 0)
        //             ->where(function($query) use ($device_name_str, $request_str) {
        //                 $query->where('document_no', "like",'%'.$device_name_str.'%')
        //                 ->orWhere('document_no', "like",'%'.$request_str.'%');
        //             })
        //             ->where(function($query) {
        //                 $query->where('document_code', 'OGM')
        //                 ->orWhere('document_code', 'IG')
        //                 ->orWhere('document_code', 'VIG');
        //             })
        //             // ->where('station', 'like', '%Final Visual%')
        //             ->limit('300')
        //             ->get();
        $device_name = $request['device_name'];
        $device_name = CommonController::getInstance()->validate_device_name_acdcs_packing($device_name);
        $request_str = $request->str;
        $DrawingNo = RapidActiveDocs::where('doc_no','LIKE', '%'.$request_str.'%')
        ->orWhere('doc_no', '%'.$device_name.'%')
        // ->orWhere('doc_no', 'IG')
        // ->orWhere('doc_no', 'VIG')
        // ->where('series', 'LIKE', '%' . $device_name . '%')
        ->limit(300)
        ->get();
        $doc = array();
        $doc['doc'] = $DrawingNo;

        return ($doc);
    }

    public function get_PPDoc(Request $request){
        // $device_name = explode('-', $request['device_name']);
        // // $device_name_str = $device_name[0].'-'.$device_name[1];
        // $device_name_str = $device_name[0];
        // $request_str = $request->str;
        // // if( strlen($request_str)<3 ){
        // //     $request_str = 'show nothing';
        // // }
        // $DrawingNo = DrawingNo::where('logdel', 0)
        //             ->where(function($query) use ($device_name_str, $request_str) {
        //                 $query->where('document_no', "like",'%'.$device_name_str.'%')
        //                 ->orWhere('document_no', "like",'%'.$request_str.'%');
        //             })
        //             ->where('document_code', 'PP')
        //             ->where('station', 'like', '%Final Visual%')
        //             ->limit('300')
        //             ->get();

        $device_name = $request['device_name'];
        $device_name = CommonController::getInstance()->validate_device_name_acdcs_packing($device_name);
        $request_str = $request->str;
        $DrawingNo = RapidActiveDocs::
        where('doc_no','LIKE', '%'.$request_str.'%')
        ->orWhere('doc_title', 'LIKE', '%' . $request_str . '%')
        ->orWhere('doc_title', '%' . $device_name . '%')
        // ->where('doc_type', 'PP')
        // ->orWhere('doc_type', 'Point Panel')
        ->limit(300)
        ->get();
        $doc = array();
        $doc['doc'] = $DrawingNo;

        return ($doc);
    }

    public function get_UDDoc(Request $request){
        $device_name = explode('-', $request['device_name']);
        $request_str = $request->str;
        if( strlen($request_str)<3 ){
            $request_str = 'show nothing';
        }
        // $DrawingNo = DrawingNo::where('logdel', 0)
        // $DrawingNo = ACDCSDrawingNo::where('logdel', 0)
        //             // ->where(function($query) use ($device_name_str, $request_str) {
        //             //     $query->where('doc_title', "like",'%'.$device_name_str.'%')
        //             //     ->orWhere('doc_title', "like",'%'.$request_str.'%');
        //             // })
        //             ->where('doc_type', 'Urgent Direction')
        //             ->where('originator_code', 'TS')
        //             // ->where('station', 'like', '%Final Visual%')
        //             ->limit('200')
        //             ->get();
        $device_name = $request['device_name'];
        $device_name = CommonController::getInstance()->validate_device_name_acdcs_packing($device_name);
        $request_str = $request->str;
        $DrawingNo = RapidActiveDocs::
        // where('doc_no','LIKE', '%'.$request_str.'%')
        where('doc_title', 'LIKE', '%' . $request_str . '%')
        ->orWhere('doc_title', '%' . $device_name . '%')
        ->where('doc_type', 'Urgent Direction')
         ->where('originator_code', 'TS')
        ->limit(300)
        ->get();
        $doc = array();
        $doc['doc'] = $DrawingNo;

        return ($doc);
    }

    public function get_PMDoc(Request $request){
        $device_name = explode('-', $request['device_name']);
        // $device_name_str = $device_name[0].'-'.$device_name[1];
        // $device_name_str = $device_name[0];
        // $device_name_str = 'Packaging';
        $request_str = $request->str;
        if( strlen($request_str)<3 ){
            $request_str = 'show nothing';
        }
        // $DrawingNo = ACDCSDrawingNo::where('logdel', 0)
        //             // ->where(function($query) use ($device_name_str, $request_str) {
        //             //     $query->where('doc_title', "like",'%'.$device_name_str.'%')
        //             //     ->orWhere('doc_title', "like",'%'.$request_str.'%');
        //             // })
        //             // ->where('doc_title', "like",'%'.$device_name_str.'%')
        //             ->where('doc_type', 'J Drawing')
        //             ->where('originator_code', 'TS')
        //             ->orWhere('doc_type', 'R Drawing')
        //             // ->where('station', 'like', '%Final Visual%')
        //             ->limit('200')
        //             ->get();

        $device_name = $request['device_name'];
        $device_name = CommonController::getInstance()->validate_device_name_acdcs_packing($device_name);
        $request_str = $request->str;
        $DrawingNo = RapidActiveDocs::
        where('doc_no','LIKE', '%'.$request_str.'%')
        ->orwhere('doc_title', 'LIKE', '%' . $request_str . '%')
         ->where('doc_type', 'J Drawing')
        ->orWhere('doc_type', 'R Drawing')
         ->where('originator_code', 'TS')
        ->limit(300)
        ->get();

        $doc = array();
        $doc['doc'] = $DrawingNo;

        return ($doc);
    }

    // public function get_PMDoc(Request $request){
    //     $device_name = explode('-', $request['device_name']);
    //     // $device_name_str = $device_name[0].'-'.$device_name[1];
    //     $device_name_str = $device_name[0];
    //     $request_str = $request->str;
    //     // if( strlen($request_str)<3 ){
    //     //     $request_str = 'show nothing';
    //     // }
    //     $DrawingNo = DrawingNo::where('logdel', 0)
    //                 ->where(function($query) use ($device_name_str, $request_str) {
    //                     $query->where('document_no', "like",'%'.$device_name_str.'%')
    //                     ->orWhere('document_no', "like",'%'.$request_str.'%');
    //                 })
    //                 ->where('document_code', 'PM')
    //                 ->where('station', 'like', '%Final Visual%')
    //                 ->limit('300')
    //                 ->get();
    //     $doc = array();
    //     $doc['doc'] = $DrawingNo;

    //     return ($doc);
    // }


}
