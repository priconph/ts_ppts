<?php

namespace App\Http\Controllers;

use App\Model\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CSVMachineImport;
use QrCode;

class MachineController extends Controller
{
    //View Machines
	public function view_machines(){
    	$machines = Machine::all();

        return DataTables::of($machines)
            ->addColumn('label1', function($machine){
                $result = "";

                if($machine->status == 1){
                    $result .= '<span class="badge badge-pill badge-success">Active</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
                }

                return $result;
            })
            ->addColumn('action1', function($machine){
                $result = '<center><div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
                            <i class="fa fa-cog"></i> 
                          </button>
                          <div class="dropdown-menu dropdown-menu-right">';
                if($machine->status == 1){
                	$result .= '<button class="dropdown-item aEditMachine" type="button" machine-id="' . $machine->id . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalEditMachine" data-keyboard="false">Edit</button>';

                    $result .= '<button class="dropdown-item aChangeMachineStat" type="button" machine-id="' . $machine->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeMachineStat" data-keyboard="false">Deactivate</button>';

                    // $result .= '<button class="dropdown-item aGenMachineBarcode" machine-id="' . $machine->barcode . '" type="button" style="padding: 1px 1px; text-align: center;">Generate Barcode</button>';

                    $result .= '<button class="dropdown-item aGenerateBarcode" machine-id="' . $machine->id . '" barcode="' . $machine->barcode . '" title="Machine: " name="' . $machine->name . '" type="button" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalGenBarcode">Generate Barcode</button>';
                }
                else{
                    $result .= '<button class="dropdown-item aChangeMachineStat" type="button" style="padding: 1px 1px; text-align: center;" machine-id="' . $machine->id . '" status="1" data-toggle="modal" data-target="#modalChangeMachineStat" data-keyboard="false">Activate</button>';
                }
                            
                $result .= '</div>
                        </div></center>';

                return $result;
            })
            ->addColumn('checkbox', function($machine){
                return '<center><input type="checkbox" class="chkMachine" machine-id="' . $machine->id . '"></center>';
            })
            ->rawColumns(['label1', 'action1', 'checkbox'])
            ->make(true);
    }

    // Get Machine By Batch
    public function get_machine_by_batch(Request $request){
        $machines;

        if($request->machine_id == 0){
            $machines = Machine::all();
        }
        else{
            $machines = Machine::whereIn('id', $request->machine_id)->get();
        }
        $qrcode = [];

        if($machines->count() > 0){
            for($index = 0; $index < $machines->count(); $index++){
                $qrcode[] = "data:image/png;base64," . base64_encode(QrCode::format('png')
                                    ->size(200)->errorCorrection('H')
                                    ->generate($machines[$index]->barcode));
            }
        }

        return response()->json(['machines' => $machines, 'qrcode' => $qrcode]);
    }

    // Add Machine
    public function add_machine(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'unique:machines'],
            'barcode' => ['required', 'string', 'max:255', 'unique:machines'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                Machine::insert([
                    'name' => $request->name,
                    'status' => 1,
                    'barcode' => $request->barcode,
                    'created_by' => Auth::user()->id,
                    'last_updated_by' => Auth::user()->id,
                    'update_version' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
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

    // Get Machine By Id
    public function get_machine_by_id(Request $request){
        $machine = Machine::where('id', $request->machine_id)->get();
        return response()->json(['machine' => $machine]);
    }

    // Get Machines
    public function get_machines(Request $request){
        $machines = Machine::all();
        return response()->json(['machines' => $machines]);
    }

    // Edit Machine
    public function edit_machine(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255|unique:machines,name,'. $request->machine_id,
            'barcode' => 'required|string|max:255|unique:machines,barcode,'. $request->machine_id,
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                Machine::where('id', $request->machine_id)
                ->increment('update_version', 1,
                [
                    'name' => $request->name,
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

    // Change Machine Status
    public function change_machine_stat(Request $request){        
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'machine_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            try{
                Machine::where('id', $request->machine_id)
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

    public function import_machine(Request $request)
    {

        $collections = Excel::toCollection(new CSVMachineImport, request()->file('import_file'));

        DB::beginTransaction();
        try{
            for($index = 2; $index < count($collections[0]); $index++){
                Machine::insert([
                    'name' => $collections[0][$index][0],
                    'barcode' => $collections[0][$index][1],
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
}