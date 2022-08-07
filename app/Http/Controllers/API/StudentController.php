<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Activitity_log;
use App\Models\ClassModel;
use App\Models\SchoolCategory;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TermModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class StudentController extends Controller
{
    // fetch all student details here...
    public function getAllStudent()
    {
        if (auth('sanctum')->check()) {
            $all_details = Student::where('acct_status', 'Active')->orderByDesc('id')->get();
            return response()->json([
                'status' => 200,
                'student_record' => $all_details,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
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
                } else if (empty($resumption_date)) {
                    $save_new = new Student();
                    $save_new->surname = $request['surname'];
                    $save_new->other_name = $request['other_name'];
                    $save_new->sex = $request['sex'];
                    $save_new->dob = $request['dob'];
                    $save_new->st_age = $my_age;
                    $save_new->state = $request['state'];
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

                    if ($save_new->save()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Registered student details";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, added new student details";
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
                'image' => 'required|mimes:jpeg,png,jpg,gif',

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
        $validator = Validator::make($request->all(), [
            'state' => 'required',
            'class_apply' => 'required',
            'message' => 'required',
            'email_address' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                $errors = $validator->errors(),
                'status' => 422,
                'errors' => $errors,
            ]);
        } else {
            return response()->json([
                'status' => 200,
                'message' => 'Save successfully',
            ]);
        }
    }
}