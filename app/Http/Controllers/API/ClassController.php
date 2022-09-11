<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Activitity_log;
use App\Models\ClassModel;
use App\Models\User;
use Faker\Provider\UserAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ClassController extends Controller
{


    // add new class to the system


    public function addClass(Request $request)
    {

        //$device = $details->device(true);
        /* Generate unique transaction ID for each cash request record */
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $tid = substr(str_shuffle($permitted_chars), 0, 16);
        if (auth('sanctum')->check()) {

            $validator = Validator::make($request->all(), [
                'class_name' => 'required|max:191',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {

                $userDetails = auth('sanctum')->user();
                $send_code = $request->coupon_code;
                // check if the class name is already exist
                $className = ClassModel::where('class_name', $request->class_name)->first();
                if (!empty($className)) {
                    return response()->json([
                        $errors = $validator->errors(),
                        'status' => 402,
                        // 'validator_err' => $validator->messages(),
                        'message' => 'Class name already exist!',
                    ]);
                } else if (empty($className)) {
                    $class = new ClassModel();
                    $class->class_name = $request->class_name;
                    $class->added_by = $userDetails->name;
                    $class->status = "Active";
                    $class->record_date = date('d/m/Y H:i:s');
                    $class->save();

                    if ($class->save()) {
                        // create history record here...
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Created new class";
                        $logs->m_status = "Successful";
                        $logs->m_details = "A new class was created by $userDetails->name";
                        $logs->m_date = date('d/m/Y H:i:s');
                        $logs->m_uid = $userDetails->id;
                        $logs->m_ip = request()->ip;

                        $logs->save();

                        return response()->json([
                            'status' => 200,
                            'message' => 'Class Created Successfully',
                        ]);
                    } else {
                        return response()->json([
                            'status' => 500,
                            'message' => 'Error occurred! Try again',
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

    // fetch all class details here
    public function fetchClassDetail()
    {
        if (auth('sanctum')->check()) {

            $user_details = auth('sanctum')->user();
            //$userWallet = User::where('id', $sender_details->id)->where('acct_status', 'Active')->sum('gamount');
            $class_details = ClassModel::where('status', 'Active')->orderByDesc('id')->get();
            if (!empty($class_details)) {
                return response()->json([
                    'status' => 200,
                    'class_record' => $class_details,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "No record found",
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    // delete record comes here..

    public function deleteClass($id)
    {
        $userDetails = auth('sanctum')->user();
        $class = ClassModel::find($id);
        if (!empty($class)) {
            $class->update([
                'status' => 'Deleted',
            ]);
            $logs = new Activitity_log();
            $logs->m_username = $userDetails->username;
            $logs->m_action = "Deleted class";
            $logs->m_status = "Successful";
            $logs->m_details = "$userDetails->name, Delete class details";
            $logs->m_date = date('d/m/Y H:i:s');
            $logs->m_uid = $userDetails->id;
            $logs->m_ip = request()->ip;
            $logs->m_record_id = $id;

            $logs->save();
            return response()->json([
                'status' => 200,
                'message' => 'Deleted Successfully',
            ]);
        } else {
            return response()->json([
                'status' => 402,
                'message' => 'Operation failed! Try again',
            ]);
        }
    }

    public function getClass($id)
    {
        if (auth('sanctum')->check()) {
            $classDetails = ClassModel::where('id', $id)->first();
            if ($classDetails) {
                return response()->json([
                    'status' => 200,
                    'classDetails' => $classDetails,
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

    // update class details...

    public function saveUpdateClass(Request $request)
    {
        if (auth('sanctum')->check()) {

            $validator = Validator::make($request->all(), [
                'class_name' => 'required|max:191',
                'id' => 'required|max:191',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {

                $userDetails = auth('sanctum')->user();
                $class = ClassModel::where('id', $request->id)->first();
                if (!empty($class)) {
                    $class->update([
                        'class_name' => $request->class_name,
                    ]);
                    $logs = new Activitity_log();
                    $logs->m_username = $userDetails->username;
                    $logs->m_action = "Updated class";
                    $logs->m_status = "Successful";
                    $logs->m_details = "$userDetails->name, Updated class details";
                    $logs->m_date = date('d/m/Y H:i:s');
                    $logs->m_uid = $userDetails->id;
                    $logs->m_ip = request()->ip;
                    $logs->m_record_id = $class->id;

                    $logs->save();
                    return response()->json([
                        'status' => 200,
                        'message' => 'Record Updated Successfully',
                    ]);
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