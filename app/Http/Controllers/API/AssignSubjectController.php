<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Activitity_log;
use App\Models\AssignedSubject;
use App\Models\Staff;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AssignSubjectController extends Controller
{
    // save assign subject here...
    public function saveAssignSubject(Request $request)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

            $tid = substr(str_shuffle($permitted_chars), 0, 16);
            //validate input details
            $validator = Validator::make($request->all(), [
                'staff_name' => 'required',
                'data.*.subject_name' => 'required'
            ], [
                'data.*.subject_name.required' => 'Subject Name is required',
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
                $subject_name = $request->input('data.subject_name');
                $staff_name = $request->staff_name;
                // check if teacher have any record before
                $staffCheck = AssignedSubject::where('sub_teacher_id', $staff_name)
                    ->first();

                // get teacher name here..
                $tech_name = Staff::where('id', $staff_name)
                    ->where('acct_status', 'Active')->first();
                if (empty($staffCheck)) {
                    foreach ($request->data as $data) {
                        // get subject name here..
                        $sub_name = Subject::where('id', $data['subject_name'])
                            ->where('sub_status', 'Active')->first();
                        // create the new record here...
                        AssignedSubject::create([
                            'sub_teacher_id' => $staff_name,
                            'sub_subject_id' => $data['subject_name'],
                            'sub_teacher_name' => $tech_name->surname . ' ' . $tech_name->other_name,
                            'sub_subject_name' => $sub_name->subject_name,
                            'sub_status' => 'Active',
                            'sub_tid' => $tid,
                            'sub_addby' => $userDetails->username,
                            'sub_date' => date('d/m/Y H:i:s'),
                        ]);
                    }
                } else if (!empty($staffCheck)) {
                    foreach ($request->data as $data) {
                        // get subject name here..
                        $sub_name = Subject::where('id', $data['subject_name'])
                            ->where('sub_status', 'Active')->first();
                        // create the new record here...
                        AssignedSubject::create([
                            'sub_teacher_id' => $staff_name,
                            'sub_subject_id' => $data['subject_name'],
                            'sub_teacher_name' => $tech_name->surname . ' ' . $tech_name->other_name,
                            'sub_subject_name' => $sub_name->subject_name,
                            'sub_status' => 'Active',
                            'sub_tid' => $staffCheck->sub_tid,
                            'sub_addby' => $userDetails->username,
                            'sub_date' => date('d/m/Y H:i:s'),
                        ]);
                    }
                }

                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Assign subject to teacher";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, New subject assigned to teacher details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Subject Assigned Successfully',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    // get assign subjects details with the id passed from route here...
    public function getAssignSubject($id)
    {
        if (auth('sanctum')->check()) {

            $userDetails = auth('sanctum')->user();
            $sub_id = AssignedSubject::where('id', $id)->first();
            $sub_attetails = AssignedSubject::where('sub_tid', $sub_id->sub_tid)
                ->orderBy('sub_subject_name', 'desc')
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
    // get all details here...
    public function fetchAssignSubject()
    {
        // always check if user login before requesting this route
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            //query database to fetch details here
            $get_subjectAll = DB::table('assigned_subjects')
                ->selectRaw('count(sub_subject_id) as total_subject, id, sub_teacher_id, sub_subject_id
            ,sub_teacher_name, sub_subject_name, sub_status, sub_tid,sub_addby,sub_date')
                ->orderBy('id', 'desc')
                ->groupBy('sub_teacher_id')
                ->paginate(15);
            if (!empty($get_subjectAll)) {
                return response()->json([
                    'status' => 200,
                    'resultAll' => $get_subjectAll,
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

    // get staff details here
    public function getStaffDetails()
    {
        if (auth('sanctum')->check()) {
            // get staff name
            $staff_info = Staff::where('acct_status', 'Active')
                ->orderBy('surname', 'desc')
                ->get();
            // get class
            $subject_info = Subject::where('sub_status', 'Active')
                ->orderBy('subject_name', 'desc')
                ->get();

            if (!empty($staff_info)) {
                return response()->json([
                    'status' => 200,
                    'all_Details' => [
                        'staff_details' => $staff_info,
                        'subject_details' => $subject_info,
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

    // delete all subject here....
    public function deleteAllSubject($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $check_deleteID = AssignedSubject::where('sub_tid', $id)->first();
            if (!empty($check_deleteID)) {
                // rund the delete query here
                AssignedSubject::query()
                    ->where('sub_tid', $id)
                    ->update([
                        'sub_status' => "Deleted",
                    ]);
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Deleted subject for teacher";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, All Subject assign to teacher was deleted";
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

    // delete single subject assign here...
    public function deleteSubject($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $find_deleteID = AssignedSubject::where('id', $id)->first();
            if (!empty($find_deleteID)) {
                // rund the delete query here
                $find_deleteID->update([
                    'sub_status' => "Deleted",
                ]);

                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Subject Deleted";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, A subject assign to teacher was deleted";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Subject Deleted Successfully',
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

    // fetch single subject details for edit here...
    public function fetchSubject($id)
    {
        if (auth('sanctum')->check()) {
            $fetch_subject = AssignedSubject::where('id', $id)->first();
            if (!empty($fetch_subject)) {
                return response()->json([
                    'status' => 200,
                    'fetch_info' => $fetch_subject,
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

    // update subject from edit api send

    public function updateEditSubject(Request $request)
    {
        if (auth('sanctum')->check()) {
            //dd($request->all());
            //validate input details
            $validator = Validator::make($request->all(), [
                'sub_subject_name' => 'required',
            ], [
                'sub_subject_name.required' => 'Subject Update Required'
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
            $check_updateID = AssignedSubject::where('id', $recordID)->first();
            $subs_name = Subject::where('id', $request->sub_subject_name)
                ->where('sub_status', 'Active')->first();

            if (!empty($check_updateID)) {
                // rund the update query here
                $check_updateID->update([
                    'sub_subject_id' => $request->sub_subject_name,
                    'sub_subject_name' => $subs_name->subject_name,
                ]);
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Update subject for teacher";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Update Subject assign to teacher";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Subject Updated Successfully',
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
}