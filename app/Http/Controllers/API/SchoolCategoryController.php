<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Activitity_log;
use App\Models\SchoolCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SchoolCategoryController extends Controller
{
    //function to save/create new category

    public function saveCategory(Request $request)
    {
        if (auth('sanctum')->check()) {

            $validator = Validator::make($request->all(), [
                'category_name' => 'required|max:191',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {

                $userDetails = auth('sanctum')->user();
                $academic_term = SchoolCategory::where('sc_name', $request->category_name)->where('sc_status', 'Active')->first();
                if (!empty($academic_term)) {
                    return response()->json([
                        'status' => 402,
                        'message' => 'Record already exist',
                    ]);
                } else if (empty($academic_term)) {
                    $save_term = new SchoolCategory();
                    $save_term->sc_name = $request['category_name'];
                    $save_term->sc_add_by = $userDetails->username;
                    $save_term->sc_status = "Active";
                    $save_term->sc_date = date('d/m/Y H:i:s');

                    $save_term->save();

                    if ($save_term->save()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Created school category";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, added new school category details";
                        $logs->m_date = date('d/m/Y H:i:s');
                        $logs->m_uid = $userDetails->id;
                        $logs->m_ip = request()->ip;

                        $logs->save();
                        return response()->json([
                            'status' => 200,
                            'message' => 'Record Added Successfully',
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

    // fetch all record
    public function fetchCategory()
    {
        if (auth('sanctum')->check()) {
            $allcategory_details = SchoolCategory::where('sc_status', 'Active')->orderByDesc('id')->get();
            if ($allcategory_details) {
                return response()->json([
                    'status' => 200,
                    'category_record' => $allcategory_details,
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

    //get details from database when edit button is clicked
    public function getCategory($id)
    {
        if (auth('sanctum')->check()) {
            $categoryDetails = SchoolCategory::where('id', $id)->first();
            if ($categoryDetails) {
                return response()->json([
                    'status' => 200,
                    'category_Details' => $categoryDetails,
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

    // update record here
    public function updateCategory(Request $request)
    {
        if (auth('sanctum')->check()) {

            $validator = Validator::make($request->all(), [
                'category_name' => 'required|max:191',
                'id' => 'required|max:191',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {

                $userDetails = auth('sanctum')->user();
                $category = SchoolCategory::where('id', $request->id)->first();
                if (!empty($category)) {
                    $category->update([
                        'sc_name' => $request->category_name,
                    ]);
                    $logs = new Activitity_log();
                    $logs->m_username = $userDetails->username;
                    $logs->m_action = "Updated school category";
                    $logs->m_status = "Successful";
                    $logs->m_details = "$userDetails->name, Updated school category details";
                    $logs->m_date = date('d/m/Y H:i:s');
                    $logs->m_uid = $userDetails->id;
                    $logs->m_ip = request()->ip;
                    $logs->m_record_id = $category->id;

                    $logs->save();
                    return response()->json([
                        'status' => 200,
                        'message' => 'Record Updated Successfully',
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
    // delete record here...
    public function deleteCategory($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $category_record = SchoolCategory::find($id);
            if (!empty($category_record)) {
                $category_record->update([
                    'sc_status' => 'Deleted',
                ]);
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Deleted school category";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Delete school category details";
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
}