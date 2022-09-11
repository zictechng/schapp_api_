<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Activitity_log;
use App\Models\CAResultProcessStart;
use App\Models\ClassModel;
use App\Models\ResultCA;
use App\Models\ResultProcessStart;
use App\Models\SchoolCategory;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TermModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CAResultController extends Controller
{
    // fetch all CA here...

    public function getAllCA()
    {
        if (auth('sanctum')->check()) {
            $ca_details = CAResultProcessStart::where('status', '!=', 'Deleted')->orderByDesc('id')->get();
            return response()->json([
                'status' => 200,
                'ca_record' => $ca_details,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    //process CA result here...
    public function processAllCA(Request $request)
    {
        if (auth('sanctum')->check()) {
            /* Generate unique transaction ID for each cash request record */
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

            $tid = substr(str_shuffle($permitted_chars), 0, 16);

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
                //check if this result exist in the result table before proceeding..
                $check_ca = ResultCA::where('rst_year', $request->school_year)
                    ->where('rst_term', $request->school_term)
                    ->where('rst_class', $request->class)
                    ->where('rst_subject', $request->subject)
                    ->where('rst_status', 'Active')->first();

                if (!empty($check_ca)) {
                    return response()->json([
                        'status' => 403,
                        'message' => 'Sorry, CA Result already exist',
                    ]);
                } else if (empty($check_ca)) {
                    $save_new = new CAResultProcessStart();
                    $save_new->year = $request['school_year'];
                    $save_new->term = $request['school_term'];
                    $save_new->class = $request['class'];
                    $save_new->sch_category = $request['school_type'];
                    $save_new->subject = $request['subject'];
                    $save_new->tid_code = $tid;
                    $save_new->add_by = $userDetails->username;
                    $save_new->addby_user_id = $userDetails->id;
                    $save_new->record_date = date('d/m/Y H:i:s');
                    $save_new->status = 'Active';

                    $save_new->save();

                    if ($save_new->save()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Initiated CA result processing";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, Initiated new result processing details";
                        $logs->m_date = date('d/m/Y H:i:s');
                        $logs->m_uid = $userDetails->id;
                        $logs->m_ip = request()->ip;

                        $logs->save();

                        // get the result detail to pass to front end
                        $fetch_resultdetails = CAResultProcessStart::where('tid_code', $tid)->first();
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
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }
    //save CA result here...
    public function resultCASave(Request $request)
    {
        if (auth('sanctum')->check()) {
            /* Generate unique transaction ID for each cash request record */
            // $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            // $tid = substr(str_shuffle($permitted_chars), 0, 16);
            $userDetails = auth('sanctum')->user();

            //check if the CA have been entered already
            $check_ac = ResultCA::where('rst_year', $request->year)
                ->where('rst_term', $request->term)
                ->where('rst_subject', $request->subject)
                ->where('rst_class', $request->class)->first();
            if (!empty($check_ac)) {
                return response()->json([
                    'status' => 403,
                    'message' => "Sorry, CA result already exist",
                ]);
            }
            foreach ($request->data as $data) {

                ResultCA::create([
                    'st_admin_id' => $data['admin_number'],
                    'ca1' => $data['ca1_score'],
                    'ca2' => $data['ca2_score'],
                    'ca_total' => $data['ca1_score'] + $data['ca2_score'],
                    'rst_year' => $request->year,
                    'rst_term' => $request->term,
                    'rst_subject' => $request->subject,
                    'rst_class' => $request->class,
                    'rst_tid' => $request->t_code,
                    'rst_addby' => $userDetails->username,
                    'rst_category' => $request->school_category,
                    'rst_status' => 'Active',
                    'rst_date' => date('d/m/Y H:i:s'),
                ]);
                $tid = $request->t_code;
                // get value from the request send from the front end
                $recordID = $tid;
                $subjectID = $request->subject;
                $classID = $request->class;
                $categoryID = $request->school_category;
                $termID = $request->term;
                $yearID = $request->year;

                $get_recordID = ResultCA::where('rst_tid', $recordID)->first();
                // get student name base on the admission number here...
                $get_studentName = Student::where('st_admin_number', $data['admin_number'])->first();
                ResultCA::query()->where('st_admin_id', $get_studentName->st_admin_number)->where('rst_tid', $recordID)
                    ->update([
                        'st_name' => $get_studentName->other_name,
                    ]);
            }


            // delete the record from process start result table
            $r_record = CAResultProcessStart::where('tid_code', $recordID)->first();
            if (!empty($r_record)) {
                $r_record->delete();
            }
            // history record here...
            $logs = new Activitity_log();
            $logs->m_username = $userDetails->username;
            $logs->m_action = "Entered CA result";
            $logs->m_status = "Successful";
            $logs->m_details = "$userDetails->name, Entered new CA result details";
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
            $keep = new CAResultProcessStart();
            $keep->tid_code = $recordID;
            $keep->year = $yearName->academic_name;
            $keep->term = $termName->term_name;
            $keep->class = $className->class_name;
            $keep->sch_category = $categoryName->sc_name;
            $keep->subject = $sbujectName->subject_name;
            $keep->add_by = $userDetails->username;
            $keep->addby_user_id = $userDetails->id;
            $keep->status = "Saved, Successfully";
            $keep->record_date = date('d/m/Y H:i:s');
            $keep->save();
            return response()->json([
                'status' => 200,
                'message' => "CA Result added successfully",
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }
    // single CA save here...
    public function resultSingleCASave(Request $request)
    {
        if (auth('sanctum')->check()) {

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

            /* Generate unique transaction ID for each cash request record */
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $tid = substr(str_shuffle($permitted_chars), 0, 16);
            $userDetails = auth('sanctum')->user();
            //check if the CA have been entered already
            $checkAC = ResultCA::where('rst_year', $request->year)
                ->where('rst_term', $request->term)
                ->where('rst_subject', $request->subject)
                ->where('rst_class', $request->class)->first();
            if (!empty($checkAC)) {
                return response()->json([
                    'status' => 403,
                    'message' => "Sorry, CA result already exist",
                ]);
            }
            foreach ($request->data as $data) {

                ResultCA::create([
                    'st_admin_id' => $data['admin_number'],
                    'ca1' => $data['ca1_score'],
                    'ca2' => $data['ca2_score'],
                    'ca_total' => $data['ca1_score'] + $data['ca2_score'],
                    'rst_year' => $request->year,
                    'rst_term' => $request->term,
                    'rst_subject' => $request->subject,
                    'rst_class' => $request->class,
                    'rst_tid' => $tid,
                    'rst_addby' => $userDetails->username,
                    'rst_category' => $request->school_category,
                    'rst_status' => 'Active',
                    'rst_date' => date('d/m/Y H:i:s'),
                ]);

                // get value from the request send from the front end
                $recordID = $tid;
                $subjectID = $request->subject;
                $classID = $request->class;
                $categoryID = $request->school_category;
                $termID = $request->term;
                $yearID = $request->year;
                // get student name base on the admission number here...
                $get_studentName = Student::where('st_admin_number', $data['admin_number'])->first();
                ResultCA::query()->where('st_admin_id', $get_studentName->st_admin_number)->where('rst_tid', $recordID)
                    ->update([
                        'st_name' => $get_studentName->other_name,
                    ]);
            }

            // delete the record from process start result table
            $r_record = CAResultProcessStart::where('tid_code', $recordID)->first();
            if (!empty($r_record)) {
                $r_record->delete();
            }
            // history record here...
            $logs = new Activitity_log();
            $logs->m_username = $userDetails->username;
            $logs->m_action = "Entered CA result";
            $logs->m_status = "Successful";
            $logs->m_details = "$userDetails->name, Entered new CA result details";
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
            $keep = new CAResultProcessStart();
            $keep->tid_code = $recordID;
            $keep->year = $yearName->academic_name;
            $keep->term = $termName->term_name;
            $keep->class = $className->class_name;
            $keep->sch_category = $categoryName->sc_name;
            $keep->subject = $sbujectName->subject_name;
            $keep->add_by = $userDetails->username;
            $keep->addby_user_id = $userDetails->id;

            $keep->status = "Saved, Successfully";
            $keep->record_date = date('d/m/Y H:i:s');
            $keep->save();
            return response()->json([
                'status' => 200,
                'message' => "CA Result added successfully",
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }
    // fetch process start CA result details here...
    public function getFetchProcessCA($id)
    {
        $get_caDetails = CAResultProcessStart::where('tid_code', $id)->first();
        $get_stDetails = Student::where('class_apply', $get_caDetails->class)->get();
        if ($get_stDetails) {
            return response()->json([
                'status' => 200,
                'all_details' => [
                    'start_item' => $get_caDetails,
                    'student_result' => $get_stDetails,
                ]
            ]);
        } else {
            return response()->json([
                'status' => 402,
                'message' => "something went wrong! Try again",
            ]);
        }
    }

    // delete CA result details here...

    public function deleteCA($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $check_delete_result = CAResultProcessStart::find($id);

            if (empty($check_delete_result)) {
                return response()->json([
                    'status' => 402,
                    'message' => 'Record not exist',
                ]);
            } else if (!empty($check_delete_result)) {
                // details from result table
                $check_ca_result_details = ResultCA::where('rst_tid', $check_delete_result->tid_code);
                if (!empty($check_ca_result_details)) {
                    // delete result details from result table
                    $check_ca_result_details->update([
                        'rst_status' => 'Deleted',

                    ]);
                }
                // delete result details from processing start table
                $check_delete_result->update([
                    'status' => 'Deleted',
                ]);
                // keep history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Deleted CA Result Details";
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
            }
        } else {
            return response()->json([
                'status' => 402,
                'message' => "something went wrong! Try again",
            ]);
        }
    }

    // fetch CA details for viewing details page here...

    public function getCADetails($id)
    {
        if (auth('sanctum')->check()) {
            $fetch_CAResult = ResultCA::where('rst_tid', $id)->get();
            $fetch_CAResults = ResultCA::where('rst_tid', $id)->first();
            if (!empty($fetch_CAResult)) {
                return response()->json([
                    'status' => 200,
                    'resultAll' => [
                        'fetch_info' => $fetch_CAResult,
                        'other' => $fetch_CAResults,
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

    // delete_all CA from viewing detail page here...
    public function deleteAllCA($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $check_deleteID = ResultCA::where('rst_tid', $id)->first();
            if (!empty($check_deleteID)) {
                // run multiple_delete with query here
                ResultCA::query()
                    ->where('rst_tid', $id)
                    ->update([
                        'rst_status' => "Deleted",
                    ]);
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Deleted CA result ";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, All CA result was deleted";
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

    // delete single CA from viewing details page here...
    public function deleteCA_ID($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $check_deleteID = ResultCA::where('id', $id)->first();
            if (!empty($check_deleteID)) {
                // run single delete with query here
                ResultCA::query()
                    ->where('id', $id)
                    ->update([
                        'rst_status' => "Deleted",
                    ]);
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Deleted CA result ";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, CA result was deleted";
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

    // fetch CA result for edit in viewing details page here...
    public function fetchCA_ID($id)
    {
        if (auth('sanctum')->check()) {
            $fetch_caResult = ResultCA::where('id', $id)->first();
            if (!empty($fetch_caResult)) {
                return response()->json([
                    'status' => 200,
                    'fetch_info_CA' => $fetch_caResult,
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

    // update CA result details from viewing details page here...
    public function saveCAUpdate(Request $request)
    {
        if (auth('sanctum')->check()) {
            //dd($request->all());
            //validate input details
            $validator = Validator::make($request->all(), [
                'ca1' => 'required',
                'ca2' => 'required',
                'ca_total' => 'required',
            ], [
                'ca1.required' => 'CA 1 Required',
                'ca2.required' => 'CA 2 Required',
                'ca_total.required' => 'Total CA Required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            }
            //if CA_value is greater than 20
            if ($request->ca1 > "20" || $request->ca2 > "20") {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 423,
                    'errors' => "CA Score exceed 20",
                ]);
            }
            //if CA_total value is greater than 40
            if ($request->ca_total > "40") {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 423,
                    'errors' => "CA Total Score exceed 40",
                ]);
            }
            $recordID = $request->id;
            $userDetails = auth('sanctum')->user();
            $check_updateCA_ID = ResultCA::where('id', $recordID)->first();

            if (!empty($check_updateCA_ID)) {
                // rund the update query here
                $check_updateCA_ID->update([
                    'ca1' => $request->ca1,
                    'ca2' => $request->ca2,
                    'ca_total' => $request->ca_total,
                ]);
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Update CA Result";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Update CA result details";
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