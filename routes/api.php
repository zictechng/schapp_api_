<?php

use App\Http\Controllers\API\AcademicSessionController;
use App\Http\Controllers\API\AdminUserController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ClassController;
use App\Http\Controllers\API\CurrentSessionController;
use App\Http\Controllers\API\DaysSchoolopenController;
use App\Http\Controllers\API\ResultController;
use App\Http\Controllers\API\SchoolCategoryController;
use App\Http\Controllers\API\SchoolResumptionController;
use App\Http\Controllers\API\StaffController;
use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\SubjectController;
use App\Http\Controllers\API\TermController;
use App\Http\Controllers\API\TestRecordController;
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

    Route::post('save_class', [ClassController::class, 'addClass']);
    Route::get('fetch_class', [ClassController::class, 'fetchClass']);
    Route::delete('delete_class/{id}', [ClassController::class, 'deleteClass']);
    Route::delete('delete_subject/{id}', [SubjectController::class, 'deleteSubject']);
    Route::post('save_subject', [SubjectController::class, 'saveSubject']);
    Route::post('update_subject', [SubjectController::class, 'saveUpdateSubject']);
    Route::post('update_class', [ClassController::class, 'saveUpdateClass']);
    Route::post('save_academic', [AcademicSessionController::class, 'saveAcademic']);
    Route::get('fetch_subject', [SubjectController::class, 'fetchSubject']);
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
    Route::get('fetch_all_category', [SchoolCategoryController::class, 'fetchCategory']);
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
    Route::get('fetch_all', [CurrentSessionController::class, 'fetchAll']);
    Route::get('getsession/{id}', [CurrentSessionController::class, 'getSession']);
    Route::post('update_current_session', [CurrentSessionController::class, 'updateCurrentSession']);
    Route::delete('delete_session/{id}', [CurrentSessionController::class, 'deleteSession']);

    Route::get('fetch_all_student', [StudentController::class, 'getAllStudent']);
    Route::get('fetch_all_details', [StudentController::class, 'getAllDetails']);
    Route::get('fetch_all_class', [StudentController::class, 'getClassDetails']);
    Route::get('fetch_all_category', [StudentController::class, 'getCategoryDetails']);
    Route::post('save_student', [StudentController::class, 'saveStudent']);
    Route::get('fetch_edit_details/{id}', [StudentController::class, 'fetchStudent']);
    Route::post('student_update/{id}', [StudentController::class, 'updateStudent']);
    Route::delete('delete_student/{id}', [StudentController::class, 'deleteStudent']);
    Route::post('update_user_image/{id}', [StudentController::class, 'updateProfileImage']);
    Route::post('save_text', [StudentController::class, 'saveText']);


    Route::get('fetch_all_staff', [StaffController::class, 'getStaff']);
    Route::post('save_staff', [StaffController::class, 'saveStaff']);
    Route::get('fetch_edit_staff/{id}', [StaffController::class, 'fetchStaff']);
    Route::post('save_staff_update/{id}', [StaffController::class, 'staffUpdate']);
    Route::delete('delete_staff/{id}', [StaffController::class, 'deleteStaff']);
    Route::post('update_staff_image/{id}', [StaffController::class, 'updateStaffImage']);
    Route::post('update_staff_password/{id}', [StaffController::class, 'updateStaffPassword']);

    Route::get('fetch_all_admin', [AdminUserController::class, 'getAdmin']);
    Route::post('save_admin_user', [AdminUserController::class, 'saveAdminUser']);
    Route::get('fetch_edit/{id}', [AdminUserController::class, 'getAdminEdit']);
    Route::post('update_admin_user/{id}', [AdminUserController::class, 'updateAdmin']);
    Route::post('update_password/{id}', [AdminUserController::class, 'updateAdminPassword']);
    Route::delete('delete_admin/{id}', [AdminUserController::class, 'deleteAdmin']);

    Route::get('fetch_result', [ResultController::class, 'getAllResult']);
    Route::post('result_process_start', [ResultController::class, 'resultProcessStart']);
    Route::post('result_process_save', [ResultController::class, 'resultSave']);
    Route::get('get_result_process/{id}', [ResultController::class, 'getFetchResult']);
    Route::post('process_result', [TestRecordController::class, 'processSaveResult']);
});


Route::post('register', [AuthController::class, 'registerUser']);
Route::post('login', [AuthController::class, 'loginUser']);

Route::get('send_process', [AuthController::class, 'saveProcessResult']);