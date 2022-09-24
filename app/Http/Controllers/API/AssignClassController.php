<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Activitity_log;
use App\Models\AssignClass;
use App\Models\ClassModel;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Builder\Class_;

class AssignClassController extends Controller
{
    // get all assign class here...
    public function fetchAssignClass()
    {
        // always check if user login before requesting this route
        if (auth('sanctum')->check()) {
            //query database to fetch details here
            $get_classAll = DB::table('assign_classes')
                ->selectRaw('count(cls__class_id) as total_class, id, cls_teacher_id, cls__class_id
            ,cls__teacher_name, cls__class_name, cls__status, cls__tid,cls__addby,cls__date')
                ->orderBy('id', 'desc')
                ->groupBy('cls_teacher_id')
                ->paginate(15);
            if (!empty($get_classAll)) {
                return response()->json([
                    'status' => 200,
                    'resultAll' => $get_classAll,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'No details found!',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    // save assign class request here...
    public function saveAssignClass(Request $request)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

            $tid = substr(str_shuffle($permitted_chars), 0, 16);
            //validate input details
            $validator = Validator::make($request->all(), [
                'staff_name' => 'required',
                'data.*.class_name' => 'required'
            ], [
                'data.*.class_name.required' => 'Class Name is required',
                'staff_name.required' => 'Select Staff name'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {

                // get array value and re-assign it to value here...
                $class_name = $request->input('data.class_name');
                $staff_name = $request->staff_name;

                // get teacher name here..
                $tech_name = Staff::where('id', $staff_name)
                    ->where('acct_status', 'Active')->first();
                // check if teacher have any record before
                $staffCheck = AssignClass::where('cls_teacher_id', $staff_name)
                    ->first();
                if (empty($staffCheck)) {
                    foreach ($request->data as $data) {
                        // get subject name here..
                        $sub_name = ClassModel::where('id', $data['class_name'])
                            ->where('status', 'Active')->first();
                        AssignClass::create([
                            'cls_teacher_id' => $staff_name,
                            'cls__class_id' => $data['class_name'],
                            'cls__teacher_name' => $tech_name->surname . ' ' . $tech_name->other_name,
                            'cls__class_name' => $sub_name->class_name,
                            'cls__status' => 'Active',
                            'cls__tid' => $tid,
                            'cls__addby' => $userDetails->username,
                            'cls__date' => date('d/m/Y H:i:s'),
                        ]);
                    }
                } elseif (!empty($staffCheck)) {
                    foreach ($request->data as $data) {
                        // get subject name here..
                        $sub_name = ClassModel::where('id', $data['class_name'])
                            ->where('status', 'Active')->first();
                        AssignClass::create([
                            'cls_teacher_id' => $staff_name,
                            'cls__class_id' => $data['class_name'],
                            'cls__teacher_name' => $tech_name->surname . ' ' . $tech_name->other_name,
                            'cls__class_name' => $sub_name->class_name,
                            'cls__status' => 'Active',
                            'cls__tid' => $staffCheck->cls__tid,
                            'cls__addby' => $userDetails->username,
                            'cls__date' => date('d/m/Y H:i:s'),
                        ]);
                    }
                }

                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Assign class to teacher";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, New class assigned to teacher details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Class Assigned Successfully',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    // get class assign detail view here....
    public function getAssignClass($id)
    {
        if (auth('sanctum')->check()) {

            $userDetails = auth('sanctum')->user();
            $sub_id = AssignClass::where('id', $id)->first();
            $sub_attetails = AssignClass::where('cls__tid', $sub_id->cls__tid)
                ->orderBy('cls__class_name', 'desc')
                ->get();

            if (!empty($sub_attetails)) {
                return response()->json([
                    'status' => 200,
                    'sub_assignDetails' => [
                        'proDetails' => $sub_attetails,
                        'pDetails' => $sub_id,
                    ]
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "No record found at the moment",
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // delete single class here...
    public function deleteClass($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $find_deleteID = AssignClass::where('id', $id)->first();
            if (!empty($find_deleteID)) {
                // rund the delete query here
                $find_deleteID->update([
                    'cls__status' => "Deleted",
                ]);

                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Class Deleted";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, A class assign to teacher was deleted";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Class Deleted Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "No record found at the moment",
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // delete all class at once here...
    public function deleteAllClass($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $check_deleteID = AssignClass::where('cls__tid', $id)->first();
            if (!empty($check_deleteID)) {
                // rund the delete query here
                AssignClass::query()
                    ->where('cls__tid', $id)
                    ->update([
                        'cls__status' => "Deleted",
                    ]);
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Deleted class for teacher";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, All Class assign to teacher was deleted";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'All Deleted Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "No record found at the moment",
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // update edit class here...
    public function updateEditClass(Request $request)
    {
        if (auth('sanctum')->check()) {
            //dd($request->all());
            //validate input details
            $validator = Validator::make($request->all(), [
                'class_name' => 'required',
            ], [
                'class_name.required' => 'Class Name Required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            }
            $recordID = $request->input('data.id');
            $userDetails = auth('sanctum')->user();
            $check_updateID = AssignClass::where('id', $recordID)->first();
            $subs_name = ClassModel::where('id', $request->class_name)
                ->where('status', 'Active')->first();

            if (!empty($check_updateID)) {
                // rund the update query here
                $check_updateID->update([
                    'cls__class_id' => $request->class_name,
                    'cls__class_name' => $subs_name->class_name,
                ]);
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Update class for teacher";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Update class assign to teacher";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Class Updated Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "No record found at the moment",
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // fetch edit class details here...
    public function fetchClass($id)
    {
        if (auth('sanctum')->check()) {
            $fetch_class = AssignClass::where('id', $id)->first();
            if (!empty($fetch_class)) {
                return response()->json([
                    'status' => 200,
                    'fetch_info' => $fetch_class,
                ]);
            } else
                return response()->json([
                    'status' => 404,
                    'message' => 'No record found!',
                ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }
    // get staff details here
    public function getStaffDetails()
    {
        if (auth('sanctum')->check()) {
            // get staff name
            $staff_info = Staff::where('acct_status', 'Active')
                ->orderBy('surname', 'desc')
                ->get();
            // get class
            $subject_info = ClassModel::where('status', 'Active')
                ->orderBy('class_name', 'desc')
                ->get();

            if (!empty($staff_info)) {
                return response()->json([
                    'status' => 200,
                    'all_Details' => [
                        'staff_details' => $staff_info,
                        'class_details' => $subject_info,
                    ]
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'No record found',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }
}