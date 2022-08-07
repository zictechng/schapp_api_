<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Activitity_log;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    // create new staff details here

    public function saveStaff(Request $request)
    {
        if (auth('sanctum')->check()) {

            $validator = Validator::make($request->all(), [
                'surname' => 'required|max:191',
                'other_name' => 'required|max:191',
                'sex' => 'required|max:191',
                'school_category' => 'required|max:191',
                'phone' => 'required|max:191',
                'username' => 'required|max:191',
                'home_address' => 'required|max:191',
                'staff_level' => 'required|max:191',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {

                $age = Carbon::parse($request->dob)->diff(Carbon::now())->y;
                $my_age = $age . " Years";

                $userDetails = auth('sanctum')->user();
                $check_record = Staff::where('phone', $request->phone)->where('acct_status', 'Active')->first();
                if (!empty($check_record)) {
                    return response()->json([
                        'status' => 402,
                        'message' => 'Staff phone number exist',
                    ]);
                } else if (empty($resumption_date)) {
                    $save_new = new Staff();
                    $save_new->surname = $request['surname'];
                    $save_new->other_name = $request['other_name'];
                    $save_new->sex = $request['sex'];
                    $save_new->dob = $request['dob'];
                    $save_new->state = $request['state'];
                    $save_new->country = $request['country'];
                    $save_new->email = $request['email'];
                    $save_new->phone = $request['phone'];
                    $save_new->class = $request['class_apply'];
                    $save_new->school_category = $request['school_category'];
                    $save_new->staff_id = $request['staff_id'];
                    $save_new->qualification = $request['qualification'];
                    $save_new->acct_username = $request['username'];
                    $save_new->home_address = $request['home_address'];
                    $save_new->staff_level = $request['staff_level'];
                    $save_new->reg_date = date('d/m/Y H:i:s');
                    $save_new->acct_status = 'Active';
                    $save_new->addby = $userDetails->username;

                    $save_new->save();

                    if ($save_new->save()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Registered staff details";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, added new staff details";
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

    // get all staff details from here
    public function getStaff()
    {
        if (auth('sanctum')->check()) {
            $allstaff = Staff::where('acct_status', 'Active')->orderByDesc('id')->get();
            return response()->json([
                'status' => 200,
                'all_staff' => $allstaff
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    // fetch staff details from edit button clicked 
    public function fetchStaff($id)
    {
        if (auth('sanctum')->check()) {
            $getStaffDetails = Staff::where('id', $id)->where('acct_status', 'Active')->first();
            if ($getStaffDetails) {
                return response()->json([
                    'status' => 200,
                    'staff_editDetails' => $getStaffDetails,
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

    // update staff details here...
    public function staffUpdate(Request $request, $id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();

            $validator = Validator::make($request->all(), [
                'surname' => 'required|max:191',
                'other_name' => 'required|max:191',
                'sex' => 'required|max:191',
                'school_category' => 'required|max:191',
                'phone' => 'required|max:191',
                'acct_username' => 'required|max:191',
                'home_address' => 'required|max:191'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {
                $find_staff = Staff::find($id);

                if (empty($find_staff)) {
                    return response()->json([
                        'status' => 402,
                        'message' => 'Record not found',
                    ]);
                } else if (!empty($find_staff)) {
                    $find_staff->update([
                        'surname' => $request->surname,
                        'other_name' => $request->other_name,
                        'sex' => $request->sex,
                        'dob' => $request->dob,
                        'state' => $request->state,
                        'country' => $request->country,
                        'phone' => $request->phone,
                        'email' => $request->email,
                        'class' => $request->class,
                        'staff_id' => $request->staff_id,
                        'home_address' => $request->home_address,
                        'school_category' => $request->school_category,
                        'acct_username' => $request->acct_username,
                        'qualification' => $request->qualification,
                        'staff_level' => $request->staff_level,
                    ]);
                    if ($find_staff->update()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Update staff details";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, updated staff details";
                        $logs->m_date = date('d/m/Y H:i:s');
                        $logs->m_uid = $find_staff->id;
                        $logs->m_ip = request()->ip;

                        $logs->save();
                        return response()->json([
                            'status' => 200,
                            'message' => 'Staff Details Updated Successfully',
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

    // delete staff details here..
    public function deleteStaff($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();

            $staff_record = Staff::find($id);
            if (!empty($staff_record)) {
                $staff_record->update([
                    'acct_status' => 'Deleted',
                ]);
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Deleted staff record";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Delete staff info details";
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

    // update staff image profile here..
    public function updateStaffImage(Request $request, $id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();

            $validator = Validator::make($request->all(), [
                'image' => 'required|mimes:jpeg,png,jpg,gif',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {
                $find_user = Staff::find($id);
                if (empty($find_user)) {
                    return response()->json([
                        'status' => 402,
                        'message' => 'User not found',
                    ]);
                } else if (!empty($find_user)) {
                    /* this check if there is an image the uploade or do not process */
                    if ($request->hasFile('image')) {
                        /* check if the previous image exist then delete before uplaoding new one */
                        $path = $find_user->staff_image; // this image colunm already have the image path in the database
                        if (File::exists($path)) {
                            File::delete($path);
                        }
                        /* image deleting ends here --*/

                        $file = $request->file('image');
                        $extension = $file->getClientOriginalExtension();
                        $filename = time() . '.' . $extension;
                        $file->move('uploads/staff_image/', $filename);
                        $find_user->staff_image = 'uploads/staff_image/' . $filename;
                    }
                    /* ends here */
                    $find_user->update();
                    return response()->json([
                        'status' => 200,
                        'message' => 'Product Updated Successfully.',
                    ]);
                    if ($find_user->update()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Update staff profile image";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, updated staff account profile picture";
                        $logs->m_date = date('d/m/Y H:i:s');
                        $logs->m_uid = $find_user->id;
                        $logs->m_ip = request()->ip;
                        $logs->save();
                        return response()->json([
                            'status' => 200,
                            'message' => 'Profile Picture Updated Successfully',
                        ]);
                    } else {
                        return response()->json([
                            'status' => 500,
                            'message' => 'Operation failed',
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

    // update password here...
    public function updateStaffPassword(Request $request, $id)
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
                $find_staff = Staff::find($id);

                if (empty($find_staff)) {
                    return response()->json([
                        'status' => 402,
                        'message' => 'Record not found',
                    ]);
                } else if (!empty($find_staff)) {
                    $find_staff->update([
                        'staff_password' => Hash::make($request->new_password),
                    ]);
                    if ($find_staff->update()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Update staff password details";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, staff password details updated";
                        $logs->m_date = date('d/m/Y H:i:s');
                        $logs->m_uid = $find_staff->id;
                        $logs->m_ip = request()->ip;

                        $logs->save();
                        return response()->json([
                            'status' => 200,
                            'message' => 'Staff Password Updated Successfully',
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
}