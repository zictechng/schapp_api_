<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Activitity_log;
use App\Models\LoginStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    // user registration code goes here
    public function registerUser(Request $request)
    {
        /* validate details here */
        $validator = Validator::make($request->all(), [

            'fname' => 'required|max:191',
            'lname' => 'required|max:191',
            'email' => 'required|email|max:191|unique:users,email,id',
            'phone' => 'required|max:11',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json([
                $errors = $validator->errors(),
                // 'validator_err' => $validator->messages(),
                'validation_errors' => $errors,
            ]);
        } elseif (empty($errors)) {
            $user = User::create([
                'name' => $request->fname . ' ' . $request->lname,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);
            // generate token and assigned to use record
            $token = $user->createToken($user->email . '_token')->plainTextToken;
            return response()->json([
                'status' => 200,
                'username' => $user->name,
                'token' => $token,
                'message' => 'Registration Successful.'
            ]);
        }
        // if no validation errors, proceed and register user
        else {
            return response()->json([
                'status' => 401,
                // 'validator_err' => $validator->messages(),
                'message' => 'Something went wrong! Try again',
            ]);
        }
    }


    // function to login here
    public function loginUser(Request $request)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $tid = substr(str_shuffle($permitted_chars), 0, 30);
        /* validate details here */
        $validator = Validator::make($request->all(), [

            'email' => 'required|email|max:191',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json([
                $errors = $validator->errors(),
                // 'validator_err' => $validator->messages(),
                'validation_errors' => $errors,
            ]);
        } else {
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid Credentials',
                ]);
                // throw ValidationException::withMessages([
                //     'email' => ['The provided credentials are incorrect.'],
                // ]);
            } else {
                /* this will help check user role permission */
                if ($user->role == 'Admin') // 1= admin, 0 = normal user
                {
                    $role = 'admin';
                    $token = $user->createToken($user->email . '_AdminToken', ['server:admin'])->plainTextToken;
                } else if ($user->role == 'User') {
                    $role = 'user';
                    $token = $user->createToken($user->email . '_Token', [''])->plainTextToken;
                } else {
                    $role = 'staff';
                    $token = $user->createToken($user->email . '_Token', [''])->plainTextToken;
                }
                /* ends here */
                $logs = new Activitity_log();
                $logs->m_username = $user->username;
                $logs->m_action = "Login";
                $logs->m_status = "Successful";
                $logs->m_details = "$user->name, user login to account";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $user->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                // insert for logged in table here
                $logg_in = new LoginStatus();
                $logg_in->user_id = $user->id;
                $logg_in->user_name = $user->username;
                $logg_in->login_name = $user->name;
                $logg_in->login_date = date('d/m/Y H:i:s');
                $logg_in->login_nature = "User Logged in Successfully";
                $logg_in->login_uid = $tid;
                $logg_in->login_status = '1';
                $logg_in->logg_action = 'Authenticated';
                $logg_in->login_role = $user->role;
                $logg_in->save();

                if ($user) {
                    User::query()->where('id', $user->id)
                        ->update([
                            'user_logg_status' => "Authenticated",
                        ]);
                }
                $getUserLog = LoginStatus::where('login_uid', $tid)->first();
                //
                return response()->json([
                    'status' => 200,
                    'loginState' => [
                        'token' => $token,
                        'message' => 'Logged In Successful.',
                        'role' => $user->role,
                        'userDetails' => $user,
                        'loggedUID' => $getUserLog,
                        'logged_id' => $tid,
                    ]
                ]);
            }
        }
    }

    // check if user is looged in and fetch is logged UID here..
    public function fetchLoggedINUser($id)
    {
        $checkUserLogged = LoginStatus::where('login_uid', $id)
            ->where('login_status', '1')
            ->first();
        $loggedInUser = User::where('id', $checkUserLogged->user_id)
            ->first();
        $get_myMessage = DB::table('message_systems')
            ->selectRaw('count(id) as total_message')
            ->where('mes_status', 'Active')
            ->where('receiver_user_id', $loggedInUser->staff_id)
            ->first();
        if ($checkUserLogged) {
            return response()->json([
                'status' => 200,
                'loggStatus' => [
                    'logginUser' => $loggedInUser,
                    'checkUserLoggin' => $checkUserLogged,
                    'myMessage' => $get_myMessage,
                ]
            ]);
        }
    }

    // Authenticate user loggin here...
    public function authenticateUser($id)
    {
        // ResultTable::query()->where('admin_number', $get_studentName->st_admin_number)
        // ->where('tid_code', $recordID)
        // ->update([
        //     'student_name' => $get_studentName->other_name,
        // ]);
        $loggCheck = DB::table('login_statuses')
            ->selectRaw('id, user_id, login_uid,login_role, logg_action')
            ->where('login_uid', '=', $id)
            ->first();
        $userLog =  User::query()->where('id', $loggCheck->user_id)
            ->where('user_logg_status', '!=', '')->first();

        if ($userLog) {
            return response()->json([
                'status' => 200,
                'loggStatus' => [
                    'userCode' => $userLog,
                ]
            ]);
        } else if (empty($userLog)) {
            return response()->json([
                'status' => 404,
                'message' => "Authentication Failed",

            ]);
        }
    }

    // fetch logged user here from side bar
    public function fetchUserLogged($id)
    {
        $userLog = LoginStatus::where('login_uid', $id)->first();
        if ($userLog) {
            return response()->json([
                'status' => 200,
                'userLogg' => $userLog,
            ]);
        } else {
            return response()->json([
                'status' => 200,
                'message' => "Not Logged In",
            ]);
        }
    }

    // save the result details send from the processing page.

    public function saveProcessResult(Request $request)
    {
        dd($request->all());
    }

    // get dashboard 1 activities here
    public function getDash1()
    {
        $get_all = DB::table('students')
            ->selectRaw('count(id) as all_user')
            ->first();

        $get_active = DB::table('students')
            ->selectRaw('count(id) as active_user')
            ->where('acct_status', 'Active')
            ->first();

        $get_all_staff = DB::table('staff')
            ->selectRaw('count(id) as all_staff')
            ->first();

        $get_active_staff = DB::table('staff')
            ->selectRaw('count(id) as staff_active')
            ->where('acct_status', 'Active')
            ->first();

        return response()->json([
            'status' => 200,
            'all_details' => [
                'all_student' => $get_all,
                'active_student' => $get_active,
                'all_staff' => $get_all_staff,
                'active_staff' => $get_active_staff,
            ]
        ]);
    }

    // fetch dash 2 here...
    public function getDash2()
    {
        $get_graduate = DB::table('graduations')
            ->selectRaw('count(id) as all_graduate')
            ->first();

        $get_online_user = DB::table('students')
            ->selectRaw('count(id) as online_user')
            ->where('acct_status', 'Online Reg')
            ->first();

        $get_all_suspend = DB::table('students')
            ->selectRaw('count(id) as all_suspend')
            ->where('acct_status', 'Suspended')
            ->first();
        return response()->json([
            'status' => 200,
            'allDetails' => [
                'all_graduated' => $get_graduate,
                'all_regOnline' => $get_online_user,
                'all_suspend' => $get_all_suspend,

            ]
        ]);
    }

    // fetch all recent logs to dashboard here...
    public function getActivityLog()
    {
        // $get_log = DB::table('activitity_logs')
        //     ->selectRaw('id, m_username,m_action,m_status,m_details,m_date,m_uid,
        // m_device_name,m_broswer,m_device_number,m_location,m_ip,
        // m_city,m_record_id')
        //     ->get(15);
        $allLog = Activitity_log::query()
            ->orderBy('id', 'desc')
            ->paginate('5');
        return response()->json([
            'status' => 200,
            'all_detail' => $allLog,
        ]);
    }

    // get birthdays of student here...
    public function getBirthday()
    {
        $ldate = date('Y-m-d');
        $get_bith = DB::table('students')
            ->selectRaw('count(id) as birthday_number')
            ->where('dob', '=', $ldate)
            ->first();
        return response()->json([
            'status' => 200,
            'birthday' => $get_bith,
        ]);
    }


    // logout user details here...

    public function LogoutUser()
    {
        $userDetails = auth('sanctum')->user();
        $userlogot = auth()->user()->tokens()->delete();
        if ($userlogot) {
            $logs = new Activitity_log();
            $logs->m_username = auth()->user()->username;
            $logs->m_action = "Logout";
            $logs->m_status = "Successful";
            $logs->m_details = auth()->user()->name . " logout from the system";
            $logs->m_date = date('d/m/Y H:i:s');
            $logs->m_uid = auth()->user()->id;
            $logs->m_ip = request()->ip;
            $logs->save();

            // insert for logged in table here
            $logg_in = new LoginStatus();
            $logg_in->user_id = auth()->user()->id;
            $logg_in->user_name = auth()->user()->username;
            $logg_in->login_name = auth()->user()->name;
            $logg_in->login_date = date('d/m/Y H:i:s');
            $logg_in->login_nature = "User Logged out Successfully";
            $logg_in->login_status = '0';
            $logg_in->save();

            if ($userlogot) {
                User::query()->where('id', auth()->user()->id)
                    ->update([
                        'user_logg_status' => "",
                    ]);
            }
            //$find_user = LoginStatus::find($userDetails->id);
            $find_user = LoginStatus::where('user_id', auth()->user()->id)
                ->where('login_status', '=', '1');
            if ($find_user) {
                $find_user->update([
                    'login_status' => '0',
                    'login_nature' => 'Logout successfully',
                    'logout_date' => date('d/m/Y H:i:s'),
                    'logg_action' => "",
                ]);
            }
        }
        return response()->json([
            'status' => 200,
            'message' => 'Logged Out Successfully!'
        ]);
    }
}