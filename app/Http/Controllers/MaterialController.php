<?php

namespace App\Http\Controllers;

use App\Model\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CSVMaterialImport;
use QrCode;

class MaterialController extends Controller
{
	public function get_material_by_stat(Request $request){
        $materials = Material::where('status', $request->status)->get();
        return response()->json(['materials' => $materials]);
    }

    //View Materials
	public function view_materials(){
    	$materials = Material::all();

        return DataTables::of($materials)
            ->addColumn('label1', function($material){
                $result = "";

                if($material->status == 1){
                    $result .= '<span class="badge badge-pill badge-success">Active</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
                }

                return $result;
            })
            ->addColumn('action1', function($material){
                $result = '<center><div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
                            <i class="fa fa-cog"></i> 
                          </button>
                          <div class="dropdown-menu dropdown-menu-right">';
                if($material->status == 1){
                	$result .= '<button class="dropdown-item aEditMaterial" type="button" material-id="' . $material->id . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalEditMaterial" data-keyboard="false">Edit</button>';

                    $result .= '<button class="dropdown-item aChangeMaterialStat" type="button" material-id="' . $material->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeMaterialStat" data-keyboard="false">Deactivate</button>';

                    // $result .= '<button class="dropdown-item aGenMaterialBarcode" material-id="' . $material->barcode . '" type="button" style="padding: 1px 1px; text-align: center;">Generate Barcode</button>';

                    // $result .= '<button class="dropdown-item aGenerateBarcode" material-id="' . $material->id . '" barcode="' . $material->barcode . '" title="Material: " name="' . $material->name . '" type="button" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalGenBarcode">Generate Barcode</button>';
                }
                else{
                    $result .= '<button class="dropdown-item aChangeMaterialStat" type="button" style="padding: 1px 1px; text-align: center;" material-id="' . $material->id . '" status="1" data-toggle="modal" data-target="#modalChangeMaterialStat" data-keyboard="false">Activate</button>';
                }
                            
                $result .= '</div>
                        </div></center>';

                return $result;
            })
            ->addColumn('checkbox', function($material){
                return '<center><input type="checkbox" class="chkMaterial" material-id="' . $material->id . '"></center>';
            })
            ->rawColumns(['label1', 'action1', 'checkbox'])
            ->make(true);
    }

    // Get Material By Batch
    public function get_material_by_batch(Request $request){
        $materials;

        if($request->material_id == 0){
            $materials = Material::all();
        }
        else{
            $materials = Material::whereIn('id', $request->material_id)->get();
        }
        $qrcode = [];

        // if($materials->count() > 0){
        //     for($index = 0; $index < $materials->count(); $index++){
        //         $qrcode[] = "data:image/png;base64," . base64_encode(QrCode::format('png')
        //                             ->size(200)->errorCorrection('H')
        //                             ->generate($materials[$index]->barcode));
        //     }
        // }

        return response()->json(['materials' => $materials]);
    }

    // Add Material
    public function add_material(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'unique:materials'],
            // 'barcode' => ['required', 'string', 'max:255', 'unique:materials'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                Material::insert([
                    'name' => $request->name,
                    'status' => 1,
                    // 'barcode' => $request->barcode,
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

    // Get Material By Id
    public function get_material_by_id(Request $request){
        $material = Material::where('id', $request->material_id)->get();
        return response()->json(['material' => $material]);
    }

    // Get Materials
    public function get_materials(Request $request){
        $materials = Material::all();
        return response()->json(['materials' => $materials]);
    }

    // Edit Material
    public function edit_material(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255|unique:materials,name,'. $request->material_id,
            // 'barcode' => 'required|string|max:255|unique:materials,barcode,'. $request->material_id,
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                Material::where('id', $request->material_id)
                ->increment('update_version', 1,
                [
                    'name' => $request->name,
                    // 'barcode' => $request->barcode,
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

    // Change Material Status
    public function change_material_stat(Request $request){        
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'material_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            try{
                Material::where('id', $request->material_id)
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

    public function import_material(Request $request)
    {

        $collections = Excel::toCollection(new CSVMaterialImport, request()->file('import_file'));

        DB::beginTransaction();
        try{
            for($index = 0; $index < count($collections[0]); $index++){
                Material::insert([
                    'name' => $collections[0][$index][0],
                    // 'barcode' => $collections[0][$index][1],
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