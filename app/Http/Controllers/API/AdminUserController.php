<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Activitity_log;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminUserController extends Controller
{
    // get all admin users here...

    public function getAdmin()
    {
        if (auth('sanctum')->check()) {

            $user_details = auth('sanctum')->user();
            //$userWallet = User::where('id', $sender_details->id)->where('acct_status', 'Active')->sum('gamount');
            $all_details = AdminUser::where('acct_status', 'Active')->orderByDesc('id')->get();
            if ($all_details) {
                return response()->json([
                    'status' => 200,
                    'adminuser_record' => $all_details,
                ]);
            } else if (empty($$all_details)) {
                return response()->json([
                    'status' => 404,
                    'adminuser_record' => 'No record fund',
                ]);
            } else {
                return response()->json([
                    'status' => 405,
                    'adminuser_record' => 'Server error occurred',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    // create/register new admin user here...
    public function saveAdminUser(Request $request)
    {
        if (auth('sanctum')->check()) {

            $validator = Validator::make($request->all(), [
                'surname' => 'required|max:191',
                'other_name' => 'required|max:191',
                'phone' => 'required|max:191',
                'password' => 'required|min:8',
                'access_level' => 'required',
                'username' => 'required|unique:admin_users,user_name,id',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {


                $userDetails = auth('sanctum')->user();
                $check_admin = AdminUser::where('user_name', $request->username)->where('acct_status', 'Active')->first();
                if (!empty($check_admin)) {
                    return response()->json([
                        'status' => 402,
                        'message' => 'Username already exist',
                    ]);
                } else if (empty($resumption_date)) {
                    $save_new = new AdminUser();
                    $save_new->first_name = $request['surname'];
                    $save_new->other_name = $request['other_name'];
                    $save_new->sex = $request['sex'];
                    $save_new->email = $request['email'];
                    $save_new->phone = $request['phone'];
                    $save_new->access_level = $request['access_level'];
                    $save_new->user_name = $request['username'];
                    $save_new->password = Hash::make($request->password);
                    $save_new->reg_date = date('d/m/Y H:i:s');
                    $save_new->acct_status = 'Active';
                    $save_new->addby = $userDetails->username;

                    $save_new->save();

                    if ($save_new->save()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Registered admin user details";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, added new admin user details";
                        $logs->m_date = date('d/m/Y H:i:s');
                        $logs->m_uid = $userDetails->id;
                        $logs->m_ip = request()->ip;

                        $logs->save();
                        return response()->json([
                            'status' => 200,
                            'message' => 'Staff Details Added Successfully',
                        ]);
                    }
                }
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // fetch detail when edit button is clicked
    public function getAdminEdit($id)
    {
        if (auth('sanctum')->check()) {
            $getAdminDetails = AdminUser::where('id', $id)->where('acct_status', 'Active')->first();
            if ($getAdminDetails) {
                return response()->json([
                    'status' => 200,
                    'admin_editDetails' => $getAdminDetails,
                ]);
            } else {
                return response()->json([
                    'status' => 402,
                    'message' => "something went wrong! Try again",
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // update admin user details here...
    public function updateAdmin(Request $request, $id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();

            $validator = Validator::make($request->all(), [
                'first_name' => 'required|max:191',
                'other_name' => 'required|max:191',
                'phone' => 'required|max:191',
                'access_level' => 'required',
                'user_name' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {
                $find_admin_staff = AdminUser::find($id);

                if (empty($find_admin_staff)) {
                    return response()->json([
                        'status' => 402,
                        'message' => 'Record not found',
                    ]);
                } else if (!empty($find_admin_staff)) {
                    $find_admin_staff->update([
                        'first_name' => $request->first_name,
                        'other_name' => $request->other_name,
                        'sex' => $request->sex,
                        'phone' => $request->phone,
                        'email' => $request->email,
                        'access_level' => $request->access_level,
                        'user_name' => $request->user_name,
                    ]);
                    if ($find_admin_staff->update()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Update admin details";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, updated admin staff details";
                        $logs->m_date = date('d/m/Y H:i:s');
                        $logs->m_uid = $find_admin_staff->id;
                        $logs->m_ip = request()->ip;

                        $logs->save();
                        return response()->json([
                            'status' => 200,
                            'message' => 'Admin Details Updated Successfully',
                        ]);
                    }
                }
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // update admin password here..
    public function updateAdminPassword(Request $request, $id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();

            $validator = Validator::make($request->all(), [
                'new_password' => 'required|min:8',
                'confirm_password' => 'required|same:new_password|min:8',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {
                $find_admin_staff = AdminUser::find($id);

                if (empty($find_admin_staff)) {
                    return response()->json([
                        'status' => 402,
                        'message' => 'Record not found',
                    ]);
                } else if (!empty($find_admin_staff)) {
                    $find_admin_staff->update([
                        'password' => Hash::make($request->new_password),
                    ]);
                    if ($find_admin_staff->update()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Update admin password details";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, admin staff password details updated";
                        $logs->m_date = date('d/m/Y H:i:s');
                        $logs->m_uid = $find_admin_staff->id;
                        $logs->m_ip = request()->ip;

                        $logs->save();
                        return response()->json([
                            'status' => 200,
                            'message' => 'Admin Password Updated Successfully',
                        ]);
                    }
                }
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // delete admin user details
    public function deleteAdmin($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();

            $delete_record = AdminUser::find($id);
            if (!empty($delete_record)) {
                $delete_record->update([
                    'acct_status' => 'Deleted',
                ]);
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Deleted admin record";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Delete admin staff info details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->m_record_id = $id;

                $logs->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Record Deleted Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 402,
                    'message' => 'Operation failed! Try again',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }
}