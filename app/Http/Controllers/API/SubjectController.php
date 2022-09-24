<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Activitity_log;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    // function to save new subject details

    public function saveSubject(Request $request)
    {
        if (auth('sanctum')->check()) {

            $validator = Validator::make($request->all(), [
                'subject_name' => 'required|max:191',
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
                $className = Subject::where('subject_name', $request->subject_name)->first();
                if (!empty($className)) {
                    return response()->json([
                        $errors = $validator->errors(),
                        'status' => 402,
                        // 'validator_err' => $validator->messages(),
                        'message' => 'Subject name already exist!',
                    ]);
                } else if (empty($className)) {
                    $subject = new Subject();
                    $subject->subject_name = $request->subject_name;
                    $subject->sub_addedby = $userDetails->name;
                    $subject->sub_status = "Active";
                    $subject->sub_date = date('d/m/Y H:i:s');
                    $subject->save();

                    if ($subject->save()) {
                        // create history record here...
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Created new subject";
                        $logs->m_status = "Successful";
                        $logs->m_details = "A new subject was created by $userDetails->name";
                        $logs->m_date = date('d/m/Y H:i:s');
                        $logs->m_uid = $userDetails->id;
                        $logs->m_ip = request()->ip;

                        $logs->save();

                        return response()->json([
                            'status' => 200,
                            'message' => 'Subject Created Successfully',
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

    // fetch all subject here...
    public function fetchSubject()
    {
        if (auth('sanctum')->check()) {
            //$subject_details = Subject::where('sub_status', 'Active')->orderByDesc('id')->get();

            $subject_details = Subject::query()
                ->where('sub_status', 'Active')
                ->orderByDesc('id')
                ->paginate('15');
            if ($subject_details) {
                return response()->json([
                    'status' => 200,
                    'subject_record' => $subject_details,
                ]);
            } else if (empty($ca_details)) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No record at the moment'
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // fetch subject to show in entry result place

    public function getAllSubject()
    {
        if (auth('sanctum')->check()) {
            $subject_details = Subject::where('sub_status', 'Active')->orderByDesc('id')->get();
            if ($subject_details) {
                return response()->json([
                    'status' => 200,
                    'subject_record' => $subject_details,
                ]);
            } else if (empty($ca_details)) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No record at the moment'
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // delete subject goes here...

    public function deleteSubject($id)
    {
        if (auth('sanctum')->check()) {

            $userDetails = auth('sanctum')->user();
            $subject = Subject::find($id);
            if (!empty($subject)) {
                $subject->update([
                    'sub_status' => 'Deleted',
                ]);
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Deleted subject";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Delete subject details";
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
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // update subject goes here...

    public function saveUpdateSubject(Request $request)
    {
        if (auth('sanctum')->check()) {

            $validator = Validator::make($request->all(), [
                'subject_name' => 'required|max:191',
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
                $subject = Subject::where('id', $request->id)->first();
                if (!empty($subject)) {
                    $subject->update([
                        'subject_name' => $request->subject_name,
                    ]);
                    $logs = new Activitity_log();
                    $logs->m_username = $userDetails->username;
                    $logs->m_action = "Updated subject";
                    $logs->m_status = "Successful";
                    $logs->m_details = "$userDetails->name, Updated subject details";
                    $logs->m_date = date('d/m/Y H:i:s');
                    $logs->m_uid = $userDetails->id;
                    $logs->m_ip = request()->ip;
                    $logs->m_record_id = $subject->id;

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

    public function getSubject($id)
    {
        if (auth('sanctum')->check()) {
            $subjectDetails = Subject::where('id', $id)->first();
            if ($subjectDetails) {
                return response()->json([
                    'status' => 200,
                    'subject_details' => $subjectDetails,
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
}