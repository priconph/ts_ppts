<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;
use QrCode;

class CommonController extends Controller
{
    private static $instance = null;

	public static function getInstance() {
		if(!self::$instance instanceof self)
		{
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function __clone()
	{
		trigger_error('Clone is not allowed.',E_USER_ERROR);
	}

	public function __wakeup()
	{
		trigger_error('Deserializing is not allowed.',E_USER_ERROR);
	}

    public function generate_qrcode(Request $request){
        try{
            if(isset($request->qrcode)){
                $qrcode = QrCode::format('png')
                        ->size(200)->errorCorrection('H')
                        ->generate($request->qrcode);

                return response()->json(['result' => "1", 'qrcode' => "data:image/png;base64," . base64_encode($qrcode)]);
            }
            else{
                return response()->json(['result' => "0"]);
            }
        }
        catch(\Exception $e){
            return response()->json(['result' => "0"]);
        }
    }
    public function validate_device_name($device_name, $for_packing_only = false){
        // return $device_name;

        // return 'asd';

        if(isset($device_name)){
            if( strpos( $device_name, "Test" ) == true) {
                $temp = explode('-', $device_name);
                unset($temp[count($temp) - 1]);
                $device_name = implode('-', $temp);
                $device_name = trim($device_name);
            }
            if( strpos( $device_name, "Burn-in Others" ) == true) {
                $device_name = trim($device_name,"- (Burn-in Others)");
                if( strpos( $device_name, "(" ) == true) {
                    $device_name = $device_name.")";
                }
            }
            if( strpos( $device_name, "- (Burn-in)" ) == true) {
                $device_name = trim($device_name,"- (Burn-in)");
                if( strpos( $device_name, "(" ) == true) {
                    $device_name = $device_name.")";
                }
            }
            if( strpos( $device_name, "- (Burn-in Sockets)" ) == true) {
                $device_name = trim($device_name,"- (Burn-in Sockets)");
                if( strpos( $device_name, "(" ) == true) {
                    $device_name = $device_name.")";
                }
            }
            if( strpos( $device_name, "- (Burn-in others)" ) == true) {
                $device_name = trim($device_name,"- (Burn-in others)");
                if( strpos( $device_name, "(" ) == true) {
                    $device_name = $device_name.")";
                }
            }
        }
        return $device_name;
    }

    public function validate_device_name_acdcs_packing($device_name){
        // return $device_name;
        if(isset($device_name)){
            if( strpos( $device_name, "Test" ) == true) {
                $temp = explode('-', $device_name);
                unset($temp[count($temp) - 1]);
                $device_name = implode('-', $temp);
                $device_name = trim($device_name);
            }
            if( strpos( $device_name, "Burn-in Others" ) == true) {
                $device_name = trim($device_name,"- (Burn-in Others)");
                if( strpos( $device_name, "(" ) == true) {
                    $device_name = $device_name.")";
                }
            }
            if( strpos( $device_name, "Burn-in Memory" ) == true) {
                $device_name = trim($device_name,"- (Burn-in Memory)");
                if( strpos( $device_name, "(" ) == true) {
                    $device_name = $device_name.")";
                }
            }
            if( strpos( $device_name, "- (Burn-in)" ) == true) {
                $device_name = trim($device_name,"- (Burn-in)");
                if( strpos( $device_name, "(" ) == true) {
                    $device_name = $device_name.")";
                }
            }
            if( strpos( $device_name, "- (Burn-in Sockets)" ) == true) {
                $device_name = trim($device_name,"- (Burn-in Sockets)");
                if( strpos( $device_name, "(" ) == true) {
                    $device_name = $device_name.")";
                }
            }
            if( strpos( $device_name, "- (Burn-in others)" ) == true) {
                $device_name = trim($device_name,"- (Burn-in others)");
                if( strpos( $device_name, "(" ) == true) {
                    $device_name = $device_name.")";
                }
            }
        }
        return $device_name;
    }

    /**/

}
