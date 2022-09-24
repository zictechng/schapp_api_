<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Activitity_log;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\DaysSchoolOpen;
use App\Models\GeneratePin;
use App\Models\MessageSystem;
use App\Models\PsychomotoDomian;
use App\Models\ResultCA;
use App\Models\ResultTable;
use App\Models\SchoolCategory;
use App\Models\SchoolResumption;
use App\Models\Staff;
use App\Models\Student;
use App\Models\StudentComment;
use App\Models\StudentPosition;
use App\Models\Subject;
use App\Models\SubmitAssignment;
use App\Models\SystemSetup;
use App\Models\TermModel;
use App\Models\TestSave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Stevebauman\Location\Facades\Location;

class StudentController extends Controller
{

    // fetch all student details here...
    public function getAllStudent()
    {

        //$all_details = Student::where('acct_status', 'Active')->orderByDesc('id')->get();
        $all_details = Student::query()
            ->where('acct_status', '=', 'Active')
            ->orderByDesc('id')
            ->paginate('15');
        if ($all_details) {
            return response()->json([
                'status' => 200,
                'student_record' => $all_details,
            ]);
        } else if (empty($all_details)) {
            return response()->json([
                'status' => 404,
                'message' => 'No record fund',
            ]);
        }
    }

    public function getResultStudent()
    {
        $all_student_details = Student::where('acct_status', 'Active')->orderByDesc('id')->get();
        if ($all_student_details) {
            return response()->json([
                'status' => 200,
                'student_record' => $all_student_details,
            ]);
        } else if (empty($all_details)) {
            return response()->json([
                'status' => 404,
                'message' => 'No record fund',
            ]);
        }
    }

    //fetch all class/ school year/ school category here

    public function getAllDetails()
    {
        if (auth('sanctum')->check()) {
            $all_session = AcademicSession::where('a_status', 'Active')->get();

            $all_category = SchoolCategory::where('sc_status', 'Active')->get();

            $all_class = ClassModel::where('status', 'Active')->get();
            $all_subject = Subject::where('sub_status', 'Active')->get();
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

    public function getClassDetails()
    {
        if (auth('sanctum')->check()) {
            $allclass = ClassModel::where('status', 'Active')->get();

            return response()->json([
                'status' => 200,
                'all_classes' => $allclass
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    public function getCategoryDetails()
    {
        if (auth('sanctum')->check()) {
            $allcategory = SchoolCategory::where('sc_status', 'Active')->get();

            return response()->json([
                'status' => 200,
                'all_category' => $allcategory
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }
    // function to save new student details
    public function saveStudent(Request $request)
    {
        if (auth('sanctum')->check()) {

            $validator = Validator::make($request->all(), [
                'surname' => 'required|max:191',
                'other_name' => 'required|max:191',
                'dob' => 'required|max:191',
                'class_apply' => 'required|max:191',
                'academic_year' => 'required|max:191',
                'school_category' => 'required|max:191',
                'admission_number' => 'required|max:191',
                'guardian_phone' => 'required|max:191',
                'guardian_address' => 'required|max:191',

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
                $check_record = Student::where('st_admin_number', $request->admission_number)->where('acct_status', 'Active')->first();
                if (!empty($check_record)) {
                    return response()->json([
                        'status' => 402,
                        'message' => 'Student Admission Number exist',
                    ]);
                } else if (empty($check_record)) {
                    $save_new = new Student();
                    $save_new->surname = $request['surname'];
                    $save_new->other_name = $request['other_name'];
                    $save_new->sex = $request['sex'];
                    $save_new->dob = $request['dob'];
                    $save_new->st_age = $my_age;
                    $save_new->state = $request['state'];
                    $save_new->st_password = Hash::make('12345678');
                    $save_new->lga = $request['lga'];
                    $save_new->country = $request['country'];
                    $save_new->last_sch_attend = $request['last_sch_attend'];
                    $save_new->last_class_attend = $request['last_class_attend'];
                    $save_new->class_apply = $request['class_apply'];
                    $save_new->schooling_type = $request['school_type'];
                    $save_new->academic_year = $request['academic_year'];
                    $save_new->school_category = $request['school_category'];
                    $save_new->st_admin_number = $request['admission_number'];
                    $save_new->guardia_name = $request['guardian_name'];
                    $save_new->guardia_email = $request['guardian_email'];
                    $save_new->guardia_number = $request['guardian_phone'];
                    $save_new->guardia_address = $request['guardian_address'];
                    $save_new->staff_zone = $request['staff_office_zone'];
                    $save_new->staff_depart = $request['staff_department'];
                    $save_new->staff_rank = $request['staff_rank'];
                    $save_new->staff_file_no = $request['staff_no'];
                    $save_new->health_issue = $request['health_issues'];
                    $save_new->reg_date = date('d/m/Y H:i:s');
                    $save_new->acct_status = 'Active';
                    $save_new->acct_action = $userDetails->username;

                    $save_new->save();
                    $reg_student = Student::where('st_admin_number', $request['admission_number'])->first();
                    // create student detail in user table
                    $save_user = new User();
                    $save_user->name = $request['surname'] . ' ' . $request['other_name'];
                    $save_user->email = $request['guardian_email'];
                    $save_user->username =  $request['admission_number'];
                    $save_user->acct_status = "Active";
                    $save_user->student_id = $reg_student->id;
                    $save_user->password = Hash::make('12345678');
                    $save_user->reg_date = date('d/m/Y H:i:s');
                    $save_user->role = "Student";
                    $save_user->phone = $request['guardian_phone'];

                    $save_user->save();

                    if ($save_new->save()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Registered student details";
                        $logs->m_status = "Successful";
                        $logs->m_details = $userDetails->name . " added new student details";
                        $logs->m_date = date('d/m/Y H:i:s');
                        $logs->m_uid = $userDetails->id;
                        $logs->m_ip = request()->ip;

                        $logs->save();
                        return response()->json([
                            'status' => 200,
                            'message' => 'Student Details Added Successfully',
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

    // fetch student details from the edit button click
    public function fetchStudent($id)
    {
        if (auth('sanctum')->check()) {
            $getDetails = Student::where('id', $id)->where('acct_status', 'Active')->first();
            if ($getDetails) {
                return response()->json([
                    'status' => 200,
                    'edit_Details' => $getDetails,
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

    //update student record here...
    public function updateStudent(Request $request, $id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();

            $validator = Validator::make($request->all(), [
                'surname' => 'required|max:191',
                'other_name' => 'required|max:191',
                'dob' => 'required|max:191',
                'class_apply' => 'required|max:191',
                'academic_year' => 'required|max:191',
                'schooling_type' => 'required|max:191',
                'st_admin_number' => 'required|max:191',
                'guardia_number' => 'required|max:191',
                'guardia_address' => 'required|max:191',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {
                $find_user = Student::find($id);

                if (empty($find_user)) {
                    return response()->json([
                        'status' => 402,
                        'message' => 'Record not found',
                    ]);
                } else if (!empty($find_user)) {
                    $find_user->update([
                        'surname' => $request->surname,
                        'other_name' => $request->other_name,
                        'sex' => $request->sex,
                        'dob' => $request->dob,
                        'st_age' => $request->st_age,
                        'state' => $request->state,
                        'country' => $request->country,
                        'last_sch_attend' => $request->last_sch_attend,
                        'last_class_attend' => $request->last_class_attend,
                        'class_apply' => $request->class_apply,
                        'schooling_type' => $request->schooling_type,
                        'academic_year' => $request->academic_year,
                        'st_admin_number' => $request->st_admin_number,
                        'guardia_name' => $request->guardia_name,
                        'guardia_email' => $request->guardia_email,
                        'guardia_number' => $request->guardia_number,
                        'guardia_address' => $request->guardia_address,
                        'staff_zone' => $request->staff_zone,
                        'staff_depart' => $request->staff_depart,
                        'staff_rank' => $request->staff_rank,
                        'health_issue' => $request->health_issue,
                        'staff_file_no' => $request->staff_file_no,
                    ]);
                    if ($find_user->update()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Update student details";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, updated student details";
                        $logs->m_date = date('d/m/Y H:i:s');
                        $logs->m_uid = $find_user->id;
                        $logs->m_ip = request()->ip;

                        $logs->save();
                        return response()->json([
                            'status' => 200,
                            'message' => 'Student Details Updated Successfully',
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

    // delete student details here...
    public function deleteStudent($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();

            $student_record = Student::find($id);
            if (!empty($student_record)) {
                $student_record->update([
                    'acct_status' => 'Deleted',
                ]);
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Deleted student record";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Delete student info details";
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

    // update student profile image here....
    public function updateProfileImage(Request $request, $id)
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
                $find_user = Student::find($id);

                if (empty($find_user)) {
                    return response()->json([
                        'status' => 402,
                        'message' => 'User not found',
                    ]);
                } else if (!empty($find_user)) {
                    /* this check if there is an image the uploade or do not process */
                    if ($request->hasFile('image')) {
                        /* check if the previous image exist then delete before uplaoding new one */
                        $path = $find_user->st_image; // this image colunm already have the image path in the database
                        if (File::exists($path)) {
                            File::delete($path);
                        }
                        /* image deleting ends here --*/

                        $file = $request->file('image');
                        $extension = $file->getClientOriginalExtension();
                        $filename = time() . '.' . $extension;
                        $file->move('uploads/student_image/', $filename);
                        $find_user->st_image = 'uploads/student_image/' . $filename;
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
                        $logs->m_action = "Update student profile image";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, updated student account profile picture";
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

    //texting  message sending...
    public function saveText(Request $request)
    {
        //dd($request->all());

        //dd($values);
        $validator = Validator::make(
            $request->all(),
            [
                'message' => 'required',
                'email_address' => 'required',
                'class' => 'required',
            ],
            [
                'message.required' => 'Message Required',
                'email_address.required' => 'Email Required',
                'class.required' => 'Class Required',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                $errors = $validator->errors(),
                'status' => 422,
                'errors' => $errors,
            ]);
        } else {
            /* Generate unique transaction ID for each cash request record */
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $tid = substr(str_shuffle($permitted_chars), 0, 16);
            // dd($request,$request->get('state'));
            $fff = (object)$request->all('data');

            // state split value and save independently
            $values = explode(",", $request->state);
            $state_inserts = [];
            foreach ($values as $bid) {
                $state_inserts[] =
                    [
                        'state_details' => $bid,
                        'status' => 'Active',
                        'tran_code' => $tid,
                        'message_details' => $request->message,
                        'email' => $request->email_address,
                        'reg_date' => date('d/m/Y H:i:s'),
                    ];
            }
            // save all the operation here
            DB::table('test_saves')->insert($state_inserts);

            // class split value and save independently
            $value_class = explode(",", $request->class);
            $insert_class = [];
            foreach ($value_class as $bid_class) {
                $insert_class[] =
                    [
                        'class_details' => $bid_class,
                        'status' => 'Active',
                        'tran_code' => $tid,
                        'message_details' => $request->message,
                        'email' => $request->email_address,
                        'reg_date' => date('d/m/Y H:i:s'),
                    ];
            }
            DB::table('test_saves')->insert($insert_class);

            // this will save in single colum in the database.
            $data = new TestSave();
            $data->class_details = $request->class;
            $data->state_details = $request->state;
            $data->message_details = $request->message;
            $data->status = "Pending";
            $data->tran_code = $tid;
            $data->email = $request->email_address;
            $data->reg_date = date('d/m/Y H:i:s');
            $data->save();

            // history record here...
            $logs = new Activitity_log();
            $logs->m_action = "Test multiple saving added";
            $logs->m_status = "Successful";
            $logs->m_date = date('d/m/Y H:i:s');
            $logs->m_ip = request()->ip;
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
        }
    }
    // fetch state and class here...
    public function fetchState()
    {

        $allState = TestSave::where('status', 'Active')->get();
        $allClass = TestSave::where('status', 'Active')->get();

        // fetch state details here to show in front end
        // $state_all = DB::table('test_saves')
        //     ->selectRaw('id, state_details')
        //     ->where('status', '=', 'Active')
        //     ->where('state_details', '!=', '')
        //     ->get();
        // // fetch class details here to show in front end
        // $allClass = DB::table('test_saves')
        //     ->selectRaw('id, class_details')
        //     ->where('status', '=', 'Active')
        //     ->where('class_details', '!=', '')
        //     ->get();
        return response()->json([
            'status' => 200,
            'allsDetails' => [
                'all_state' => $allState,
                'all_class' => $allClass,
            ]

        ]);
    }

    public function fetchAllState()
    {
        $allStates = TestSave::where('status', 'Active')->get();
        $grade = DB::table('test_saves')
            ->selectRaw('id,state_details')
            ->where('status', '=', 'Active')
            ->get();
        //$allClass = TestSave::where('status', 'Active')->get();
        $mystate = $grade;

        $values = explode(",", $mystate);

        return response()->json([
            'status' => 200,
            'all_state' => $values,

        ]);
    }
    private function array_map_assoc($array)
    {
        $r = array();
        foreach ($array as $key => $value)
            $r[$key] = "$value";
        return $r;
    }

    // fetch class here....
    public function fetchClass()
    {
        if (auth('sanctum')->check()) {
            $allclass = ClassModel::where('status', 'Active')->get();

            return response()->json([
                'status' => 200,
                'all_classes' => $allclass
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }
    // update student password details here...
    public function updateStudentPassword(Request $request)
    {
        //dd($request->all());
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
                $find_staff = User::where('student_id', $request->id)
                    ->first();

                if (empty($find_staff)) {
                    return response()->json([
                        'status' => 402,
                        'message' => 'Record not found',
                    ]);
                } else if (!empty($find_staff)) {
                    $find_staff->update([
                        'password' => Hash::make($request->new_password),
                    ]);
                    if ($find_staff->update()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Update student password";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, student password details updated";
                        $logs->m_date = date('d/m/Y H:i:s');
                        $logs->m_uid = $find_staff->id;
                        $logs->m_ip = request()->ip;

                        $logs->save();
                        return response()->json([
                            'status' => 200,
                            'message' => 'Student Password Updated Successfully',
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

    // fetch login student profile here...
    public function fetchStudentProfile()
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $getMyDetails = Student::where('id', auth('sanctum')->user()->student_id)->where('acct_status', 'Active')->first();

            // get assignment assigned
            $myAssign_details = Assignment::where('assign_class_id', $getMyDetails->class_apply)
                ->get();

            // get assignment assigned
            $myClassName = ClassModel::where('id', $getMyDetails->class_apply)
                ->first();

            $get_myMessage = DB::table('message_systems')
                ->selectRaw('count(id) as total_message')
                ->where('mes_status', 'Active')
                ->where('receiver_user_id', $userDetails->id)
                ->first();

            $ldate = date('Y-m-d');
            $get_myClassBirthday = DB::table('students')
                ->selectRaw('count(id) as birth_total')
                ->where('dob', '=', $ldate)
                ->where('class_apply', $getMyDetails->class_apply)
                ->first();
            $get_Assignment = DB::table('assignments')
                ->selectRaw('count(id) as all_assign_home')
                ->where('assign_class_id', $getMyDetails->class_apply)
                ->first();

            if ($getMyDetails) {
                return response()->json([
                    'status' => 200,
                    'student_profileDetails' => $getMyDetails,
                    'get_myAssignment' => $get_Assignment,
                    'get_myclassBirthday' => $get_myClassBirthday,
                    'get_mymessage' => $get_myMessage,
                    'get_assignmentDetails' => $myAssign_details,
                    'get_className' => $myClassName,
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
    // fetch logs activities here...
    public function fetchMyLog()
    {
        // get logged staff details here
        $get_AllLog = Activitity_log::query()
            ->orderByDesc('id')
            ->where('m_uid', '=', auth('sanctum')->user()->id)
            ->paginate('5');
        return response()->json([
            'status' => 200,
            'myLog' => $get_AllLog,
        ]);
    }

    // student password update here...
    public function updateMyPassword(Request $request)
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
                $find_student = User::where('id', $userDetails->id)
                    ->first();
                //dd($find_student);
                if (empty($find_student)) {
                    return response()->json([
                        'status' => 402,
                        'message' => 'Record not found',
                    ]);
                } else if (!empty($find_student)) {
                    $find_student->update([
                        'password' => Hash::make($request->new_password),
                    ]);
                    if ($find_student->update()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Update password";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, student password details updated";
                        $logs->m_date = date('d/m/Y H:i:s');
                        $logs->m_uid = $find_student->id;
                        $logs->m_ip = request()->ip;

                        $logs->save();
                        return response()->json([
                            'status' => 200,
                            'message' => 'Password Updated Successfully',
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

    // All my notification here...
    public function myAllNotification()
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $staff_details = MessageSystem::where('receiver_user_id', auth('sanctum')->user()->student_id)
                ->first();
            $message_active = MessageSystem::where('mes_status', '!=', 'Deleted')
                ->where('receiver_user_id', auth('sanctum')->user()->student_id)
                ->get();
            $message_active_count = $message_active->count();

            $message_delete = MessageSystem::where('mes_status', '=', 'Deleted')
                ->where('receiver_user_id', auth('sanctum')->user()->student_id)
                ->get();
            $message_delete_count = $message_delete->count();

            // failed message here...
            $message_failed = MessageSystem::where('mes_status', '=', 'Failed')
                ->where('sender_user_id', auth('sanctum')->user()->student_id)
                ->get();
            $message_failed_count = $message_failed->count();

            $get_AllMyMessage = MessageSystem::query()
                ->orderByDesc('id')
                ->where('receiver_user_id', '=', auth('sanctum')->user()->student_id)
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

    // fetch my assignment here...
    public function myAssignment()
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $getMyDetails = Student::where('id', auth('sanctum')->user()->student_id)
                ->where('acct_status', 'Active')->first();
            $all_assign_details = Assignment::query()
                ->where('assign_status', '!=', 'Deleted')
                ->Where('assign_status', '!=', 'Pending')
                ->where('assign_class_id',  $getMyDetails->class_apply)
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

    // submit assignment here by student here...
    public function submitAssignment(Request $request)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $request->validate([
                'message_body' => 'required',
            ], [
                'message_body.required' => 'Message is required',
            ]);

            $file_size = 0;
            $file_path = 'http://localhost:8000/uploads/assignment_folder/';
            /* Generate unique transaction ID for each cash request record */
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $tid = substr(str_shuffle($permitted_chars), 0, 16);
            // dd($request,$request->get('state'));
            $assignment_details = Assignment::where('assign_tid', $request->record_id)->first();

            //$uploade_path = $path;
            $data = new SubmitAssignment();
            $data->assign_id = $assignment_details->id;
            $data->student_id = $userDetails->student_id;
            $data->teacher_id = $assignment_details->addby_user_id;
            $data->assign_code = $assignment_details->assign_tid;

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
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('uploads/assignment_folder/', $filename);
                $data->assign_file_path = 'http://localhost:8000/uploads/assignment_folder/' . $filename;
                $data->assign_file_name = $filename;
            }
            $data->assign_message = $request->message_body;
            $data->assign_scores = "";
            $data->assign_remark = "";
            $data->assign_status = "Submitted";
            $data->assign_submit_date = date('d/m/Y H:i:s');
            $data->assign_updated_date = "";
            $data->assign_submit_code = $tid;
            $data->save();

            $check_admin = Staff::where('id', $assignment_details->addby_user_id)->first();

            if (!empty($check_admin)) {
                $check_admin->id;
                //$receiverMail = $check_student_id->guardia_email;
                $receiverMail = $check_admin->email;
                $save_message = new MessageSystem();
                $save_message->sender_user_id = $userDetails->id;
                $save_message->receiver_user_id = $check_admin->id;
                $save_message->mes_nature = "Message";
                $save_message->mes_title = "Assignment Notification";
                $save_message->mes_body = $userDetails->name . ', Submitted assignment which you need to review and rate base on the performance.';
                $save_message->mes_sender_name = $userDetails->name;
                $save_message->mes_receiver_email = $receiverMail;
                $save_message->mes_status = "Active";
                $save_message->mes_receiver_status = "New";
                $save_message->mes_send_date = date('d/m/Y H:i:s');
                $save_message->mes_tid = $tid;

                $save_message->save();
            }
            // history record here...
            $logs = new Activitity_log();
            $logs->m_action = "Submitted Assignment";
            $logs->m_status = "Successful";
            $logs->m_details = "$userDetails->name, Submit assignment details";
            $logs->m_date = date('d/m/Y H:i:s');
            $logs->m_ip = request()->ip;
            $logs->m_uid = $userDetails->id;
            $logs->save();
            if ($data->save()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Submitted successfully',
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

    // get assignment to reply here..
    public function getAssignmentToReply($id)
    {
        if (auth('sanctum')->check()) {
            $message_read = Assignment::where('assign_status', '!=', 'Deleted')
                ->where('assign_tid', $id)
                ->get();
            $message_reads = Assignment::where('assign_status', '!=', 'Deleted')
                ->where('assign_tid', $id)
                ->first();

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

    // result checker process goes here...
    public function resultChecker(Request $request)
    {
        $userDetails = auth('sanctum')->user();
        //dd($request->all());
        $request->validate([
            'school_year' => 'required',
            'school_term' => 'required',
            'class' => 'required',
            'card_pin' => 'required',
        ], [
            'school_year.required' => 'Academic Year is required',
            'school_term.required' => 'Academic Term is required',
            'class.required' => 'Class is required',
            'card_pin.required' => 'Checking Pin is required',
        ]);
        if (auth('sanctum')->check()) {
            $year = $request->school_year;
            $class = $request->class;
            $term = $request->school_term;
            $cardPin = $request->card_pin;

            // get student details here...
            $studentDetails = Student::where('id', $userDetails->student_id)
                ->where('acct_status', 'Active')
                ->first();

            $resultClassName = ClassModel::where('id', $request->class)->first();
            $resultYearName = AcademicSession::where('id', $request->school_year)->first();
            $resultTermName = TermModel::where('id', $request->school_term)->first();

            // check if scratch card is valid or not here...
            $scratch_card = GeneratePin::where('card_pin', $cardPin)
                ->where('card_status', 'Active')
                ->first();

            if (empty($scratch_card)) {
                return response()->json([
                    'status' => 402,
                    'message' => 'Invalid scratch card entered',
                ]);
            } else if (!empty($scratch_card)) {
                if ($scratch_card->card_usage_count > 1000) {
                    return response()->json([
                        'status' => 403,
                        'message' => 'Scratch card usage exceeded',
                    ]);
                }
                // if the card is used already and is not this user that use it
                else if ($scratch_card->card_usage_status == 'Used' && $scratch_card->card_use_username !== $studentDetails->st_admin_number) {
                    return response()->json([
                        'status' => 405,
                        'message' => 'Scratch card have been used',
                    ]);
                } else if ($scratch_card->card_use_username == "" && $scratch_card->card_status == 'Active') {
                    $scratch_card->update([
                        'card_use_username' => $studentDetails->st_admin_number,
                        'card_usage_status' => 'Used',
                    ]);
                }
                // increase the usage count here...
                $cardUsage_Count = ($scratch_card->card_usage_count + 1);
                // update the usage count here...
                $increaseCount = GeneratePin::where('card_pin', $cardPin)
                    ->where('card_status', 'Active')
                    ->first();
                if (!empty($increaseCount)) {
                    $increaseCount->update([
                        'card_usage_count' => $cardUsage_Count,
                        'card_use_date' => date('d/m/Y H:i:s'),
                    ]);
                }
                $year = $request->school_year;
                $class = $request->class;
                $term = $request->school_term;

                // here start to check / process the user result details
                $checkResult = ResultTable::where('academic_year', $request->school_year)
                    ->where('academy_term', $request->school_term)
                    ->where('class', $request->class)
                    ->where('admin_number', $studentDetails->st_admin_number)
                    ->where('result_status', 'Active')
                    ->first();

                if (empty($checkResult)) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Sorry, No result found',
                    ]);
                } else if (!empty($checkResult)) {
                    $fetchResult = ResultTable::where('academic_year', $request->school_year)
                        ->where('academy_term', $request->school_term)
                        ->where('class', $request->class)
                        ->where('admin_number', $studentDetails->st_admin_number)
                        ->where('result_status', 'Active')
                        ->get();
                    // get the interpretation name here...
                    $resultClassName = ClassModel::where('id', $request->class)->first();
                    $resultYearName = AcademicSession::where('id', $request->school_year)->first();
                    $resultTermName = TermModel::where('id', $request->school_term)->first();

                    // count the number of subject offered by the user here...
                    $subjectCount = DB::table('result_tables')
                        ->selectRaw('count(subject) as total_subject,
                 admin_number,academic_year, academy_term, class,
                result_status')
                        ->where('academic_year', $request->school_year)
                        ->where('academy_term', $request->school_term)
                        ->where('class', $request->class)
                        ->where('admin_number', $studentDetails->st_admin_number)
                        ->first();
                    // get total sum of all subjects offered by the user here...
                    $grandScore = DB::table('result_tables')
                        ->selectRaw('sum(total_scores) as user_total, total_scores,
                 admin_number,student_name, academic_year, academy_term, class,
                  result_status')
                        ->where('academic_year', $request->school_year)
                        ->where('academy_term', $request->school_term)
                        ->where('class', $request->class)
                        ->where('admin_number', $studentDetails->st_admin_number)
                        ->where('result_status', 'Active')
                        ->first();
                    // get the user class position here...
                    $classPosition = StudentPosition::where('sch_year', $request->school_year)
                        ->where('sch_term', $request->school_term)
                        ->where('sch_class', $request->class)
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
                    // get teacher comment here ...
                    $teacherComment = StudentComment::where('comm_year', $resultYearName->academic_name)
                        ->where('comm_term', $resultTermName->term_name)
                        ->where('comm_class', $resultClassName->class_name)
                        ->where('comm_stu_number', $studentDetails->st_admin_number)
                        ->where('comm_status', 'Active')
                        ->where('comm_teacher', '!=', '')
                        ->first();

                    // get school opening and closing date here ...
                    $school_start = SchoolResumption::where('school_year', $request->school_year)
                        ->where('school_term', $request->school_term)
                        ->where('status', 'Active')
                        ->first();
                    if (!empty($school_start)) {
                        $schStart = $school_start;
                    } else {
                        $schStart = '';
                    }

                    // get number of days school opened here ...
                    $school_open = DaysSchoolOpen::where('open_year', $request->school_year)
                        ->where('open_term', $request->school_term)
                        ->where('open_status', 'Active')
                        ->first();
                    if (!empty($school_open)) {
                        $openingSchool = $school_open;
                    } else {
                        $openingSchool = '';
                    }
                    // get attendance of the student here ...
                    $attendance_count = Attendance::where('atten_year', $request->school_year)
                        ->where('atten_term', $request->school_term)
                        ->where('atten_class', $request->class)
                        ->where('atten_admin_no', $studentDetails->st_admin_number)
                        ->where('atten_status', 'Active')
                        ->get();
                    if (!empty($attendance_count)) {

                        $myAttendance = count($attendance_count);
                    } else {
                        $myAttendance = '';
                    }
                    // class average here...
                    $student_count = Student::where('class_apply', $request->class)
                        ->where('acct_status', 'Active')
                        ->get();
                    if (!empty($student_count)) {

                        $totalStudent_inClass = count($student_count);
                        $average =  ($grandScore->user_total / $totalStudent_inClass);
                    } else {
                        $average = '';
                    }
                    // student average in exam here...
                    $student_subject = ResultTable::where('academic_year', $request->school_year)
                        ->where('academy_term', $request->school_term)
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
                    // get principle comment here ...
                    $principleComment = StudentComment::where('comm_year', $resultYearName->academic_name)
                        ->where('comm_term', $resultTermName->term_name)
                        ->where('comm_class', $resultClassName->class_name)
                        ->where('comm_stu_number', $studentDetails->st_admin_number)
                        ->where('comm_status', 'Active')
                        ->where('comm_prin_comment', '!=', '')
                        ->first();

                    // get student psychomotor domain details here...
                    $psychomotorDomain = PsychomotoDomian::where('aff_year', $request->school_year)
                        ->where('aff_term', $request->school_term)
                        ->where('aff_class', $request->class)
                        ->where('aff_admin_number', $studentDetails->st_admin_number)
                        ->where('aff_status', 'Active')
                        ->first();

                    // $count_student = ResultTable::where('tid_code', $recordID)->count('id');
                    return response()->json([
                        'status' => 200,
                        'result_info' => [
                            'resultDetails' => $fetchResult,
                            'classDetails' => $request->class,
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
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // Result CA checking goes here...
    public function resultCAChecker(Request $request)
    {
        $userDetails = auth('sanctum')->user();
        //dd($request->all());
        $request->validate([
            'school_year' => 'required',
            'school_term' => 'required',
            'class' => 'required',
        ], [
            'school_year.required' => 'Academic Year is required',
            'school_term.required' => 'Academic Term is required',
            'class.required' => 'Class is required',
        ]);
        if (auth('sanctum')->check()) {

            //$resultClassName = ClassModel::where('id', $request->class)->first();
            $resultYearName = AcademicSession::where('id', $request->school_year)->first();
            $resultTermName = TermModel::where('id', $request->school_term)->first();
            // get student details here...

            $studentDetails = Student::where('id', $userDetails->student_id)
                ->where('acct_status', 'Active')
                ->first();
            $resultClassName = ClassModel::where('id', $studentDetails->class_apply)->first();
            $year = $request->school_year;
            $class = $request->class;
            $term = $request->school_term;

            // dd($resultTermName);

            // here start to check / process the user result details
            $checkResultCA = ResultCA::where('rst_year', $request->school_year)
                ->where('rst_term', $request->school_term)
                ->where('rst_class', $request->class)
                ->where('st_admin_id', $studentDetails->st_admin_number)
                ->where('rst_status', 'Active')
                ->first();

            if (empty($checkResultCA)) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Sorry, No CA result found',
                ]);
            } else if (!empty($checkResultCA)) {
                $fetchResultCA = ResultCA::where('rst_year', $request->school_year)
                    ->where('rst_term', $request->school_term)
                    ->where('rst_class', $request->class)
                    ->where('st_admin_id', $studentDetails->st_admin_number)
                    ->where('rst_status', 'Active')
                    ->get();
                // get total sum of all subjects offered by the user here...
                $grandScore = DB::table('result_c_a_s')
                    ->selectRaw('sum(ca_total) as myca_total, ca_total,
                 st_admin_id, rst_year, rst_term, rst_class,
                 rst_status')
                    ->where('rst_year', $request->school_year)
                    ->where('rst_term', $request->school_term)
                    ->where('rst_class', $request->class)
                    ->where('st_admin_id', $studentDetails->st_admin_number)
                    ->first();
                // $count_student = ResultTable::where('tid_code', $recordID)->count('id');
                return response()->json([
                    'status' => 200,
                    'result_info' => [
                        'resultDetails' => $fetchResultCA,
                        'studentDetail' => $studentDetails,
                        'term_name' => $resultTermName,
                        'year_name' => $resultYearName,
                        'name_class' => $resultClassName,
                        'allTotal' => $grandScore,
                    ]
                ]);
            } else {
                return response()->json([
                    'status' => 405,
                    'message' => 'Try again, error occurred',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // get all monthly birthday details here...
    public function getBirthdayList()
    {
        $today_date = date('d');
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $today = now();

            $getbithdayDetails = Student::whereMonth('dob', $today->month)
                ->whereDay('dob', $today->day)
                ->where('acct_status', 'Active')
                ->orderByDesc('id')
                ->paginate(15);

            // $getbithdayDetails = Student::where('dob', '=', $today_date)

            //     ->where('acct_status', 'Active')
            //     ->orderByDesc('id')
            //     ->paginate(15);
            if ($getbithdayDetails) {
                return response()->json([
                    'status' => 200,
                    'allbithday_list' => $getbithdayDetails,
                ]);
            } else if (empty($getbithdayDetails)) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No record fund',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // get student names base on class id selected here...
    public function getStudentName(Request $request)
    {
        $getStudentName = Student::where('class_apply', '=', $request->id)
            ->where('acct_status', 'Active')->get();
        //->orderByDesc('st_admin_number');
        if ($getStudentName) {
            return response()->json([
                'status' => 200,
                'allstudent_list' => $getStudentName,
                'message' => 'Successful',
            ]);
        } else if (empty($getStudentName)) {
            return response()->json([
                'status' => 404,
                'message' => 'No record fund',
            ]);
        }
    }

    // get ip details here...
    public function systemSetting()
    {
        $getSystemSetting = SystemSetup::all()->first();
        if ($getSystemSetting) {
            return response()->json([
                'status' => 200,
                'system_setting' => $getSystemSetting,

            ]);
        } else if (empty($getSystemSetting)) {
            return response()->json([
                'status' => 404,
                'message' => 'System is down',
            ]);
        }
    }
}