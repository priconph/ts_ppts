<?php

namespace App\Http\Controllers;

use Auth;
use Mail;
use QrCode;
use App\User;
use DataTables;
use App\Model\OQCStamp;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Imports\CSVUserImport;
use Illuminate\Validation\Rule;
use App\Jobs\SendUserPasswordJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Sign In
    public function sign_in(Request $request){
        $user_data = array(
            'username' => $request->get('username'),
            'password' => $request->get('password'),
            'status' => "1"
        );

        $validator = Validator::make($user_data, [
            'username' => 'required',
            'password' => 'required|min:5'
        ]);

        if($validator->passes()){
            if(Auth::attempt($user_data)){
                if(Auth::user()->is_password_changed == 0){
                    return response()->json(['result' => "2"]);
                }
                else{
                    return response()->json(['result' => "1"]);
                }
            }
            else{
                return response()->json(['result' => "0", 'error_message' => 'Login Failed!', 'error' => $validator->messages()]);
            }
        }
        else{
            return response()->json(['result' => "0", 'error' => $validator->messages()]);
        }
    }

    // Sign Out
    public function sign_out(Request $request){
        Auth::logout();
        return response()->json(['result' => "1"]);
    }

    public function check_employee_id(Request $request)
    {
        $valid = false;
        $data = User::where('employee_id', $request->employee_id)->get();
        if( count( $data ) > 0 )
            $valid = true;
        return response()->json(['result' => $valid, 'data' => $data]);
    }

    // Change Password
    public function change_pass(Request $request){
        date_default_timezone_set('Asia/Manila');
        $user_data = array(
            'username' => $request->username,
            'password' => $request->password,
            'new_password' => $request->new_password,
            'confirm_password' => $request->confirm_password,
        );

        $validator = Validator::make($user_data, [
            'username' => 'required',
            'password' => 'required|min:5',
            'new_password' => 'required|min:5|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'required|min:5'
        ]);

        if($validator->passes()){

            if(Auth::attempt($user_data)){
                try{
                    User::where('id', Auth::user()->id)
                        ->increment('update_version', 1,
                            [
                                'is_password_changed' => 1,
                                'password' => Hash::make($request->new_password),
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
                return response()->json(['result' => "0", 'error' => 'Login Failed!']);
            }
        }
        else{
            return response()->json(['result' => "0", 'error' => $validator->messages()]);
        }
    }

    // Change User Status
    public function change_user_stat(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'user_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            try{
                User::where('id', $request->user_id)
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

    // Reset Password
    public function reset_password(Request $request){
        date_default_timezone_set('Asia/Manila');

        // $password = 'pmi1234' . Str::random(10);
        $password = 'pmi12345';

        try{
            User::where('id', $request->user_id)
                ->increment('update_version', 1,
                    [
                        'is_password_changed' => 0,
                        'password' => Hash::make($password),
                        'last_updated_by' => Auth::user()->id,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]
                );

            $has_email = 0;
            $user = User::where('id', $request->user_id)->get();

            if(count($user) > 0 && $user[0]->email != ""){
                $has_email = 1;
                // $has_email = 0;
                $subject = 'PATS User Reset Password';
                $email = $user[0]->email;
                $message = 'This is a notification from PATS. Your PATS user password account was successfully reset.';

                dispatch(new SendUserPasswordJob($subject, $message, $user[0]->username, $password, $email));
            }
            DB::commit();
            return response()->json(['result' => "1", 'user' => $user, 'has_email' => $has_email, 'password' => $password]);
        }
        catch(\Exception $e) {
            DB::rollback();
            // throw $e;
            return response()->json(['result' => "0"]);
        }
    }

    //View Users
	public function view_users(){
    	$users = User::with([
                    'user_level',
                    'oqc_stamps' => function($query) {
                        $query->where('status', 1);
                        $query->orderBy('id', 'desc');
                    }
                ])
                ->get();

        return DataTables::of($users)
            ->addColumn('label1', function($user){
                $result = "";

                if($user->status == 1){
                    $result .= '<span class="badge badge-pill badge-success">Active</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
                }

                return $result;
            })
            ->addColumn('action1', function($user){

                $emplen = strlen($user->employee_id);

                if ($emplen == 4){
                    $disabled = 'disabled';
                }else{
                    $disabled = '';
                }

                $result = '<center><div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
                            <i class="fa fa-cog"></i>
                          </button>
                          <div class="dropdown-menu dropdown-menu-right">';
                if($user->status == 1){
                	$result .= '<button class="dropdown-item aEditUser" type="button" user-id="' . $user->id . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalEditUser" data-keyboard="false">Edit</button>';

                    $result .= '<button class="dropdown-item aChangeUserStat" type="button" user-id="' . $user->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeUserStat" data-keyboard="false">Deactivate</button>';

                    $result .= '<button class="dropdown-item aResetUserPass" user-id="' . $user->id . '" type="button" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalResetUserPass" data-keyboard="false">Reset Password</button>';

                    $result .= '<button class="dropdown-item aGenUserBarcode" user-id="' . $user->id . '" employee-id="' . $user->employee_id . '" '. $disabled .' type="button" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalGenUserBarcode">Generate Barcode</button>';
                }
                else{
                    $result .= '<button class="dropdown-item aChangeUserStat" type="button" style="padding: 1px 1px; text-align: center;" user-id="' . $user->id . '" status="1" data-toggle="modal" data-target="#modalChangeUserStat" data-keyboard="false">Activate</button>';
                }

                $result .= '</div>
                        </div></center>';

                return $result;
            })
            ->addColumn('checkbox', function($user){
                return '<center><input type="checkbox" class="chkUser" user-id="' . $user->id . '"></center>';
            })
            ->rawColumns(['label1', 'action1', 'checkbox'])
            ->make(true);
    }

    // Add User
    public function add_user(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        // return $data;
        $email = '';
        $has_email = 0;
        // $password = 'pmi1234' . Str::random(10);
        $password = 'pmi12345';

        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'employee_id' => 'required|string|max:255',
            'user_level_id' => 'required|string|max:255',
            'position' => 'required',
        ];

        // if(isset($request->with_email)){
        //     $rules['email'] = 'required|string|max:255|unique:users';
        //     $has_email = 1;
        // }

        // if(isset($request->with_oqc_stamp)){
            //Error Chris WBS Print Read DB
            // $rules['oqc_stamp'] = 'required|string|max:255|unique:oqc_stamps,status,2';
            // $rules['oqc_stamp'] = ['required','string','max:255',Rule::unique((new OqcStamp)->getTable(), 'oqc_stamp')->ignore(2)];
        // }

        if(isset($request->with_oqc_stamp)){
            $oqc_stamp = OqcStamp::where('oqc_stamp',$request->oqc_stamp)->count();
            if( $oqc_stamp > 0){
                return response()->json(['result' => '0', 'error' => 'OQC Stamp already exists.']);
            }
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {

            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{

            DB::beginTransaction();
            try{
                $user_id = User::insertGetId([
                    'name' => $request->name,
                    'username' => $request->username,
                    // 'email' => $request->email,
                    'employee_id' => $request->employee_id,
                    'position' => $request->position,
                    'fvi_no' => $request->fvi_no,
                    'password' => Hash::make($password),
                    'is_password_changed' => 0,
                    'status' => 1,
                    'user_level_id' => $request->user_level_id,
                    'created_by' => Auth::user()->id,
                    'last_updated_by' => Auth::user()->id,
                    'update_version' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                if(isset($request->oqc_stamp)){

                    OQCStamp::insert([
                        'user_id' => $user_id,
                        'oqc_stamp' => $request->oqc_stamp,
                        'created_by' => Auth::user()->id,
                        'last_updated_by' => Auth::user()->id,
                        'update_version' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }

                if(isset($request->send_email)){
                    $subject = 'TS PPTS User Registration';
                    $email = $request->email;
                    $message = 'This is a notification from TS PPTS. Your TS PPTS user account was successfully registered.';

                    dispatch(new SendUserPasswordJob($subject, $message, $request->username, $password, $email));
                }

                DB::commit();

                return response()->json(['result' => "1", 'password' => $password, 'has_email' => $has_email, 'username' => $request->username]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => "0", 'message' => $e]);
            }

        }

    }

    // Get User By Id
    public function get_user_by_id(Request $request){
        $user = User::with([
                            'oqc_stamps' => function($query) {
                                $query->where('status', 1);
                                $query->orderBy('id', 'desc');
                            }
                        ])->where('id', $request->user_id)->get();

        $qrcode = QrCode::format('png')
                            ->size(200)->errorCorrection('H')
                            ->generate($user[0]->employee_id);

        return response()->json(['user' => $user, 'qrcode' => "data:image/png;base64," . base64_encode($qrcode)]);
    }

    public function get_user_by_employee_no(Request $request){
        $data = User::with([
                            'oqc_stamps' => function($query) {
                                $query->where('status', 1);
                                $query->orderBy('id', 'desc');
                            }
                        ])->where('employee_id', $request->employee_no)->first();

        return response()->json(['data' => $data]);
    }

    public function get_user_by_emp_id(Request $request){
        $user = User::where('employee_id', $request->employee_id)->first();

        return response()->json(['user' => $user]);
    }

    public function get_user_list(Request $request){
        $users = User::all();

        return response()->json(['users' => $users]);
    }

    // Get User By Batch
    public function get_user_by_batch(Request $request){
        $users;

        if($request->user_id == 0){
            $users = User::all();
        }
        else{
            $users = User::whereIn('id', $request->user_id)->get();
        }
        $qrcode = [];

        if($users->count() > 0){
            for($index = 0; $index < $users->count(); $index++){
                $qrcode[] = "data:image/png;base64," . base64_encode(QrCode::format('png')
                                    ->size(200)->errorCorrection('H')
                                    ->generate($users[$index]->employee_id));
            }
        }

        return response()->json(['users' => $users, 'qrcode' => $qrcode]);
    }

    // Get User By Status
    public function get_user_by_stat(Request $request){
        $user = User::where('status', $request->status)->get();
        return response()->json(['user' => $user]);
    }

    // Edit User
    public function edit_user(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        // $password = 'pmi1234' . Str::random(10);
        $password = 'pmi12345';

        if(isset($request->with_email)){
            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'employee_id' => 'required|string|max:255',
                'email' => 'required|string|max:255',
                'user_level_id' => 'required|string|max:255',
                'position' => 'required|',
            ]);
        }
        else{
            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'employee_id' => 'required|string|max:255',
                'user_level_id' => 'required|string|max:255',
                'position' => 'required|',
            ]);
        }

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                User::where('id', $request->user_id)
                ->increment('update_version', 1,
                [
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'employee_id' => $request->employee_id,
                    'user_level_id' => $request->user_level_id,
                    'position' => $request->position,
                    'fvi_no' => $request->fvi_no,
                    'oqc_stamp' => $request->oqc_stamp,
                    'last_updated_by' => Auth::user()->id,
                    'update_version' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                if(isset($request->oqc_stamp)){
                    $oqc_stamps = OQCStamp::where('user_id', $request->user_id)
                                        ->where('status', 1)
                                        ->orderBy('id', 'desc')
                                        ->limit(1)
                                        ->get();

                    if($oqc_stamps->count() > 0){
                        if($request->oqc_stamp != $oqc_stamps[0]->oqc_stamp){
                            OQCStamp::where('id', $oqc_stamps[0]->id)
                                ->increment('update_version', 1,
                                [
                                    'status' => 2,
                                    'last_updated_by' => Auth::user()->id,
                                    'updated_at' => date('Y-m-d H:i:s'),
                                ]);

                            OQCStamp::insert([
                                'user_id' => $request->user_id,
                                'oqc_stamp' => $request->oqc_stamp,
                                'created_by' => Auth::user()->id,
                                'last_updated_by' => Auth::user()->id,
                                'update_version' => 1,
                                'updated_at' => date('Y-m-d H:i:s'),
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        }
                    }
                    else{
                        OQCStamp::insert([
                            'user_id' => $request->user_id,
                            'oqc_stamp' => $request->oqc_stamp,
                            'created_by' => Auth::user()->id,
                            'last_updated_by' => Auth::user()->id,
                            'update_version' => 1,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }

                }

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

    public function generate_user_qrcode(Request $request){
        // action: 1-Add, 2-Edit, 3-Generate Only

        // $user = [];
        // if($request->action == "1" || $request->action == "3"){
        //     $user = User::where('employee_id', $request->qrcode)->get();
        // }
        // else if($request->action == "2"){
        //     $user = User::where('employee_id', $request->qrcode)
        //                 ->where('id', '!=', $request->user_id)
        //                 ->get();
        // }

        // $user = User::where('id', $request->user_id)->get();

        // $qrcode = $user[0]->barcode;

        try{
            if(isset($request->qrcode)){
                $user = User::where('employee_id', $request->qrcode)->get();

                $qrcode = QrCode::format('png')
                        ->size(200)->errorCorrection('H')
                        ->generate($request->qrcode);

                return response()->json(['result' => "1", 'qrcode' => "data:image/png;base64," . base64_encode($qrcode), 'user' => $user]);
            }
            else{
                return response()->json(['result' => "0"]);
            }
        }
        catch(\Exception $e){
            return response()->json(['result' => "0"]);
        }

        // if(count($user) <= 0){
        //     try{
        //         if(isset($request->qrcode)){
        //             $qrcode = QrCode::format('png')
        //                     ->size(200)->errorCorrection('H')
        //                     ->generate($request->qrcode);

        //             return response()->json(['result' => "1", 'qrcode' => "data:image/png;base64," . base64_encode($qrcode)]);
        //         }
        //         else{
        //             return response()->json(['result' => "0"]);
        //         }
        //     }
        //     catch(\Exception $e){
        //         return response()->json(['result' => "0"]);
        //     }
        // }
        // else{
        //     return response()->json(['result' => "2"]);
        // }
    }

    public function import_user(Request $request)
    {
        $collections = Excel::toCollection(new CSVUserImport, request()->file('import_file'));

        // $password = 'pmi1234' . Str::random(10);
        $password = 'pmi12345';
        $user_level_id = 3;

        DB::beginTransaction();
        try{
            for($index = 2; $index < count($collections[0]); $index++){
                if($collections[0][$index][4] == 0){
                    $user_level_id = 2;
                }
                else{
                    $user_level_id = 3;
                }

                $user_id = User::insertGetId([
                    'name' => $collections[0][$index][0],
                    'username' => $collections[0][$index][1],
                    'email' => $collections[0][$index][2],
                    'employee_id' => $collections[0][$index][3],
                    'password' => Hash::make($password),
                    'position' => $collections[0][$index][4],
                    'user_level_id' => $user_level_id,
                    'is_password_changed' => 0,
                    'status' => 1,
                    'created_by' => Auth::user()->id,
                    'last_updated_by' => Auth::user()->id,
                    'update_version' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                // if(trim($collections[0][$index][5]) != ""){
                //     OQCStamp::insert([
                //         'user_id' => $user_id,
                //         'oqc_stamp' => $collections[0][$index][5],
                //         'created_by' => Auth::user()->id,
                //         'last_updated_by' => Auth::user()->id,
                //         'update_version' => 1,
                //         'updated_at' => date('Y-m-d H:i:s'),
                //         'created_at' => date('Y-m-d H:i:s')
                //     ]);
                // }
            }

            DB::commit();

            return response()->json(['result' => "1"]);
        }
        catch(\Exception $e) {
            DB::rollback();
            return response()->json(['result' => $e]);
        }
    }

    public function getAllUserByPosition(Request $request)
    {
        $user = User::where('status', 1)/*->where('oqc_stamp', '!=', null)*/;
        if( isset( $request->position ) )
            $user = $user->where('position', $request->position);
        $user = $user->get();
        return response()->json(['users' => $user]);
    }

    public function employee_id_checker(Request $request)
    {

        $user = User::where('status', 1)->where('employee_id', $request->employee_id)->get();
        if( count($user) == 0 ){
            return response()->json(['error_msg' => 'Invalid Employee ID.']);
        }else{
            if( $user[0]->position == $request->position ){
                return response()->json(['result' => 1, 'emp_id' => $request->employee_id]);
            }else if( $user[0]->user_level_id == $request->user_level_id || $user[0]->user_level_id == 1 ){
                return response()->json(['result' => 1, 'emp_id' => $request->employee_id]);
            }else{
                return response()->json(['result' => 0]);
            }
        }
    }

    function validate_employeee_id(Request $request)
    {
        try {
            $is_employee_registered = User::where('employee_id',$request->employee_id)
                                                ->where('status',1)->count();

            $employee = User::where('employee_id',$request->employee_id)
            ->where(function ($query) {
                $query->where('position',1)
                    ->orWhere('position',2)
                    ->orWhere('position',3);
            })->get();
            if($is_employee_registered === 1 ){
                if(count($employee) === 1){
                    return response(['is_employee_registered'=> true,"is_employee_validated" => true,'validated_emp_id' =>$employee[0]->employee_id]);
                }else{
                    return response(['is_employee_registered'=> true,"is_employee_validated" => false,'validated_emp_id' => '']);
                }
            }else{
                return response(['is_employee_registered'=> false ,"is_employee_validated" => false,'validated_emp_id' => '']);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
