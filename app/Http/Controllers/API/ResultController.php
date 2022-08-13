<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Activitity_log;
use App\Models\ClassModel;
use App\Models\ResultCA;
use App\Models\ResultProcessStart;
use App\Models\ResultSave;
use App\Models\ResultTable;
use App\Models\ResultViewCheck;
use App\Models\SchoolCategory;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TermModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResultController extends Controller
{
    // fetch all result details here...
    public function getAllResult()
    {
        if (auth('sanctum')->check()) {
            $user_details = auth('sanctum')->user();

            //$all_result_details = ResultProcessStart::get();
            $all_result_details = ResultProcessStart::where('r_status', '!=', 'Deleted')
                ->orWhereNull('r_status')
                ->orderBy('id', 'Desc')->get();
            if ($all_result_details) {
                return response()->json([
                    'status' => 200,
                    'result_record' => $all_result_details,
                ]);
            } else if (empty($$all_details)) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No record fund',
                ]);
            } else {
                return response()->json([
                    'status' => 405,
                    'message' => 'Server error occurred',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    // start result process here...
    public function resultProcessStart(Request $request)
    {
        /* Generate unique transaction ID for each cash request record */
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $tid = substr(str_shuffle($permitted_chars), 0, 16);
        if (auth('sanctum')->check()) {
            $validator = Validator::make($request->all(), [
                'school_year' => 'required|max:191',
                'school_term' => 'required|max:191',
                'class' => 'required|max:191',
                'subject' => 'required',
                'school_type' => 'required',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {
                $userDetails = auth('sanctum')->user();
                $save_new = new ResultProcessStart();
                $save_new->school_year = $request['school_year'];
                $save_new->school_term = $request['school_term'];
                $save_new->class = $request['class'];
                $save_new->school_category = $request['school_type'];
                $save_new->subject = $request['subject'];
                $save_new->r_tid = $tid;
                $save_new->addby = $userDetails->username;
                $save_new->r_date = date('d/m/Y H:i:s');
                $save_new->r_status = 'Active';

                $save_new->save();

                if ($save_new->save()) {
                    $logs = new Activitity_log();
                    $logs->m_username = $userDetails->username;
                    $logs->m_action = "Initiated result processing";
                    $logs->m_status = "Successful";
                    $logs->m_details = "$userDetails->name, Initiated new result processing details";
                    $logs->m_date = date('d/m/Y H:i:s');
                    $logs->m_uid = $userDetails->id;
                    $logs->m_ip = request()->ip;

                    $logs->save();

                    // get the result detail to pass to front end
                    $fetch_resultdetails = ResultProcessStart::where('r_tid', $tid)->first();
                    if ($fetch_resultdetails) {
                        return response()->json([
                            'status' => 200,
                            'allResultDetails' => [
                                'message' => 'Result Initiated Successfully',
                                'result_record_details' => $fetch_resultdetails,
                            ]

                        ]);
                    } else if (empty($fetch_resultdetails)) {
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

    // process result save here...
    // public function resultSave(Request $request)
    // {
    //     //dd($request['t_code']);
    //     foreach ($request->all() as $data) {
    //         ResultSave::create([
    //             'admin_number' => $data['admin_number'],
    //             'ca_1' => $data['ca1_score'],
    //             'ca_2' => $data['ca2_score'],
    //             'ca_total' => $data['ca1_score'] + $data['ca2_score'],
    //             'exam_score' => $data['exam_score'],
    //             'total' => $data['total'],
    //             'record_id' => $data['t_code'],
    //             'term' => $data['term'],
    //             'class' => $data['class'],
    //             'year' => $data['year'],
    //             'subject' => $data['subject'],
    //             'addby' => 'Ken',
    //             'res_status' => 'Active',
    //             'reg_date' => date('d/m/Y H:i:s'),
    //         ]);
    //         // delete the record from process start result table
    //         $recordID = $data['t_code'];
    //         $r_record = ResultProcessStart::where('r_tid', $recordID)->first();
    //         if (!empty($r_record)) {
    //             $r_record->delete();
    //         }
    //     }
    //     return response()->noContent();
    // }

    //delete result from view page goes here...
    public function deleteResultView($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            // details from result table
            $check_details = ResultTable::where('id', $id);
            if (empty($check_details)) {
                return response()->json([
                    'status' => 402,
                    'message' => 'Record not found! Try again',
                ]);
            } else if (!empty($check_details)) {
                // delete result details from result table
                $check_details->update([
                    'result_status' => 'Deleted',
                    'result_action_date' => date('d/m/Y H:i:s'),
                ]);
            }
            // keep history record here...
            $logs = new Activitity_log();
            $logs->m_username = $userDetails->username;
            $logs->m_action = "Deleted Result Details";
            $logs->m_status = "Successful";
            $logs->m_details = "$userDetails->name, Delete student result details";
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
    // function to delete result here...
    public function deleteResult($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $check_result = ResultProcessStart::find($id);

            if (empty($check_result)) {
                return response()->json([
                    'status' => 402,
                    'message' => 'Record not exist',
                ]);
            } else if (!empty($check_result)) {
                // details from result table
                $check_result_details = ResultTable::where('tid_code', $check_result->r_tid);
                if (!empty($check_result_details)) {
                    // delete result details from result table
                    $check_result_details->update([
                        'result_status' => 'Deleted',
                        'result_action_date' => date('d/m/Y H:i:s'),
                    ]);
                }
                // delete result details from processing start table
                $check_result->update([
                    'r_status' => 'Deleted',
                ]);
                // keep history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Deleted Result Details";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Delete student result details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Record Deleted Successfully',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }
    // process result save here...
    public function resultSave(Request $request)
    {
        $request->validate([
            'data.*.exam_score' => 'required'
        ], ['data.*.exam_score.required' => 'The exam score field is required']);
        // Check if this result exist before saving it...
        $check_resultDetail = ResultTable::where('academic_year', $request->year)
            ->where('academy_term', $request->term)
            ->where('subject', $request->subject)
            ->where('class', $request->class)->first();
        if (!empty($check_resultDetail)) {
            return response()->json([
                'status' => 403,
                'message' => 'Sorry, Result already exist',
            ]);
        } else {
            $userDetails = auth('sanctum')->user();
            //dd($request['t_code']);
            foreach ($request->data as $data) {
                if ($data['total'] >= 80) {
                    $grade = "A";
                    $remark = "Excellent";
                } elseif ($data['total'] >= 70) {
                    $grade = "B";
                    $remark = "Very Good";
                } elseif ($data['total'] >= 60) {
                    $grade = "C";
                    $remark = "Good";
                } elseif ($data['total'] >= 50) {
                    $grade = "D";
                    $remark = "Pass";
                } elseif ($data['total'] >= 40) {
                    $grade = "E";
                    $remark = "Fair";
                } elseif ($data['total'] >= 0) {
                    $grade = "F";
                    $remark = "Fail";
                }
                ResultTable::create([
                    'admin_number' => $data['st_admin_id'],
                    'first_ca' => $data['ca1'],
                    'second_ca' => $data['ca2'],
                    'tca_score' => $data['ca1'] + $data['ca2'],
                    'exam_scores' => $data['exam_score'],
                    'total_scores' => $data['total'],
                    'student_name' => $data['other_name'],
                    'academic_year' => $request->year,
                    'academy_term' => $request->term,
                    'subject' => $request->subject,
                    'class' => $request->class,
                    'tid_code' => $request->t_code,
                    'grade' => $grade,
                    'remark' => $remark,
                    'username' => $userDetails->username,
                    'school_category' => $request->school_category,
                    'result_status' => 'Active',
                    'result_date' => date('d/m/Y H:i:s'),
                ]);

                // get value from the request send from the front end
                $recordID = $request->t_code;
                $subjectID = $request->subject;
                $classID = $request->class;
                $categoryID = $request->school_category;
                $termID = $request->term;
                $yearID = $request->year;
                // how to get average score in the subject...
                $get_average = ResultTable::where('tid_code', $recordID)->sum('total_scores');
                // count total number of student...
                $count_student = ResultTable::where('tid_code', $recordID)->count('id');
                $average_score = ResultTable::where('tid_code', $recordID)->first();

                // get highest and lowest score here...
                $highest_score = ResultTable::where('tid_code', $recordID)->max('total_scores');
                $lower_score = ResultTable::where('tid_code', $recordID)->min('total_scores');
                $get_recordID = ResultTable::where('tid_code', $recordID)->first();
                if (!empty($get_recordID)) {
                    ResultTable::query()->where('tid_code', $recordID)->update([
                        'result_lowest' => $lower_score,
                        'result_highest' => $highest_score
                    ]);
                    // ResultTable::where('to_be_used_by_user_id', '!=' , 2)
                    // ->orWhereNull('to_be_used_by_user_id')->get();
                }
                if (!empty($average_score)) {
                    $nowAverage = ($get_average / $count_student);
                    ResultTable::query()->where('tid_code', $recordID)
                        ->update([
                            'average_scores' => $nowAverage,
                        ]);
                }
                // get student name here...
                $get_studentName = Student::where('st_admin_number', $data['st_admin_id'])->first();

                ResultTable::query()->where('admin_number', $get_studentName->st_admin_number)->where('tid_code', $recordID)
                    ->update([
                        'student_name' => $get_studentName->other_name,
                    ]);
            }
            // delete the record from process start result table
            $r_record = ResultProcessStart::where('r_tid', $recordID)->first();
            if (!empty($r_record)) {
                $r_record->delete();
            }
            // history record here...
            $logs = new Activitity_log();
            $logs->m_username = $userDetails->username;
            $logs->m_action = "Entered result";
            $logs->m_status = "Successful";
            $logs->m_details = "$userDetails->name, Entered new result details";
            $logs->m_date = date('d/m/Y H:i:s');
            $logs->m_uid = $userDetails->id;
            $logs->m_ip = request()->ip;
            $logs->save();

            // get these ID name stand in respective name.
            $sbujectName = Subject::where('id', $subjectID)->first();
            $className = ClassModel::where('id', $classID)->first();
            $yearName = AcademicSession::where('id', $yearID)->first();
            $termName = TermModel::where('id', $termID)->first();
            $categoryName = SchoolCategory::where('id', $categoryID)->first();

            // update process table to keep update of result processed.
            $keep = new ResultProcessStart();
            $keep->r_tid = $recordID;
            $keep->school_year = $yearName->academic_name;
            $keep->school_term = $termName->term_name;
            $keep->class = $className->class_name;
            $keep->school_category = $categoryName->sc_name;
            $keep->subject = $sbujectName->subject_name;
            $keep->addby = $userDetails->username;
            $keep->r_status = "Saved, Successfully";
            $keep->r_date = date('d/m/Y H:i:s');
            $keep->save();
            return response()->json([
                'status' => 200,
                'message' => "Result added successfully",
            ]);
        }
    }


    // process result save here...
    public function resultSingleSave(Request $request)
    {
        $request->validate([
            'year' => 'required',
            'term' => 'required',
            'subject' => 'required',
            'class' => 'required',
            'school_category' => 'required',

        ], [
            'year.required' => 'Academic year is required',
            'term.required' => 'Academic term is required',
            'subject.required' => 'Subject is required',
            'class.required' => 'Class is required',
            'school_category.required' => 'School Category is required'
        ]);
        // Check if this result exist before saving it...
        $check_resultDetail = ResultTable::where('academic_year', $request->year)
            ->where('academy_term', $request->term)
            ->where('subject', $request->subject)
            ->where('class', $request->class)->first();
        if (!empty($check_resultDetail)) {
            return response()->json([
                'status' => 403,
                'message' => 'Sorry, Result already exist',
            ]);
        } else {
            /* Generate unique transaction ID for each cash request record */
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

            $tid = substr(str_shuffle($permitted_chars), 0, 16);

            $userDetails = auth('sanctum')->user();
            //dd($request['t_code']);
            foreach ($request->data as $data) {
                if ($data['total'] >= 80) {
                    $grade = "A";
                    $remark = "Excellent";
                } elseif ($data['total'] >= 70) {
                    $grade = "B";
                    $remark = "Very Good";
                } elseif ($data['total'] >= 60) {
                    $grade = "C";
                    $remark = "Good";
                } elseif ($data['total'] >= 50) {
                    $grade = "D";
                    $remark = "Pass";
                } elseif ($data['total'] >= 40) {
                    $grade = "E";
                    $remark = "Fair";
                } elseif ($data['total'] >= 0) {
                    $grade = "F";
                    $remark = "Fail";
                }
                ResultTable::create([
                    'admin_number' => $data['admin_number'],
                    'first_ca' => $data['ca1_score'],
                    'second_ca' => $data['ca2_score'],
                    'tca_score' => $data['ca1_score'] + $data['ca2_score'],
                    'exam_scores' => $data['exam_score'],
                    'total_scores' => $data['total'],
                    'academic_year' => $request->year,
                    'academy_term' => $request->term,
                    'subject' => $request->subject,
                    'class' => $request->class,
                    'tid_code' => $tid,
                    'grade' => $grade,
                    'remark' => $remark,
                    'username' => $userDetails->username,
                    'school_category' => $request->school_category,
                    'result_status' => 'Active',
                    'result_date' => date('d/m/Y H:i:s'),
                ]);
                // get value from the request send from the front end
                $recordID = $tid;
                $subjectID = $request->subject;
                $classID = $request->class;
                $categoryID = $request->school_category;
                $termID = $request->term;
                $yearID = $request->year;
                // how to get average score in the subject...
                $get_average = ResultTable::where('tid_code', $recordID)->sum('total_scores');
                // count total number of student...
                $count_student = ResultTable::where('tid_code', $recordID)->count('id');
                $average_score = ResultTable::where('tid_code', $recordID)->first();

                // get highest and lowest score here...
                $highest_score = ResultTable::where('tid_code', $recordID)->max('total_scores');
                $lower_score = ResultTable::where('tid_code', $recordID)->min('total_scores');
                $get_recordID = ResultTable::where('tid_code', $recordID)->first();
                if (!empty($get_recordID)) {
                    ResultTable::query()->where('tid_code', $recordID)->update([
                        'result_lowest' => $lower_score,
                        'result_highest' => $highest_score
                    ]);
                    // ResultTable::where('to_be_used_by_user_id', '!=' , 2)
                    // ->orWhereNull('to_be_used_by_user_id')->get();
                }
                if (!empty($average_score)) {
                    $nowAverage = ($get_average / $count_student);
                    ResultTable::query()->where('tid_code', $recordID)
                        ->update([
                            'average_scores' => $nowAverage,
                        ]);
                }

                // get student name here...
                $get_studentName = Student::where('st_admin_number', $data['admin_number'])->first();
                ResultTable::query()->where('admin_number', $get_studentName->st_admin_number)->where('tid_code', $recordID)
                    ->update([
                        'student_name' => $get_studentName->other_name,
                    ]);
            }
            // delete the record from process start result table
            $r_record = ResultProcessStart::where('r_tid', $recordID)->first();
            if (!empty($r_record)) {
                $r_record->delete();
            }
            // history record here...
            $logs = new Activitity_log();
            $logs->m_username = $userDetails->username;
            $logs->m_action = "Entered result";
            $logs->m_status = "Successful";
            $logs->m_details = "$userDetails->name, Entered new result details";
            $logs->m_date = date('d/m/Y H:i:s');
            $logs->m_uid = $userDetails->id;
            $logs->m_ip = request()->ip;
            $logs->save();

            // get these ID name stand in respective name.
            $sbujectName = Subject::where('id', $subjectID)->first();
            $className = ClassModel::where('id', $classID)->first();
            $yearName = AcademicSession::where('id', $yearID)->first();
            $termName = TermModel::where('id', $termID)->first();
            $categoryName = SchoolCategory::where('id', $categoryID)->first();

            // update process table to keep update of result processed.
            $keep = new ResultProcessStart();
            $keep->r_tid = $recordID;
            $keep->school_year = $yearName->academic_name;
            $keep->school_term = $termName->term_name;
            $keep->class = $className->class_name;
            $keep->school_category = $categoryName->sc_name;
            $keep->subject = $sbujectName->subject_name;
            $keep->addby = $userDetails->username;
            $keep->r_status = "Saved, Successfully";
            $keep->r_date = date('d/m/Y H:i:s');
            $keep->save();
            return response()->json([
                'status' => 200,
                'message' => "Result added successfully",
            ]);
        }
    }

    // fetch the result start inserted....
    public function getFetchResult($id)
    {
        $get_etDetails = ResultProcessStart::where('r_tid', $id)->first();
        //get CA details here
        $fetch_ca = ResultCA::where('rst_class', $get_etDetails->class)
            ->where('rst_year', $get_etDetails->school_year)
            ->where('rst_subject', $get_etDetails->subject)
            ->where('rst_term', $get_etDetails->school_term)
            ->get();

        if ($fetch_ca) {
            return response()->json([
                'status' => 200,
                'all_details' => [
                    'start_item' => $get_etDetails,
                    'student_result' => $fetch_ca,
                ]
            ]);
        } else {
            return response()->json([
                'status' => 402,
                'message' => "something went wrong! Try again",
            ]);
        }
    }

    // process view result here..

    public function viewResultProcess(Request $request)
    {
        /* Generate unique transaction ID for each cash request record */
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $tid = substr(str_shuffle($permitted_chars), 0, 16);

        if (auth('sanctum')->check()) {
            $validator = Validator::make($request->all(), [
                'class_year' => 'required|max:191',
                'class_term' => 'required|max:191',
                'class_apply' => 'required|max:191',
                'class_category' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {
                $get_class = ResultTable::where('class', $request->class_apply)
                    ->where('academic_year', $request->class_year)
                    ->where('academy_term', $request->class_term)
                    ->where('school_category', $request->class_category)
                    ->where('result_status', 'Active')->first();
                if (empty($get_class)) {
                    return response()->json([
                        'status' => 404,
                        'message' => "No result found for class",
                    ]);
                } elseif (!empty($get_class)) {
                    $userDetails = auth('sanctum')->user();
                    $save_new = new ResultViewCheck();
                    $save_new->year = $request['class_year'];
                    $save_new->term = $request['class_term'];
                    $save_new->class = $request['class_apply'];
                    $save_new->category = $request['class_category'];
                    $save_new->subject = $request['subject'];
                    $save_new->view_code = $tid;
                    $save_new->view_by = $userDetails->username;
                    $save_new->reg_date = date('d/m/Y H:i:s');
                    $save_new->status = 'Active';

                    $save_new->save();
                }
                if ($save_new->save()) {
                    $logs = new Activitity_log();
                    $logs->m_username = $userDetails->username;
                    $logs->m_action = "View result detail";
                    $logs->m_status = "Successful";
                    $logs->m_details = "$userDetails->name, Initiated result viewing";
                    $logs->m_date = date('d/m/Y H:i:s');
                    $logs->m_uid = $userDetails->id;
                    $logs->m_ip = request()->ip;

                    $logs->save();
                }
                return response()->json([
                    'status' => 200,
                    'view_code' => $tid,
                    'message' => 'Result generated! Viewing available',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // load check result here...
    public function loadResultView($id)
    {
        $get_etview = ResultViewCheck::where('view_code', $id)->first();

        //get CA details here
        $fetch_view = ResultTable::where('class', $get_etview->class)
            ->where('academic_year', $get_etview->year)
            ->where('academy_term', $get_etview->term)
            ->where('result_status', 'Active')
            ->get();
        if ($fetch_view) {
            return response()->json([
                'status' => 200,
                'all_details' => [
                    'result' => $fetch_view,

                ]
            ]);
        } else {
            return response()->json([
                'status' => 402,
                'message' => "something went wrong! Try again",
            ]);
        }
    }
    // load result base on subject selected from the front end.
    public function loadSubjectView($id)
    {
        $get_viewSubject = ResultViewCheck::where('view_code', $id)->first();

        //get CA details here
        $fetch_view = ResultTable::where('class', $get_viewSubject->class)
            ->where('academic_year', $get_viewSubject->year)
            ->where('academy_term', $get_viewSubject->term)
            ->where('subject', $get_viewSubject->subject)
            ->where('result_status', 'Active')
            ->get();
        if ($fetch_view) {
            return response()->json([
                'status' => 200,
                'all_details' => [
                    'result' => $fetch_view,

                ]
            ]);
        } else {
            return response()->json([
                'status' => 402,
                'message' => "something went wrong! Try again",
            ]);
        }
    }
    // get subject details when edit is clicked from view
    // result page

    public function loadView($id)
    {
        //get CA details here
        $fetch_result = ResultTable::where('id', $id)->where('result_status', 'Active')->first();
        if ($fetch_result) {
            return response()->json([
                'status' => 200,
                'resultDetails' => $fetch_result,
            ]);
        } else {
            return response()->json([
                'status' => 402,
                'message' => "something went wrong! Try again",
            ]);
        }
    }
    //update result operation from view page goes here...
    public function updateResultView(Request $request)
    {
        if (auth('sanctum')->check()) {
            $validator = Validator::make($request->all(), [
                'first_ca' => 'required|max:191',
                'second_ca' => 'required|max:191',
                'tca_score' => 'required|max:191',
                'exam_scores' => 'required',
                'total_scores' => 'required',
                'grade' => 'required',
                'remark' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {
                $userDetails = auth('sanctum')->user();
                // details from result table
                $check_result_details = ResultTable::where('id', $request->id);
                if (!empty($check_result_details)) {
                    // delete result details from result table
                    $check_result_details->update([
                        'first_ca' => $request->first_ca,
                        'second_ca' => $request->second_ca,
                        'tca_score' => $request->tca_score,
                        'exam_scores' => $request->exam_scores,
                        'total_scores' => $request->total_scores,
                        'grade' => $request->grade,
                        'remark' => $request->remark,
                    ]);
                }
                // keep history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Update Result Details";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Updated student result details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Record Updated Successfully',
                ]);
            }
            return response()->json([
                'status' => 403,
                'message' => 'Error occurred! Try again',
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // get result details base on subject selected from the
    // front end.
    public function viewSubjectResult(Request $request)
    {
        /* Generate unique transaction ID for each cash request record */
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $tid = substr(str_shuffle($permitted_chars), 0, 16);

        if (auth('sanctum')->check()) {
            $validator = Validator::make($request->all(), [
                'year' => 'required|max:191',
                'term' => 'required|max:191',
                'class' => 'required|max:191',
                'subject' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {
                $get_subject = ResultTable::where('class', $request->class)
                    ->where('academic_year', $request->year)
                    ->where('academy_term', $request->term)
                    ->where('subject', $request->subject)
                    ->where('result_status', 'Active')->first();
                if (empty($get_subject)) {
                    return response()->json([
                        'status' => 404,
                        'message' => "No result found for subject",
                    ]);
                } elseif (!empty($get_subject)) {
                    $userDetails = auth('sanctum')->user();
                    $save_new = new ResultViewCheck();
                    $save_new->year = $request['year'];
                    $save_new->term = $request['term'];
                    $save_new->class = $request['class'];
                    $save_new->subject = $request['subject'];
                    $save_new->view_code = $tid;
                    $save_new->view_by = $userDetails->username;
                    $save_new->reg_date = date('d/m/Y H:i:s');
                    $save_new->status = 'Active';

                    $save_new->save();
                }
                if ($save_new->save()) {
                    $logs = new Activitity_log();
                    $logs->m_username = $userDetails->username;
                    $logs->m_action = "View result detail";
                    $logs->m_status = "Successful";
                    $logs->m_details = "$userDetails->name, Initiated result viewing";
                    $logs->m_date = date('d/m/Y H:i:s');
                    $logs->m_uid = $userDetails->id;
                    $logs->m_ip = request()->ip;

                    $logs->save();
                }
                return response()->json([
                    'status' => 200,
                    'view_code' => $tid,
                    'message' => 'Result generated! Viewing available',
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