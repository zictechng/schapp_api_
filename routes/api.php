<?php

use App\Http\Controllers\API\AcademicSessionController;
use App\Http\Controllers\API\AdminUserController;
use App\Http\Controllers\API\AssignClassController;
use App\Http\Controllers\API\AssignSubjectController;
use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CAResultController;
use App\Http\Controllers\API\ClassController;
use App\Http\Controllers\API\CurrentSessionController;
use App\Http\Controllers\API\DaysSchoolopenController;
use App\Http\Controllers\API\GeneratePinController;
use App\Http\Controllers\API\PsychomotorDomainController;
use App\Http\Controllers\API\ResultController;
use App\Http\Controllers\API\SchoolCategoryController;
use App\Http\Controllers\API\SchoolResumptionController;
use App\Http\Controllers\API\StaffController;
use App\Http\Controllers\API\StudentCommentController;
use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\SubjectController;
use App\Http\Controllers\API\TermController;
use App\Http\Controllers\API\TestRecordController;
use App\Http\Controllers\API\UploadFilesController;
use App\Models\SystemSetup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// this is the protected route for the application
Route::middleware(['auth:sanctum'])->group(function () {

    // this route help to check if user actually login from the frontend
    // to protect the application in case if the token is leak.
    Route::get('/checkingAuthenticated', function () {
        return response()->json(['message' => 'Access granted as an Admin', 'status' => 200], 200);
    });

    // other application route for operation goes here...
    Route::post('save_class', [ClassController::class, 'addClass']);
    Route::get('fetch_class_details', [ClassController::class, 'fetchClassDetail']);
    Route::delete('delete_class_id/{id}', [ClassController::class, 'deleteClass']);
    Route::delete('delete_subject_details/{id}', [SubjectController::class, 'deleteSubject']);
    Route::post('save_subject', [SubjectController::class, 'saveSubject']);
    Route::post('update_subject', [SubjectController::class, 'saveUpdateSubject']);
    Route::post('update_class', [ClassController::class, 'saveUpdateClass']);
    Route::post('save_academic', [AcademicSessionController::class, 'saveAcademic']);
    Route::get('fetch_subject', [SubjectController::class, 'fetchSubject']);
    Route::get('get_all_subject', [SubjectController::class, 'getAllSubject']);
    Route::get('get_subject/{id}', [SubjectController::class, 'getSubject']);
    Route::get('get_class/{id}', [ClassController::class, 'getClass']);


    Route::get('fetch_academic_session', [AcademicSessionController::class, 'fetch_session']);
    Route::get('get_academic_session/{id}', [AcademicSessionController::class, 'get_session']);
    Route::post('update_academic_session', [AcademicSessionController::class, 'update_session']);
    Route::delete('delete_academic/{id}', [AcademicSessionController::class, 'deleteSession']);

    Route::post('save_term', [TermController::class, 'saveTerm']);
    Route::get('fetch_all_term', [TermController::class, 'fetchTerm']);
    Route::get('get_term/{id}', [TermController::class, 'getTerm']);
    Route::post('update_term', [TermController::class, 'termUpdate']);
    Route::delete('delete_academic_term/{id}', [TermController::class, 'deleteTerm']);

    Route::post('save_category', [SchoolCategoryController::class, 'saveCategory']);
    Route::get('fetch_category', [SchoolCategoryController::class, 'fetchCategory']);
    Route::get('get_category/{id}', [SchoolCategoryController::class, 'getCategory']);
    Route::post('update_category', [SchoolCategoryController::class, 'updateCategory']);
    Route::delete('delete_category/{id}', [SchoolCategoryController::class, 'deleteCategory']);

    Route::get('fetch_school_session', [AcademicSessionController::class, 'fetchSchoolSession']);
    Route::get('fetch_allterm', [TermController::class, 'fetchSchoolTerm']);

    Route::post('save_resumption', [SchoolResumptionController::class, 'saveNew']);
    Route::get('fetch_all_resumption', [SchoolResumptionController::class, 'fetchResumption']);
    Route::get('get_resumption/{id}', [SchoolResumptionController::class, 'getResumption']);
    Route::post('update_resumption', [SchoolResumptionController::class, 'updateResumption']);
    Route::delete('delete_resumption/{id}', [SchoolResumptionController::class, 'deleteResumption']);

    Route::post('save_days', [DaysSchoolopenController::class, 'saveOpen_days']);
    Route::get('get_numbers_open/{id}', [DaysSchoolopenController::class, 'getNumber_days']);
    Route::get('fetch_all', [DaysSchoolopenController::class, 'getAll']);
    Route::post('update_open_day', [DaysSchoolopenController::class, 'updateOpen']);
    Route::delete('delete_open_days/{id}', [DaysSchoolopenController::class, 'deleteOpen']);
    Route::get('fetch_all_open', [DaysSchoolopenController::class, 'fetchCategory']);

    Route::post('save_session', [CurrentSessionController::class, 'saveCurrent_Session']);
    Route::get('fetch_all_session', [CurrentSessionController::class, 'fetchAll']);
    Route::get('getsession/{id}', [CurrentSessionController::class, 'getSession']);
    Route::post('update_current_session', [CurrentSessionController::class, 'updateCurrentSession']);
    Route::delete('delete_session/{id}', [CurrentSessionController::class, 'deleteSession']);

    Route::get('fetch_all_student', [StudentController::class, 'getAllStudent']);
    Route::get('fetch_all_student_result', [StudentController::class, 'getResultStudent']);
    Route::get('fetch_all_details', [StudentController::class, 'getAllDetails']);
    Route::get('fetch_all_class', [StudentController::class, 'getClassDetails']);
    Route::get('fetch_all_category', [StudentController::class, 'getCategoryDetails']);
    Route::post('save_student', [StudentController::class, 'saveStudent']);
    Route::get('fetch_edit_details/{id}', [StudentController::class, 'fetchStudent']);
    Route::post('student_update/{id}', [StudentController::class, 'updateStudent']);
    Route::delete('delete_student/{id}', [StudentController::class, 'deleteStudent']);
    Route::post('update_user_image/{id}', [StudentController::class, 'updateProfileImage']);
    Route::post('textSave', [StudentController::class, 'saveText']);
    Route::post('update_student_password', [StudentController::class, 'updateStudentPassword']);



    Route::get('fetch_class', [StudentController::class, 'fetchClass']);
    Route::get('fetch_all_staff', [StaffController::class, 'getStaff']);
    Route::post('save_staff', [StaffController::class, 'saveStaff']);
    Route::get('fetch_edit_staff/{id}', [StaffController::class, 'fetchStaff']);
    Route::post('save_staff_update/{id}', [StaffController::class, 'staffUpdate']);
    Route::delete('delete_staff/{id}', [StaffController::class, 'deleteStaff']);
    Route::post('update_staff_image/{id}', [StaffController::class, 'updateStaffImage']);
    Route::post('update_staff_password/{id}', [StaffController::class, 'updateStaffPassword']);
    Route::get('fetch_system_log', [StaffController::class, 'fetchSystemLog']);
    Route::get('fetch_all_admin', [AdminUserController::class, 'getAdmin']);
    Route::post('save_admin_user', [AdminUserController::class, 'saveAdminUser']);
    Route::get('fetch_admin_edit/{id}', [AdminUserController::class, 'getAdminEdit']);
    Route::post('update_admin_user/{id}', [AdminUserController::class, 'updateAdmin']);
    Route::post('update_password/{id}', [AdminUserController::class, 'updateAdminPassword']);
    Route::delete('delete_admin/{id}', [AdminUserController::class, 'deleteAdmin']);
    Route::get('fetch_birthday_list', [StudentController::class, 'getBirthdayList']);

    Route::get('fetch_result', [ResultController::class, 'getAllResult']);
    Route::post('result_process_start', [ResultController::class, 'resultProcessStart']);
    Route::post('result_process_save', [ResultController::class, 'resultSave']);
    Route::get('get_result_process/{id}', [ResultController::class, 'getFetchResult']);
    Route::delete('delete_result/{id}', [ResultController::class, 'deleteResult']);
    Route::post('process_result', [TestRecordController::class, 'processSaveResult']);
    Route::post('save_result_process', [ResultController::class, 'saveResultProcess']);
    Route::post('single_result_save', [ResultController::class, 'resultSingleSave']);
    Route::post('view_result_process', [ResultController::class, 'viewResultProcess']);
    Route::get('load_view_result/{id}', [ResultController::class, 'loadResultView']);
    Route::get('get_resultview/{id}', [ResultController::class, 'loadView']);
    Route::post('update_result_view', [ResultController::class, 'updateResultView']);
    Route::delete('delete_result_view/{id}', [ResultController::class, 'deleteResultView']);
    Route::post('view_result_subject', [ResultController::class, 'viewSubjectResult']);
    Route::get('load_view_subject/{id}', [ResultController::class, 'loadSubjectView']);
    Route::get('get_subject_id/{id}', [ResultController::class, 'getSubjectID']);
    Route::post('fetch_all_student_name', [StudentController::class, 'getStudentName']);
    // CA route here...
    Route::get('fetch_ca_result', [CAResultController::class, 'getAllCA']);
    Route::post('result_process_ca', [CAResultController::class, 'processAllCA']);
    Route::get('get_ca_result_process/{id}', [CAResultController::class, 'getFetchProcessCA']);
    Route::post('result_ca_save', [CAResultController::class, 'resultCASave']);
    Route::post('result_single_ca_save', [CAResultController::class, 'resultSingleCASave']);
    Route::delete('delete_ca_result/{id}', [CAResultController::class, 'deleteCA']);
    Route::get('get_ca_id/{id}', [CAResultController::class, 'getCADetails']);
    Route::delete('delete_all_ca_result/{id}', [CAResultController::class, 'deleteAllCA']);
    Route::delete('delete_ca_id/{id}', [CAResultController::class, 'deleteCA_ID']);
    Route::get('fetch_ca/{id}', [CAResultController::class, 'fetchCA_ID']);
    Route::post('save_ca_update', [CAResultController::class, 'saveCAUpdate']);

    //repair and delete result details
    Route::post('repair_result', [ResultController::class, 'repairResult']);
    Route::delete('trash_repair_result/{id}', [ResultController::class, 'trashRepairResult']);
    Route::delete('trash_position_result/{id}', [ResultController::class, 'trashPositionResult']);
    Route::get('fetch_result_view/{id}', [ResultController::class, 'repairResultGet']);
    Route::post('repair_subject', [ResultController::class, 'repairResultSubject']);
    Route::post('repair_ca', [ResultController::class, 'repairResultCA']);
    Route::get('fetch_result_ca/{id}', [ResultController::class, 'getResultCADetails']);
    Route::delete('trash_repair_ca/{id}', [ResultController::class, 'trashResultCA']);
    Route::post('repair_result_position', [ResultController::class, 'repairResultPosition']);
    Route::post('repair_result_position', [ResultController::class, 'repairResultPosition']);
    Route::get('fetch_position_view/{id}', [ResultController::class, 'repairPositionGet']);

    // grade student result here...
    Route::get('fetch_all_grades', [ResultController::class, 'fetchGrade']);
    Route::post('start_grade', [ResultController::class, 'startGrading']);
    Route::get('fetch_grade_result/{id}', [ResultController::class, 'getAllGrading']);
    Route::post('result_save_grade', [ResultController::class, 'saveGradePosition']);
    Route::get('get_grade_id/{i}', [ResultController::class, 'getGradeDetails']);
    Route::delete('delete_all_grade/{i}', [ResultController::class, 'deleteAllGrade']);
    Route::delete('delete_position_id/{i}', [ResultController::class, 'deletePositionID']);

    //grade student subject position here...
    Route::get('fetch_subject_position/{id}', [ResultController::class, 'fetchSubjectPosition']);
    Route::post('save_subject_position', [ResultController::class, 'saveSubjectPosition']);
    Route::get('fetch_subject_result/{id}', [ResultController::class, 'fetchSubjectResult']);
    Route::post('save_result_subject_update', [ResultController::class, 'updateResultSubject']);
    Route::delete('delete_subject_result/{id}', [ResultController::class, 'deleteSubjectResult']);
    Route::delete('delete_all_subject_result/{id}', [ResultController::class, 'deleteAllSubjectResult']);

    // promotion api routes here...
    Route::post('start_promotion', [ResultController::class, 'startPromotion']);
    Route::post('start_promotion_return', [ResultController::class, 'startPromotionReturn']);
    Route::get('fetch_promotion/{id}', [ResultController::class, 'fetchPromotion']);
    Route::delete('mark_promote/{id}', [ResultController::class, 'markPromotion']);
    Route::delete('return_promote/{id}', [ResultController::class, 'returnPromotion']);
    Route::delete('promote_all/{id}', [ResultController::class, 'promotionAll']);
    Route::delete('return_promote_all/{id}', [ResultController::class, 'returnAll']);
    Route::delete('promote_reserved/{id}', [ResultController::class, 'reversedPromotion']);

    // graduation route goes here...
    Route::post('start_promotion', [ResultController::class, 'startGraduation']);
    Route::get('fetch_graduation/{id}', [ResultController::class, 'fetchStartGraduation']);
    Route::delete('mark_graduated/{id}', [ResultController::class, 'graduateStudent']);
    Route::delete('return_graduation/{id}', [ResultController::class, 'returnedGraduation']);
    Route::get('fetch_result_grade', [ResultController::class, 'fetchAllGraduated']);
    Route::delete('graduate_all/{id}', [ResultController::class, 'graduateAll']);

    // attendance routes goes here...
    Route::get('fetch_attendance', [AttendanceController::class, 'getAttendance']);
    Route::post('start_attendance', [AttendanceController::class, 'startAttendance']);
    Route::get('fetch_start_attendance/{id}', [AttendanceController::class, 'getStartAttendance']);
    Route::delete('mark_attend/{id}', [AttendanceController::class, 'markAttendance']);
    Route::delete('return_attendance/{id}', [AttendanceController::class, 'returnAttendance']);
    Route::delete('mark_all/{id}', [AttendanceController::class, 'markAllAttendance']);
    Route::get('fetch_view_attend/{id}', [AttendanceController::class, 'viewAllAttendance']);
    Route::delete('remove_attendance/{id}', [AttendanceController::class, 'removeAttendance']);
    Route::delete('delete_attend_all/{id}', [AttendanceController::class, 'removeAllAttendance']);

    // scratch card pin routes goes here...
    Route::post('start_pin', [GeneratePinController::class, 'startPIN']);
    Route::get('fetch_pin', [GeneratePinController::class, 'fetchPIN']);
    Route::get('get_pin/{id}', [GeneratePinController::class, 'getAllPins']);
    Route::delete('activate_card/{id}', [GeneratePinController::class, 'activatePin']);
    Route::delete('delete_card/{id}', [GeneratePinController::class, 'deletePin']);
    Route::delete('activate_all_card/{id}', [GeneratePinController::class, 'activateAllPin']);
    Route::delete('de_activate_card/{id}', [GeneratePinController::class, 'deActivatePin']);
    Route::delete('de_activate_all_card/{id}', [GeneratePinController::class, 'deActivateAllPin']);

    // assign subjects route goes here...
    Route::get('get_assign_subject', [AssignSubjectController::class, 'fetchAssignSubject']);
    Route::get('fetch_staff_detail', [AssignSubjectController::class, 'getStaffDetails']);
    Route::post('save_assign_subject', [AssignSubjectController::class, 'saveAssignSubject']);
    Route::get('get_assign_subject_id/{id}', [AssignSubjectController::class, 'getAssignSubject']);
    Route::delete('delete_all_subject/{id}', [AssignSubjectController::class, 'deleteAllSubject']);
    Route::delete('delete_subject/{id}', [AssignSubjectController::class, 'deleteSubject']);
    Route::get('fetch_subject/{id}', [AssignSubjectController::class, 'fetchSubject']);
    Route::get('fetch_all_subject', [AssignSubjectController::class, 'getFetchSubject']);
    Route::post('save_subject_update', [AssignSubjectController::class, 'updateEditSubject']);

    // Assign class routes goes here
    Route::get('get_assign_class', [AssignClassController::class, 'fetchAssignClass']);
    Route::post('save_assign', [AssignClassController::class, 'saveAssignClass']);
    Route::get('fetch_staff_details', [AssignClassController::class, 'getStaffDetails']);
    Route::get('get_assign_class_id/{id}', [AssignClassController::class, 'getAssignClass']);
    Route::delete('delete_class/{id}', [AssignClassController::class, 'deleteClass']);
    Route::delete('delete_all_class/{id}', [AssignClassController::class, 'deleteAllClass']);
    Route::post('save_class_update', [AssignClassController::class, 'updateEditClass']);
    Route::get('fetch_class/{id}', [AssignClassController::class, 'fetchClass']);

    // Psychomotor domain routes goes here...
    Route::get('get_psychomotor', [PsychomotorDomainController::class, 'getPsychomotor']);
    Route::post('start_psychomotor', [PsychomotorDomainController::class, 'startPsychomotor']);
    Route::get('fetch_psy_start/{id}', [PsychomotorDomainController::class, 'fetchStartPsychomotor']);
    Route::post('save_psychomotor', [PsychomotorDomainController::class, 'savePsychomotor']);
    Route::get('fetchPsychomotor_id/{id}', [PsychomotorDomainController::class, 'fetchPsychomotor']);
    Route::delete('delete_psychomotor/{id}', [PsychomotorDomainController::class, 'deletePsychomotor']);
    Route::delete('delete_all/{id}', [PsychomotorDomainController::class, 'deleteAllPsychomotor']);
    Route::get('fetch_edit/{id}', [PsychomotorDomainController::class, 'fetchPsychomotorEdit']);
    Route::post('save_update', [PsychomotorDomainController::class, 'updatePsychomotor']);

    // upload files route goes here...
    Route::post('upload_pin', [UploadFilesController::class, 'uploadPinFile']);
    Route::post('upload_finance', [UploadFilesController::class, 'uploadFinanceReport']);
    Route::post('upload_ca', [UploadFilesController::class, 'uploadCAReport']);
    Route::post('upload_result', [UploadFilesController::class, 'uploadResultReport']);


    // comment routes goes here....

    Route::get('fetch_comment', [StudentCommentController::class, 'fetchAllComment']);
    Route::post('comment_process', [StudentCommentController::class, 'processComment']);
    Route::get('get_student_comment/{id}', [StudentCommentController::class, 'getStudentComment']);
    Route::post('save_comment', [StudentCommentController::class, 'saveComment']);
    Route::delete('delete_comment/{id}', [StudentCommentController::class, 'deleteAllComment']);
    Route::delete('delete_comment_id/{id}', [StudentCommentController::class, 'deleteComment']);
    Route::delete('delete_all_comment/{id}', [StudentCommentController::class, 'deleteCommentAll']);
    Route::get('get_comment_id/{id}', [StudentCommentController::class, 'fetchCommentDetails']);
    Route::get('fetch_edit_comment/{id}', [StudentCommentController::class, 'fetchEditComment']);
    Route::post('save_comment_update', [StudentCommentController::class, 'saveCommentUpdate']);


    // get student result template route here...
    Route::post('get_template', [ResultController::class, 'fetchTemplate']);
    Route::get('check_loggin_user/{id}', [AuthController::class, 'fetchLoggedINUser']);
    Route::get('check_user_logged/{id}', [AuthController::class, 'fetchUserLogged']);
    Route::get('authenticate_user/{id}', [AuthController::class, 'authenticateUser']);

    // setting route goes here...
    Route::post('save_setting_details', [StaffController::class, 'settingDetails']);
    Route::get('fetch_setting_details', [StaffController::class, 'fetchAllSetting']);
    Route::post('upload_sch_logo', [StaffController::class, 'uploadSchoolLogo']);
    Route::post('upload_sch_banner', [StaffController::class, 'uploadSchoolBanner']);
    // fetch dashboard activities here
    Route::get('fetch_dash1', [AuthController::class, 'getDash1']);
    Route::get('fetch_dash2', [AuthController::class, 'getDash2']);
    Route::get('fetch_activity_log', [AuthController::class, 'getActivityLog']);
    Route::get('fetch_birthday', [AuthController::class, 'getBirthday']);
    Route::get('fetch_staff_dash1', [StaffController::class, 'getStaffStudent']);
    Route::get('fetch_dash_details', [StaffController::class, 'fetchDashDetails']);
    Route::get('fetch_dash_log', [StaffController::class, 'fetchLogDetails']);
    Route::get('fetch_active_student', [StaffController::class, 'fetchActiveStudent']);


    // staff/ teacher portal routes goes here
    Route::get('fetch_my_student', [StaffController::class, 'getMyStudent']);
    Route::get('fetch_result_staff', [StaffController::class, 'fetchResultStaff']);
    Route::get('get_result_start_process/{id}', [StaffController::class, 'fetchResultStart']);
    Route::post('start_result_process', [StaffController::class, 'startResultProcess']);
    Route::get('fetch_myclass', [StaffController::class, 'fetchMyClass']);
    Route::post('process_save_result', [StaffController::class, 'saveMyResult']);
    Route::post('result_save_final', [StaffController::class, 'saveFinalResult']);
    Route::get('my_student', [StaffController::class, 'myAllStudent']);
    Route::post('my_single_result_save', [StaffController::class, 'saveMySingleResult']);
    Route::get('my_ca_result', [StaffController::class, 'fetchMyCAResult']);
    Route::post('process_my_ca', [StaffController::class, 'processCAResult']);
    Route::post('save_myca', [StaffController::class, 'saveMyCA']);
    Route::post('my_single_ca_save', [StaffController::class, 'saveMySingleCA']);
    Route::post('post_assignment', [StaffController::class, 'saveAssignment']);
    Route::get('fetch_myassignment', [StaffController::class, 'fetchMyPostedAssignment']);
    Route::get('fetch_submission_assignment', [StaffController::class, 'fetchSubmissionAssignment']);
    Route::delete('delete_assign/{id}', [StaffController::class, 'deleteAssign']);
    Route::get('get_assignment_id/{id}', [StaffController::class, 'getAssignmentID']);
    Route::get('fetch_edit_assign/{id}', [StaffController::class, 'fetchEditAssignment']);
    Route::post('save_assign_update', [StaffController::class, 'saveUpdateAssignment']);
    Route::get('fetch_my_attendance', [StaffController::class, 'myAttendance']);
    Route::post('start_my_attendance', [StaffController::class, 'startMyAttendance']);
    Route::get('get_my_psychomotor', [StaffController::class, 'getMyPsychomotor']);
    Route::get('fetch_my_comment', [StaffController::class, 'fetchMyComment']);
    Route::get('fetch_my_profile', [StaffController::class, 'fetchMyProfile']);
    Route::get('fetch_mynotification', [StaffController::class, 'myNotification']);
    Route::post('send_message', [StaffController::class, 'sendNotification']);
    Route::get('get_readmessage_id/{id}', [StaffController::class, 'getReadMessage']);
    Route::post('send_assignment_remark', [StaffController::class, 'sendAssignmentRemark']);
    Route::post('fetch_student_result', [StaffController::class, 'fetchStudentResult']);
    Route::get('fetch_view_result/{id}', [StaffController::class, 'fetchViewResult']);

    // STUDENT DASHBOARD / PORTAL ROUTES HERE...
    Route::get('fetch_student_profile', [StudentController::class, 'fetchStudentProfile']);
    Route::get('fetch_my_log', [StudentController::class, 'fetchMyLog']);
    Route::post('save_password_update', [StudentController::class, 'updateMyPassword']);
    Route::get('my_notification', [StudentController::class, 'myAllNotification']);
    Route::get('my_assignment', [StudentController::class, 'myAssignment']);
    Route::post('submit_assignment', [StudentController::class, 'submitAssignment']);
    Route::get('get_messages_id/{id}', [StudentController::class, 'getAssignmentToReply']);
    Route::post('check_result', [StudentController::class, 'resultChecker']);
    Route::post('check_ca_result', [StudentController::class, 'resultCAChecker']);
    Route::get('system_setting', [StudentController::class, 'systemSetting']);
});

Route::get('fetch_state', [StudentController::class, 'fetchState']);
Route::get('fetch_ip', [StudentController::class, 'fetchIP']);
Route::get('fetch_allip', [StudentController::class, 'fetchALLIP']);
Route::get('fetch_states', [StudentController::class, 'fetchAllState']);

Route::get('text_sum', [ResultController::class, 'textSum']);

Route::post('register', [AuthController::class, 'registerUser']);
Route::post('login', [AuthController::class, 'loginUser']);
Route::post('student_login', [AuthController::class, 'loginStudent']);

Route::get('send_process', [AuthController::class, 'saveProcessResult']);




/* this for all users logout route */
Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'LogoutUser']);
});