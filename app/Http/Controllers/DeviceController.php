<?php

namespace App\Http\Controllers;

use App\Model\Device;
use App\Model\YeuKitting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;
use Illuminate\Validation\Rules\Exists;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CSVPackingMatrixImport;

class DeviceController extends Controller
{
    //View Devices
	public function view_devices(){
    	$devices = Device::all();

        return DataTables::of($devices)
            ->addColumn('label1', function($device){
                $result = "";

                if($device->status == 1){
                    $result .= '<span class="badge badge-pill badge-success">Active</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
                }

                return $result;
            })
            ->addColumn('type_name', function($device){
                $result = "";

                if($device->type == 1){
                    $result .= 'Burn-in Sockets';
                }else if($device->type == 2){
                    $result .= 'Micron PATS';
                }else if($device->type == 3){
                    $result .= 'Test Sockets';
                }else{
                    $result .= '---';
                }

                return $result;
            })
            ->addColumn('label_name', function($device){
                $result = "";

                if($device->label == 0){
                    $result .= 'No Series Name';
                }else if($device->label == 1){
                    $result .= 'With Series Name';
                }else{
                    $result .= '---';
                }

                return $result;
            })
            ->addColumn('process1', function($device){
                $result = "";

                if($device->process == 0){
                    $result .= '<span class="badge badge-pill badge-primary">Without</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-success">With</span>';
                }

                return $result;
            })
            ->addColumn('action1', function($device){
                $result = '<center><div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
                            <i class="fa fa-cog"></i>
                          </button>
                          <div class="dropdown-menu dropdown-menu-right">';
                if($device->status == 1){
                	$result .= '<button class="dropdown-item aEditDevice" type="button" device-id="' . $device->id . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalEditDevice" data-keyboard="false">Edit</button>';

                    $result .= '<button class="dropdown-item aChangeDeviceStat" type="button" device-id="' . $device->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeDeviceStat" data-keyboard="false">Deactivate</button>';

                    // $result .= '<button class="dropdown-item aShowDeviceDevProc" device-id="' . $device->id . '" device-name="' . $device->name . '" type="button" style="padding: 1px 1px; text-align: center;">Show Device Process</button>';

                    // $result .= '<button class="dropdown-item aGenDeviceBarcode" device-id="' . $device->barcode . '" type="button" style="padding: 1px 1px; text-align: center;">Generate Barcode</button>';

                    // Removed temporarily - undefined value from barcode
                    // $result .= '<button class="dropdown-item aGenerateBarcode" device-id="' . $device->id . '" barcode="' . $device->barcode . '" type="button" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalGenBarcode">Generate Barcode</button>';
                }
                else{
                    $result .= '<button class="dropdown-item aChangeDeviceStat" type="button" style="padding: 1px 1px; text-align: center;" device-id="' . $device->id . '" status="1" data-toggle="modal" data-target="#modalChangeDeviceStat" data-keyboard="false">Activate</button>';
                }

                $result .= '<input type="hidden" value="' . $device->id . '" class="form-control td_device_id">';
                $result .= '<input type="hidden" value="' . $device->name . '" class="form-control td_device_name">';

                $result .= '</div>
                        </div></center>';

                return $result;
            })
            ->rawColumns(['type_name', 'label_name', 'process1', 'label1', 'action1'])
            ->make(true);
    }

    // Add Device
    public function add_device(Request $request){
        date_default_timezone_set('Asia/Manila');

         $data = $request->all();
        $data = array(
            'name' => $request->name,
            'process' => $request->process,
            'boxing' => $request->boxing,
            'ship_boxing' => $request->ship_boxing,
            'type' => $request->type,
            'label' => $request->label,
            // 'barcode' => $request->barcode,
        );

        $validator = Validator::make($data, [
            // 'barcode' => ['required', 'numeric'],
            'name' => ['required', 'string', 'max:255'],
            // 'process' => ['required', 'numeric'],
            // 'boxing' => ['required', 'numeric', 'required'],
            // 'ship_boxing' => ['required', 'numeric', 'required'],
            // 'type' => ['required', 'numeric'],
            // 'label' => ['required', 'numeric'],
            // 'barcode' => ['required', 'string', 'max:255', 'unique:devices'],
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();
            $is_barcode_exist = Device::where('barcode',$request->barcode)->where('status',1)->get();
            if( count($is_barcode_exist) != 0){
                DB::rollback();
                return response()->json(['result' => '0', 'error' =>'Saving Failed: Duplicate Product Code!'],500);
            }
            try{
                Device::insert([
                    'name' => $request->name,
                    // 'process' => $request->process,
                    // 'boxing' => $request->boxing,
                    // 'ship_boxing' => $request->ship_boxing,
                    // 'type' => $request->type,
                    // 'label' => $request->label,
                    'status' => 1,
                    'barcode' => $request->barcode,
                    'created_by' => Auth::user()->id,
                    'last_updated_by' => Auth::user()->id,
                    'update_version' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                // DB::rollback();
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

    // Get Device By Id
    public function get_device_by_id(Request $request){
        $device = Device::where('id', $request->device_id)->get();
        return response()->json(['device' => $device]);
    }

    // Edit Device
    public function edit_device(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            // 'name' => 'required|string|max:255|unique:devices,name,'. $request->device_id,
            'name' => ['required', 'string', 'max:255'],
            'process' => ['required', 'string'],
            // 'barcode' => ['required', 'string', 'max:255', 'unique:devices'],
            'boxing' => ['required', 'numeric', 'required'],
            'ship_boxing' => ['required', 'numeric', 'required'],
            'type' => ['required', 'string'],
            'label' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                Device::where('id', $request->device_id)
                ->increment('update_version', 1,
                [
                    'name' => $request->name,
                    'process' => $request->process,
                    'boxing' => $request->boxing,
                    'ship_boxing' => $request->ship_boxing,
                    'type' => $request->type,
                    'label' => $request->label,
                    'status' => 1,
                    'barcode' => $request->barcode,
                    'last_updated_by' => Auth::user()->id,
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

    // Change Device Status
    public function change_device_stat(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'device_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            try{
                Device::where('id', $request->device_id)
                    ->increment('update_version', 1,
                        [
                            'status' => $request->status,
                            'last_updated_by' => Auth::user()->id,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]
                    );
                DB::commit();
                return response()->json(['result' => "1"]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => "0"]);
            }

            return response()->json(['result' => 1]);
        }
        else{
            return response()->json(['result' => "0", 'error' => $validator->messages()]);
        }
    }

    public function import_packing_matrix(Request $request)
    {

        $collections = Excel::toCollection(new CSVPackingMatrixImport, request()->file('import_file'));
        DB::beginTransaction();
        try{
            for($index = 0; $index < count($collections[0]); $index++){
                Device::insert([
                    'name' => $collections[0][$index][1],
                    'mrp_no' => $collections[0][$index][2],
                    'boxing' => $collections[0][$index][3],
                    'ship_boxing' => $collections[0][$index][4],
                    'barcode' => $collections[0][$index][0],
                    'status' => 1,
                    'created_by' => Auth::user()->id,
                    'last_updated_by' => Auth::user()->id,
                    'update_version' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            DB::commit();

            return response()->json(['result' => "1"]);
        }
        catch(\Exception $e) {
            DB::rollback();
            return response()->json(['result' => $e]);
        }
    }

    public function get_device_from_yeu_kittings (Request $request){

        $device = YeuKitting::where('item_code', $request->barcode)->get();

        // return 'pasok sa device';

        return response()->json(['device' => $device]);
    }
}
