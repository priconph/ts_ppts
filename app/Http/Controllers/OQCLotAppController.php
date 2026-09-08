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

use App\User;
use QrCode;


use DataTables;

use Excel;
use App\Exports\PartsLotReportExport;

class OQCLotAppController extends Controller
{

    public function update_ww_in_oqclotapp(Request $request)
    {
        try{
            oqcLotApp::where('id', $request->id)->update([ 'ww' => $request->ww, ]);
            return response()->json(['result' => 'WW successfully update.']);
        }catch(\Exception $e){
            return response()->json(['result' => 'Something went wrong.']);
        }
    }

    public function generate_qrcode_for_oqc_lot_app(Request $request)
    { //working function
        // return 'working function';
        // 09-04-24 Remove Burn-in & Test, if the Device Name in WBS Issuance & Kitting
        $device_name = $request->device_name;
       $device_name = CommonController::getInstance()->validate_device_name($device_name);
        $oqcLotApp = oqcLotApp::where('fkid_runcard', $request->id)->get();
        // return $oqcLotApp;
        $runcards = ProductionRuncard::where('id', $request->id)->get(); // 05242024 by Nessa
        $device = Device::where('name', $device_name)->where('status', 1)->get();
        // return $device;
      $result = ProductionRuncardStation::where('production_runcard_id', $request->id)->where('status', 1)->get();
        $ttl = 0;
        for ($i=0; $i < count($result); $i++)
            $ttl = $ttl + $result[$i]->qty_output;

            $list_of_name_and_qtt_done = ProductionRuncardStation::with('ct_area_info', 'terminal_area_info')->select('*', DB::raw("SUM(qty_output) as ttl_qtt"))->where('production_runcard_id', $request->id)->where('status', 1)->groupBy('ct_area', 'terminal_area')->get();
        // if( count($list_of_name_and_qtt_done) == 2 ){
        //     if( (int)$list_of_name_and_qtt_done[1]->ttl_qtt > (int)$list_of_name_and_qtt_done[0]->ttl_qtt ){
        //         $hold = $list_of_name_and_qtt_done[1];
        //         $list_of_name_and_qtt_done[1] = $list_of_name_and_qtt_done[0];
        //         $list_of_name_and_qtt_done[0] = $hold;
        //     }
        // }
        for ($i=0; $i < count($list_of_name_and_qtt_done); $i++) {
            for ($j=0; $j < count($list_of_name_and_qtt_done); $j++) {
                if( (int)$list_of_name_and_qtt_done[$i]->ttl_qtt > (int)$list_of_name_and_qtt_done[$j]->ttl_qtt ){
                    $hold = $list_of_name_and_qtt_done[$i];
                    $list_of_name_and_qtt_done[$i] = $list_of_name_and_qtt_done[$j];
                    $list_of_name_and_qtt_done[$j] = $hold;
                }
            }
        }
        // return $list_of_name_and_qtt_done;
        // 05242024 by Nessa --> change $oqcLotApp[0]->lot_batch_no to $runcards[0]->runcard_no
          $QrCode = QrCode::format('png')->errorCorrection('H')->size(200)->generate($oqcLotApp[0]->po_no . '
        ' . explode(' - ', $device[0]->name)[0] . '
        ' . $runcards[0]->runcard_no . '
        ' . $oqcLotApp[0]->print_lot . '
        ' . $ttl . '
        ' . $device[0]->ship_boxing);
        $QrCode = "data:image/png;base64," . base64_encode($QrCode);

        $po_no = ProductionRuncard::where('id', $request->id)->get()[0]->po_no;
        $prd_runcards = ProductionRuncard::where('po_no', $po_no)->orderBy('id')->get();
        $prd_runcards_counter = [];
        for ($i=0; $i < count($prd_runcards); $i++)
            $prd_runcards_counter[ $prd_runcards[$i]->id ] = ($i+1);


        $cnt = ceil($prd_runcards[0]->po_qty / $device[0]->ship_boxing);

        // return $cnt;

        $sticker_cnt = ceil($device[0]->ship_boxing / $device[0]->boxing);
        if( $device[0]->ship_boxing > $ttl )
            $sticker_cnt = ceil($ttl / $device[0]->boxing);

        $data = [];

        $content = '';
        $serial_no = "";
        $serial_no_html = "";
        if( $oqcLotApp[0]->print_lot != 'N/A' ){
            $serial_no = $oqcLotApp[0]->print_lot . "
";
            $serial_no_html = $oqcLotApp[0]->print_lot . '</b><br>';
        }
        $lot_number = (int)(explode('-', $oqcLotApp[0]->lot_batch_no)[1]);

        $lot_start_counter = ( $lot_number - 1 ) * (  (int)$device[0]->ship_boxing / (int)$device[0]->boxing );
        $_stations__ = [];
        // change $lot_start_counter to total trays count before the trays in the lot selected
        if( $prd_runcards[0]->po_qty <= 99 ){
            $lot_start_counter = 0;
            // $sticker_cnt = 1;
            $current_lot_id_is_selected = false;

            for ($i=0; $i < count($prd_runcards); $i++) {

                if( !$current_lot_id_is_selected ) {
                    if( $request->id == $prd_runcards[$i]->id )
                        $current_lot_id_is_selected = true;

                    $_stations = ProductionRuncardStation::select('*', DB::raw("SUM(qty_output) as ttl_qtt"))->where('production_runcard_id', $prd_runcards[$i]->id)->where('status', 1)->limit(1)->get();
                    $_stations__[] = $_stations;

                    if( $request->id != $prd_runcards[$i]->id ){
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
        } else {
            $data = [];

            // $lbl = 'PO No.: ' . $oqcLotApp[0]->po_no . '<br>Device Name: ' . explode(' - ', $device[0]->name)[0]. '<br>Runcard/Lot No.: ' . $oqcLotApp[0]->lot_batch_no . '<br>Serial No./WW/Print Lot No.: ' . $oqcLotApp[0]->print_lot . '<br>Actual Lot Quantity: ' . $ttl . '<br>No. of Label of how many tray/boxes: ' . $prd_runcards_counter[$request->id] . '/' . $sticker_cnt;
            // return $oqcLotApp[0];

            $content = '';
            $serial_no = "";
            $serial_no_html = "";
            if( $oqcLotApp[0]->print_lot != 'N/A' ){
                $serial_no = $oqcLotApp[0]->print_lot . "
    ";
                $serial_no_html = $oqcLotApp[0]->print_lot . '</b><br>';
            }

            // return $oqcLotApp;
            $lot_number = explode('-', $oqcLotApp[0]->lot_batch_no);
            $lot_number = (int)($lot_number[count($lot_number)-1]);
            // $lot_number = (int)(explode(' ', $oqcLotApp[0]->lot_batch_no)[1]);
            // $lot_start_counter = ( $lot_number - 1 ) * (  (int)$device[0]->ship_boxing / (int)$device[0]->boxing );
            $lot_start_counter = ( $lot_number - 1 ) * ceil(  (int)$device[0]->ship_boxing / (int)$device[0]->boxing );
            // return $lot_number;
            // return $lot_start_counter;
            $ttl_lot_qtt_box = 0;
        }
        // end

        // return $lot_start_counter . " - " . ( $lot_number - 1 ) . " - " . $device[0]->ship_boxing . " - " . $device[0]->boxing . " - " . (  (int)$device[0]->ship_boxing / (int)$device[0]->boxing );

        for ($i=1; $i <= $sticker_cnt; $i++) {

            $name = '';
            $qtt_tray = $i * (int)($device[0]->boxing);
            $index = 0;

            $output_qty_count = 0;
            for ($index_name=0; $index_name < count($list_of_name_and_qtt_done); $index_name++) {
                $output_qty_count += $list_of_name_and_qtt_done[$index_name]->ttl_qtt;
                if( $output_qty_count >= $qtt_tray ){
                    $index = $index_name;
                    break;
                }
                else if( $index_name == count($list_of_name_and_qtt_done)-1 ){
                    $index = $index_name;
                    break;
                }
            }

            // return $list_of_name_and_qtt_done;

            if( $list_of_name_and_qtt_done[$index]->ct_area == $list_of_name_and_qtt_done[$index]->terminal_area ){


                $mm = explode(' ', $list_of_name_and_qtt_done[$index]->ct_area_info->name);
                // $name .= $mm[0] . ' ' . $mm[2][0] . '.';
                if( count($mm) == 3 )
                    $name .= $mm[0] . ' ' . $mm[2][0] . '.';
                else
                    $name .= $mm[0] . ' ' . $mm[1][0] . '.';
            }else{
                $mm = explode(' ', $list_of_name_and_qtt_done[$index]->ct_area_info->name);
                // $name .= $mm[0] . ' ' . $mm[2][0] . '., ';
                if( count($mm) == 3 )
                    $name .= $mm[0] . ' ' . $mm[2][0] . '., ';
                else
                    $name .= $mm[0] . ' ' . $mm[1][0] . '., ';
                $mm = explode(' ', $list_of_name_and_qtt_done[$index]->terminal_area_info->name);
                // $name .= $mm[0] . ' ' . $mm[2][0] . '.';
                if( count($mm) == 3 )
                    $name .= $mm[0] . ' ' . $mm[2][0] . '.';
                else
                    $name .= $mm[0] . ' ' . $mm[1][0] . '.';
            }
            // $fviname = strtoupper($name);
            $fviname = $name;

            if( $ttl >= $qtt_tray )
                $qtt_tray = (int)($device[0]->boxing);
            else
                $qtt_tray = (int)($device[0]->boxing) - ($qtt_tray - $ttl);

            $arr_qr_detail = [
                'oqc_lotapp_po_no' => $oqcLotApp[0]->po_no,
                'oqc_lotapp_device' => explode(' - ', $device[0]->name)[0],
                'oqc_lotapp_lot_batch_no' => $oqcLotApp[0]->lot_batch_no,
                // 'oqc_lotapp_emp_name' => $name,
                'oqc_lotapp_serial_no' => $serial_no . $ttl,
                'oqc_lotapp_qtt_tray' => $qtt_tray,
                'oqc_lotapp_lot_sticker_cnt' => ($lot_start_counter + $i) . '/' . ($lot_start_counter + $sticker_cnt),
            ];
            $obj_qr_detail = json_encode($arr_qr_detail);
            $qrcode = QrCode::format('png')
            ->size(250)->errorCorrection('H')
            ->generate($obj_qr_detail);
            $lcl_QrCode = "data:image/png;base64," . base64_encode($qrcode);

            //Enable if the Device Name is need to be wrap
            // $word_wrap = wordwrap(explode(' - ', $device[0]->name)[0],15,"<br>\n");
            // $data[] = array('img' => $lcl_QrCode, 'text' => '<b><br>' .$oqcLotApp[0]->po_no . '</b><br>' . '<b>'.$word_wrap . '</b><br>' .

            $data[] = array('img' => $lcl_QrCode, 'text' => '<b><br>' .$oqcLotApp[0]->po_no . '</b><br>' . '<b>'. explode(' - ', $device[0]->name)[0]. '</b><br>' .
                $runcards[0]->runcard_no . '</b><br>' .
                $fviname . '<br>' .
                $serial_no_html .
                $ttl . '</b><br>' .
                $qtt_tray . '</b><br>' .
                // 8 . '</b><br>' . // use this for temporary fix of qtt per tray, also change the qtt_tray in lcl_QrCode 06-11-2023
                ($lot_start_counter + $i) . '/' . ($lot_start_counter + $sticker_cnt).'</b>');
                // (1) . '/' . (1).'</b>'); // tempo 08102023

            $lbl = 'PO no.: ' . $oqcLotApp[0]->po_no . '<br>Device name: ' . explode(' - ', $device[0]->name)[0]. '<br>Lot no.: ' . $runcards[0]->runcard_no . '<br>FVI name: ' . $fviname . '<br>Actual lot quantity: ' . $ttl . '<br>Quantity per tray: ' . $qtt_tray . '<br>Count of tray/total tray per lot: ' . $prd_runcards_counter[$request->id] . '/' . $sticker_cnt;
        }
        return response()->json(['QrCode' => $QrCode, 'label' => $lbl, 'label_hidden' => $data, '_stations__' => $_stations__,'obj_qr_detail'=>$obj_qr_detail]);
    }

    public function generate_qrcode_for_oqc_lot_app_2022_03_22(Request $request)
    {
        $oqcLotApp = oqcLotApp::where('fkid_runcard', $request->id)->get();
        $device = Device::where('name', $request->device_name)->get();
        // return $device;
        $result = ProductionRuncardStation::where('production_runcard_id', $request->id)->where('status', 1)->get();
        $ttl = 0;
        for ($i=0; $i < count($result); $i++)
            $ttl = $ttl + $result[$i]->qty_output;

        $list_of_name_and_qtt_done = ProductionRuncardStation::with('ct_area_info', 'terminal_area_info')->select('*', DB::raw("SUM(qty_output) as ttl_qtt"))->where('production_runcard_id', $request->id)->where('status', 1)->groupBy('ct_area', 'terminal_area')->get();
        for ($i=0; $i < count($list_of_name_and_qtt_done); $i++) {
            for ($j=0; $j < count($list_of_name_and_qtt_done); $j++) {
                if( (int)$list_of_name_and_qtt_done[$i]->ttl_qtt > (int)$list_of_name_and_qtt_done[$j]->ttl_qtt ){
                    $hold = $list_of_name_and_qtt_done[$i];
                    $list_of_name_and_qtt_done[$i] = $list_of_name_and_qtt_done[$j];
                    $list_of_name_and_qtt_done[$j] = $hold;
                }
            }
        }

        $QrCode = QrCode::format('png')->errorCorrection('H')->size(200)->generate($oqcLotApp[0]->po_no . '
' . explode(' - ', $device[0]->name)[0] . '
' . $oqcLotApp[0]->lot_batch_no . '
' . $oqcLotApp[0]->print_lot . '
' . $ttl . '
' . $device[0]->ship_boxing);
        $QrCode = "data:image/png;base64," . base64_encode($QrCode);

        $po_no = ProductionRuncard::where('id', $request->id)->get()[0]->po_no;
        $prd_runcards = ProductionRuncard::where('po_no', $po_no)->orderBy('id')->get();
        $prd_runcards_counter = [];
        for ($i=0; $i < count($prd_runcards); $i++)
            $prd_runcards_counter[ $prd_runcards[$i]->id ] = ($i+1);


        $cnt = ceil($prd_runcards[0]->po_qty / $device[0]->ship_boxing);

        // return $cnt;

        $sticker_cnt = ceil($device[0]->ship_boxing / $device[0]->boxing);
        if( $device[0]->ship_boxing > $ttl )
            $sticker_cnt = ceil($ttl / $device[0]->boxing);

        // $sticker_cnt change to total count of trays from first lot to lot selected
        if( $prd_runcards[0]->po_qty <= 99 ){
            $sticker_cnt = 1;
        }
        // end

        $data = [];

        $content = '';
        $serial_no = "";
        $serial_no_html = "";
        if( $oqcLotApp[0]->print_lot != 'N/A' ){
            $serial_no = $oqcLotApp[0]->print_lot . "
";
            $serial_no_html = $oqcLotApp[0]->print_lot . '</b><br>';
        }

        $lot_number = (int)(explode('-', $oqcLotApp[0]->lot_batch_no)[1]);
        $lot_start_counter = ( $lot_number - 1 ) * (  (int)$device[0]->ship_boxing / (int)$device[0]->boxing );

        // change $lot_start_counter to total trays count before the trays in the lot selected
        if( $prd_runcards[0]->po_qty <= 99 ){
            // $sticker_cnt = 0;
            $lot_start_counter = 0;
            $current_lot_id_is_selected = false;

            for ($i=0; $i < count($prd_runcards); $i++) {

                if( !$current_lot_id_is_selected ) {
                    if( $request->id == $prd_runcards[$i]->id )
                        $current_lot_id_is_selected = true;

                    $_stations = ProductionRuncardStation::select('*', DB::raw("SUM(qty_output) as ttl_qtt"))->where('production_runcard_id', $prd_runcards[$i]->id)->where('status', 1)->limit(1)->get();

                    // for ($kk=0; $kk < count($_stations); $kk++) {

                    // }

                    if( $request->id != $prd_runcards[$i]->id ){
                        $_ttl_qtt = $_stations[0]->ttl_qtt;
                        for ($mm=0; $mm < 100; $mm++) {
                            $_ttl_qtt = $_ttl_qtt - (int)$device[0]->boxing;
                            $lot_start_counter++;
                            if( $_ttl_qtt <= 0  )
                                break;
                        }
                    }

                    // $lot_start_counter += ceil($_stations[0]->ttl_qtt / $device[0]->boxing);


                    // $_ttl_qtt = $_stations[0]->ttl_qtt;
                    // for ($mm=0; $mm < 100; $mm++) {
                    //     $_ttl_qtt = $_ttl_qtt - (int)$device[0]->boxing;
                    //     $sticker_cnt++;
                    //     if( $_ttl_qtt <= 0  )
                    //         break;
                    // }
                }

            }
        }
        // end

        $ttl_lot_qtt_box = 0;

        for ($i=1; $i <= $sticker_cnt; $i++) {

            $name = '';
            $qtt_tray = $i * (int)($device[0]->boxing);


            $index = 0;
            $output_qty_count = 0;
            for ($index_name=0; $index_name < count($list_of_name_and_qtt_done); $index_name++) {
                $output_qty_count += $list_of_name_and_qtt_done[$index_name]->ttl_qtt;
                if( $output_qty_count >= $qtt_tray ){
                    $index = $index_name;
                    break;
                }
                else if( $index_name == count($list_of_name_and_qtt_done)-1 ){
                    $index = $index_name;
                    break;
                }
            }

            if( $list_of_name_and_qtt_done[$index]->ct_area == $list_of_name_and_qtt_done[$index]->terminal_area ){
                $mm = explode(' ', $list_of_name_and_qtt_done[$index]->ct_area_info->name);
                $name .= $mm[0] . ' ' . $mm[2][0] . '.';
            }else{
                $mm = explode(' ', $list_of_name_and_qtt_done[$index]->ct_area_info->name);
                $name .= $mm[0] . ' ' . $mm[2][0] . '., ';
                $mm = explode(' ', $list_of_name_and_qtt_done[$index]->terminal_area_info->name);
                $name .= $mm[0] . ' ' . $mm[2][0] . '.';
            }
            $fviname = $name;

            if( $ttl >= $qtt_tray )
                $qtt_tray = (int)($device[0]->boxing);
            else
                $qtt_tray = (int)($device[0]->boxing) - ($qtt_tray - $ttl);

            $lcl_QrCode = QrCode::format('png')->errorCorrection('H')->size(200)->generate($oqcLotApp[0]->po_no . '
' . explode(' - ', $device[0]->name)[0] . '
' . $oqcLotApp[0]->lot_batch_no . '
' . $fviname . '
' . $serial_no . $ttl . '
' . $qtt_tray . '
' . ($lot_start_counter + $i) . '/' . ($lot_start_counter + $sticker_cnt));
            $lcl_QrCode = "data:image/png;base64," . base64_encode($lcl_QrCode);

            $data[] = array('img' => $lcl_QrCode, 'text' => '<b><br>' .$oqcLotApp[0]->po_no . '</b><br>' . '<b>'. explode(' - ', $device[0]->name)[0]. '</b><br>' .
                $oqcLotApp[0]->lot_batch_no . '</b><br>' .
                $fviname . '<br>' .
                $serial_no_html .
                $ttl . '</b><br>' .
                $qtt_tray . '</b><br>' .
                ($lot_start_counter + $i) . '/' . ($lot_start_counter + $sticker_cnt).'</b>');

        $lbl = 'PO no.: ' . $oqcLotApp[0]->po_no . '<br>Device name: ' . explode(' - ', $device[0]->name)[0]. '<br>Lot no.: ' . $oqcLotApp[0]->lot_batch_no . '<br>FVI name: ' . $fviname . '<br>Actual lot quantity: ' . $ttl . '<br>Quantity per tray: ' . $qtt_tray . '<br>Count of tray/total tray per lot: ' . $prd_runcards_counter[$request->id] . '/' . $sticker_cnt;
        }

        return response()->json(['QrCode' => $QrCode, 'label' => $lbl, 'label_hidden' => $data]);
    }

    public function generate_qrcode_for_oqc_lot_app_2022_03_08(Request $request)
    { //TEST
        $oqcLotApp = oqcLotApp::where('fkid_runcard', $request->id)->get();
        $device = Device::where('name', $request->device_name)->get();
        // return $device;
        $result = ProductionRuncardStation::where('production_runcard_id', $request->id)->where('status', 1)->get();
        $ttl = 0;
        for ($i=0; $i < count($result); $i++)
            $ttl = $ttl + $result[$i]->qty_output;

        $list_of_name_and_qtt_done = ProductionRuncardStation::with('ct_area_info', 'terminal_area_info')->select('*', DB::raw("SUM(qty_output) as ttl_qtt"))->where('production_runcard_id', $request->id)->where('status', 1)->groupBy('ct_area', 'terminal_area')->get();
        // if( count($list_of_name_and_qtt_done) == 2 ){
        //     if( (int)$list_of_name_and_qtt_done[1]->ttl_qtt > (int)$list_of_name_and_qtt_done[0]->ttl_qtt ){
        //         $hold = $list_of_name_and_qtt_done[1];
        //         $list_of_name_and_qtt_done[1] = $list_of_name_and_qtt_done[0];
        //         $list_of_name_and_qtt_done[0] = $hold;
        //     }
        // }
        for ($i=0; $i < count($list_of_name_and_qtt_done); $i++) {
            for ($j=0; $j < count($list_of_name_and_qtt_done); $j++) {
                if( (int)$list_of_name_and_qtt_done[$i]->ttl_qtt > (int)$list_of_name_and_qtt_done[$j]->ttl_qtt ){
                    $hold = $list_of_name_and_qtt_done[$i];
                    $list_of_name_and_qtt_done[$i] = $list_of_name_and_qtt_done[$j];
                    $list_of_name_and_qtt_done[$j] = $hold;
                }
            }
        }
        // return $list_of_name_and_qtt_done;

        $QrCode = QrCode::format('png')->errorCorrection('H')->size(200)->generate($oqcLotApp[0]->po_no . '
' . explode(' - ', $device[0]->name)[0] . '
' . $oqcLotApp[0]->lot_batch_no . '
' . $oqcLotApp[0]->print_lot . '
' . $ttl . '
' . $device[0]->ship_boxing);
        $QrCode = "data:image/png;base64," . base64_encode($QrCode);

        $po_no = ProductionRuncard::where('id', $request->id)->get()[0]->po_no;
        $prd_runcards = ProductionRuncard::where('po_no', $po_no)->orderBy('id')->get();
        $prd_runcards_counter = [];
        for ($i=0; $i < count($prd_runcards); $i++)
            $prd_runcards_counter[ $prd_runcards[$i]->id ] = ($i+1);


        $cnt = ceil($prd_runcards[0]->po_qty / $device[0]->ship_boxing);

        // return $cnt;

        $sticker_cnt = ceil($device[0]->ship_boxing / $device[0]->boxing);
        if( $device[0]->ship_boxing > $ttl )
            $sticker_cnt = ceil($ttl / $device[0]->boxing);
        $data = [];

        // $lbl = 'PO No.: ' . $oqcLotApp[0]->po_no . '<br>Device Name: ' . explode(' - ', $device[0]->name)[0]. '<br>Runcard/Lot No.: ' . $oqcLotApp[0]->lot_batch_no . '<br>Serial No./WW/Print Lot No.: ' . $oqcLotApp[0]->print_lot . '<br>Actual Lot Quantity: ' . $ttl . '<br>No. of Label of how many tray/boxes: ' . $prd_runcards_counter[$request->id] . '/' . $sticker_cnt;
        // return $oqcLotApp[0];

        $content = '';
        $serial_no = "";
        $serial_no_html = "";
        if( $oqcLotApp[0]->print_lot != 'N/A' ){
            $serial_no = $oqcLotApp[0]->print_lot . "
";
            $serial_no_html = $oqcLotApp[0]->print_lot . '</b><br>';
        }

        // return $oqcLotApp;
        $lot_number = (int)(explode('-', $oqcLotApp[0]->lot_batch_no)[1]);
        // $lot_number = (int)(explode(' ', $oqcLotApp[0]->lot_batch_no)[1]);
        // $lot_start_counter = ( $lot_number - 1 ) * (  (int)$device[0]->ship_boxing / (int)$device[0]->boxing );
        $lot_start_counter = ( $lot_number - 1 ) * (  (int)$device[0]->ship_boxing / (int)$device[0]->boxing );
        // return $lot_number;
        // return $lot_start_counter;
        $ttl_lot_qtt_box = 0;

        // return $lot_start_counter . " - " . ( $lot_number - 1 ) . " - " . $device[0]->ship_boxing . " - " . $device[0]->boxing . " - " . (  (int)$device[0]->ship_boxing / (int)$device[0]->boxing );

        for ($i=1; $i <= $sticker_cnt; $i++) {

            $name = '';
            $qtt_tray = $i * (int)($device[0]->boxing);

            // if( count($list_of_name_and_qtt_done) == 1 ){
            //     if( $list_of_name_and_qtt_done[0]->ct_area == $list_of_name_and_qtt_done[0]->terminal_area ){
            //         $mm = explode(' ', $list_of_name_and_qtt_done[0]->ct_area_info->name);
            //         $name .= $mm[0] . ' ' . strtoupper($mm[1][0]) . '.';
            //     }else{
            //         $mm = explode(' ', $list_of_name_and_qtt_done[0]->ct_area_info->name);
            //         $name .= $mm[0] . ' ' . strtoupper($mm[1][0]) . '.';
            //         $mm = explode(' ', $list_of_name_and_qtt_done[0]->terminal_area_info->name);
            //         $name .= $mm[0] . ' ' . strtoupper($mm[1][0]) . '.';
            //     }
            // }else{
            //     $index = 0;
            //     if( (int)$list_of_name_and_qtt_done[0]->ttl_qtt >= $qtt_tray ){
            //         $index = 0;
            //     }


            //     if( $list_of_name_and_qtt_done[$index]->ct_area == $list_of_name_and_qtt_done[$index]->terminal_area ){
            //         $mm = explode(' ', $list_of_name_and_qtt_done[$index]->ct_area_info->name);
            //         $name .= $mm[0] . ' ' . strtoupper($mm[1][0]) . '.';
            //     }else{
            //         $mm = explode(' ', $list_of_name_and_qtt_done[$index]->ct_area_info->name);
            //         $name .= $mm[0] . ' ' . strtoupper($mm[1][0]) . '.';
            //         $mm = explode(' ', $list_of_name_and_qtt_done[$index]->terminal_area_info->name);
            //         $name .= $mm[0] . ' ' . strtoupper($mm[1][0]) . '.';
            //     }
            // }


            $index = 0;
            // if( count($list_of_name_and_qtt_done) == 1 )
            //     $index = 0;
            // else if( (int)$list_of_name_and_qtt_done[0]->ttl_qtt >= $qtt_tray )
            //     $index = 0;
            // else{
            //     if( ceil( (int)($device[0]->boxing) / 2 ) <= ( $qtt_tray - (int)$list_of_name_and_qtt_done[0]->ttl_qtt ) && (int)($device[0]->boxing) > ( $qtt_tray - (int)$list_of_name_and_qtt_done[0]->ttl_qtt ) )
            //         $index = 0;
            //     else
            //         $index = 1;
            // }
            $output_qty_count = 0;
            for ($index_name=0; $index_name < count($list_of_name_and_qtt_done); $index_name++) {
                $output_qty_count += $list_of_name_and_qtt_done[$index_name]->ttl_qtt;
                if( $output_qty_count >= $qtt_tray ){
                    $index = $index_name;
                    break;
                }
                else if( $index_name == count($list_of_name_and_qtt_done)-1 ){
                    $index = $index_name;
                    break;
                }
            }

            // return $list_of_name_and_qtt_done;

            if( $list_of_name_and_qtt_done[$index]->ct_area == $list_of_name_and_qtt_done[$index]->terminal_area ){
                $mm = explode(' ', $list_of_name_and_qtt_done[$index]->ct_area_info->name);
                $name .= $mm[0] . ' ' . $mm[2][0] . '.';
            }else{
                $mm = explode(' ', $list_of_name_and_qtt_done[$index]->ct_area_info->name);
                $name .= $mm[0] . ' ' . $mm[2][0] . '., ';
                $mm = explode(' ', $list_of_name_and_qtt_done[$index]->terminal_area_info->name);
                $name .= $mm[0] . ' ' . $mm[2][0] . '.';
            }
            // $fviname = strtoupper($name);
            $fviname = $name;

            if( $ttl >= $qtt_tray )
                $qtt_tray = (int)($device[0]->boxing);
            else
                $qtt_tray = (int)($device[0]->boxing) - ($qtt_tray - $ttl);

            // $qtt_tray = 0;
            // if( ((int)($device[0]->ship_boxing - $ttl_lot_qtt_box)) >= (int)($device[0]->boxing) ){
            //     $qtt_tray = (int)($device[0]->boxing);
            //     $ttl_lot_qtt_box += $qtt_tray;
            // }else{
            //     $qtt_tray = (int)($device[0]->ship_boxing) - ($ttl_lot_qtt_box + (int)($device[0]->boxing));
            // }

            $lcl_QrCode = QrCode::format('png')->errorCorrection('H')->size(200)->generate($oqcLotApp[0]->po_no . '
' . explode(' - ', $device[0]->name)[0] . '
' . $oqcLotApp[0]->lot_batch_no . '
' . $fviname . '
' . $serial_no . $ttl . '
' . $qtt_tray . '
' . ($lot_start_counter + $i) . '/' . ($lot_start_counter + $sticker_cnt));
            $lcl_QrCode = "data:image/png;base64," . base64_encode($lcl_QrCode);

// ' . $device[0]->boxing . '/' . $device[0]->ship_boxing . '

            $data[] = array('img' => $lcl_QrCode, 'text' => '<b><br>' .$oqcLotApp[0]->po_no . '</b><br>' . '<b>'. explode(' - ', $device[0]->name)[0]. '</b><br>' .
                $oqcLotApp[0]->lot_batch_no . '</b><br>' .
                $fviname . '<br>' .
                $serial_no_html .
                $ttl . '</b><br>' .
                $qtt_tray . '</b><br>' .
                ($lot_start_counter + $i) . '/' . ($lot_start_counter + $sticker_cnt).'</b>'); //nmodify

                // $device[0]->boxing . '/' . $device[0]->ship_boxing . '</b><br>' .

            // $content .= '<div class="rotated">';
            // $content .= '<tr>';
            //     $content .= '<td style="text-align: center;">';
            //     $content .= '<img src="' . $lcl_QrCode . '" style="min-width: 55px; max-width: 55px;">';
            //     $content .= '</td>';
            //     $content .= '<td style="font-size: 9px;">' . $oqcLotApp[0]->po_no . '<br>' . $oqcLotApp[0]->lot_batch_no . '<br>' . $oqcLotApp[0]->print_lot . '<br>' . $ttl . '<br>' . $device[0]->boxing . '/' . $device[0]->ship_boxing . '</td>';
            // $content .= '</tr>';
            // $content .= '</div>';

        $lbl = 'PO no.: ' . $oqcLotApp[0]->po_no . '<br>Device name: ' . explode(' - ', $device[0]->name)[0]. '<br>Lot no.: ' . $oqcLotApp[0]->lot_batch_no . '<br>FVI name: ' . $fviname . '<br>Actual lot quantity: ' . $ttl . '<br>Quantity per tray: ' . $qtt_tray . '<br>Count of tray/total tray per lot: ' . $prd_runcards_counter[$request->id] . '/' . $sticker_cnt;


        }

// '<br>Device Name: ' . explode(' - ', $device[0]->name)[0] .


// '<br>' . explode(' - ', $device[0]->name)[0] .
        // $lbl_hidden = $oqcLotApp[0]->po_no . '<br>' . $oqcLotApp[0]->lot_batch_no . '<br>' . $oqcLotApp[0]->print_lot . '<br>' . $ttl . '<br>' . $device[0]->boxing . '/' . $device[0]->ship_boxing;

        return response()->json(['QrCode' => $QrCode, 'label' => $lbl, 'label_hidden' => $data]);
    }

    public function bk_generate_qrcode_for_oqc_lot_app(Request $request)
    { //TEST
        //TODO: MIGZ 09-04-24 Remove Burn-in & Test, if the Device Name in WBS Issuance & Kitting
        $device_name = $request->device_name;
        $device_name = CommonController::getInstance()->validate_device_name($device_name);
        $oqcLotApp = oqcLotApp::where('fkid_runcard', $request->id)->get();
        $device = Device::where('name', $device_name)->where('status', 1)->get();
        // return $device;
        $result = ProductionRuncardStation::where('production_runcard_id', $request->id)->where('status', 1)->get();
        $ttl = 0;
        for ($i=0; $i < count($result); $i++)
            $ttl = $ttl + $result[$i]->qty_output;

// Device Name: ' . explode(' - ', $device[0]->name)[0] . '
        $QrCode = QrCode::format('png')->errorCorrection('H')->size(200)->generate($oqcLotApp[0]->po_no . '
' . $oqcLotApp[0]->lot_batch_no . '
' . $oqcLotApp[0]->print_lot . '
' . $ttl . '
' . $device[0]->ship_boxing);
        $QrCode = "data:image/png;base64," . base64_encode($QrCode);

        $po_no = ProductionRuncard::where('id', $request->id)->get()[0]->po_no;
        $prd_runcards = ProductionRuncard::where('po_no', $po_no)->orderBy('id')->get();
        $prd_runcards_counter = [];
        for ($i=0; $i < count($prd_runcards); $i++)
            $prd_runcards_counter[ $prd_runcards[$i]->id ] = ($i+1);

        $cnt = ceil($prd_runcards[0]->po_qty / $device[0]->ship_boxing);

        // return $cnt;

        $sticker_cnt = ceil($device[0]->ship_boxing / $device[0]->boxing);
        if( $device[0]->ship_boxing > $ttl )
            $sticker_cnt = ceil($ttl / $device[0]->boxing);
        $data = [];

        $content = '';
        for ($i=1; $i <= $sticker_cnt; $i++) {

            $lcl_QrCode = QrCode::format('png')->errorCorrection('H')->size(200)->generate($oqcLotApp[0]->po_no . '
' . $oqcLotApp[0]->lot_batch_no . '
' . $oqcLotApp[0]->print_lot . '
' . $ttl . '
' . $device[0]->boxing . '/' . $device[0]->ship_boxing . '
' . $prd_runcards_counter[$request->id] . '/' . $cnt);
            $lcl_QrCode = "data:image/png;base64," . base64_encode($lcl_QrCode);

            $data[] = array('img' => $lcl_QrCode, 'text' => '<b><br>' .$oqcLotApp[0]->po_no . '</b><br>' . '<b>'. explode(' - ', $device[0]->name)[0]. '</b><br>' .
                $oqcLotApp[0]->lot_batch_no . '</b><br>' .
                $oqcLotApp[0]->print_lot . '</b><br>' .
                $ttl . '</b><br>' .
                $device[0]->boxing . '/' . $device[0]->ship_boxing . '</b><br>' .
                $prd_runcards_counter[$request->id] . '/' . $cnt.'</b>');

            // $content .= '<div class="rotated">';
            // $content .= '<tr>';
            //     $content .= '<td style="text-align: center;">';
            //     $content .= '<img src="' . $lcl_QrCode . '" style="min-width: 55px; max-width: 55px;">';
            //     $content .= '</td>';
            //     $content .= '<td style="font-size: 9px;">' . $oqcLotApp[0]->po_no . '<br>' . $oqcLotApp[0]->lot_batch_no . '<br>' . $oqcLotApp[0]->print_lot . '<br>' . $ttl . '<br>' . $device[0]->boxing . '/' . $device[0]->ship_boxing . '</td>';
            // $content .= '</tr>';
            // $content .= '</div>';

        }

// '<br>Device Name: ' . explode(' - ', $device[0]->name)[0] .
        $lbl = 'PO No.: ' . $oqcLotApp[0]->po_no . '<br>Device Name: ' . explode(' - ', $device[0]->name)[0]. '<br>Runcard/Lot No.: ' . $oqcLotApp[0]->lot_batch_no . '<br>Serial No./WW/Print Lot No.: ' . $oqcLotApp[0]->print_lot . '<br>Actual Lot Quantity: ' . $ttl . '<br>Count of tray/total tray per lot: ' . $prd_runcards_counter[$request->id] . '/' . $cnt;

// '<br>' . explode(' - ', $device[0]->name)[0] .
        // $lbl_hidden = $oqcLotApp[0]->po_no . '<br>' . $oqcLotApp[0]->lot_batch_no . '<br>' . $oqcLotApp[0]->print_lot . '<br>' . $ttl . '<br>' . $device[0]->boxing . '/' . $device[0]->ship_boxing;

        return response()->json(['QrCode' => $QrCode, 'label' => $lbl, 'label_hidden' => $data, 'sticker_cnt' => $sticker_cnt]);
    }

    // Get added data
    public function get_oqc_lot_app_data(Request $request){
        // return 'asd';
        // return 'get_oqc_lot_app_data';
        // return $request['device_name'];
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
            'wbs_kitting.device_info'
        ])
        ->where('po_no',$request['po_no'])
        ->whereNull('deleted_at') // MIGZ 03-05-24 Condition to hide runcards
        // ->where(function($query){
        //     $query
        //         ->where('status', 7)
        //         ->orWhere('status', 8);
        // })
        // ->whereIn('status', [3,4])
        ->whereIn('status', [3,4])
        ->get();

        // return $oqc_inspections;

        // return $oqc_inspection;

        // return $request['device_name'];

        //TODO: MIGZ 09-04-24 Remove Burn-in & Test, if the Device Name in WBS Issuance & Kitting
        $wbs_device_name = $request['device_name'];
        $device_name_print = 'not found';
        $device_name_print = CommonController::getInstance()->validate_device_name($wbs_device_name);
        // return $wbs_device_name;

        if( count($oqc_inspections) > 0 ){
          $device = Device::where('name', $device_name_print)->where('status', 1)->get();
            // $device = Device::where('name', $wbs_device_name)->where('status', 1)->get();
        }else{
            $device = null;
        }

        // return $device_name_print;

        return DataTables::of($oqc_inspections)

        ->addColumn('action', function($oqc_inspection){
            $result = "";

            $result.='<button type="button" class="btn btn-sm btn-success btn_update_lot" id="btn_update" data-toggle="modal" value="'.$oqc_inspection['id'].'" title="View/Update Details"><i class="fa fa-pencil-alt fa-sm"></i></button>';
            // $result.=' <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" title="No Lot App Details"><i class="fa fa-print fa-sm"></i></button>';

            if ($oqc_inspection->oqc_details != null){
              // $result.=' <button type="button" class="btn btn-sm btn-primary btn_print_lot" id="btn_print" data-toggle="modal" value="'.$oqc_inspection['id'].'" title="Print barcode"><i class="fa fa-print fa-sm"></i></button>';
              $result.=' <button type="button" class="btn btn-sm btn-primary btn_print_lot" id="btn_print" data-toggle="modal" value="'.$oqc_inspection->oqc_details['fkid_runcard'].'" title="Print barcode"><i class="fa fa-print fa-sm"></i></button>';
            //   $result.=' <button type="button" class="btn btn-sm btn-primary btnEditWW" value="'.$oqc_inspection->oqc_details['fkid_runcard'].'" title="Edit WW"><i class="fa fa-edit fa-sm"></i></button>';
              $result.=' <button type="button" class="btn btn-sm btn-primary btnEditWW" value="'.$oqc_inspection['id'].'" title="Edit WW"><i class="fa fa-edit fa-sm"></i></button>'; // 05242024 by Nessa
            }else{
                if( $oqc_inspection['id'] != 28008 )
                    $result.=' <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" title="No Lot App Details" disabled><i class="fa fa-print fa-sm"></i></button>';
                // else
                //     $result.=' <button type="button" class="btn btn-sm btn-primary btn_print_lot" id="btn_print" data-toggle="modal" value="'.$oqc_inspection->oqc_details['fkid_runcard'].'" title="Print barcode"><i class="fa fa-print fa-sm"></i></button>';
            }
            return $result;
        })

        ->addColumn('lot_qty', function($oqc_inspection) use ($device){
            if( isset( $device[0]->ship_boxing ) )
                return $device[0]->ship_boxing;
            else
                return 0;
        })

        ->addColumn('output_qty_raw', function($oqc_inspection){

            $result = ProductionRuncardStation::where('production_runcard_id', $oqc_inspection->id)->where('status', 1)->get();
            $ttl = 0;
            for ($i=0; $i < count($result); $i++)
                $ttl = $ttl + $result[$i]->qty_output;
            return $ttl;

            // $result = "";

            // if( $oqc_inspection->require_oqc_before_emboss == 1){ //old

            //     $temp_prod_runcard_station_many_details = array();
            //     foreach ($oqc_inspection['prod_runcard_station_many_details'] as $key => $value) {
            //         if( $value['has_emboss'] == 0 ){
            //             $temp_prod_runcard_station_many_details[] = $value;
            //         }
            //         // else{
            //         //     $temp_prod_runcard_station_many_details[] = $value;
            //         // }
            //     }

            //     $ix = count($temp_prod_runcard_station_many_details);
            //     if($ix>0)
            //         $result = $temp_prod_runcard_station_many_details[$ix-1]->qty_output;

            //     if ( $oqc_inspection['oqc_details'] ){
            //         if ( $oqc_inspection['oqc_details']->output_qty != null ){
            //             $result = $oqc_inspection['oqc_details']->output_qty;
            //         }
            //     }


            // }else{

            //     $ix = count($oqc_inspection['prod_runcard_station_many_details']);
            //     if($ix>0)
            //         $result = $oqc_inspection['prod_runcard_station_many_details'][$ix-1]->qty_output;

            //     if ( $oqc_inspection['oqc_details'] ){
            //         if ( $oqc_inspection['oqc_details']->output_qty != null ){
            //             $result = $oqc_inspection['oqc_details']->output_qty;
            //         }
            //     }
            // }

            // return $result;

            /*$col_prod_runcard = null;

                // if($prod_runcard->require_oqc_before_emboss == 1){
                //     if(count($prod_runcard->prod_runcard_station_many_details) > 0){
                //         $total_no_of_emboss_ng = 0;

                //         $col_prod_runcard = collect($prod_runcard->prod_runcard_station_many_details)->where('has_emboss', 0)->sortByDesc('step_num')->slice(0, 1)->flatten(1);
                //     }
                // }
                // else{
                $qty_output = 0;
                    if(count($oqc_inspection->prod_runcard_station_many_details) > 0){
                        $total_no_of_emboss_ng = 0;

                        $col_prod_runcard = collect($oqc_inspection->prod_runcard_station_many_details)->sortByDesc('step_num')->slice(0, 1)->flatten(1);
                        $qty_output = $col_prod_runcard->pluck('qty_output')[0];
                    }
                // }

                // return $total_no_of_emboss_ng;


                // if($col_prod_runcard > 0){
                // }
                return $qty_output;*/
        })

        ->addColumn('fvo_raw', function($oqc_inspection){
            $result = null;

            if ( isset($oqc_inspection['oqc_details']['id']) ){
                $empno_arr = array();
                $empno_arr = $oqc_inspection['oqc_details']->FVO_empid;
                $empno_arr = explode(',', $empno_arr);
                $user_details = array();
                // if(count( $empno_arr )){
                    for ($i=0; $i < count($empno_arr) ; $i++) {
                        $user_details_temp = array();
                        $user_details_temp = User::where('id',$empno_arr[$i])->get();
                        if( count($user_details_temp) > 0 )
                            array_push($user_details, $user_details_temp[0]);
                    }
                // }
                // return $user_details;

                // if( count($user_details) ){
                    foreach ($user_details as $key => $value) {
                        $result .= '<span class="badge badge-pill badge-info"> '.$value['name'].'</span> ';
                    }
                // }
            }

            return $result;
        })

        ->addColumn('status_raw', function($oqc_inspection){
            $result = "";

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

        ->addColumn('sub_lot_raw', function($oqc_inspection)use($oqc_inspections, $device){
            $result = "";
            $runcard_no = ($oqc_inspection->runcard_no);
            $newstring = substr($runcard_no, -3);

            // $device_name = ($oqc_inspection->wbs_kitting->device_name); // 07152024
            // $devices = Device::where('name', $device_name)->get()->take(1);
            $devices = $device;

            // return $device_name;
            // return $oqc_inspections;
            $ctr = 1;
            // return $devices;
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

            return $ctr;
        })

        // ->addColumn('packing_code_raw', function($oqc_inspection)use($oqc_inspections){
        //     $result = "";
        //     $series_name = "";

        //     $runcard_no = ($oqc_inspection->runcard_no);
        //     $newstring = substr($runcard_no, -3);

        //     $device_name = ($oqc_inspection->wbs_kitting->device_name);
        //     $devices     = Device::where('name', $device_name)->get()->take(1);
        //     $exp_series  = explode("-", $devices[0]->name)[0];
        //     $series_code = Series::where('series_name', 'like','%'.$exp_series.'%')->get()->take(1);
        //     $month       = date('m');

        //     $ctr = 1;
        //     if ( $devices ){

        //         $i_total = 1000;
        //         $q = $devices[0]->ship_boxing / $devices[0]->boxing;

        //         for($i=$q;$i<$i_total; $i=$i+$q){
        //             if( $newstring <= $i ){
        //                 break;
        //             }
        //             $ctr++;
        //         }

        //     }
        //     $counter = sprintf('%03d', $ctr);

        //     $packing_code = $series_code[0]->series_code.''.$month.'-'.$counter;
        //     return $packing_code;

        // })

        ->addColumn('ww', function($oqc_inspection){
            $result = "";
            if ($oqc_inspection->oqc_details != null){
                $result = $oqc_inspection->oqc_details->ww;
            }else{
                $result ='---';
            }

            return $result;
        })

        ->rawColumns(['action','status_raw','fvo_raw','output_qty_raw','subm_raw','sub_lot_raw'])
        // ->rawColumns(['action','status_raw','fvo_raw','output_qty_raw','subm_raw','sub_lot_raw','packing_code_raw'])
        ->make(true);
    }

    // Get lot app summary
    public function get_oqc_lot_app_data_summary(Request $request){

        $data = oqcLotApp::where( 'fkid_runcard', $request['lot_batch_no'] )->get();
        // $runcards = ProductionRuncard::where('po_no', )
        $po = '0';
        if( count($data) > 0 )
            $po = $data[0]->po_no;

        // $oqc_inspections = oqcLotApp::where('po_no',$po)
        $oqc_inspections = oqcLotApp::where( 'fkid_runcard', $request['lot_batch_no'] )
        // ->where('status', 2)
        ->orderBy('submission','asc')->get();
        return DataTables::of($oqc_inspections)
        ->addColumn('sub_raw', function($oqc_inspection){
            $result = "";

                switch ($oqc_inspection->submission) {
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

            return $result;
        })

        // ->addColumn('devcat_raw', function($oqc_inspection){
        //     $result = "";

        //         switch ($oqc_inspection->device_cat) {
        //             case 1:
        //                 $result ='Automotive';
        //                 break;
        //             case 2:
        //                 $result ='Non-Automotive';
        //                 break;
        //         }

        //     return $result;
        // })

        // ->addColumn('certlot_raw', function($oqc_inspection){
        //     $result = "";

        //         switch ($oqc_inspection->cert_lot) {
        //             case 1:
        //                 $result ='New Operator';
        //                 break;
        //             case 2:
        //                 $result ='New product/model';
        //                 break;
        //             case 3:
        //                 $result ='Evaluation lot';
        //                 break;
        //             case 4:
        //                 $result ='Re-inspection';
        //                 break;
        //             case 5:
        //                 $result ='Flexibility';
        //                 break;
        //             case 6:
        //                 $result ='N/A';
        //                 break;
        //         }

        //     return $result;
        // })

        // ->addColumn('assy_raw', function($oqc_inspection){
        // $result = "";

        // $assy_line = AssemblyLine::where('id',$oqc_inspection['assy_line'])->get();
        //     if( $assy_line ){
        //         $result = $assy_line[0]->name;
        //     }

        // return $result;

        // })

        ->addColumn('guar_lot_raw', function($oqc_inspection){
            $result = "";

                switch ($oqc_inspection->guaranteed_lot) {
                    case 1:
                        $result = '<font color="red">With</font>';
                        break;
                    case 2:
                        $result = '<font color="black">Without</font>';
                        break;
                }

            return $result;
        })

        ->addColumn('fvo_raw', function($oqc_inspection){
            $result = null;

            if ( $oqc_inspection ){
                $empno_arr = array();
                $empno_arr = $oqc_inspection->FVO_empid;
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

        ->addColumn('app_date_raw', function($oqc_inspection){
            $result = "";
                $result = date('F j, Y',strtotime($oqc_inspection->app_date));
            return $result;
        })

        ->addColumn('app_time_raw', function($oqc_inspection){
            $result = "";
                $result .= date('h:i a',strtotime('2001-01-01'.$oqc_inspection->app_time));
            return $result;
        })

        ->addColumn('action', function($oqc_inspection){
            $result = "";
            return $result;
        })

        ->rawColumns(['action','guar_lot_raw','sub_raw','fvo_raw','app_date_raw','app_time_raw'])
        ->make(true);
    }

    // Get lot app by ID
    public function get_lot_app_by_id(Request $request){
        // $lot_app_by_id = oqcLotApp::with(['wbs_kitting'])->where('id',$request['id'])->get(); //02072020
        $lot_app_by_id = oqcLotApp::with(['wbs_kitting'])->where('fkid_runcard',$request['id'])->get();
        $lot_app_code = '';
        $po_no = '';

        if($lot_app_by_id->count() > 0){
            $lot_app_code = QrCode::format('png')
                            ->size(200)->errorCorrection('H')
                            ->generate($lot_app_by_id[0]->lot_batch_no);

            $lot_app_code = "data:image/png;base64," . base64_encode($lot_app_code);
        }

        if($lot_app_by_id->count() > 0){
            $po_no = QrCode::format('png')
                            ->size(200)->errorCorrection('H')
                            ->generate($lot_app_by_id[0]->po_no);

            $po_no = "data:image/png;base64," . base64_encode($po_no);
        }

        $inspection_standard = [];
        $rdrawing   = [];
        $IS_drawing = '';
        $R_drawing  = '';
        $A_drawing  = '';

        if ( count($lot_app_by_id) > 0 ){
            $doc_details_query = RapidActiveDocs::where('doc_title', 'LIKE', '%' . $lot_app_by_id[0]->wbs_kitting->device_name . '%')->get();

            if(count($doc_details_query) > 0){
                $inspection_standard = collect($doc_details_query)->where('doc_type', 'Inspection Standard')->flatten(1);
                $rdrawing = collect($doc_details_query)->where('doc_type', 'R Drawing')->flatten(1);
                $adrawing = collect($doc_details_query)->where('doc_type', 'A Drawing')->flatten(1);
            }

            if($inspection_standard->count() > 0){
                $IS_drawing = QrCode::format('png')
                                ->size(200)->errorCorrection('H')
                                ->generate($inspection_standard[0]->doc_no);
                                // ->generate($inspection_standard[0]->doc_no.'-'.$inspection_standard[0]->rev_no); //02072020

                $IS_drawing = "data:image/png;base64," . base64_encode($IS_drawing);
            }

            if($rdrawing->count() > 0){
                $R_drawing = QrCode::format('png')
                                ->size(200)->errorCorrection('H')
                                ->generate($rdrawing[0]->doc_no);
                                // ->generate($rdrawing[0]->doc_no.'-'.$rdrawing[0]->rev_no); //02072020

                $R_drawing = "data:image/png;base64," . base64_encode($R_drawing);
            }

            if($adrawing->count() > 0){
                $A_drawing = QrCode::format('png')
                                ->size(200)->errorCorrection('H')
                                ->generate($adrawing[0]->doc_no);
                                // ->generate($rdrawing[0]->doc_no.'-'.$rdrawing[0]->rev_no); //02072020

                $A_drawing = "data:image/png;base64," . base64_encode($A_drawing);
            }


        }

        return response()->json(['lot_app_by_id' => $lot_app_by_id, 'lot_app_code' => $lot_app_code, 'po_no' => $po_no, 'inspection_standard' => $inspection_standard, 'rdrawing' => $rdrawing, 'adrawing' => $adrawing, 'IS_drawing' => $IS_drawing, 'R_drawing' => $R_drawing, 'A_drawing' => $A_drawing]);
    }

    // Get runcard data
    public function get_runcard_details(Request $request){

        $runcard_details = ProductionRuncard::with([
            'oqc_details' => function($query){
                $query->orderBy('submission', 'DESC');},
            'oqc_details.oqcvir_details',
            'oqc_details.user_details',
            'oqc_details.supervisor_prod_info',
            'oqc_details.supervisor_qc_info',
            'prod_runcard_station_many_details' => function($query){
                $query
                // $query->where('has_emboss', '!=', 1)
                ->orderBy(\DB::raw('CONVERT(SUBSTRING_INDEX(step_num,"-", 1), UNSIGNED INTEGER)', 'ASC'))
                ->orderBy(\DB::raw('right(step_num,LOCATE("-",step_num) - 1)', 'ASC'));
            },
            'wbs_kitting',
            'wbs_kitting.device_info',
            'yeu_kitting' // added 7/15/24 darren DAPAT SA OQCLOTAPPNEWCONTROLLER

        ])
        ->where('id',$request['id'])
        ->where(function($query){
            $query
                ->where('status', 7)
                ->orWhere('status', 8);
        })
        ->get();
        //- sub lot no.

        // return $runcard_details[0]->yeu_kitting;

        $sub_lot_no = 0;
        if( $runcard_details ){
            $runcard_no = ($runcard_details[0]->runcard_no);
            $newstring = substr($runcard_no, -3);

            // added 7/15/24 darren DAPAT SA OQCLOTAPPNEWCONTROLLER
            if($runcard_details[0]->wbs_kitting == null ){
                $device_name = $runcard_details[0]->yeu_kitting->product_name;
            }else{
                $device_name = $runcard_details[0]->wbs_kitting->device_name;
            }

            //TODO: MIGZ 09-04-24 Remove Burn-in & Test, if the Device Name in WBS Issuance & Kitting
            $wbs_device_name = CommonController::getInstance()->validate_device_name($device_name);
            $devices = Device::where('name', $wbs_device_name)->get()->take(1);

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
        // return $runcard_details;
    }

    // Get PO Details from WBS kitting
    public function get_po_details(Request $request){

        // return 'WORKING';

        $subPO = substr($request->po, 0, 15); // added by migs and jd 11-06-2023
         $po_details = ProductionRuncard::with([
        'wbs_kitting',
        'wbs_kitting.device_info'])
        ->where('po_no', $subPO)
        ->get();



        // return $po_details;
        // return $subPO;

        // added 7/15/24 darren
        if(is_null($po_details[0]->wbs_kitting)){
            $po_details = ProductionRuncard::with([
                'yeu_kitting'
            ])
            ->where('po_no', $subPO)
            // ->whereNotNull('product_name')
            ->get();

            $device_name_print = 'not found';

            $device_name_print = CommonController::getInstance()->validate_device_name($po_details[0]['yeu_kitting']);
        }else{
            $device_name_print = 'not found';

            $device_name_print = CommonController::getInstance()->validate_device_name($po_details[0]['wbs_kitting']);
        }

        // return $device_name_print;

        return response()->json(['po_details' => $po_details,'device_name_print' => $device_name_print]);
    }

    // Get User details
    public function get_user_details_lotapp(Request $request){
        $user_details = User::where('employee_id','like','%'. $request->employee_id.'%')
        ->orWhere('name','like','%'. $request->employee_id.'%')
        ->orWhere('id','like','%'. $request->employee_id.'%')->get();
        return response()->json(['user_details' => $user_details]);
    }

    // Get User details - fullname arr
    public function get_user_details_lotapp_arr(Request $request){
        $empno_arr = array();
        $empno_arr = $request->empno_arr;
        $empno_arr = explode(',', $empno_arr);
        $user_details = array();
        if(count( $empno_arr )){
            for ($i=0; $i < count($empno_arr) ; $i++) {
                $user_details_temp = array();
                $user_details_temp = User::where('id',$empno_arr[$i])->get();
                array_push($user_details, $user_details_temp);
            }
        }
        return response()->json(['user_details' =>  $user_details ]);
    }

    // Get Production User details
    public function get_prod_user_details(Request $request){
        $user_details = User::where('employee_id', $request->employee_id)->where('position', 1)->get();
        return response()->json(['user_details' => $user_details]);
    }

    // Get OQC User details
    public function get_oqc_user_details(Request $request){
        $user_details = User::where('employee_id', $request->employee_id)->where('position', 2)->get();
        return response()->json(['user_details' => $user_details]);
    }

    // Add
    public function add_oqc_lot_app(Request $request){
        date_default_timezone_set('Asia/Manila');

        $user_exist = User::where('id', $request->add_packing_operator_name)->get();

        if($user_exist->count() == 0){
            return response()->json(['result' => "2"]); //invalid employee no.
        }

        if ( $request->hidden_sub > 3 ){
            return response()->json(['result' => "3"]); // more than 3rd sub Device name not found
        }

        $data = $request->all();

        $validator = Validator::make($data, [
            'hidden_runcard_id' => ['required', 'string', 'max:255'],
            'name_po_no' => ['required', 'string', 'max:255'],
            'name_select_Device' => ['required', 'string', 'max:255'],
            'name_CertLot' => ['required', 'string', 'max:255'],
            'name_AssyLine' => ['required', 'string', 'max:255'],
            'name_LotBatch' => ['required', 'string', 'max:255'],
            'add_packing_operator_name' => ['required', 'string', 'max:255'],
            'name_ReelNo' => ['string', 'max:255'],
            'name_PrintLotNo' => ['required', 'string', 'max:255'],
            'name_LotQty' => ['required', 'string', 'max:255'],
            'name_OutputQty' => ['required', 'string', 'max:255'],
            'name_UrgentDirection' => ['max:255'],
            'name_ADrawing' => ['required', 'string', 'max:255'],
            'name_GDrawing' => ['required', 'string', 'max:255'],
            'name_TtlNoReels' => ['max:255'],
            'name_AppDate' => ['max:255'],
            'name_AppTime' => ['max:255'],
            'name_GuaranteedLot' => ['required', 'max:255'],
            'name_Problem' => ['max:255'],
            'name_DocNo' => ['max:255'],
            'name_prodn_supv' => ['max:255'],
            'name_oqc_supv' => ['max:255'],
            'name_OQCRemarks' => ['max:255'],
            'partial_lot_confirmation' => ['max:255'],
            'name_ww' => ['required']

        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{

            DB::beginTransaction();

            if ( $request->name_OutputQty == $request->name_LotQty ){
                try{

                    $runcard = ProductionRuncard::find($request->hidden_runcard_id);

                    // return $runcard;

                    if( isset($runcard->id) ){
                        //TODO: MIGZ 09-04-24 Remove Burn-in & Test, if the Device Name in WBS Issuance & Kitting
                        $device_name = $runcard->device_name;
                        $dn = $runcard->device_name;
                        $d_name = Device::where('name', $dn)->get();
                        $device_name = CommonController::getInstance()->validate_device_name($device_name);
                        $device = Device::where('name', $device_name)->get();
                        if( count($device)>0 ){

                            OQCLotApp::insert([
                                'fkid_runcard' => $request->hidden_runcard_id,
                                'po_no' => $request->name_po_no,
                                'status' => 2,
                                'submission' => $request->hidden_sub,
                                'device_cat' => $request->name_select_Device,
                                'cert_lot' => $request->name_CertLot,
                                'assy_line' => $request->name_AssyLine,
                                'lot_batch_no' => $request->name_LotBatch,
                                'sub_lot_no' => $request->hidden_sub_lot,
                                'FVO_empid' => $request->add_packing_operator_name,
                                'reel_lot' => $request->name_ReelNo,
                                'print_lot' => $request->name_PrintLotNo,
                                'lot_qty' => $request->name_LotQty,
                                'output_qty' => $request->name_OutputQty,
                                'direction' => ($request->name_UrgentDirection)?$request->name_UrgentDirection:'N/A',
                                'Adrawing' => $request->name_ADrawing,
                                'Gdrawing' => $request->name_GDrawing,
                                'ttl_reel' => ($request->name_TtlNoReels)?$request->name_TtlNoReels:'N/A',
                                'app_date' => $request->name_AppDate,
                                'partial_lot_confirmation' => $request->partial_lot_confirmation,
                                'app_time' => date('H:i:s'),
                                'guaranteed_lot' => $request->name_GuaranteedLot,
                                'problem' => ($request->name_Problem)?$request->name_Problem:'N/A',
                                'doc_no' => ($request->name_DocNo)?$request->name_DocNo:'N/A',
                                'remarks' => ($request->name_OQCRemarks)?$request->name_OQCRemarks:'N/A',
                                'ww' => ($request->name_ww)?$request->name_ww:'N/A',
                                // 'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);

                            /**
                             * This will only execute if the Process - Overall Inspection(column) in the Matrix(Module) is updated
                             * Else this will not update the Production Runcard status(column) to 4 that is needed to the next process
                             * Since the next process OQC Inspection(Module) pre-requisites the Production Runcard status(column) value that needs to be 4
                             * ? -JD
                             */
                            // if( $device[0]->process==0)
                            if( $device[0]->process==0 || $d_name[0]->process==0) // by nessa 05152024
                                ProductionRuncard::where('id', $request->hidden_runcard_id)->update([ 'status' => 4, ]);

                            // DB::rollback();
                            DB::commit();
                            return response()->json(['result' => "1"]);
                        }
                        return response()->json(['error_msg' => "Device name not found."]);
                    }
                    return response()->json(['error_msg' => "Runcard not found."]);

                }
                catch(\Exception $e) {
                    DB::rollback();
                    return response()->json(['result' => $e]);
                }

            } else {
                try{

                    $runcard = ProductionRuncard::find($request->hidden_runcard_id);

                    // return $runcard;
                    if( isset($runcard->id) ){
                        //TODO: MIGZ 09-04-24 Remove Burn-in & Test, if the Device Name in WBS Issuance & Kitting
                        $device_name = $runcard->device_name;
                        $device_name = CommonController::getInstance()->validate_device_name($device_name);
                        $device = Device::where('name', $device_name)->get();

                        // return $device_name;
                        if( count($device)>0 ){

                            OQCLotApp::insert([
                                'fkid_runcard' => $request->hidden_runcard_id,
                                'po_no' => $request->name_po_no,
                                'status' => 0,
                                'submission' => $request->hidden_sub,
                                'device_cat' => $request->name_select_Device,
                                'cert_lot' => $request->name_CertLot,
                                'assy_line' => $request->name_AssyLine,
                                'lot_batch_no' => $request->name_LotBatch,
                                'sub_lot_no' => $request->hidden_sub_lot,
                                'FVO_empid' => $request->add_packing_operator_name,
                                'reel_lot' => $request->name_ReelNo,
                                'print_lot' => $request->name_PrintLotNo,
                                'lot_qty' => $request->name_LotQty,
                                'output_qty' => $request->name_OutputQty,
                                'direction' => ($request->name_UrgentDirection)?$request->name_UrgentDirection:'N/A',
                                'Adrawing' => $request->name_ADrawing,
                                'Gdrawing' => $request->name_GDrawing,
                                'ttl_reel' => ($request->name_TtlNoReels)?$request->name_TtlNoReels:'N/A',
                                'app_date' => $request->name_AppDate,
                                'partial_lot_confirmation' => $request->partial_lot_confirmation,
                                'app_time' => date('H:i:s'),
                                'guaranteed_lot' => $request->name_GuaranteedLot,
                                'problem' => ($request->name_Problem)?$request->name_Problem:'N/A',
                                'doc_no' => ($request->name_DocNo)?$request->name_DocNo:'N/A',
                                'remarks' => ($request->name_OQCRemarks)?$request->name_OQCRemarks:'N/A',
                                'ww' => ($request->name_ww)?$request->name_ww:'N/A',
                                'created_at' => date('Y-m-d H:i:s'),
                                // 'updated_at' => date('Y-m-d H:i:s')
                            ]);

                            /**
                             * This will only execute if the Process - Overall Inspection(column) in the Matrix(Module) is updated
                             * Else this will not update the Production Runcard status(column) to 4 that is needed to the next process
                             * Since the next process OQC Inspection(Module) pre-requisites the Production Runcard status(column) value that needs to be 4
                             * ? -JD
                             */
                            if( $device[0]->process==0 )
                                ProductionRuncard::where('id', $request->hidden_runcard_id)->update([ 'status' => 4, ]);

                            DB::commit();
                            // DB::rollback();
                            return response()->json(['result' => "1"]);
                        }
                        return response()->json(['error_msg' => "Device name not found."]);
                    }
                    return response()->json(['error_msg' => "Runcard not found."]);

                }
                catch(\Exception $e) {
                    DB::rollback();
                    return response()->json(['result' => $e]);
                }



                // return response()->json(['result' => "4"]); // not equal to lot qty

            }

        }
    }

    // Get OQC lot app data
    public function get_oqc_lot_app_details(Request $request){
        $oqc_details = oqcLotApp::with(['user_details'])->where('id',$request['id'])->get();
        return $oqc_details;
    }

    // Update approved Prodn
    public function update_approved_prod(Request $request){
        date_default_timezone_set('Asia/Manila');

        // $user_exist = User::where('id', $request->name_prodn_supv_id)->get();
        $user_exist = User::where('employee_id', $request->name_prodn_supv_id)->get();
        if($user_exist->count() == 0){
            return response()->json(['result' => "2"]); //invalid employee no.
        }

        //update
        DB::beginTransaction();
        try {
            oqcLotApp::where('id',$request->hidden_OQCLotApp_id_query)
                ->update(
                    [
                        'prodn_supervisor'=>$user_exist[0]->id,
                        'status'=>1,
                        'updated_at'=>date('Y-m-d H:i:s'),
                    ]
                );
            DB::commit();
            return response()->json(['result' => '1']);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['result' => $e]);
        }

        return $result;
    }

    // Update approved OQC
    public function update_approved_oqc(Request $request){
        date_default_timezone_set('Asia/Manila');

        // $user_exist = User::where('id', $request->name_oqc_supv_id)->get();
        $user_exist = User::where('employee_id', $request->name_oqc_supv_id)->get();
        if($user_exist->count() == 0){
            return response()->json(['result' => "2"]); //invalid employee no.
        }

        //update
        DB::beginTransaction();
        try {
            oqcLotApp::where('id',$request->hidden_OQCLotApp_id_query_oqc)
                ->update(
                    [
                        'oqc_supervisor'=>$user_exist[0]->id,
                        'status'=>2,
                        'updated_at'=>date('Y-m-d H:i:s'),
                    ]
                );
            DB::commit();
            return response()->json(['result' => '1']);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['result' => $e]);
        }

        return $result;
    }

    //EXPORT EXCEL
    public function export_parts_lot_record(Request $request){

        $myStartDate    = $request->start_date;
        $myEndDate      = $request->end_date;
        $device_name    = $request->series_name;

        $partsLot_records = ProductionRuncard::with([
            'prod_runcard_material_list',
            'prod_runcard_material_list.wbs_material_kitting' => function($query){
                $query->distinct('item_desc');
            },
            'prod_runcard_material_list.wbs_sakidashi_issuance',
            'prod_runcard_material_list.wbs_sakidashi_issuance.tbl_wbs_sakidashi_issuance_item',
            'prod_runcard_material_list.wbs_material_kitting.parts_prep',
            'prod_runcard_material_list.wbs_material_kitting.parts_prep.user_details',
            'wbs_kitting',
            'wbs_kitting.device_info' => function($query)use($myStartDate,$myEndDate,$request){
                $query->where('name', $request->series_name);
            }
        ])
        ->whereDate('updated_at', '>=', $myStartDate)
        ->whereDate('updated_at', '<=', $myEndDate)
        ->orderBy('updated_at', 'ASC')
        ->get();

        return Excel::download(new PartsLotReportExport($partsLot_records, $device_name), $request->series_name . ' - PATS generated.xlsx');

        if(count($partsLot_records) > 0)
        {
            return view('exports.partslot')->with(compact('partsLot_records','device_name'));
        }
        else
        {
            echo "<script>";
            echo "alert('No data found for " . $device_name . " from " . $request->start_date . " to " . $request->end_date."');";
            echo "window.close();";
            echo "</script>";
        }

    }

}
