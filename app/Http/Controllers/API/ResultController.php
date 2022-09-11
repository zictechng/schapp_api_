<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Activitity_log;
use App\Models\ClassModel;
use App\Models\Graduation;
use App\Models\ProcessGrading;
use App\Models\ResultCA;
use App\Models\ResultProcessStart;
use App\Models\ResultTable;
use App\Models\ResultViewCheck;
use App\Models\SchoolCategory;
use App\Models\StartGraduation;
use App\Models\StartPromotion;
use App\Models\Student;
use App\Models\StudentPosition;
use App\Models\Subject;
use App\Models\TermModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $all_resultID = ResultProcessStart::where('r_status', '!=', 'Deleted')
                ->first();
            if ($all_result_details) {
                return response()->json([
                    'status' => 200,
                    'allResultPost' => [
                        'allPostResult' => $all_result_details,
                        'result_ID' => $all_resultID,
                    ]

                ]);
            } else if (empty($all_details)) {
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
                $save_new->addby_id = $userDetails->id;
                $save_new->r_date = date('d/m/Y H:i:s');
                $save_new->r_status = 'Pending';

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

    // fetch subject details here base on TID
    public function getSubjectID($id)
    {
        if (auth('sanctum')->check()) {

            $userDetails = auth('sanctum')->user();
            $sub_id = ResultTable::where('tid_code', $id)->first();
            $sub_attetails = ResultTable::where('tid_code', $sub_id->tid_code)
                ->orderBy('total_scores', 'desc')
                ->get();
            $className = ClassModel::where('id', $sub_id->class)->first();
            $subjectName = Subject::where('id', $sub_id->subject)->first();
            if (!empty($sub_attetails)) {
                return response()->json([
                    'status' => 200,
                    'sub_assignDetails' => [
                        'proDetails' => $sub_attetails,
                        'pDetails' => $sub_id,
                        'pClass' => $className,
                        'pSubject' => $subjectName,
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
                    'result_status' => 'Pending',
                    'result_date' => date('d/m/Y H:i:s'),
                ]);

                // get value from the request send from the front end
                $recordID = $request->t_code;

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
            $logs->m_action = "Result entered";
            $logs->m_status = "Successful";
            $logs->m_details = "$userDetails->name, Entered new result details";
            $logs->m_date = date('d/m/Y H:i:s');
            $logs->m_uid = $userDetails->id;
            $logs->m_ip = request()->ip;
            $logs->save();

            // generate subject position here

            $grades = DB::table('result_tables')
                ->selectRaw('sum(total_scores) as user_total, sum(tca_score) as user_tca, sum(exam_scores) as user_exam_total, tca_score, total_scores, exam_scores,
                   admin_number,student_name, academic_year, academy_term, class,
                    school_category, username, result_status, tid_code')
                ->where('academic_year', '=', $request->year)
                ->where('academy_term', '=', $request->term)
                ->where('class', '=', $request->class)
                ->groupBy('admin_number')
                ->orderBy('user_total', 'desc')
                ->get();
            $rank = 0;
            $same = 1;
            $previous = null;
            foreach ($grades as $score) {
                $rank++;
                if ($previous && $previous->user_total != $score->user_total) {
                    $same = $rank;
                }
                if ($previous && $previous->user_total == $score->user_total) {
                    $score->rank = $same;
                } else {
                    $score->rank = $rank;
                }
                $previous = $score;
            }
            //dd($grouped);
            $get_g = ResultTable::where('tid_code', $recordID)
                ->first();
            // get class name here
            $className = ClassModel::where('id', $get_g->class)->first();
            if (!empty($grades)) {
                return response()->json([
                    'status' => 200,
                    'resultAll' => [
                        'fetchResult' => $grades,
                        'tID' => $get_g,
                        'className' => $className,
                    ]
                ]);
            }

            // return response()->json([
            //     'status' => 200,
            //     'message' => "Result added successfully",
            // ]);
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
                    'result_status' => 'Pending',
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
                ResultTable::query()->where('admin_number', $get_studentName->st_admin_number)
                    ->where('tid_code', $recordID)
                    ->update([
                        'student_name' => $get_studentName->other_name,
                    ]);
            }
            // delete the record from process start result table
            $r_record = ResultProcessStart::where('r_tid', $recordID)->first();
            if (!empty($r_record)) {
                $r_record->delete();
            }
            // generate subject position here

            $grades = DB::table('result_tables')
                ->selectRaw('sum(total_scores) as user_total, sum(tca_score) as user_tca, sum(exam_scores) as user_exam_total, tca_score, total_scores, exam_scores,
                admin_number,student_name, academic_year, academy_term, class,
                 school_category, username, result_status, tid_code')
                ->where('academic_year', '=', $request->year)
                ->where('academy_term', '=', $request->term)
                ->where('class', '=', $request->class)
                ->groupBy('admin_number')
                ->orderBy('user_total', 'desc')
                ->get();
            $rank = 0;
            $same = 1;
            $previous = null;
            foreach ($grades as $score) {
                $rank++;
                if ($previous && $previous->user_total != $score->user_total) {
                    $same = $rank;
                }
                if ($previous && $previous->user_total == $score->user_total) {
                    $score->rank = $same;
                } else {
                    $score->rank = $rank;
                }
                $previous = $score;
            }
            //dd($grouped);
            $get_g = ResultTable::where('tid_code', $recordID)
                ->first();

            // history record here...
            $logs = new Activitity_log();
            $logs->m_username = $userDetails->username;
            $logs->m_action = "Single result initiated";
            $logs->m_status = "Successful";
            $logs->m_details = "$userDetails->name, Single result process get started";
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
            $keep->r_status = "Pending";
            $keep->r_date = date('d/m/Y H:i:s');
            $keep->save();
            return response()->json([
                'status' => 200,
                'resultAll' => [
                    'fetchResult' => $grades,
                    'tID' => $get_g,
                    'className' => $className,
                ]
            ]);
        }
    }

    // fetch the result start inserted....
    public function getFetchResult($id)
    {
        $get_etDetails = ResultProcessStart::where('r_tid', $id)->first();

        $fetch_ca_check = ResultCA::where('rst_class', $get_etDetails->class)
            ->where('rst_year', $get_etDetails->school_year)
            ->where('rst_subject', $get_etDetails->subject)
            ->where('rst_term', $get_etDetails->school_term)
            ->first();
        //get CA details here
        $fetch_ca = ResultCA::where('rst_class', $get_etDetails->class)
            ->where('rst_year', $get_etDetails->school_year)
            ->where('rst_subject', $get_etDetails->subject)
            ->where('rst_term', $get_etDetails->school_term)
            ->get();
        $fetch_student = Student::where('class_apply', $get_etDetails->class)
            ->where('acct_status', 'Active')
            ->get();

        if (empty($fetch_ca_check)) {
            return response()->json([
                'status' => 201,
                // 'message' => 'CA Score not found'
                'all_details' => [
                    'start_item' => $get_etDetails,
                    'student_result' => $fetch_student,
                ]
            ]);
        } else if (!empty($fetch_ca_check)) {
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

    // repair result function goes here...
    public function repairResult(Request $request)
    {
        if (auth('sanctum')->check()) {
            /* Generate unique transaction ID for each cash request record */
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $tid = substr(str_shuffle($permitted_chars), 0, 16);
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
                //get th transaction ID
                $get_id = ResultTable::where('academic_year', $request->school_year)
                    ->where('academy_term', $request->school_term)
                    ->where('class', $request->class)
                    ->where('result_status', 'Active')->first();

                if (!empty($get_id)) {
                    return response()->json([
                        'status' => 200,
                        'resultDetails' => [
                            // 'fetchResult' => $checkResults,
                            'tID' => $get_id,
                        ]
                    ]);
                } else if (empty($get_id)) {
                    return response()->json([
                        'status' => 404,
                        'message' => "No result found! Try again",
                    ]);
                }
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    // trash result here from repair result front end action...
    public function trashRepairResult($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            // details from result table
            $check_id = ResultTable::where('tid_code', $id);
            if (!empty($check_id)) {
                // delete result details from result table
                $check_id->update([
                    'result_status' => "Deleted",
                ]);
                // keep history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Delete Result Details";
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
            } elseif (empty($check_id)) {
                return response()->json([
                    'status' => 403,
                    'message' => 'Error occurred! Try again',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    //get result details here...
    public function repairResultGet($id)
    {
        if (auth('sanctum')->check()) {
            // details from result table
            $checkRes = ResultTable::where('tid_code', $id)->where('result_status', 'Active')->get();
            $getID = ResultTable::where('tid_code', $id)->first();
            if (!empty($checkRes)) {
                return response()->json([
                    'status' => 200,
                    'resultAll' => [
                        'fetchResult' => $checkRes,
                        'tID' => $getID,
                    ]
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "No result found at the moment",
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    // repair result position function goes here...
    public function repairResultPosition(Request $request)
    {
        if (auth('sanctum')->check()) {
            /* Generate unique transaction ID for each cash request record */
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $tid = substr(str_shuffle($permitted_chars), 0, 16);
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
                //get th transaction ID
                $get_id = StudentPosition::where('sch_year', $request->school_year)
                    ->where('sch_term', $request->school_term)
                    ->where('sch_class', $request->class)
                    ->where('p_status', 'Active')->first();

                if (!empty($get_id)) {
                    return response()->json([
                        'status' => 200,
                        'resultDetails' => [
                            // 'fetchResult' => $checkResults,
                            'tID' => $get_id,
                        ]
                    ]);
                } else if (empty($get_id)) {
                    return response()->json([
                        'status' => 404,
                        'message' => "No result found! Try again",
                    ]);
                }
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }
    //get position details by ID here...
    public function repairPositionGet($id)
    {
        if (auth('sanctum')->check()) {
            // details from result table
            $checkPos = StudentPosition::where('user_code', $id)->where('p_status', 'Active')->get();
            $getIDPos = StudentPosition::where('user_code', $id)->first();
            if (!empty($checkPos)) {
                return response()->json([
                    'status' => 200,
                    'resultAll' => [
                        'fetchPosition' => $checkPos,
                        'pID' => $getIDPos,
                    ]
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "No result found at the moment",
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    // trash result position here from repair result front end action...
    public function trashPositionResult($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            // details from result table
            $check_id = StudentPosition::where('user_code', $id);
            if (!empty($check_id)) {
                // delete result details from result table
                $check_id->update([
                    'p_status' => "Deleted",
                ]);
                // keep history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Delete Position Details";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Delete student class position result details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Record Deleted Successfully',
                ]);
            } elseif (empty($check_id)) {
                return response()->json([
                    'status' => 403,
                    'message' => 'Error occurred! Try again',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }
    // repair subject here...
    public function repairResultSubject(Request $request)
    {
        if (auth('sanctum')->check()) {
            /* Generate unique transaction ID for each cash request record */
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $tid = substr(str_shuffle($permitted_chars), 0, 16);
            $validator = Validator::make($request->all(), [
                'school_year' => 'required|max:191',
                'school_term' => 'required|max:191',
                'class' => 'required|max:191',
                'subject' => 'required|max:191',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {
                //get th transaction ID
                $get_id = ResultTable::where('academic_year', $request->school_year)
                    ->where('academy_term', $request->school_term)
                    ->where('class', $request->class)
                    ->where('subject', $request->subject)
                    ->where('result_status', 'Active')->first();

                if (!empty($get_id)) {
                    return response()->json([
                        'status' => 200,
                        'resultDetails' => [
                            // 'fetchResult' => $checkResults,
                            'tID' => $get_id,
                        ]
                    ]);
                } else if (empty($get_id)) {
                    return response()->json([
                        'status' => 404,
                        'message' => "No result found! Try again",
                    ]);
                }
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    // CA repair operation here...
    public function repairResultCA(Request $request)
    {
        if (auth('sanctum')->check()) {
            /* Generate unique transaction ID for each cash request record */
            $validator = Validator::make($request->all(), [
                'school_year' => 'required|max:191',
                'school_term' => 'required|max:191',
                'class' => 'required|max:191',
                'class_category' => 'required|max:191',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {
                //get th transaction ID
                $get_id = ResultCA::where('rst_year', $request->school_year)
                    ->where('rst_term', $request->school_term)
                    ->where('rst_class', $request->class)
                    ->where('rst_category', $request->class_category)
                    ->where('rst_status', 'Active')->first();

                if (!empty($get_id)) {
                    return response()->json([
                        'status' => 200,
                        'resultDetails' => [
                            // 'fetchResult' => $checkResults,
                            'tID' => $get_id,
                        ]
                    ]);
                } else if (empty($get_id)) {
                    return response()->json([
                        'status' => 404,
                        'message' => "No result found! Try again",
                    ]);
                }
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    // trash repair CA here...
    public function getResultCADetails($id)
    {
        if (auth('sanctum')->check()) {
            // details from result table
            $check_ca = ResultCA::where('rst_tid', $id)->where('rst_status', 'Active')->get();
            $get_caID = ResultCA::where('rst_tid', $id)->where('rst_status', 'Active')->first();
            if (!empty($check_ca)) {
                return response()->json([
                    'status' => 200,
                    'resultAll' => [
                        'fetchResult' => $check_ca,
                        'tID' => $get_caID,
                    ]
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "No result found at the moment",
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }
    // trash CA result details here...
    public function trashResultCA($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            // details from result table
            $check_id = ResultCA::where('rst_tid', $id);
            if (!empty($check_id)) {
                // delete result details from result table
                $check_id->update([
                    'rst_status' => "Deleted",
                ]);
                // keep history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Delete CA Result Details";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Delete student CA result details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Record Deleted Successfully',
                ]);
            } elseif (empty($check_id)) {
                return response()->json([
                    'status' => 403,
                    'message' => 'Error occurred! Try again',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    // fetch all graded score here...
    public function fetchGrade()
    {
        if (auth('sanctum')->check()) {
            $all_grade = StudentPosition::where('p_status', '!=', 'Deleted')
                ->orWhereNull('p_status')
                ->orderBy('id', 'Desc')
                ->groupBy('sch_class')->get();
            if ($all_grade) {
                return response()->json([
                    'status' => 200,
                    'grade_record' => $all_grade,
                ]);
            } else if (empty($all_grade)) {
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

    // generate student score to process grading...
    public function startGrading(Request $request)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $tid = substr(str_shuffle($permitted_chars), 0, 16);

        if (auth('sanctum')->check()) {
            /* Generate unique transaction ID for each cash request record */
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
                $year = $request->school_year;
                $term = $request->school_term;
                $class = $request->class;
                //get th transaction ID
                // $grade_code = ResultTable::where('academic_year', $request->school_year)
                //     ->where('academy_term', $request->school_term)
                //     ->where('class', $request->class)
                //     ->where('result_status', 'Active')
                //     ->get();
                $grade = DB::table('result_tables')
                    ->selectRaw('sum(total_scores) as user_total, sum(tca_score) as user_tca, sum(exam_scores) as user_exam_total, tca_score, total_scores, exam_scores,
                   admin_number,student_name, academic_year, academy_term, class,
                    school_category, username, result_status')
                    ->where('result_status', '=', 'Active')
                    ->where('academic_year', '=', $year)
                    ->where('academy_term', '=', $term)
                    ->where('class', '=', $class)
                    ->groupBy('admin_number')
                    ->orderBy('user_total', 'desc')
                    ->get();

                $grade_start = ResultTable::where('academic_year', $request->school_year)
                    ->where('academy_term', $request->school_term)
                    ->where('class', $request->class)
                    ->where('result_status', 'Active')->first();

                if (!empty($grade_start)) {

                    // select and insert into database table here...
                    $inserts = [];
                    foreach ($grade as $bid) {

                        $inserts[] =
                            [
                                'stu_admin_no' => $bid->admin_number,
                                'stu_name' => $bid->student_name,
                                'g_class' => $bid->class,
                                'g_term' => $bid->academy_term,
                                'g_year' => $bid->academic_year,
                                'g_category' => $bid->school_category,
                                'total_ca' => $bid->user_tca,
                                'g_exam' => $bid->user_exam_total,
                                'total_score' => $bid->user_total,
                                'g_code' => $tid,
                                'g_addby' => $bid->username,
                                'g_date' => date('d/m/Y H:i:s'),
                                'g_status' => "Active",
                            ];
                    }
                    // save all the operation here
                    DB::table('process_gradings')->insert($inserts);

                    return response()->json([
                        'status' => 200,
                        'resultDetails' => [
                            // 'fetchResult' => $checkResults,
                            'tID' => $tid,
                        ]
                    ]);
                } else if (empty($grade_start)) {
                    return response()->json([
                        'status' => 404,
                        'message' => "No record found! Try again",
                    ]);
                }
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    // fetch all student score base on class selected for grading
    public function getAllGrading($id)
    {
        // details from result table
        $checkGrades = ProcessGrading::where('g_code', $id)->where('g_status', 'Active')
            ->orderBy('total_score', 'DESC')
            ->get();
        $rank = 0;
        $same = 1;
        $previous = null;
        foreach ($checkGrades as $score) {
            $rank++;
            if ($previous && $previous->total_score != $score->total_score) {
                $same = $rank;
            }
            if ($previous && $previous->total_score == $score->total_score) {
                $score->rank = $same;
            } else {
                $score->rank = $rank;
            }
            $previous = $score;
        }
        //dd($grouped);
        $get_g = ProcessGrading::where('g_code', $id)->where('g_status', 'Active')->first();
        // get class name here
        $className = ClassModel::where('id', $get_g->g_class)->first();
        if (!empty($checkGrades)) {
            return response()->json([
                'status' => 200,
                'resultAll' => [
                    'fetchResult' => $checkGrades,
                    'tID' => $get_g,
                    'className' => $className,
                ]
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No result found at the moment",
            ]);
        }
    }
    // save the grade result position here...
    public function saveGradePosition(Request $request)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            // Check if this result exist before saving it...
            $check_positionDetail = StudentPosition::where('sch_year', $request->g_year)
                ->where('sch_term', $request->g_term)
                ->where('sch_class', $request->g_class)->first();
            if (!empty($check_positionDetail)) {
                return response()->json([
                    'status' => 403,
                    'message' => 'Sorry, position details already exist',
                ]);
            } else if (empty($check_positionDetail)) {
                /* Generate unique transaction ID for each cash request record */
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $tid = substr(str_shuffle($permitted_chars), 0, 16);
                //dd($request['t_code']);
                foreach ($request->data as $data) {
                    StudentPosition::create([
                        'sch_year' => $data['year'],
                        'sch_term' => $data['term'],
                        'sch_class' => $data['class'],
                        'sch_category' => $data['sch_category'],
                        'stu_admin_number' => $data['st_admin_number'],
                        'tca_score' => $data['tca'],
                        'exam_score' => $data['exam_total'],
                        'total_score' => $data['total_score'],
                        'user_code' => $data['tid_code'],
                        'position' => $data['position'],
                        'add_by' => $userDetails->username,
                        'student_name' => $data['stud_name'],
                        'p_status' => 'Active',
                        'p_date' => date('d/m/Y H:i:s'),
                    ]);
                }
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Grading result added";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Student grading result details added";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();
                // delete the details after saving from process grade table

                $user_delete = ProcessGrading::where('g_code', $request->tid);
                $user_delete->delete();
                return response()->json([
                    'status' => 200,
                    'message' => "Grading result added successfully",
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    // fetch subject grade position here....

    public function fetchSubjectPosition($id)
    {
        // details from result table
        $checkGrades = ResultTable::where('tid_code', $id)
            ->orderBy('total_scores', 'DESC')
            ->get();
        $rank = 0;
        $same = 1;
        $previous = null;
        foreach ($checkGrades as $score) {
            $rank++;
            if ($previous && $previous->total_scores != $score->total_scores) {
                $same = $rank;
            }
            if ($previous && $previous->total_scores == $score->total_scores) {
                $score->rank = $same;
            } else {
                $score->rank = $rank;
            }
            $previous = $score;
        }
        //dd($grouped);
        $get_g = ResultTable::where('tid_code', $id)->first();
        // get class name here
        $className = ClassModel::where('id', $get_g->class)->first();
        $subjectName = Subject::where('id', $get_g->subject)->first();
        if (!empty($checkGrades)) {
            return response()->json([
                'status' => 200,
                'resultAll' => [
                    'fetchResult' => $checkGrades,
                    'tID' => $get_g,
                    'className' => $className,
                    'subject' => $subjectName,
                ]
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No result found at the moment",
            ]);
        }
    }

    // fetch subject result edit here...
    // fetch single subject details for edit here...
    public function fetchSubjectResult($id)
    {
        if (auth('sanctum')->check()) {
            $fetch_subjectResult = ResultTable::where('id', $id)->first();
            if (!empty($fetch_subjectResult)) {
                return response()->json([
                    'status' => 200,
                    'fetch_info' => $fetch_subjectResult,
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

    // update edit result subject details here...
    public function updateResultSubject(Request $request)
    {
        if (auth('sanctum')->check()) {
            //dd($request->all());
            //validate input details
            $validator = Validator::make($request->all(), [
                'first_ca' => 'required',
                'second_ca' => 'required',
                'exam_scores' => 'required',
                'tca_score' => 'required',
                'total_scores' => 'required',
                'grade' => 'required',
                'remark' => 'required',
                'position' => 'required',
                'average_scores' => 'required',
                'result_highest' => 'required',
                'result_lowest' => 'required',
            ], [
                'first_ca.required' => 'CA 1 Required',
                'second_ca.required' => 'CA 2 Required',
                'exam_scores.required' => 'Exam Required',
                'tca_score.required' => 'Total CA Required',
                'total_scores.required' => 'Total Score Required',
                'grade.required' => 'Grade Required',
                'remark.required' => 'Remark Required',
                'position.required' => 'Position Required',
                'average_scores.required' => 'Average Score Required',
                'result_highest.required' => 'Highest Score Required',
                'result_lowest.required' => 'Lowest Score Required',
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
            $check_updateID = ResultTable::where('id', $recordID)->first();

            if (!empty($check_updateID)) {
                // rund the update query here
                $check_updateID->update([
                    'first_ca' => $request->first_ca,
                    'second_ca' => $request->second_ca,
                    'tca_score' => $request->tca_score,
                    'exam_scores' => $request->exam_scores,
                    'total_scores' => $request->total_scores,
                    'grade' => $request->grade,
                    'remark' => $request->remark,
                    'position' => $request->position,
                    'average_scores' => $request->average_scores,
                    'result_highest' => $request->result_highest,
                    'result_lowest' => $request->result_lowest,
                ]);
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Update subject request";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Update subject details";
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

    // delete_single single subject result details here
    public function deleteSubjectResult($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $find_deleteSubjectID = ResultTable::where('id', $id)->first();
            if (!empty($find_deleteSubjectID)) {
                // rund the delete query here
                $find_deleteSubjectID->update([
                    'result_status' => "Deleted",
                ]);

                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Result Deleted";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, subject result was deleted";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Result Deleted Successfully',
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
    // delete all subject result details from preview page here

    public function deleteAllSubjectResult($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $check_deleteID = ResultTable::where('tid_code', $id)->first();
            if (!empty($check_deleteID)) {
                // run multiple_delete with query here
                ResultTable::query()
                    ->where('tid_code', $id)
                    ->update([
                        'result_status' => "Deleted",
                    ]);
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Deleted subject result ";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, All subject result was deleted";
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
    // save subject position here...
    public function saveSubjectPosition(Request $request)
    {
        //dd($request->all());

        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            // Check if this result exist before saving it...
            $check_subjectP = ResultTable::where('academic_year', $request->g_year)
                // ->where('academy_term', $request->g_term)
                // ->where('class', $request->g_class)
                // ->where('subject', $request->g_subject)
                ->where('tid_code', $request->tid)
                ->first();
            if (empty($check_subjectP)) {
                return response()->json([
                    'status' => 403,
                    'message' => 'Sorry, operation failed',
                ]);
            } else if (!empty($check_subjectP)) {
                // delete_all record where ID is same
                ResultTable::query()
                    ->where('tid_code', $request->tid)
                    ->delete();

                // delete all end here...

                /* Generate unique transaction ID for each cash request record */
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $tid = substr(str_shuffle($permitted_chars), 0, 16);
                //dd($request['t_code']);
                foreach ($request->data as $data) {
                    ResultTable::create([
                        'academic_year' => $data['year'],
                        'academy_term' => $data['term'],
                        'class' => $data['class'],
                        'school_category' => $data['sch_category'],
                        'admin_number' => $data['st_admin_number'],
                        'tca_score' => $data['tca'],
                        'exam_scores' => $data['exam_total'],
                        'total_scores' => $data['total_score'],
                        'subject' => $data['subject'],
                        'first_ca' => $data['first_ca'],
                        'second_ca' => $data['second_ca'],
                        'grade' => $data['grade'],
                        'remark' => $data['remark'],
                        'average_scores' => $data['average_scores'],
                        'result_highest' => $data['result_highest'],
                        'result_lowest' => $data['result_lowest'],
                        'tid_code' => $request->tid,
                        'position' => $data['position'],
                        'username' => $userDetails->username,
                        'student_name' => $data['stud_name'],
                        'result_status' => 'Active',
                        'result_date' => date('d/m/Y H:i:s'),
                    ]);
                }
                $recordID = $request->tid;
                $subjectID = $request->g_subject;
                $classID = $request->g_class;
                $categoryID = $request->g_category;
                $termID = $request->g_term;
                $yearID = $request->g_year;
                // get these ID name stand in respective name.
                $sbujectName = Subject::where('id', $subjectID)->first();
                $className = ClassModel::where('id', $classID)->first();
                $yearName = AcademicSession::where('id', $yearID)->first();
                $termName = TermModel::where('id', $termID)->first();
                $categoryName = SchoolCategory::where('id', $categoryID)->first();

                // check if TID code record exist then delete before adding new one
                $check_process = ResultProcessStart::where('r_tid', $recordID)
                    ->first();
                if (!empty($check_process))
                    $check_process->delete();

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
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Result added";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Student result details added";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();
                // delete the details after saving from process grade table

                // $user_delete = ProcessGrading::where('g_code', $request->tid);
                // $user_delete->delete();
                return response()->json([
                    'status' => 200,
                    'message' => "Result added successfully",
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }
    public function startPromotion(Request $request)
    {
        //dd($request->all());
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $tid = substr(str_shuffle($permitted_chars), 0, 16);

        $validator = Validator::make($request->all(), [
            'from_class' => 'required',
            'to_class' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                $errors = $validator->errors(),
                'status' => 422,
                'errors' => $errors,
            ]);
        } else {
            if (auth('sanctum')->check()) {
                $promo_check = Student::where('class_apply', $request->from_class)
                    ->where('acct_status', 'Active')
                    ->first();
                if (empty($promo_check)) {
                    return response()->json([
                        'status' => 404,
                        'message' => "No record found! Try again",
                    ]);
                }

                $userDetails = auth('sanctum')->user();
                $promotion = DB::table('students')
                    ->selectRaw('surname, other_name, class_apply, st_admin_number, acct_status')
                    ->where('acct_status', 'Active')
                    ->where('class_apply', $request->from_class)
                    ->orderBy('st_admin_number', 'desc')
                    ->get();
                // next class name here...
                $grade_start = ClassModel::where('id', $request->from_class)
                    ->where('status', 'Active')
                    ->first();
                // current class here....
                $grade_nextClass = ClassModel::where('id', $request->to_class)
                    ->where('status', 'Active')
                    ->first();

                if (!empty($grade_start)) {
                    // select and insert into database table here...
                    $inserts = [];
                    foreach ($promotion as $bid) {
                        $inserts[] =
                            [
                                'stu_adm_number' => $bid->st_admin_number,
                                'stu_name' => $bid->other_name,
                                'stu_class' => $bid->class_apply,
                                'stu_next_class' => $request->to_class,
                                'stu_status' => 'Active',
                                'stu_tid' => $tid,
                                'stu_date' => date('d/m/Y H:i:s'),
                                'stu_addby' => $userDetails->username,
                                'stu_now_classname' => $grade_start->class_name,
                                'stu_next_classname' => $grade_nextClass->class_name,
                            ];
                    }
                    // save all the operation here
                    DB::table('start_promotions')->insert($inserts);
                    return response()->json([
                        'status' => 200,
                        'promoDetails' => [
                            'next_class' => $grade_start->class_name,
                            'tID' => $tid,
                        ]
                    ]);
                } else if (empty($grade_start)) {
                    return response()->json([
                        'status' => 404,
                        'message' => "No record found! Try again",
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

    // get start promotion details here...
    public function fetchPromotion($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $promoDetails = StartPromotion::where('stu_tid', $id)->where('stu_addby', $userDetails->username)->get();
            $proDetails = StartPromotion::where('stu_tid', $id)->where('stu_addby', $userDetails->username)->first();
            if (!empty($promoDetails)) {
                return response()->json([
                    'status' => 200,
                    'promotionDetails' => [
                        'proDetails' => $promoDetails,
                        'pDetails' => $proDetails,
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

    // mark promotion of student here...
    public function markPromotion($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $promoMark = StartPromotion::where('id', $id)->first();
            $proMark = StartPromotion::where('id', $id)->first();
            // get student details from here...
            $proStudent = Student::where('st_admin_number', $proMark->stu_adm_number)->first();
            if (!empty($proMark)) {
                $proStudent->update([
                    'class_apply' => $proMark->stu_next_class,
                ]);
                $promoMark->update([
                    'stu_status' => "Marked",
                ]);
                return response()->json([
                    'status' => 200,
                    'message' => 'Marked Successful',
                ]);
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Promoted student";
                $logs->m_status = "Successful";
                $logs->m_details = "$proStudent->other_name, student promotion details added";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();
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

    // returned promotion operation here...
    public function returnPromotion($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $promoReturned = StartPromotion::where('id', $id)->first();
            $proReturned = StartPromotion::where('id', $id)->first();
            // get student details from here...
            $proStudent = Student::where('st_admin_number', $proReturned->stu_adm_number)->first();
            if (!empty($proReturned)) {
                $proStudent->update([
                    'class_apply' => $proReturned->stu_class,
                ]);
                $promoReturned->update([
                    'stu_status' => "Active",
                ]);
                return response()->json([
                    'status' => 200,
                    'message' => 'Returned Successful',
                ]);
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Returned Promoted Student";
                $logs->m_status = "Successful";
                $logs->m_details = "$proStudent->other_name, student promotion details returned";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();
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

    // promote all student at once operation goes here...
    public function promotionAll($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $promoAll = StartPromotion::where('stu_tid', $id)->first();
            //dd($promoAll);
            $pro = StartPromotion::where('stu_tid', $id)->first();
            // get student details from here...
            $proStudent = Student::where('st_admin_number', $pro->stu_adm_number)->first();

            if (!empty($promoAll)) {
                Student::query()
                    ->where('class_apply', $pro->stu_class)
                    ->where('acct_status', 'Active')
                    ->update([
                        'class_apply' => $pro->stu_next_class,
                    ]);
                StartPromotion::query()
                    ->where('stu_tid', $id)
                    ->update([
                        'stu_status' => 'Marked',
                    ]);
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Run Promoted Student";
                $logs->m_status = "Successful";
                $logs->m_details = "Run student promotion details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'All Promoted Successful',
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

    //start promotion returned here...
    public function startPromotionReturn(Request $request)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $tid = substr(str_shuffle($permitted_chars), 0, 16);

        $validator = Validator::make($request->all(), [
            'from_class' => 'required',
            'to_class' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                $errors = $validator->errors(),
                'status' => 422,
                'errors' => $errors,
            ]);
        } else {
            if (auth('sanctum')->check()) {
                $promo_check = Student::where('class_apply', $request->from_class)
                    ->where('acct_status', 'Active')
                    ->first();
                if (empty($promo_check)) {
                    return response()->json([
                        'status' => 404,
                        'message' => "No record found! Try again",
                    ]);
                }

                $userDetails = auth('sanctum')->user();
                $promotion = DB::table('students')
                    ->selectRaw('surname, other_name, class_apply, st_admin_number, acct_status')
                    ->where('acct_status', 'Active')
                    ->where('class_apply', $request->from_class)
                    ->orderBy('st_admin_number', 'desc')
                    ->get();
                // next class name here...
                $grade_start = ClassModel::where('id', $request->from_class)
                    ->where('status', 'Active')
                    ->first();
                // current class here....
                $grade_nextClass = ClassModel::where('id', $request->to_class)
                    ->where('status', 'Active')
                    ->first();

                if (!empty($grade_start)) {
                    // select and insert into database table here...
                    $inserts = [];
                    foreach ($promotion as $bid) {
                        $inserts[] =
                            [
                                'stu_adm_number' => $bid->st_admin_number,
                                'stu_name' => $bid->other_name,
                                'stu_class' => $bid->class_apply,
                                'stu_next_class' => $request->to_class,
                                'stu_status' => 'Marked',
                                'stu_tid' => $tid,
                                'stu_date' => date('d/m/Y H:i:s'),
                                'stu_addby' => $userDetails->username,
                                'stu_now_classname' => $grade_start->class_name,
                                'stu_next_classname' => $grade_nextClass->class_name,
                            ];
                    }
                    // save all the operation here
                    DB::table('start_promotions')->insert($inserts);
                    return response()->json([
                        'status' => 200,
                        'promoDetails' => [
                            'next_class' => $grade_start->class_name,
                            'tID' => $tid,
                        ]
                    ]);
                } else if (empty($grade_start)) {
                    return response()->json([
                        'status' => 404,
                        'message' => "No record found! Try again",
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
    // return all student promotion here..
    public function returnAll($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $promoAll = StartPromotion::where('stu_tid', $id)->first();
            //dd($promoAll);
            $pro = StartPromotion::where('stu_tid', $id)->first();
            // get student details from here...
            $proStudent = Student::where('st_admin_number', $pro->stu_adm_number)->first();

            if (!empty($promoAll)) {
                // update multiple row here...
                Student::query()
                    ->where('class_apply', $pro->stu_class)
                    ->update([
                        'class_apply' => $pro->stu_next_class,
                    ]);
                StartPromotion::query()
                    ->where('stu_tid', $id)
                    ->update([
                        'stu_status' => 'Returned',
                    ]);
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Returned Promoted Student";
                $logs->m_status = "Successful";
                $logs->m_details = "Returned all student promoted details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Successfully Returned',
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

    // returned promotion operation here...
    public function reversedPromotion($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $promoReturned = StartPromotion::where('id', $id)->first();
            $proReturned = StartPromotion::where('id', $id)->first();
            // get student details from here...
            $proStudent = Student::where('st_admin_number', $proReturned->stu_adm_number)->first();
            if (!empty($proReturned)) {
                $proStudent->update([
                    'class_apply' => $proReturned->stu_next_class,
                ]);
                //single update
                $promoReturned->update([
                    'stu_status' => "Returned",
                    'stu_class' => $proReturned->stu_next_class,
                ]);
                return response()->json([
                    'status' => 200,
                    'message' => 'Returned Successful',
                ]);
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Returned Promoted Student";
                $logs->m_status = "Successful";
                $logs->m_details = "$proStudent->other_name, student promotion details returned";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();
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

    // start graduation here....
    public function startGraduation(Request $request)
    {
        //dd($request->all());
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $tid = substr(str_shuffle($permitted_chars), 0, 16);

        $validator = Validator::make($request->all(), [
            'from_class' => 'required',
            'school_year' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                $errors = $validator->errors(),
                'status' => 422,
                'errors' => $errors,
            ]);
        } else {
            if (auth('sanctum')->check()) {
                $grad_check = Student::where('class_apply', $request->from_class)
                    ->where('acct_status', 'Active')
                    ->first();
                if (empty($grad_check)) {
                    return response()->json([
                        'status' => 404,
                        'message' => "No student record found! Try again",
                    ]);
                }

                $userDetails = auth('sanctum')->user();
                $graduate = DB::table('students')
                    ->selectRaw('surname, other_name, class_apply, st_admin_number, acct_status')
                    ->where('acct_status', 'Active')
                    ->where('class_apply', $request->from_class)
                    ->orderBy('st_admin_number', 'desc')
                    ->get();
                // next class name here...
                $grad_start = ClassModel::where('id', $request->from_class)
                    ->where('status', 'Active')
                    ->first();

                if (!empty($grad_start)) {
                    // select and insert into database table here...
                    $inserts = [];
                    foreach ($graduate as $bid) {
                        $inserts[] =
                            [
                                'gs_st_admin' => $bid->st_admin_number,
                                'gs_st_name' => $bid->other_name,
                                'gs_class' => $bid->class_apply,
                                'gs_year' => $request->school_year,
                                'gs_status' => 'Initiated',
                                'gs_tid' => $tid,
                                'gs_class_name' => $grad_start->class_name,
                                'gs_date' => date('d/m/Y H:i:s'),
                                'gs_added' => $userDetails->username,
                            ];
                    }
                    // save all the operation here
                    DB::table('start_graduations')->insert($inserts);
                    return response()->json([
                        'status' => 200,
                        'gDetails' => [
                            'next_class' => $grad_start->class_name,
                            'tID' => $tid,
                        ]
                    ]);
                } else if (empty($grade_start)) {
                    return response()->json([
                        'status' => 404,
                        'message' => "No record found! Try again",
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
    // fetch start graduation here....
    public function fetchStartGraduation($id)
    {

        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $gradDetails = StartGraduation::where('gs_tid', $id)->where('gs_added', $userDetails->username)->get();
            $grDetails = StartGraduation::where('gs_tid', $id)->where('gs_added', $userDetails->username)->first();
            if (!empty($gradDetails)) {
                return response()->json([
                    'status' => 200,
                    'graduate_Details' => [
                        'proDetails' => $gradDetails,
                        'pDetails' => $grDetails,
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

    // mark graduation here...
    public function graduateStudent($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $MarkGrad = StartGraduation::where('id', $id)->first();
            $graduateMark = StartGraduation::where('id', $id)->first();
            // get student details from here...
            $proStudent = Student::where('st_admin_number', $graduateMark->gs_st_admin)->first();
            if (!empty($proMark)) {
                $proStudent->update([
                    'acct_status' => "Graduated",
                ]);
                $MarkGrad->update([
                    'gs_status' => "Graduated",
                ]);

                //multiple insert into graduated table here...
                $graduated_now = DB::table('students')
                    ->selectRaw('surname, other_name, class_apply, st_admin_number, acct_status')
                    ->where('acct_status', 'Active')
                    ->where('class_apply', $MarkGrad->gs_class)
                    ->orderBy('st_admin_number', 'desc')
                    ->get();
                // next class name here...
                $grad_now = ClassModel::where('id', $MarkGrad->gs_class)
                    ->where('status', 'Active')
                    ->first();

                if (!empty($grad_now)) {
                    // select and insert into database table here...
                    $inserts = [];
                    foreach ($graduated_now as $bid) {
                        $inserts[] =
                            [
                                'g_st_admin' => $bid->st_admin_number,
                                'g_st_name' => $bid->other_name,
                                'g_class' => $grad_now->class_name,
                                'g_year' => $MarkGrad->gs_year,
                                'g_status' => 'Graduated',
                                'g_tid' => $MarkGrad->gs_tid,
                                'g_date' => date('d/m/Y H:i:s'),
                                'g_added' => $userDetails->username,
                            ];
                    }
                    // save all the operation here
                    DB::table('graduations')->insert($inserts);

                    return response()->json([
                        'status' => 200,
                        'message' => 'Graduated Successfully',
                    ]);
                    // history record here...
                    $logs = new Activitity_log();
                    $logs->m_username = $userDetails->username;
                    $logs->m_action = "Graduate student";
                    $logs->m_status = "Successful";
                    $logs->m_details = "$proStudent->other_name, student graduation details added";
                    $logs->m_date = date('d/m/Y H:i:s');
                    $logs->m_uid = $userDetails->id;
                    $logs->m_ip = request()->ip;
                    $logs->save();
                } else {
                    return response()->json([
                        'status' => 404,
                        'message' => "No record found at the moment",
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

    // return graduated student here..
    public function returnedGraduation($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $promoReturned = StartGraduation::where('id', $id)->first();
            $proReturned = StartGraduation::where('id', $id)->first();
            // get student details from here...
            $proStudent = Student::where('st_admin_number', $proReturned->gs_st_admin)->first();
            if (!empty($proReturned)) {
                $proStudent->update([
                    'acct_status' => 'Graduated',
                ]);
                $promoReturned->update([
                    'gs_status' => "Marked",
                ]);
                $userDetails = auth('sanctum')->user();
                $graduate = DB::table('students')
                    ->selectRaw('surname, other_name, class_apply, st_admin_number, acct_status')
                    ->where('st_admin_number', $promoReturned->gs_st_admin)
                    ->get();
                // next class name here...
                $grad_start = ClassModel::where('id', $promoReturned->gs_class)
                    ->where('status', 'Active')
                    ->first();
                // next year name here...
                $grad_year = AcademicSession::where('id', $promoReturned->gs_year)
                    ->where('a_status', 'Active')
                    ->first();
                if (!empty($grad_start)) {
                    // select and insert into database table here...
                    $inserts = [];
                    foreach ($graduate as $bid) {
                        $inserts[] =
                            [
                                'g_st_admin' => $bid->st_admin_number,
                                'g_st_name' => $bid->other_name . ' ' . $bid->surname,
                                'g_class' => $grad_start->class_name,
                                'g_year' => $grad_year->academic_name,
                                'g_status' => 'Graduated',
                                'g_added' => $userDetails->username,
                                'g_tid' => $promoReturned->gs_tid,
                                'g_date' => date('d/m/Y H:i:s'),
                            ];
                    }
                    // history record here...
                    $logs = new Activitity_log();
                    $logs->m_username = $userDetails->username;
                    $logs->m_action = "Graduated Student";
                    $logs->m_status = "Successful";
                    $logs->m_details = "$proStudent->other_name, student graduation details added";
                    $logs->m_date = date('d/m/Y H:i:s');
                    $logs->m_uid = $userDetails->id;
                    $logs->m_ip = request()->ip;
                    $logs->save();
                    // save all the operation here
                    DB::table('graduations')->insert($inserts);
                    return response()->json([
                        'status' => 200,
                        'message' => 'Graduated Successful',
                    ]);
                }
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
    // get graduated details here...
    public function fetchAllGraduated()
    {
        if (auth('sanctum')->check()) {
            $fetch_grdaDetails = Graduation::where('g_status', 'Graduated')->get();
            if (!empty($fetch_grdaDetails)) {
                return response()->json([
                    'status' => 200,
                    'graduate_Details' => [
                        'proDetails' => $fetch_grdaDetails,
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

    // graduate all student at once here...

    public function graduateAll($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $graduate_all = StartGraduation::where('gs_tid', $id)->first();
            $proeturned = StartGraduation::where('gs_tid', $id)->get();
            // update student status from here...
            $allStudent = Student::where('class_apply', $graduate_all->gs_class)->first();
            if (!empty($allStudent)) {

                // update start graduate process here....
                StartGraduation::query()->where('gs_tid', $id)
                    ->update([
                        'gs_status' => 'Marked',
                        //'result_highest' => $highest_score
                    ]);
                $userDetails = auth('sanctum')->user();
                $graduate_allNow = DB::table('students')
                    ->selectRaw('surname, other_name, class_apply, st_admin_number, acct_status')
                    ->where('class_apply', $graduate_all->gs_class)
                    ->where('acct_status', 'Active')
                    ->get();
                // get class name here...
                $grad_start = ClassModel::where('id', $graduate_all->gs_class)
                    ->where('status', 'Active')
                    ->first();
                // get year name here...
                $grad_year = AcademicSession::where('id', $graduate_all->gs_year)
                    ->where('a_status', 'Active')
                    ->first();
                if (!empty($grad_start)) {
                    // select and insert into database table here...
                    $inserts = [];
                    foreach ($graduate_allNow as $bid) {
                        $inserts[] =
                            [
                                'g_st_admin' => $bid->st_admin_number,
                                'g_st_name' => $bid->other_name . ' ' . $bid->surname,
                                'g_class' => $grad_start->class_name,
                                'g_year' => $grad_year->academic_name,
                                'g_status' => 'Graduated',
                                'g_added' => $userDetails->username,
                                'g_tid' => $graduate_all->gs_tid,
                                'g_date' => date('d/m/Y H:i:s'),
                            ];
                    }
                    Student::query()->where('class_apply', $graduate_all->gs_class)
                        ->update([
                            'acct_status' => 'Graduated',
                            //'result_highest' => $highest_score
                        ]);

                    // $graduate_allNow->update([
                    //     'acct_status' => "Graduated",
                    // ]);
                    // history record here...
                    $logs = new Activitity_log();
                    $logs->m_username = $userDetails->username;
                    $logs->m_action = "Graduated Student";
                    $logs->m_status = "Successful";
                    $logs->m_details = "$allStudent->other_name, student graduation details added";
                    $logs->m_date = date('d/m/Y H:i:s');
                    $logs->m_uid = $userDetails->id;
                    $logs->m_ip = request()->ip;
                    $logs->save();
                    // save all the operation here
                    DB::table('graduations')->insert($inserts);
                    return response()->json([
                        'status' => 200,
                        'message' => 'All Graduated Successfully',
                    ]);
                }
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

    // get student template in a class for result recording....
    public function fetchTemplate(Request $request)
    {
        if (auth('sanctum')->check()) {
            $validator = Validator::make($request->all(), [
                'class_name' => 'required',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            }
            // $get_allStudent = DB::table('students')
            //     ->selectRaw('*')
            //     ->where('acct_status', 'Active')
            //     ->where('class_apply', $request->class_name)
            //     ->orderBy('st_admin_number', 'desc')
            //     ->get();
            $get_allStudent = Student::where('class_apply', $request->class_name)
                ->where('acct_status', 'Active')
                ->orderBy('st_admin_number', 'desc')
                ->get();

            if ($get_allStudent == "") {
                dd($get_allStudent);
                return response()->json([
                    'status' => 404,
                    'message' => "No record found at the moment",
                ]);
                dd($get_allStudent);
            } else if (!empty($get_allStudent)) {
                $class = ClassModel::where('id', $request->class_name)->first();
                return response()->json([
                    'status' => 200,
                    'allDetails' => [
                        'studentDetails' => $get_allStudent,
                        'classDetails' => $class,
                    ]
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
        //dd($request->all());
    }

    // get student grade details for viewing here...
    public function getGradeDetails($id)
    {
        if (auth('sanctum')->check()) {
            $fetch_gradeResult = StudentPosition::where('id', $id)->first();
            $fetch_gradeAll = StudentPosition::where('user_code', $fetch_gradeResult->user_code)->get();

            $fetch_classAll = ClassModel::where('id', $fetch_gradeResult->sch_class)->first();
            $fetch_termAll = TermModel::where('id', $fetch_gradeResult->sch_term)->first();
            $fetch_yearAll = AcademicSession::where('id', $fetch_gradeResult->sch_year)->first();
            if (!empty($fetch_gradeResult)) {
                return response()->json([
                    'status' => 200,
                    'all_details' => [
                        'fetch_info' => $fetch_gradeAll,
                        'class_info' => $fetch_classAll,
                        'term_info' => $fetch_termAll,
                        'grade_id' => $fetch_gradeResult,
                    ]
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

    // delete all grade from grade view....
    public function deleteAllGrade($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $check_deleteID = StudentPosition::where('user_code', $id)->first();
            if (!empty($check_deleteID)) {
                // run multiple_delete with query here
                StudentPosition::query()
                    ->where('user_code', $id)
                    ->update([
                        'p_status' => "Deleted",
                    ]);
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Position result deleted ";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, All result position was deleted";
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

    // delete single position result here from viewing position page...
    public function deletePositionID($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $find_deleteSubjectID = StudentPosition::where('id', $id)->first();
            if (!empty($find_deleteSubjectID)) {
                // run single_delete query here
                $find_deleteSubjectID->update([
                    'p_status' => "Deleted",
                ]);

                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Position result deleted";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Position result was deleted";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Position Deleted Successfully',
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
    //text route ...
    // public function textSum()
    // {
    //     $users = DB::table('result_tables')
    //         ->selectRaw('sum(total_scores) as user_count, total_scores, exam_scores,
    //                 tca_score, tid_code, admin_number, result_status')
    //         ->where('result_status', '=', 'Active')
    //         ->groupBy('admin_number')
    //         ->get();

    //     return response()->json([
    //         'status' => 200,
    //         'result' => $users,
    //     ]);
    // }

    public function textSum()
    {
        $users = DB::table('process_gradings')
            ->selectRaw('*')
            ->where('g_status', '=', 'Active')
            ->orderBy('total_score', 'desc')
            ->get();

        $rank = 0;
        $same = 0;
        $previous = null;
        foreach ($users as $score) {
            $rank++;
            if ($previous && $previous->total_score != $score->total_score) {
                $same = $rank;
            }
            if ($previous && $previous->total_score == $score->total_score) {
                $score->rank = $same;
            } else {
                $score->rank = $rank;
            }

            $previous = $score;
        }
        return response()->json([
            'status' => 200,
            'result' => $users,

        ]);
    }
}