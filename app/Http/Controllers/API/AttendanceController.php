<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Activitity_log;
use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\StartAttendance;
use App\Models\Student;
use App\Models\TermModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    // fetch marked attendance here....

    public function getAttendance()
    {
        if (auth('sanctum')->check()) {
            // $fetch_attendance = DB::table('attendances')
            //     ->selectRaw('id, atten_class, atten_year,
            // atten_term,  atten_mark_date , atten_status, atten_addeby, atten_class_name,
            // atten_year_name, atten_term_name, atten_tid, atten_date')
            //     ->where('atten_status', '=', 'Active')
            //     ->groupBy('atten_tid')
            //     ->get();
            $fetch_attendance = Attendance::query()
                ->where('atten_status', '=', 'Active')
                ->groupBy('atten_tid')
                ->orderByDesc('id')
                ->paginate('15');
            if ($fetch_attendance) {
                return response()->json([
                    'status' => 200,
                    'attan_Details' => [
                        'attendance_Details' => $fetch_attendance,
                    ]
                ]);
            } else if (empty($fetch_attendance)) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No record at the moment'
                ]);
            }
            // $fetch_attendance = Attendance::where('atten_status', '!=', 'Deleted')
            //     ->orderBy('atten_date', 'desc')
            //     ->get();

        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // start attendance here...
    public function startAttendance(Request $request)
    {
        //dd($request->all());
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $tid = substr(str_shuffle($permitted_chars), 0, 16);
        // get array value and re-assign it to value here...
        $school_year = $request->input('data.school_year');
        $sch_class = $request->input('data.sch_class');
        $sch_term = $request->input('data.sch_term');
        $mark_date = $request->mark_date;
        $validator = Validator::make(
            $request->input('data'),
            [
                'sch_class' => 'required',
                'school_year' => 'required',
                'sch_term' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                $errors = $validator->errors(),
                'status' => 422,
                'errors' => $errors,
            ]);
        }

        //dd($mark_date);
        else {
            //check if this particular attendance has be marked
            $checkAtten = Attendance::where('atten_class', $sch_class)
                ->where('atten_year', $school_year)
                ->where('atten_term', $sch_term)
                ->where('atten_mark_date', $mark_date)
                ->first();
            if ($checkAtten) {
                return response()->json([
                    'status' => 403,
                    'message' => "Attendance already exist! Try again",
                ]);
            }

            if (auth('sanctum')->check()) {
                $grad_check = Student::where('class_apply', $sch_class)
                    ->where('acct_status', 'Active')
                    ->first();
                if (empty($grad_check)) {
                    return response()->json([
                        'status' => 404,
                        'message' => "No student record found! Try again",
                    ]);
                }

                $userDetails = auth('sanctum')->user();
                $get_student = DB::table('students')
                    ->selectRaw('surname, other_name, class_apply, st_admin_number, acct_status')
                    ->where('acct_status', 'Active')
                    ->where('class_apply', $sch_class)
                    ->orderBy('st_admin_number', 'desc')
                    ->get();
                // next class name here...
                $grad_start = ClassModel::where('id', $sch_class)
                    ->where('status', 'Active')
                    ->first();

                // next term name here...
                $grad_term = TermModel::where('id', $sch_term)
                    ->where('t_status', 'Active')
                    ->first();
                // next year name here...
                $grad_year = AcademicSession::where('id', $school_year)
                    ->where('a_status', 'Active')
                    ->first();

                if (!empty($grad_start)) {
                    // select and insert into database table here...
                    $inserts = [];
                    foreach ($get_student as $bid) {
                        $inserts[] =
                            [
                                'sta_admin_no' => $bid->st_admin_number,
                                'sta_stu_name' => $bid->other_name,
                                'sta_class' => $bid->class_apply,
                                'sta_year' => $school_year,
                                'sta_status' => 'Active',
                                'sta_term' => $sch_term,
                                'sta_tid' => $tid,
                                'sta_class_name' => $grad_start->class_name,
                                'sta_date' => date('d/m/Y H:i:s'),
                                'sta_addeby' => $userDetails->username,
                                'sta_year_name' => $grad_year->academic_name,
                                'sta_term_name' => $grad_term->term_name,
                                'sta_mark_date' => $request->mark_date,
                            ];
                    }
                    // save all the operation here
                    DB::table('start_attendances')->insert($inserts);
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

    // fetch start attendance here...
    public function getStartAttendance($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $start_attetails = StartAttendance::where('sta_tid', $id)->where('sta_addeby', $userDetails->username)->get();
            $attDetails = StartAttendance::where('sta_tid', $id)->where('sta_addeby', $userDetails->username)->first();
            if (!empty($start_attetails)) {
                return response()->json([
                    'status' => 200,
                    'start_attenDetails' => [
                        'proDetails' => $start_attetails,
                        'pDetails' => $attDetails,
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

    // mark single attendance here....
    public function markAttendance($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $pMark = StartAttendance::where('id', $id)->first();
            $mark_atten = StartAttendance::where('id', $id)->first();

            if (!empty($mark_atten)) {
                $pMark->update([
                    'sta_status' => "Marked",
                ]);
                // get attendance details from here...
                $insertAttend = DB::table('start_attendances')
                    ->selectRaw('sta_admin_no, sta_stu_name, sta_class, sta_year, sta_term, sta_mark_date
                , sta_tid')
                    ->where('sta_status', 'Marked')
                    ->where('id', $id)
                    ->get();
                $inserts = [];
                foreach ($insertAttend as $bid) {
                    $inserts[] =
                        [
                            'atten_admin_no' => $bid->sta_admin_no,
                            'atten_stu_name' => $bid->sta_stu_name,
                            'atten_class' => $bid->sta_class,
                            'atten_year' => $bid->sta_year,
                            'atten_status' => 'Active',
                            'atten_term' => $bid->sta_term,
                            'atten_tid' => $pMark->sta_tid,
                            'atten_mark_date' => $pMark->sta_mark_date,
                            'atten_class_name' => $pMark->sta_class_name,
                            'atten_submit_date' => date('d/m/Y H:i:s'),
                            'atten_addeby' => $userDetails->username,
                            'atten_year_name' => $pMark->sta_year_name,
                            'atten_term_name' => $pMark->sta_term_name,
                        ];
                }
                // save all the operation here
                DB::table('attendances')->insert($inserts);
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Mark attendance";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Added attendance details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Marked Successful',
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

    // return attendance marked before...
    public function returnAttendance($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $mark_atten = StartAttendance::where('id', $id)->first();
            // remove from attendance table here...
            $pMark = Attendance::where('atten_tid', $mark_atten->sta_tid)
                ->where('atten_admin_no', $mark_atten->sta_admin_no)
                ->first();
            if (!empty($pMark)) {
                $pMark->delete();
            }
            if (!empty($mark_atten)) {
                $mark_atten->update([
                    'sta_status' => "Active",
                ]);
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Returned marked attendance";
                $logs->m_status = "Successful";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Returned Successfully',
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

    // mark all student attendance here...
    public function markAllAttendance($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $p_allMark = StartAttendance::where('sta_tid', $id)->first();
            $mark_atten = StartAttendance::where('sta_tid', $id)->first();

            if (!empty($mark_atten)) {
                // get attendance details from here...
                $insert_all_Attend = DB::table('start_attendances')
                    ->selectRaw('sta_admin_no, sta_stu_name, sta_class, sta_year, sta_term, sta_mark_date
                , sta_tid')
                    ->where('sta_status', 'Active')
                    ->where('sta_tid', $id)
                    ->get();
                $inserts = [];
                foreach ($insert_all_Attend as $bid) {
                    $inserts[] =
                        [
                            'atten_admin_no' => $bid->sta_admin_no,
                            'atten_stu_name' => $bid->sta_stu_name,
                            'atten_class' => $bid->sta_class,
                            'atten_year' => $bid->sta_year,
                            'atten_status' => 'Active',
                            'atten_term' => $bid->sta_term,
                            'atten_tid' => $p_allMark->sta_tid,
                            'atten_mark_date' => $p_allMark->sta_mark_date,
                            'atten_class_name' => $p_allMark->sta_class_name,
                            'atten_submit_date' => date('d/m/Y H:i:s'),
                            'atten_addeby' => $userDetails->username,
                            'atten_year_name' => $p_allMark->sta_year_name,
                            'atten_term_name' => $p_allMark->sta_term_name,
                        ];
                }
                // save all the operation here
                DB::table('attendances')->insert($inserts);
                StartAttendance::query()
                    ->where('sta_tid', $id)
                    ->update([
                        'sta_status' => "Marked",
                    ]);

                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Mark attendance";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Added attendance details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'All Marked Successful',
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

    // view all attendance marked details here...
    public function viewAllAttendance($id)
    {
        if (auth('sanctum')->check()) {
            $att_allDetails = Attendance::where('id', $id)->first();
            $all_attetails = Attendance::where('atten_tid', $att_allDetails->atten_tid)->get();

            if (!empty($all_attetails)) {
                return response()->json([
                    'status' => 200,
                    'all_attenDetails' => [
                        'proDetails' => $all_attetails,
                        'pDetails' => $att_allDetails,
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
    //delete marked attendance here...
    public function removeAttendance($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $find_id = Attendance::where('id', $id)->first();
            if (!empty($find_id)) {
                $find_id->update([
                    'atten_status' => "Deleted",
                ]);
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Delete attendance";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Deleted attendance details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Deleted Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'No record found! Try again',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }
    // remove/delete all attendance at once here...
    public function removeAllAttendance($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $de_allMark = Attendance::where('atten_tid', $id)->first();
            if (!empty($de_allMark)) {
                Attendance::query()
                    ->where('atten_tid', $id)
                    ->where('atten_status', 'Active')
                    ->update([
                        'atten_status' => "Deleted",
                    ]);

                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Deleted attendance";
                $logs->m_details = "$userDetails->name, Deleted student attendance details";
                $logs->m_status = "Successful";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'All Deleted Successful',
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