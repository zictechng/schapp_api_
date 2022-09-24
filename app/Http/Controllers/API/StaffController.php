<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Activitity_log;
use App\Models\AssignClass;
use App\Models\AssignedSubject;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\CAResultProcessStart;
use App\Models\ClassModel;
use App\Models\DaysSchoolOpen;
use App\Models\MessageSystem;
use App\Models\PsychomotoDomian;
use App\Models\ResultCA;
use App\Models\ResultProcessStart;
use App\Models\ResultTable;
use App\Models\SchoolCategory;
use App\Models\SchoolResumption;
use App\Models\Staff;
use App\Models\StartPsychomotoDomain;
use App\Models\Student;
use App\Models\StudentComment;
use App\Models\StudentPosition;
use App\Models\Subject;
use App\Models\SubmitAssignment;
use App\Models\SystemSetup;
use App\Models\TermModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{

    // create new staff details here

    public function saveStaff(Request $request)
    {
        if (auth('sanctum')->check()) {

            $validator = Validator::make($request->all(), [
                'surname' => 'required|max:191',
                'other_name' => 'required|max:191',
                'sex' => 'required|max:191',
                'school_category' => 'required|max:191',
                'phone' => 'required|max:191',
                'username' => 'required|unique:username|max:191',
                'home_address' => 'required|max:191',
                'email' => 'required|email|max:255|unique:users',
                'staff_level' => 'required|max:191',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {

                $age = Carbon::parse($request->dob)->diff(Carbon::now())->y;
                $my_age = $age . " Years";

                $userDetails = auth('sanctum')->user();
                $check_record = Staff::where('phone', $request->phone)->where('acct_status', 'Active')->first();
                if (!empty($check_record)) {
                    return response()->json([
                        'status' => 402,
                        'message' => 'Staff phone number exist',
                    ]);
                } else if (empty($resumption_date)) {

                    $save_new = new Staff();
                    $save_new->surname = $request['surname'];
                    $save_new->other_name = $request['other_name'];
                    $save_new->sex = $request['sex'];
                    $save_new->dob = $request['dob'];
                    $save_new->state = $request['state'];
                    $save_new->country = $request['country'];
                    $save_new->email = $request['email'];
                    $save_new->phone = $request['phone'];
                    $save_new->class = $request['class_apply'];
                    $save_new->school_category = $request['school_category'];
                    $save_new->staff_id = $request['staff_id'];
                    $save_new->qualification = $request['qualification'];
                    $save_new->acct_username = $request['username'];
                    $save_new->staff_password = Hash::make('12345678');
                    $save_new->home_address = $request['home_address'];
                    $save_new->staff_level = $request['staff_level'];
                    $save_new->reg_date = date('d/m/Y H:i:s');
                    $save_new->acct_status = 'Active';
                    $save_new->addby = $userDetails->username;

                    $save_new->save();
                    // get newly registered staff ID in the staff table here
                    $reg_staff = Staff::where('email', $request['email'])->first();
                    // add to user table here..
                    $save_user = new User();
                    $save_user->name = $request['surname'] . ' ' . $request['other_name'];
                    $save_user->email = $request['email'];
                    $save_user->phone = $request['phone'];
                    $save_user->sex = $request['sex'];
                    $save_user->state = $request['state'];
                    $save_user->location = $request['country'];
                    $save_user->address = $request['home_address'];
                    $save_user->username = $request['username'];
                    $save_user->dob = $request['dob'];
                    $save_user->acct_status = "Active";
                    $save_user->reg_status = "Active";
                    $save_user->reg_date = date('d/m/Y H:i:s');
                    $save_user->role = "Teacher";
                    $save_user->staff_id = $reg_staff->id;
                    $save_user->password = Hash::make('12345678');

                    $save_user->save();

                    if ($save_new->save()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Registered staff details";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, added new staff details";
                        $logs->m_date = date('d/m/Y H:i:s');
                        $logs->m_uid = $userDetails->id;
                        $logs->m_ip = request()->ip;

                        $logs->save();
                        return response()->json([
                            'status' => 200,
                            'message' => 'Staff Details Added Successfully',
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

    // get all staff details from here
    public function getStaff()
    {
        if (auth('sanctum')->check()) {
            //$allstaff = Staff::where('acct_status', 'Active')->orderByDesc('id')->get();
            $allstaff = Staff::query()
                ->where('acct_status', 'Active')
                ->orderByDesc('id')
                ->paginate('15');
            if ($allstaff) {
                return response()->json([
                    'status' => 200,
                    'all_staff' => $allstaff
                ]);
            } else if (empty($allstaff)) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No staff record at the moment'
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    // fetch staff details from edit button clicked 
    public function fetchStaff($id)
    {
        if (auth('sanctum')->check()) {
            $getStaffDetails = Staff::where('id', $id)->where('acct_status', 'Active')->first();
            if ($getStaffDetails) {
                return response()->json([
                    'status' => 200,
                    'staff_editDetails' => $getStaffDetails,
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

    // fetch my profile details here...
    public function fetchMyProfile()
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $getMyDetails = Staff::where('id', auth('sanctum')->user()->staff_id)->where('acct_status', 'Active')->first();

            $getClassAssign = AssignClass::where('cls_teacher_id',  $userDetails->staff_id)
                ->where('cls__status', 'Active')
                ->get();
            // get subject assigned
            $mySubject = AssignedSubject::where('sub_teacher_id', auth('sanctum')->user()->staff_id)
                ->where('sub_status', 'Active')
                ->get();
            $get_myMessage = DB::table('message_systems')
                ->selectRaw('count(id) as total_message')
                ->where('mes_status', 'Active')
                ->where('receiver_user_id', $userDetails->staff_id)
                ->first();

            if ($getMyDetails) {
                return response()->json([
                    'status' => 200,
                    'staff_editDetails' => $getMyDetails,
                    'get_myclass' => $getClassAssign,
                    'get_mysubject' => $mySubject,
                    'get_mymessage' => $get_myMessage,
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
    // update staff details here...
    public function staffUpdate(Request $request, $id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();

            $validator = Validator::make($request->all(), [
                'surname' => 'required|max:191',
                'other_name' => 'required|max:191',
                'sex' => 'required|max:191',
                'school_category' => 'required|max:191',
                'phone' => 'required|max:191',
                'acct_username' => 'required|max:191',
                'home_address' => 'required|max:191'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {
                $find_staff = Staff::find($id);

                if (empty($find_staff)) {
                    return response()->json([
                        'status' => 402,
                        'message' => 'Record not found',
                    ]);
                } else if (!empty($find_staff)) {
                    $find_staff->update([
                        'surname' => $request->surname,
                        'other_name' => $request->other_name,
                        'sex' => $request->sex,
                        'dob' => $request->dob,
                        'state' => $request->state,
                        'country' => $request->country,
                        'phone' => $request->phone,
                        'email' => $request->email,
                        'class' => $request->class,
                        'staff_id' => $request->staff_id,
                        'home_address' => $request->home_address,
                        'school_category' => $request->school_category,
                        'acct_username' => $request->acct_username,
                        'qualification' => $request->qualification,
                        'staff_level' => $request->staff_level,
                    ]);
                    if ($find_staff->update()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Update staff details";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, updated staff details";
                        $logs->m_date = date('d/m/Y H:i:s');
                        $logs->m_uid = $find_staff->id;
                        $logs->m_ip = request()->ip;

                        $logs->save();
                        return response()->json([
                            'status' => 200,
                            'message' => 'Staff Details Updated Successfully',
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

    // delete staff details here..
    public function deleteStaff($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();

            $staff_record = Staff::find($id);
            if (!empty($staff_record)) {
                $staff_record->update([
                    'acct_status' => 'Deleted',
                ]);
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Deleted staff record";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Delete staff info details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->m_record_id = $id;

                $logs->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Record Deleted Successfully',
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

    // update staff image profile here..
    public function updateStaffImage(Request $request, $id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();

            $validator = Validator::make($request->all(), [
                'image' => 'required|mimes:jpeg,png,jpg,gif|max:2048',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {
                $find_user = Staff::find($id);
                if (empty($find_user)) {
                    return response()->json([
                        'status' => 402,
                        'message' => 'User not found',
                    ]);
                } else if (!empty($find_user)) {
                    /* this check if there is an image the uploade or do not process */
                    if ($request->hasFile('image')) {
                        /* check if the previous image exist then delete before uplaoding new one */
                        $path = $find_user->staff_image; // this image colunm already have the image path in the database
                        if (File::exists($path)) {
                            File::delete($path);
                        }
                        /* image deleting ends here --*/

                        $file = $request->file('image');
                        $extension = $file->getClientOriginalExtension();
                        $filename = time() . '.' . $extension;
                        $file->move('uploads/staff_image/', $filename);
                        $find_user->staff_image = 'uploads/staff_image/' . $filename;
                    }
                    /* ends here */
                    $find_user->update();
                    return response()->json([
                        'status' => 200,
                        'message' => 'Product Updated Successfully.',
                    ]);
                    if ($find_user->update()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Update staff profile image";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, updated staff account profile picture";
                        $logs->m_date = date('d/m/Y H:i:s');
                        $logs->m_uid = $find_user->id;
                        $logs->m_ip = request()->ip;
                        $logs->save();
                        return response()->json([
                            'status' => 200,
                            'message' => 'Profile Picture Updated Successfully',
                        ]);
                    } else {
                        return response()->json([
                            'status' => 500,
                            'message' => 'Operation failed',
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

    // update password here...
    public function updateStaffPassword(Request $request, $id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();

            $validator = Validator::make($request->all(), [
                'new_password' => 'required|min:8',
                'confirm_password' => 'required|same:new_password|min:8',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {
                $find_staff = Staff::find($id);

                if (empty($find_staff)) {
                    return response()->json([
                        'status' => 402,
                        'message' => 'Record not found',
                    ]);
                } else if (!empty($find_staff)) {
                    $find_staff->update([
                        'staff_password' => Hash::make($request->new_password),
                    ]);
                    if ($find_staff->update()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Update staff password details";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, staff password details updated";
                        $logs->m_date = date('d/m/Y H:i:s');
                        $logs->m_uid = $find_staff->id;
                        $logs->m_ip = request()->ip;

                        $logs->save();
                        return response()->json([
                            'status' => 200,
                            'message' => 'Staff Password Updated Successfully',
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
    // fetch system log activities here ...
    public function fetchSystemLog()
    {
        $get_subjectAll = DB::table('activitity_logs')
            ->selectRaw('*')
            ->orderBy('id', 'desc')
            ->get();
        //->paginate('15');

        $all_log = Activitity_log::query()
            ->orderByDesc('id')
            ->paginate('15');
        return response()->json([
            'status' => 200,
            'all_details' => $all_log
        ]);
    }

    // get student details assigned to staff/teacher here...
    public function getStaffStudent()
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();

            // get logged staff details here
            $get_staff = DB::table('staff')
                ->selectRaw('*')
                ->where('id', $userDetails->staff_id)
                ->first();

            $get_myStudentTotal = DB::table('students')
                ->selectRaw('count(id) as all_student')
                ->where('class_apply', $get_staff->class)
                ->first();

            $get_myStudentGraduated = DB::table('students')
                ->selectRaw('count(id) as graduated_total')
                ->where('acct_status', 'Graduated')
                ->where('class_apply', $get_staff->class)
                ->first();


            return response()->json([
                'status' => 200,
                'all_details' => [
                    'student_total' => $get_myStudentTotal,
                    'graduate_student' => $get_myStudentGraduated,

                ]
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // staff dashboard details fetch here...
    public function fetchDashDetails()
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $today = now();
            // get logged staff details here
            $get_Staff_info = DB::table('staff')
                ->selectRaw('*')
                ->where('id', $userDetails->staff_id)
                ->first();

            $get_myAssignment = DB::table('assignments')
                ->selectRaw('count(id) as all_assign')
                ->where('assign_class_id', $get_Staff_info->class)
                ->first();

            $get_AssignmentHome = DB::table('assignments')
                ->selectRaw('count(id) as all_assign_home')
                ->where('assign_class_id', $get_Staff_info->class)
                ->where('assign_type', 'Home Work')
                ->first();

            $ldate = date('Y-m-d');
            $get_myStudentBirthday = DB::table('students')
                ->selectRaw('count(id) as birth_total')
                ->whereMonth('dob', $today->month)
                ->whereDay('dob', $today->day)
                ->where('class_apply', $get_Staff_info->class)
                ->where('acct_status', 'Active')
                ->first();

            //message fetch here...
            $get_myMessage = DB::table('message_systems')
                ->selectRaw('count(id) as total_message')
                ->where('mes_status', 'Active')
                ->where('receiver_user_id', $get_Staff_info->id)
                ->first();

            //latest activities fetch here...
            // $get_myActivity = DB::table('activitity_logs')
            //     ->selectRaw('*')
            //     ->where()
            //     ->get();
            return response()->json([
                'status' => 200,
                'all_details' => [
                    'assignment' => $get_myAssignment,
                    'assignment_home' => $get_AssignmentHome,
                    'mystudentBirth' => $get_myStudentBirthday,
                    'myMessage' => $get_myMessage,
                ]
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }


    // fetch staff/teacher log activities here...
    public function fetchLogDetails()
    {
        // get logged staff details here
        $get_AllLog = Activitity_log::query()
            ->orderByDesc('id')
            ->where('m_uid', '=', auth('sanctum')->user()->staff_id)
            ->paginate('10');
        return response()->json([
            'status' => 200,
            'myLog' => $get_AllLog,
        ]);
    }

    // fetch my class student details here...
    public function getMyStudent()
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $staff_details = Staff::where('id', auth('sanctum')->user()->staff_id)
                ->first();

            $get_AllMyStudent = Student::query()
                ->orderByDesc('id')
                ->where('class_apply', '=', $staff_details->class)
                ->where('acct_status', '=', 'Active')
                ->paginate('10');
            return response()->json([
                'status' => 200,
                'myStudentLog' => $get_AllMyStudent,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }
    // fetch my student for single result entry here...
    public function myAllStudent()
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $staff_details = Staff::where('id', auth('sanctum')->user()->staff_id)
                ->first();

            $get_MyStudent = Student::query()
                ->orderByDesc('id')
                ->where('class_apply', '=', $staff_details->class)
                ->where('acct_status', '=', 'Active')
                ->get();
            return response()->json([
                'status' => 200,
                'myStudent' => $get_MyStudent,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }
    // fetch staff/ teacher result here...
    public function fetchResultStaff()
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            // $staff_details = Staff::where('id', auth('sanctum')->user()->staff_id)
            //     ->first();
            // $all_resultID = ResultProcessStart::where('r_status', '!=', 'Deleted')
            //     ->first();
            $all_result_details = ResultProcessStart::query()
                ->where('r_status', '!=', 'Deleted')
                ->where('r_status', '!=', 'Pending')
                ->where('addby_id', auth('sanctum')->user()->staff_id)
                ->orderByDesc('id')
                ->paginate('15');

            if ($all_result_details) {
                return response()->json([
                    'status' => 200,
                    'allPostResult' => $all_result_details,
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
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // fetch start result process here...
    public function fetchResultStart($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $staff_details = Staff::where('id', auth('sanctum')->user()->staff_id)
                ->first();

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
            $fetch_student = Student::where('class_apply', $staff_details->class)
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
        } else {
            return response()->json([
                'status' => 401,
                'message' => "Login to continue",
            ]);
        }
    }
    // start result process here...

    public function startResultProcess(Request $request)
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
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {
                $userDetails = auth('sanctum')->user();
                // get staff details here...
                $staff_details = Staff::where('id', auth('sanctum')->user()->staff_id)
                    ->first();
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

    // fetch my call details here...
    public function fetchMyClass()
    {
        if (auth('sanctum')->check()) {
            $staff_details = Staff::where('id', auth('sanctum')->user()->staff_id)
                ->first();

            $all_session = AcademicSession::where('a_status', 'Active')->get();

            $all_category = SchoolCategory::where('sc_status', 'Active')->get();

            $all_class = AssignClass::where('cls_teacher_id', auth('sanctum')->user()->staff_id)
                ->where('cls__status', 'Active')
                ->groupBy('cls__class_id')
                ->get();
            $all_subject = AssignedSubject::where('sub_teacher_id', $staff_details->id)
                ->where('sub_status', 'Active')
                ->groupBy('sub_subject_id')
                ->get();

            // $all_subject = Subject::where('sub_status', 'Active')->get();
            $all_term = TermModel::where('t_status', 'Active')->get();

            return response()->json([
                'status' => 200,
                'allDetails' => [
                    'class_details' => $all_class,
                    'sch_category_details' => $all_category,
                    'session_details' => $all_session,
                    'subject_details' => $all_subject,
                    'term_details' => $all_term,
                ]
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    // process result save here...
    public function saveMyResult(Request $request)
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
        }
    }
    // save final result here after grading the subject position 
    public function saveFinalResult(Request $request)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            // Check if this result exist before saving it...
            $check_subjectP = ResultTable::where('academic_year', $request->g_year)
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
                //$categoryName = SchoolCategory::where('id', $categoryID)->first();

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
                $keep->subject = $sbujectName->subject_name;
                $keep->addby = $userDetails->username;
                $keep->addby_id = $userDetails->id;
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

    // single result process start here...

    public function saveMySingleResult(Request $request)
    {
        $request->validate([
            'year' => 'required',
            'term' => 'required',
            'subject' => 'required',
            'class' => 'required',
        ], [
            'year.required' => 'Academic year is required',
            'term.required' => 'Academic term is required',
            'subject.required' => 'Subject is required',
            'class.required' => 'Class is required',
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
            // update process table to keep update of result processed.
            $keep = new ResultProcessStart();
            $keep->r_tid = $recordID;
            $keep->school_year = $yearName->academic_name;
            $keep->school_term = $termName->term_name;
            $keep->class = $className->class_name;
            $keep->subject = $sbujectName->subject_name;
            $keep->addby = $userDetails->username;
            $keep->addby_id = $userDetails->id;
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

    // fetch CA result details here...
    public function fetchMyCAResult()
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $all_caresult_details = CAResultProcessStart::query()
                ->where('status', '!=', 'Deleted')
                ->Where('status', '!=', 'Pending')
                ->where('addby_user_id',  $userDetails->staff_id)
                ->orderByDesc('id')
                ->paginate('15');

            if ($all_caresult_details) {
                return response()->json([
                    'status' => 200,
                    'allPostResult' => $all_caresult_details,
                ]);
            } else if (empty($all_caresult_details)) {
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
                'message' => 'Please, login to continue',
            ]);
        }
    }

    //Start process CA result here...
    public function processCAResult(Request $request)
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
                    $save_new->status = 'Pending';

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

    // 
    //save CA result here...
    public function saveMyCA(Request $request)
    {
        if (auth('sanctum')->check()) {
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
            // update process table to keep update of result processed.
            $keep = new CAResultProcessStart();
            $keep->tid_code = $recordID;
            $keep->year = $yearName->academic_name;
            $keep->term = $termName->term_name;
            $keep->class = $className->class_name;
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
    public function saveMySingleCA(Request $request)
    {
        if (auth('sanctum')->check()) {

            $request->validate([
                'year' => 'required',
                'term' => 'required',
                'subject' => 'required',
                'class' => 'required',
            ], [
                'year.required' => 'Academic year is required',
                'term.required' => 'Academic term is required',
                'subject.required' => 'Subject is required',
                'class.required' => 'Class is required',
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

    // post assignment here...
    public function saveAssignment(Request $request)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $request->validate([
                'subject' => 'required',
                'class' => 'required',
            ], [
                'subject.required' => 'Subject is required',
                'class.required' => 'Class is required',
            ]);
            //dd($request->all());
            /* Generate unique transaction ID for each cash request record */
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $tid = substr(str_shuffle($permitted_chars), 0, 16);
            // dd($request,$request->get('state'));
            $className = ClassModel::where('id', $request->class)->first();
            $sbujectName = Subject::where('id', $request->subject)->first();

            //$uploade_path = $path;
            $file_size = 0;
            $data = new Assignment();
            $data->assign_title = $request['title'];
            $data->assign_body = $request['message_body'];
            $data->assign_class = $className->class_name;
            $data->add_subject = $sbujectName->subject_name;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $file_size += $file->getSize();

                $file_size = number_format($file_size / 2048576, 2);
                //print_r($file_size.' MB');	
                //dd($file_size);
                if ($file_size > 2045) {
                    return response()->json([
                        'status' => 405,
                        'message' => 'File too large!',
                    ]);
                }

                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('uploads/assignment_folder/', $filename);
                $data->assign_file = 'http://localhost:8000/uploads/assignment_folder/' . $filename;
            }
            $data->assign_type = $request['assignment_type'];
            $data->assign_status = "Active";
            $data->addby = $userDetails->username;
            $data->addby_user_id = $userDetails->id;
            $data->assign_class_id = $request->class;
            $data->assign_submission_date = $request['submit_date'];
            $data->assign_date = date('d/m/Y H:i:s');
            $data->assign_tid = $tid;
            $data->save();

            // history record here...
            $logs = new Activitity_log();
            $logs->m_action = "Post Assignment";
            $logs->m_status = "Successful";
            $logs->m_details = "$userDetails->name, Added assignment details";
            $logs->m_date = date('d/m/Y H:i:s');
            $logs->m_ip = request()->ip;
            $logs->m_uid = $userDetails->id;
            $logs->save();
            if ($data->save()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Save successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 403,
                    'message' => 'Error occurred! Try again',
                ]);
            }

            //dd($request->all());
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    // fetch posted assignment here...
    public function fetchMyPostedAssignment()
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $all_assign_details = Assignment::query()
                ->where('assign_status', '!=', 'Deleted')
                ->Where('assign_status', '!=', 'Pending')
                ->where('addby_user_id',  $userDetails->staff_id)
                ->orderByDesc('id')
                ->paginate('15');

            if ($all_assign_details) {
                return response()->json([
                    'status' => 200,
                    'allPostResult' => $all_assign_details,
                ]);
            } else if (empty($all_assign_details)) {
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
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // fetch student assignment submission details here...
    public function fetchSubmissionAssignment()
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();


            $all_assign_details = SubmitAssignment::query()
                ->where('assign_status', '!=', 'Deleted')
                ->Where('assign_status', '!=', 'Successful')
                ->Where('assign_status', '!=', 'Reject')
                ->where('teacher_id',  $userDetails->staff_id)
                ->orderByDesc('id')
                ->paginate('15');

            if ($all_assign_details) {
                return response()->json([
                    'status' => 200,
                    'allPostResult' => $all_assign_details,
                ]);
            } else if (empty($all_assign_details)) {
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
                'message' => 'Please, login to continue',
            ]);
        }
    }
    // submit assignment remark here...
    public function sendAssignmentRemark(Request $request)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $tid = substr(str_shuffle($permitted_chars), 0, 16);
        //dd($request->all());
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $validator = Validator::make($request->all(), [
                'submission_status' => 'required|max:191',
                'message_body' => 'required|max:255',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {
                $assignmentID = SubmitAssignment::where('id', $request->record_id)->first();
                $userEmail = Student::where('id', $assignmentID->student_id)->first();
                if (!empty($assignmentID)) {
                    $assignmentID->update([
                        'assign_remark' => $request->message_body,
                        'assign_scores' => $request->score,
                        'assign_status' => $request->submission_status,
                        'assign_updated_date' => date('d/m/Y H:i:s'),
                    ]);

                    $save_message = new MessageSystem();
                    $save_message->sender_user_id = $userDetails->id;
                    $save_message->receiver_user_id = $assignmentID->student_id;
                    $save_message->mes_nature = "Assignment Remark";
                    $save_message->mes_title = "Assignment Remark";
                    $save_message->mes_body = $request['message_body'];
                    $save_message->mes_sender_name = $userDetails->name;
                    $save_message->mes_receiver_email = $userEmail->guardia_email;
                    $save_message->mes_status = "Active";
                    $save_message->mes_receiver_status = "New";
                    $save_message->mes_send_date = date('d/m/Y H:i:s');
                    $save_message->mes_tid = $tid;

                    $save_message->save();
                    return response()->json([
                        'status' => 200,
                        'message' => 'Remark Posted Successfully',
                    ]);
                } else if (empty($assignmentID)) {
                }
                // history record here...
                $logs = new Activitity_log();
                $logs->m_action = "Mark Assignment";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Added assignment remark details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_ip = request()->ip;
                $logs->m_uid = $userDetails->id;
                $logs->save();
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }
    // delete post assignment here...
    public function deleteAssign($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $check_assign = Assignment::find($id);

            if (empty($check_assign)) {
                return response()->json([
                    'status' => 402,
                    'message' => 'Record not exist',
                ]);
            } else if (!empty($check_assign)) {
                // delete result details from result table
                $check_assign->update([
                    'assign_status' => 'Deleted',
                ]);
                // keep history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Deleted Post Details";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Delete assignment details";
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

    // get assignment view details here...
    public function getAssignmentID($id)
    {
        if (auth('sanctum')->check()) {

            $userDetails = auth('sanctum')->user();
            $sub_id = Assignment::where('assign_tid', $id)->first();
            $sub_attetails = Assignment::where('id', $sub_id->id)
                ->get();
            if (!empty($sub_attetails)) {
                return response()->json([
                    'status' => 200,
                    'sub_assignDetails' => [
                        'proDetails' => $sub_attetails,
                        'pDetails' => $sub_id,
                        'pClass' => $sub_id,
                        'pSubject' => $sub_id,
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

    // fetch edit assignment here...
    public function fetchEditAssignment($id)
    {
        if (auth('sanctum')->check()) {
            $fetch_edit = Assignment::where('assign_tid', $id)->first();
            if (!empty($fetch_edit)) {
                return response()->json([
                    'status' => 200,
                    'fetch_info' => $fetch_edit,
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

    // save assignment edit update here...
    public function saveUpdateAssignment(Request $request)
    {
        //dd($request->all());
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $update_assign = Assignment::where('id', $request->id)->first();
            $className = ClassModel::where('id', $request->class)->first();
            if (!empty($update_assign)) {
                $update_assign->assign_title =  $request->title;
                $update_assign->assign_body = $request->message_body;
                $update_assign->add_subject = $request->subject;
                $update_assign->assign_type = $request->assignment_type;
                $update_assign->assign_submission_date = $request->submit_date;
                if ($request->hasFile('image')) {
                    /* check if the previous image exist then delete before uplaoding new one */
                    $path = $update_assign->assign_file; // this image colunm already have the image path in the database
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                    /* image deleting ends here --*/
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->move('uploads/assignment_folder/', $filename);
                    $update_assign->assign_file = 'uploads/assignment_folder/' . $filename;
                }
                // now update everything here...
                $update_assign->update();
                // history record here...
                $logs = new Activitity_log();
                $logs->m_action = "Update Assignment";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Updated assignment details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_ip = request()->ip;
                $logs->m_uid = $userDetails->id;
                $logs->save();
                return response()->json([
                    'status' => 200,
                    'message' => "Record Updated successfully",
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

    // fetch my attendance here...
    public function myAttendance()
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $fetch_attendance = DB::table('attendances')
                ->selectRaw('id, atten_class, atten_year,
            atten_term,  atten_mark_date , atten_status, atten_addeby, atten_class_name,
            atten_year_name, atten_term_name, atten_tid, atten_date')
                ->where('atten_addeby', '=', $userDetails->username)
                ->where('atten_status', '=', 'Active')
                ->groupBy('atten_tid')
                ->paginate(15);
            // $fetch_attendance = Attendance::query()
            //     ->where('atten_status', '!=', 'Deleted')
            //     ->Where('atten_status', '!=', 'Pending')
            //     ->where('atten_addeby',  $userDetails->username)
            //     ->orderByDesc('id')
            //     ->paginate('5');
            // $fetch_attendance = Attendance::where('atten_status', '!=', 'Deleted')
            //     ->orderBy('atten_date', 'desc')
            //     ->get();
            if (!empty($fetch_attendance)) {
                return response()->json([
                    'status' => 200,
                    'attan_Details' => [
                        'attendance_Details' => $fetch_attendance,
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
    // start attendance marking here...
    public function startMyAttendance(Request $request)
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

    // fetch my psychomotor details here...

    public function getMyPsychomotor()
    {
        // always check if user login before requesting this route
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            //query database to fetch details here
            $get_domainAll = StartPsychomotoDomain::query()
                ->where('saff_status', '=', 'Completed')
                ->Where('saff_addby', $userDetails->username)
                ->orderByDesc('id')
                ->paginate('15');

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

    // fetch my comment here..
    public function fetchMyComment()
    {
        if (auth('sanctum')->check()) {

            $user_details = auth('sanctum')->user();
            $comm_details = DB::table('student_comments')
                ->selectRaw('id, comm_stu_number, comm_stu_name,
            comm_class,  comm_year , comm_term, comm_comment, comm_prin_comment,
            comm_status, comm_addby, comm_date, comm_tid')
                ->where('comm_status', '=', 'Active')
                ->where('comm_addby', $user_details->username)
                ->groupBy('comm_tid')
                ->orderByDesc('id')
                ->paginate('15');

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

    // fetch all my notification here...
    public function myNotification()
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $staff_details = MessageSystem::where('receiver_user_id', auth('sanctum')->user()->staff_id)
                ->first();
            $message_active = MessageSystem::where('mes_status', '!=', 'Deleted')
                ->where('receiver_user_id', auth('sanctum')->user()->staff_id)
                ->get();
            $message_active_count = $message_active->count();

            $message_delete = MessageSystem::where('mes_status', '=', 'Deleted')
                ->where('receiver_user_id', auth('sanctum')->user()->staff_id)
                ->get();
            $message_delete_count = $message_delete->count();

            // failed message here...
            $message_failed = MessageSystem::where('mes_status', '=', 'Failed')
                ->where('sender_user_id', auth('sanctum')->user()->staff_id)
                ->get();
            $message_failed_count = $message_failed->count();

            $get_AllMyMessage = MessageSystem::query()
                ->orderByDesc('id')
                ->where('receiver_user_id', '=', auth('sanctum')->user()->staff_id)
                ->where('mes_status', '!=', 'Deleted')
                ->paginate('20');
            return response()->json([
                'status' => 200,
                'myMessageLog' => $get_AllMyMessage,
                'myActiveMessage' => $message_active_count,
                'myDeleteMessage' => $message_delete_count,
                'myFialedMessage' => $message_failed_count,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // send message/notification to users here....

    public function sendNotification(Request $request)
    {
        if (auth('sanctum')->check()) {

            $validator = Validator::make($request->all(), [
                'message_to' => 'required|max:191',
                'message_subject' => 'required|max:191',
                'message_body' => 'required|max:255',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {

                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $tid = substr(str_shuffle($permitted_chars), 0, 16);

                $userDetails = auth('sanctum')->user();
                $check_admin = User::where('email', $request->message_to)->first();

                if (!empty($check_admin)) {
                    $check_admin->id;
                    //$receiverMail = $check_student_id->guardia_email;
                    $receiverMail = $check_admin->email;

                    $save_message = new MessageSystem();
                    $save_message->sender_user_id = $userDetails->id;
                    $save_message->receiver_user_id = $check_admin->id;
                    $save_message->mes_nature = "Message";
                    $save_message->mes_title = $request['message_subject'];
                    $save_message->mes_body = $request['message_body'];
                    $save_message->mes_sender_name = $userDetails->name;
                    $save_message->mes_receiver_email = $receiverMail;
                    $save_message->mes_status = "Active";
                    $save_message->mes_receiver_status = "New";
                    $save_message->mes_send_date = date('d/m/Y H:i:s');
                    $save_message->mes_tid = $tid;

                    $save_message->save();

                    $logs = new Activitity_log();
                    $logs->m_username = $userDetails->username;
                    $logs->m_action = "Send message";
                    $logs->m_status = "Successful";
                    $logs->m_details = "$userDetails->name, Send new message to user";
                    $logs->m_date = date('d/m/Y H:i:s');
                    $logs->m_uid = $userDetails->id;
                    $logs->m_ip = request()->ip;

                    $logs->save();
                    return response()->json([
                        'status' => 200,
                        'message' => 'Message sent Successfully',
                    ]);
                } else if (empty($check_admin)) {
                    $check_student_id = Student::where('st_admin_number', $request->message_to)->first();
                    if (!empty($check_student_id)) {
                        $send_to = $check_student_id->st_admin_number;
                        $receiverMail = $check_student_id->guardia_email;
                        $save_message = new MessageSystem();
                        $save_message->sender_user_id = $userDetails->id;
                        $save_message->receiver_user_id = $send_to;
                        $save_message->mes_nature = "Message";
                        $save_message->mes_title = $request['message_subject'];
                        $save_message->mes_body = $request['message_body'];
                        $save_message->mes_sender_name = $userDetails->name;
                        $save_message->mes_receiver_email = $receiverMail;
                        $save_message->mes_status = "Active";
                        $save_message->mes_receiver_status = "New";
                        $save_message->mes_send_date = date('d/m/Y H:i:s');
                        $save_message->mes_tid = $tid;

                        $save_message->save();
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Send message";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, Send new message to user";
                        $logs->m_date = date('d/m/Y H:i:s');
                        $logs->m_uid = $userDetails->id;
                        $logs->m_ip = request()->ip;

                        $logs->save();
                        return response()->json([
                            'status' => 200,
                            'message' => 'Message sent Successfully',
                        ]);
                    } else {
                        $save_message = new MessageSystem();
                        $save_message->sender_user_id = $userDetails->id;
                        $save_message->receiver_user_id = '';
                        $save_message->mes_nature = "Notification";
                        $save_message->mes_title = $request['message_subject'];
                        $save_message->mes_body = $request['message_body'];
                        $save_message->mes_sender_name = $userDetails->name;
                        $save_message->mes_receiver_email = " ";
                        $save_message->mes_status = "Failed";
                        $save_message->mes_receiver_status = "New";
                        $save_message->mes_send_date = date('d/m/Y H:i:s');
                        $save_message->mes_tid = $tid;

                        $save_message->save();
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Send message";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, Message to user failed";
                        $logs->m_date = date('d/m/Y H:i:s');
                        $logs->m_uid = $userDetails->id;
                        $logs->m_ip = request()->ip;

                        $logs->save();
                        return response()->json([
                            'status' => 200,
                            'message' => 'Message sent Successfully',
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

    // get message base on ID passed for read message page here...
    public function getReadMessage($id)
    {
        if (auth('sanctum')->check()) {
            $message_read = MessageSystem::where('mes_status', '!=', 'Deleted')
                ->where('mes_tid', $id)
                ->get();
            $message_reads = MessageSystem::where('mes_status', '!=', 'Deleted')
                ->where('mes_tid', $id)
                ->first();
            MessageSystem::query()
                ->where('mes_tid', $id)
                ->update([
                    'mes_status' => 'Read',
                ]);
            if ($message_read) {
                return response()->json([
                    'status' => 200,
                    'readMessage' => $message_read,
                    'fetchMessage' => $message_reads,
                ]);
            } else if (empty($message_read)) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Error while fetching message...',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // fetch student result details to view by teacher
    public function fetchStudentResult(Request $request)
    {
        if (auth('sanctum')->check()) {

            $request->validate([
                'school_year' => 'required',
                'school_term' => 'required',
                'school_class' => 'required',
            ], [
                'school_year.required' => 'Academic Year is required',
                'school_term.required' => 'Academic Term is required',
                'school_class.required' => 'Class is required',
            ]);

            // here start to check / process the user result details
            $checkResult = ResultTable::where('academic_year', $request->school_year)
                ->where('academy_term', $request->school_term)
                ->where('class', $request->school_class)
                ->where('result_status', 'Active')
                ->first();

            if (empty($checkResult)) {
                return response()->json([
                    'status' => 404,
                    'message' => "No result record found",
                ]);
            } else if (!empty($checkResult)) {
                // get the interpretation name here...
                $resultClassName = ClassModel::where('id', $request->school_class)->first();
                $resultYearName = AcademicSession::where('id', $request->school_year)->first();
                $resultTermName = TermModel::where('id', $request->school_term)->first();

                $fetchResult = ResultTable::where('academic_year', $request->school_year)
                    ->where('academy_term', $request->school_term)
                    ->where('class', $request->school_class)
                    ->where('result_status', 'Active')
                    ->groupBy('admin_number')
                    ->get();


                $grades = DB::table('result_tables')
                    ->selectRaw('sum(total_scores) as exam_total, sum(tca_score) as ca_total, sum(exam_scores) as user_exam_total, id, tca_score, total_scores, exam_scores,
                     admin_number,student_name, academic_year, academy_term, class,
                     school_category, username, result_status, tid_code')
                    ->where('academic_year', '=', $request->school_year)
                    ->where('academy_term', '=', $request->school_term)
                    ->where('class', '=', $request->school_class)
                    ->groupBy('admin_number')
                    ->orderBy('exam_total', 'desc')
                    ->get();
                if ($grades) {
                    return response()->json([
                        'status' => 200,
                        'allDetails' => [
                            'student_resultDetails' => $grades,
                            'classDetails' => $resultClassName,
                            'termDetails' => $resultTermName,
                            'yearDetails' => $resultYearName,
                        ]
                    ]);
                } else {
                    return response()->json([
                        'status' => 403,
                        'message' => 'Something went wrong, try again',
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
    // fetch student result details to view complete result here...
    public function fetchViewResult($id)
    {
        $userDetails = auth('sanctum')->user();
        //dd($request->all());

        if (auth('sanctum')->check()) {
            $checkResult = ResultTable::where('id', $id)
                ->first();

            // get the interpretation name here...
            $resultClassName = ClassModel::where('id', $checkResult->class)->first();
            $resultYearName = AcademicSession::where('id', $checkResult->academic_year)->first();
            $resultTermName = TermModel::where('id', $checkResult->academy_term)->first();

            // get student details here...
            $studentDetails = Student::where('st_admin_number', $checkResult->admin_number)
                ->where('acct_status', 'Active')
                ->first();
            // $year = $request->school_year;
            // $class = $request->class;
            // $term = $request->school_term;

            if (empty($checkResult)) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Sorry, No result found',
                ]);
            } else if (!empty($checkResult)) {
                $fetchResult = ResultTable::where('academic_year', $checkResult->academic_year)
                    ->where('academy_term', $checkResult->academy_term)
                    ->where('class', $checkResult->class)
                    ->where('admin_number', $studentDetails->st_admin_number)
                    ->where('result_status', 'Active')
                    ->get();
                // count the number of subject offered by the user here...
                $subjectCount = DB::table('result_tables')
                    ->selectRaw('count(subject) as total_subject,
                 admin_number,academic_year, academy_term, class,
                result_status')
                    ->where('academic_year', $checkResult->academic_year)
                    ->where('academy_term', $checkResult->academy_term)
                    ->where('class', $checkResult->class)
                    ->where('admin_number', $studentDetails->st_admin_number)
                    ->first();
                // get total sum of all subjects offered by the user here...
                $grandScore = DB::table('result_tables')
                    ->selectRaw('sum(total_scores) as user_total, total_scores,
                 admin_number,student_name, academic_year, academy_term, class,
                  result_status')
                    ->where('academic_year', $checkResult->academic_year)
                    ->where('academy_term', $checkResult->academy_term)
                    ->where('class', $checkResult->class)
                    ->where('admin_number', $studentDetails->st_admin_number)
                    ->where('result_status', 'Active')
                    ->first();
                // get the user class position here...
                $classPosition = StudentPosition::where('sch_year', $checkResult->academic_year)
                    ->where('sch_term', $checkResult->academy_term)
                    ->where('sch_class', $checkResult->class)
                    ->where('stu_admin_number', $studentDetails->st_admin_number)
                    ->where('p_status', 'Active')
                    ->where('stu_admin_number', '!=', null)
                    ->first();
                if (!empty($classPosition)) {
                    if ($classPosition->position == 1 || $classPosition->position == 21 || $classPosition->position == 31 || $classPosition->position == 41 || $classPosition->position == 51) {
                        $myposition = $classPosition->position . 'st';
                    } else if ($classPosition->position == 2 || $classPosition->position == 22 || $classPosition->position == 32 || $classPosition->position == 42 || $classPosition->position == 52) {
                        $myposition = $classPosition->position . 'nd';
                    } else if ($classPosition->position == 23 || $classPosition->position == 3 || $classPosition->position == 33 || $classPosition->position == 43 || $classPosition->position == 53) {
                        $myposition = $classPosition->position . 'rd';
                    } else {
                        $myposition = $classPosition->position . 'th';
                    }
                } else {
                    $myposition = "";
                }
                // get school opening and closing date here ...
                $school_start = SchoolResumption::where('school_year', $checkResult->academic_year)
                    ->where('school_term', $checkResult->academy_term)
                    ->where('status', 'Active')
                    ->first();
                if (!empty($school_start)) {
                    $schStart = $school_start;
                } else {
                    $schStart = '';
                }

                // get number of days school opened here ...
                $school_open = DaysSchoolOpen::where('open_year', $checkResult->academic_year)
                    ->where('open_term', $checkResult->academy_term)
                    ->where('open_status', 'Active')
                    ->first();
                if (!empty($school_open)) {
                    $openingSchool = $school_open;
                } else {
                    $openingSchool = '';
                }
                // get attendance of the student here ...
                $attendance_count = Attendance::where('atten_year', $checkResult->academic_year)
                    ->where('atten_term', $checkResult->academy_term)
                    ->where('atten_class', $checkResult->class)
                    ->where('atten_admin_no', $studentDetails->st_admin_number)
                    ->where('atten_status', 'Active')
                    ->get();
                if (!empty($attendance_count)) {

                    $myAttendance = count($attendance_count);
                } else {
                    $myAttendance = '';
                }
                // class average here...
                $student_count = Student::where('class_apply', $checkResult->class)
                    ->where('acct_status', 'Active')
                    ->get();
                if (!empty($student_count)) {

                    $totalStudent_inClass = count($student_count);
                    $average =  ($grandScore->user_total / $totalStudent_inClass);
                } else {
                    $average = '';
                }
                // student average in exam here...
                $student_subject = ResultTable::where('academic_year', $checkResult->academic_year)
                    ->where('academy_term', $checkResult->academy_term)
                    ->where('admin_number', $studentDetails->st_admin_number)
                    ->where('result_status', 'Active')
                    ->groupBy('subject')
                    ->get();
                if (!empty($student_subject)) {

                    $my_total_subject = count($student_subject);
                    //$average =  ($grandScore->user_total / $totalStudent_inClass);
                } else {
                    $my_total_subject = '';
                }
                // get teacher comment here ...
                $teacherComment = StudentComment::where('comm_year', $resultYearName->academic_name)
                    ->where('comm_term', $resultTermName->term_name)
                    ->where('comm_class', $resultClassName->class_name)
                    ->where('comm_stu_number', $studentDetails->st_admin_number)
                    ->where('comm_status', 'Active')
                    ->where('comm_teacher', '!=', '')
                    ->first();

                // get principle comment here ...
                $principleComment = StudentComment::where('comm_year', $resultYearName->academic_name)
                    ->where('comm_term', $resultTermName->term_name)
                    ->where('comm_class', $resultClassName->class_name)
                    ->where('comm_stu_number', $studentDetails->st_admin_number)
                    ->where('comm_status', 'Active')
                    ->where('comm_prin_comment', '!=', '')
                    ->first();

                // get student psychomotor domain details here...
                $psychomotorDomain = PsychomotoDomian::where('aff_year', $checkResult->academic_year)
                    ->where('aff_term', $checkResult->academy_term)
                    ->where('aff_class', $checkResult->class)
                    ->where('aff_admin_number', $studentDetails->st_admin_number)
                    ->where('aff_status', 'Active')
                    ->first();

                // $count_student = ResultTable::where('tid_code', $recordID)->count('id');
                return response()->json([
                    'status' => 200,
                    'result_info' => [
                        'resultDetails' => $fetchResult,
                        'classDetails' => $checkResult->class,
                        'studentDetail' => $studentDetails,
                        'term' => $resultTermName,
                        'year' => $resultYearName,
                        'class' => $resultClassName,
                        'subject_offer' => $subjectCount,
                        'grand_score' => $grandScore,
                        'class_position' => $myposition,
                        'comment_teacher' => $teacherComment,
                        'comment_prin' => $principleComment,
                        'psychomotor' => $psychomotorDomain,
                        'sch_open' => $openingSchool,
                        'sch_start' => $schStart,
                        'attendance_count' => $myAttendance,
                        'classAverage' => $totalStudent_inClass,
                        'subject_total' => $my_total_subject,
                    ]
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // save system setup here...
    public function settingDetails(Request $request)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();

            $request->validate([
                'sch_name' => 'required',
                'sch_name_short' => 'required',
                'sch_phone' => 'required',
            ], [
                'sch_name.required' => 'School Name is required',
                'sch_name_short.required' => 'School Short Name is required',
                'sch_phone.required' => 'School Phone Number required',
            ]);

            // check if information in the database before you insert
            $checkSetting  = SystemSetup::where('id', '!=', null)->first();
            if (empty($checkSetting)) {
                $save_setting = new SystemSetup();
                $save_setting->sch_name =  $request->sch_name;
                $save_setting->sch_name_short = $request->sch_name_short;
                $save_setting->sch_phone = $request->sch_phone;
                $save_setting->sch_email = $request->sch_email;
                $save_setting->add_date = date('d/m/Y H:i:s');
                $save_setting->addby = $userDetails->username;
                $save_setting->app_status = "Active";
                $save_setting->save();
                // history record here...
                $logs = new Activitity_log();
                $logs->m_action = "Save system setting";
                $logs->m_status = "Successful";
                $logs->m_details = $userDetails->name . ", Save new system details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_ip = request()->ip;
                $logs->m_uid = $userDetails->id;
                $logs->save();
                return response()->json([
                    'status' => 200,
                    'message' => "Setting save successfully",
                ]);
            } else if (!empty($checkSetting)) {
                $checkSetting->sch_name = $request->sch_name;
                $checkSetting->sch_name_short = $request->sch_name_short;
                $checkSetting->sch_phone = $request->sch_phone;
                $checkSetting->sch_email = $request->sch_email;
                $checkSetting->add_date = date('d/m/Y H:i:s');
                $checkSetting->addby = $userDetails->username;
                $checkSetting->app_status = "Active";

                $checkSetting->update();
                return response()->json([
                    'status' => 200,
                    'message' => "Setting updated successfully",
                ]);
                $logs = new Activitity_log();
                $logs->m_action = "Update system setting";
                $logs->m_status = "Successful";
                $logs->m_details = $userDetails->name . ", Update new system details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_ip = request()->ip;
                $logs->m_uid = $userDetails->id;
                $logs->save();
            }
            //dd($request->all());
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // fetch setting details here...
    public function fetchAllSetting()
    {
        if (auth('sanctum')->check()) {

            $user_details = auth('sanctum')->user();
            // $fetch_details = SystemSetup::all()->first();
            $fetch_details = SystemSetup::where('app_status', 'Active')->first();

            if (!empty($fetch_details)) {
                return response()->json([
                    'status' => 200,
                    'setting_record' => $fetch_details,
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

    // upload school logo here ...

    public function uploadSchoolLogo(Request $request)
    {
        if (auth('sanctum')->check()) {
            $file_size = 0;
            //dd($request->all());
            $userDetails = auth('sanctum')->user();
            $checkSettingLogo  = SystemSetup::where('app_status', 'Active')->first();
            if (!empty($checkSettingLogo)) {
                if ($request->hasFile('image')) {

                    $file = $request->file('image');
                    $file_size += $file->getSize();
                    $file_size = number_format($file_size / 2048576, 2);
                    //print_r($file_size.' MB');	
                    //dd($file_size);
                    if ($file_size > 2045) {
                        return response()->json([
                            'status' => 405,
                            'message' => 'File too large!',
                        ]);
                    }
                    /* check if the previous image exist then delete before uplaoding new one */
                    $path = $checkSettingLogo->sch_logo; // this image colunm already have the image path in the database
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                    /* image deleting ends here --*/
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->move('uploads/images_folder/', $filename);
                    $checkSettingLogo->sch_logo = 'uploads/images_folder/' . $filename;
                }
                $checkSettingLogo->app_status = "Active";
                $checkSettingLogo->add_date = date('d/m/Y H:i:s');
                // now update everything here...
                $checkSettingLogo->update();
                // history record here...
                $logs = new Activitity_log();
                $logs->m_action = "Update Logo";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Updated school logo details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_ip = request()->ip;
                $logs->m_uid = $userDetails->id;
                $logs->save();
                return response()->json([
                    'status' => 200,
                    'message' => "Logo Updated successfully",
                ]);
            } else if (empty($checkSettingLogo)) {
                $save_setting_logo = new SystemSetup();
                if ($request->hasFile('image')) {

                    $file = $request->file('image');
                    $file_size += $file->getSize();
                    $file_size = number_format($file_size / 2048576, 2);
                    //print_r($file_size.' MB');	
                    //dd($file_size);
                    if ($file_size > 2045) {
                        return response()->json([
                            'status' => 405,
                            'message' => 'File too large!',
                        ]);
                    }
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->move('uploads/images_folder/', $filename);
                    $save_setting_logo->sch_logo = 'uploads/images_folder/' . $filename;
                }
                $save_setting_logo->app_status = "Active";
                $save_setting_logo->add_date = date('d/m/Y H:i:s');
                $save_setting_logo->save();

                $logs = new Activitity_log();
                $logs->m_action = "Added Logo";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Added school logo details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_ip = request()->ip;
                $logs->m_uid = $userDetails->id;
                $logs->save();
                return response()->json([
                    'status' => 200,
                    'message' => "Logo uploaded successfully",
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

    // upload school banner here...
    public function uploadSchoolBanner(Request $request)
    {
        if (auth('sanctum')->check()) {
            $file_size = 0;
            //dd($request->all());
            $userDetails = auth('sanctum')->user();
            $checkSettingBanner  = SystemSetup::where('app_status', 'Active')->first();
            if (!empty($checkSettingBanner)) {
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $file_size += $file->getSize();
                    $file_size = number_format($file_size / 2048576, 2);
                    //print_r($file_size.' MB');	
                    //dd($file_size);
                    if ($file_size > 2045) {
                        return response()->json([
                            'status' => 405,
                            'message' => 'File too large!',
                        ]);
                    }
                    /* check if the previous image exist then delete before uplaoding new one */
                    $path = $checkSettingBanner->sch_banner; // this image colunm already have the image path in the database
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                    /* image deleting ends here --*/
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->move('uploads/images_folder/', $filename);
                    $checkSettingBanner->sch_banner = 'uploads/images_folder/' . $filename;
                }
                $checkSettingBanner->app_status = "Active";
                $checkSettingBanner->add_date = date('d/m/Y H:i:s');
                // now update everything here...
                $checkSettingBanner->update();
                // history record here...
                $logs = new Activitity_log();
                $logs->m_action = "Update Banner";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Updated school banner details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_ip = request()->ip;
                $logs->m_uid = $userDetails->id;
                $logs->save();
                return response()->json([
                    'status' => 200,
                    'message' => "Logo Updated successfully",
                ]);
            } else if (empty($checkSettingBanner)) {
                $save_setting_banner = new SystemSetup();
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $file_size += $file->getSize();
                    $file_size = number_format($file_size / 2048576, 2);
                    //print_r($file_size.' MB');	
                    //dd($file_size);
                    if ($file_size > 2045) {
                        return response()->json([
                            'status' => 405,
                            'message' => 'File too large!',
                        ]);
                    }
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->move('uploads/images_folder/', $filename);
                    $save_setting_banner->sch_banner = 'uploads/images_folder/' . $filename;
                }
                $save_setting_banner->app_status = "Active";
                $save_setting_banner->add_date = date('d/m/Y H:i:s');
                $save_setting_banner->save();

                $logs = new Activitity_log();
                $logs->m_action = "Added Banner";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Added school banner details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_ip = request()->ip;
                $logs->m_uid = $userDetails->id;
                $logs->save();
                return response()->json([
                    'status' => 200,
                    'message' => "Banner uploaded successfully",
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