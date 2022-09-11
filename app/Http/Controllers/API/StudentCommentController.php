<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Activitity_log;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\StudentComment;
use App\Models\TermModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StudentCommentController extends Controller
{
    // fetch all student comment here...
    public function fetchAllComment()
    {
        if (auth('sanctum')->check()) {

            $user_details = auth('sanctum')->user();
            $comm_details = DB::table('student_comments')
                ->selectRaw('id, comm_stu_number, comm_stu_name,
            comm_class,  comm_year , comm_term, comm_comment, comm_prin_comment,
            comm_status, comm_addby, comm_date, comm_tid')
                ->where('comm_status', '=', 'Active')
                ->groupBy('comm_tid')
                ->get();

            if (!empty($comm_details)) {
                return response()->json([
                    'status' => 200,
                    'result_record' => $comm_details,
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

    // start comment process here....
    public function processComment(Request $request)
    {
        /* Generate unique transaction ID for each cash request record */
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $tid = substr(str_shuffle($permitted_chars), 0, 16);
        if (auth('sanctum')->check()) {
            $validator = Validator::make($request->all(), [
                'school_year' => 'required|max:191',
                'school_term' => 'required|max:191',
                'class' => 'required|max:191',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {
                $userDetails = auth('sanctum')->user();
                $save_new = new StudentComment();
                $save_new->comm_year = $request['school_year'];
                $save_new->comm_term = $request['school_term'];
                $save_new->comm_class = $request['class'];
                $save_new->comm_tid = $tid;
                $save_new->comm_addby = $userDetails->username;
                $save_new->comm_date = date('d/m/Y H:i:s');
                $save_new->comm_status = 'Initiated';

                $save_new->save();

                if ($save_new->save()) {
                    $logs = new Activitity_log();
                    $logs->m_username = $userDetails->username;
                    $logs->m_action = "Initiated student comment processing";
                    $logs->m_status = "Successful";
                    $logs->m_details = "$userDetails->name, Initiated new result processing details";
                    $logs->m_date = date('d/m/Y H:i:s');
                    $logs->m_uid = $userDetails->id;
                    $logs->m_ip = request()->ip;

                    $logs->save();

                    // get the result detail to pass to front end
                    $fetch_Commnentdetails = StudentComment::where('comm_tid', $tid)
                        ->first();
                    if ($fetch_Commnentdetails) {
                        return response()->json([
                            'status' => 200,
                            'allDetails' => [
                                'message' => 'Operation Initiated Successfully',
                                'result_record_details' => $fetch_Commnentdetails,
                            ]

                        ]);
                    } else if (empty($fetch_Commnentdetails)) {
                        return response()->json([
                            'status' => 404,
                            'message' => 'Error Occurred, Try Again',
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

    // get student details here for comment...
    public function getStudentComment($id)
    {
        if (auth('sanctum')->check()) {
            $get_etDetails = StudentComment::where('comm_tid', $id)->first();

            // //get CA details here
            // $fetch_ca = ResultCA::where('rst_class', $get_etDetails->class)
            //     ->where('rst_year', $get_etDetails->school_year)
            //     ->where('rst_subject', $get_etDetails->subject)
            //     ->where('rst_term', $get_etDetails->school_term)
            //     ->get();
            // get student details here...
            $fetch_student = Student::where('class_apply', $get_etDetails->comm_class)
                ->where('acct_status', 'Active')
                ->get();
            if (!empty($get_etDetails)) {
                return response()->json([
                    'status' => 200,
                    'all_details' => [
                        'start_item' => $get_etDetails,
                        'student_result' => $fetch_student,
                    ]
                ]);
            } else if (empty($get_etDetails)) {
                return response()->json([
                    'status' => 404,
                    'message' => "No record found",
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
    // save comment post here....
    public function saveComment(Request $request)
    {
        if (auth('sanctum')->check()) {
            $request->validate([
                'data.*.comment' => 'required'
            ], ['data.*.comment.required' => 'The comment field is required']);
            // Check if this result exist before saving it...
            $check_resultComment = StudentComment::where('comm_year', $request->year)
                ->where('comm_term', $request->term)
                ->where('comm_class', $request->class)
                ->where('comm_status', 'Active')
                ->where('comm_prin_comment', '!=', '')
                ->first();
            if (!empty($check_resultComment)) {
                return response()->json([
                    'status' => 403,
                    'message' => 'Sorry, comment already exist',
                ]);
            } else {
                $userDetails = auth('sanctum')->user();
                //dd($request['t_code']);
                foreach ($request->data as $data) {
                    //get class name here...
                    $className = ClassModel::where('id', $request->class)->first();
                    $yearName = AcademicSession::where('id', $request->year)->first();
                    $termName = TermModel::where('id', $request->term)->first();

                    StudentComment::create([
                        'comm_stu_number' => $data['st_admin_id'],
                        'comm_stu_name' => $data['other_name'],
                        'comm_class' => $className->class_name,
                        'comm_year' => $yearName->academic_name,
                        'comm_term' => $termName->term_name,
                        'comm_comment' => $data['comment'],
                        'comm_prin_comment' => 'Principal',
                        'comm_status' => 'Active',
                        'comm_addby' => $userDetails->username,
                        'comm_date' => date('d/m/Y H:i:s'),
                        'comm_tid' => $request->t_code,

                    ]);
                }
                // delete the record from process start result table
                // $r_record = ResultProcessStart::where('r_tid', $recordID)->first();
                // if (!empty($r_record)) {
                //     $r_record->delete();
                // }
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Comment added";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Student comment details added";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();
                // delete the started operation details here
                $r_record = StudentComment::where('comm_tid', $request->t_code)->first();
                if (!empty($r_record)) {
                    $r_record->delete();
                }
                return response()->json([
                    'status' => 200,
                    'message' => "Comment posted successfully",
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }
    // delete all comment here base on TID
    public function deleteAllComment($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            // details from result table
            $check_delete_details = StudentComment::where('id', $id)->first();
            if (empty($check_delete_details)) {
                return response()->json([
                    'status' => 402,
                    'message' => 'Record not found! Try again',
                ]);
            } else if (!empty($check_delete_details)) {
                // delete all comment details here...
                StudentComment::query()
                    ->where('comm_tid', $check_delete_details->comm_tid)
                    ->where('comm_status', 'Active')
                    ->update([
                        'comm_status' => "Deleted",
                    ]);
            }
            // keep history record here...
            $logs = new Activitity_log();
            $logs->m_username = $userDetails->username;
            $logs->m_action = "Deleted comment";
            $logs->m_status = "Successful";
            $logs->m_details = "$userDetails->name, Delete student comment details";
            $logs->m_date = date('d/m/Y H:i:s');
            $logs->m_uid = $userDetails->id;
            $logs->m_ip = request()->ip;
            $logs->save();

            return response()->json([
                'status' => 200,
                'message' => 'Record Deleted Successfully',
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // fetch comment details for viewing
    public function fetchCommentDetails($id)
    {
        if (auth('sanctum')->check()) {
            $fetch_ID = StudentComment::where('comm_tid', $id)->first();
            $get_allComment = StudentComment::where('comm_tid', $fetch_ID->comm_tid)->get();
            if (!empty($fetch_ID)) {
                return response()->json([
                    'status' => 200,
                    'all_details' => [
                        'start_item' => $fetch_ID,
                        'comment_result' => $get_allComment,
                    ]
                ]);
            } else if (empty($fetch_ID)) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No record found!',
                ]);
            } else
                return response()->json([
                    'status' => 500,
                    'message' => 'Server error, try again!',
                ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // delete single_comment here
    public function deleteComment($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $find_deleteID = StudentComment::where('id', $id)->first();
            if (!empty($find_deleteID)) {
                // rund the delete query here
                $find_deleteID->delete();

                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Comment Deleted";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Student comment was deleted";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Record Deleted Successfully',
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

    // delete all comment once here
    public function deleteCommentAll($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            // details from result table
            $check_delete_comment = StudentComment::where('comm_tid', $id)->first();
            if (empty($check_delete_comment)) {
                return response()->json([
                    'status' => 402,
                    'message' => 'Record not found! Try again',
                ]);
            } else if (!empty($check_delete_comment)) {
                // delete all comment details here...
                StudentComment::query()
                    ->where('comm_tid', $check_delete_comment->comm_tid)
                    ->where('comm_status', 'Active')
                    ->delete();
            }
            // keep history record here...
            $logs = new Activitity_log();
            $logs->m_username = $userDetails->username;
            $logs->m_action = "Deleted comment";
            $logs->m_status = "Successful";
            $logs->m_details = "$userDetails->name, Delete student comment details";
            $logs->m_date = date('d/m/Y H:i:s');
            $logs->m_uid = $userDetails->id;
            $logs->m_ip = request()->ip;
            $logs->save();

            return response()->json([
                'status' => 200,
                'message' => 'Record Deleted Successfully',
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }
    // fetch comment here for editing in the preview page
    public function fetchEditComment($id)
    {
        if (auth('sanctum')->check()) {
            $fetch_ID = StudentComment::where('id', $id)->first();
            $get_editComment = StudentComment::where('id', $fetch_ID->id)->first();
            if (!empty($fetch_ID)) {
                return response()->json([
                    'status' => 200,
                    'fetch_info' => $get_editComment,
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

    // save comment update from preview page here...

    public function saveCommentUpdate(Request $request)
    {
        if (auth('sanctum')->check()) {
            //dd($request->all());
            //validate input details
            $validator = Validator::make($request->all(), [
                'comm_comment' => 'required',
            ], [
                'comm_comment.required' => 'Comment can not be empty',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            }
            $recordID = $request->id;
            $userDetails = auth('sanctum')->user();
            $check_updateComment = StudentComment::where('id', $recordID)->first();

            if (!empty($check_updateComment)) {
                // run the update query here
                $check_updateComment->update([
                    'comm_comment' => $request->comm_comment,
                ]);
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Update student comment";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Update student comment details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Record Updated Successfully',
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