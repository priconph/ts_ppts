<?php

namespace App\Http\Controllers;

use App\Model\DrawingRef;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;

class DrawingRefController extends Controller
{
    public function view_drawing_ref(){
    	$drawing_refs = DrawingRef::all();

        return DataTables::of($drawing_refs)
            ->addColumn('status', function($drawing_ref){
                $result = "";

                if($drawing_ref->status == 1){
                    $result .= '<span class="badge badge-pill badge-success">Active</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
                }

                return $result;
            })
            ->addColumn('action', function($drawing_ref){
                $result = '<center><div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
                            <i class="fa fa-cog"></i> 
                          </button>
                          <div class="dropdown-menu dropdown-menu-right">';
                if($drawing_ref->status == 1){
                	$result .= '<button class="dropdown-item modalEditDrawingRef" type="button" drawing-ref-id="' . $drawing_ref->id . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalEditDrawingRef" data-keyboard="false">Edit</button>';

                    $result .= '<button class="dropdown-item actionChangeDrawingRefStat" type="button" drawing-ref-id="' . $drawing_ref->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeDrawingRefStat" data-keyboard="false">Deactivate</button>';

                    // $result .= '<button class="dropdown-item aGenAssemblyLineBarcode" assembly-line-id="' . $assembly_line->barcode . '" type="button" style="padding: 1px 1px; text-align: center;">Generate Barcode</button>';
                }
                else{
                    $result .= '<button class="dropdown-item actionChangeDrawingRefStat" type="button" style="padding: 1px 1px; text-align: center;" drawing-ref-id="' . $drawing_ref->id . '" status="1" data-toggle="modal" data-target="#modalChangeDrawingRefStat" data-keyboard="false">Activate</button>';
                }
                            
                $result .= '</div>
                        </div></center>';

                return $result;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }


    public function add_drawing_ref(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = array(
            'document_code' => $request->document_code,
            'document_no' => $request->document_no,
            'series' => $request->series,
            'station' => $request->station,
            'process' => $request->process,
            'rev' => $request->revision,
            'remarks' => $request->remarks,
        );


        $validator = Validator::make($data, [
            'document_code' => 'required|max:255',
            'document_no' => 'required|max:255',
            'series' => 'required|max:255',
            'station' => 'required|max:255',
            'process' => 'required|max:255',
            'rev' => 'required|max:255',
            'remarks' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                DrawingRef::insert([
                    'document_code' => $request->document_code,
                    'document_no' => $request->document_no,
                    'series' => $request->series,
                    'station' => $request->station,
                    'process' => $request->process,
                    'rev' => $request->revision,
                    'remarks' => $request->remarks,
                    'status' => 1,
                    'logdel' => 0,
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


    public function get_drawing_ref_by_id(Request $request){
        $drawing_ref = DrawingRef::where('id', $request->drawing_ref_id)->get();
        return response()->json(['drawing_ref' => $drawing_ref]);
    }


    public function edit_drawing_ref(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = array(
            'document_code' => $request->document_code,
            'document_no' => $request->document_no,
            'series' => $request->series,
            'station' => $request->station,
            'process' => $request->process,
            'rev' => $request->revision,
            'remarks' => $request->remarks,
        );


        $validator = Validator::make($data, [
            'document_code' => 'required|max:255',
            'document_no' => 'required|max:255',
            'series' => 'required|max:255',
            'station' => 'required|max:255',
            'process' => 'required|max:255',
            'rev' => 'required|max:255',
            'remarks' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                DrawingRef::where('id', $request->drawing_ref_id)
                ->update([
                    'document_code' => $request->document_code,
                    'document_no' => $request->document_no,
                    'series' => $request->series,
                    'station' => $request->station,
                    'process' => $request->process,
                    'rev' => $request->revision,
                    'remarks' => $request->remarks,
                    'status' => 1,
                    'logdel' => 0,
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


    public function change_drawing_ref_stat(Request $request){        
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'drawing_ref_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            try{
                DrawingRef::where('id', $request->drawing_ref_id)
                    ->update([
                            'status' => $request->status,
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
}
