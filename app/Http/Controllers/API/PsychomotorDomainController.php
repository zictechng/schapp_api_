<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Activitity_log;
use App\Models\ClassModel;
use App\Models\PsychomotoDomian;
use App\Models\StartPsychomotoDomain;
use App\Models\Student;
use App\Models\TermModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PsychomotorDomainController extends Controller
{
    // get all the psychomotor details entered already here..
    public function getPsychomotor()
    {
        // always check if user login before requesting this route
        if (auth('sanctum')->check()) {
            //query database to fetch details here
            $get_domainAll = DB::table('start_psychomoto_domains')
                ->selectRaw('*')
                ->where('saff_status', 'Completed')
                ->orderBy('saff_date', 'desc')
                ->groupBy('saff_tid')
                ->get();
            if (!empty($get_domainAll)) {
                return response()->json([
                    'status' => 200,
                    'resultAll' => $get_domainAll,
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
    // start psychomotor domain here...
    public function startPsychomotor(Request $request)
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
                $save_new = new StartPsychomotoDomain();
                $save_new->saff_year = $request['school_year'];
                $save_new->saff_term = $request['school_term'];
                $save_new->saff_class = $request['class'];
                $save_new->saff_tid = $tid;
                $save_new->saff_addby = $userDetails->username;
                $save_new->saff_date = date('d/m/Y H:i:s');
                $save_new->saff_status = 'Active';
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
                    $fetch_resultdetails = StartPsychomotoDomain::where('saff_tid', $tid)->first();
                    if ($fetch_resultdetails) {
                        return response()->json([
                            'status' => 200,
                            'allDetails' => [
                                'message' => 'Process Initiated Successfully',
                                'result_details' => $fetch_resultdetails,
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
    // fetch start psychomotor domain here...
    public function fetchStartPsychomotor($id)
    {
        $get_etetails = StartPsychomotoDomain::where('saff_tid', $id)->first();
        //get CA details here
        $fetch_stu = Student::where('class_apply', $get_etetails->saff_class)
            ->where('acct_status', 'Active')
            ->get();
        if ($fetch_stu) {
            return response()->json([
                'status' => 200,
                'all_details' => [
                    'start_item' => $get_etetails,
                    'student_result' => $fetch_stu,
                ]
            ]);
        } else {
            return response()->json([
                'status' => 402,
                'message' => "something went wrong! Try again",
            ]);
        }
    }

    //save post psychomotor domain here
    public function savePsychomotor(Request $request)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            //dd($request->all());
            // get array value and re-assign it to value here...
            $stu_number = $request->input('data.st_admin_number');

            $aff_year = $request->year;
            $aff_class = $request->class_input;
            $aff_term = $request->term;
            $aff_code = $request->t_code;
            // check if teacher have any record before
            $domain_Check = PsychomotoDomian::where('aff_year', $aff_year)
                ->where('aff_term', $aff_term)
                ->where('aff_class', $aff_class)
                ->where('aff_status', 'Active')
                ->first();
            if (!empty($domain_Check)) {
                return response()->json([
                    'status' => 403,
                    'message' => 'Record exist for this class already',
                ]);
            } else if (empty($domain_Check)) {
                foreach ($request->data as $data) {
                    // get class name here..
                    $sub_name = ClassModel::where('id', $aff_class)
                        ->where('status', 'Active')->first();
                    // get student name here..
                    $tech_name = Student::where('st_admin_number', $data['st_admin_number'])
                        ->where('acct_status', 'Active')->first();
                    // create the new record here...
                    PsychomotoDomian::create([
                        'effectiveness' => $data['effectiveness'],
                        'neatness_score' => $data['neatness'],
                        'craft_score' => $data['craft'],
                        'punctuality_score' => $data['punctuality'],
                        'sport_score' => $data['sport'],
                        'aff_year' => $aff_year,
                        'aff_term' => $aff_term,
                        'aff_class' => $aff_class,
                        'aff_admin_number' => $data['st_admin_number'],
                        'aff_student_name' => $tech_name->surname . ' ' . $tech_name->other_name,
                        'aff_addedby' => $userDetails->username,
                        'aff_tid' => $aff_code,
                        'aff_status' => 'Active',
                        'aff_date' => date('d/m/Y H:i:s'),
                    ]);
                }
            }
            // update the start psychomotor domain here...
            $class_name = ClassModel::where('id', $aff_class)
                ->where('status', 'Active')->first();
            $year_name = AcademicSession::where('id', $aff_year)
                ->where('a_status', 'Active')->first();
            $term_name = TermModel::where('id', $aff_term)
                ->where('t_status', 'Active')->first();

            StartPsychomotoDomain::query()
                ->where('saff_tid', $aff_code)
                ->update([
                    'saff_year' => $year_name->academic_name,
                    'saff_term' => $term_name->term_name,
                    'saff_class' => $class_name->class_name,
                    'saff_status' => "Completed",
                ]);
            // history record here...
            $logs = new Activitity_log();
            $logs->m_username = $userDetails->username;
            $logs->m_action = "Psychomotor Domain Added";
            $logs->m_status = "Successful";
            $logs->m_details = "$userDetails->name, New psychomotor details added";
            $logs->m_date = date('d/m/Y H:i:s');
            $logs->m_uid = $userDetails->id;
            $logs->m_ip = request()->ip;
            $logs->save();

            return response()->json([
                'status' => 200,
                'message' => 'Record Added Successfully',
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => "Login to continue...",
            ]);
        }
    }

    // get view psychomotor details here...
    public function fetchPsychomotor($id)
    {
        if (auth('sanctum')->check()) {
            $sub_attetails = PsychomotoDomian::where('aff_tid', $id)
                ->get();
            $sub_id  = StartPsychomotoDomain::where('saff_tid', $id)
                ->first();
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

    // delete single psychomotor details here...
    public function deletePsychomotor($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $find_deleteID = PsychomotoDomian::where('id', $id)->first();
            if (!empty($find_deleteID)) {
                // rund the delete query here
                $find_deleteID->update([
                    'aff_status' => "Deleted",
                ]);

                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Psychomotor Deleted";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Psychomotor details was deleted";
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

    // delete all psychomotor details at once here...
    public function deleteAllPsychomotor($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $check_deleteID = PsychomotoDomian::where('aff_tid', $id)->first();
            if (!empty($check_deleteID)) {
                // rund the delete query here
                PsychomotoDomian::query()
                    ->where('aff_tid', $id)
                    ->update([
                        'aff_status' => "Deleted",
                    ]);
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Deleted all psychomotor";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, All psychomotor was deleted";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'All Record Deleted Successfully',
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

    // fetch single details for editing here...
    public function fetchPsychomotorEdit($id)
    {
        if (auth('sanctum')->check()) {
            $fetch_domain = PsychomotoDomian::where('id', $id)->first();
            $fetch_class = StartPsychomotoDomain::where('saff_tid', $fetch_domain->aff_tid)->first();
            if (!empty($fetch_domain)) {

                return response()->json([
                    'status' => 200,
                    'result' => [
                        'fetch_info' => $fetch_domain,
                        'className' => $fetch_class,
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

    // update edit psychomotor domain here...
    public function updatePsychomotor(Request $request)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            //dd($request->all());
            //validate input details
            $validator = Validator::make($request->all(), [
                'effectiveness' => 'required',
                'neatness_score' => 'required',
                'craft_score' => 'required',
                'punctuality_score' => 'required',
                'sport_score' => 'required',
            ], [
                'effectiveness.required' => 'Effective Required',
                'neatness_score.required' => 'Neatness Required',
                'craft_score.required' => 'Craft Required',
                'punctuality_score.required' => 'Punctuality Required',
                'sport_score.required' => 'Sport Required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {
                $recordID = $request->input('id');
                $check_updateID = PsychomotoDomian::where('id', $recordID)->first();

                if (!empty($check_updateID)) {
                    // rund the update query here
                    $check_updateID->update([
                        'effectiveness' => $request->effectiveness,
                        'neatness_score' => $request->neatness_score,
                        'craft_score' => $request->craft_score,
                        'punctuality_score' => $request->punctuality_score,
                        'sport_score' => $request->sport_score,
                    ]);
                    // history record here...
                    $logs = new Activitity_log();
                    $logs->m_username = $userDetails->username;
                    $logs->m_action = "Update psychomotor";
                    $logs->m_status = "Successful";
                    $logs->m_details = "$userDetails->name, Update psychomotor details";
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
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }
}