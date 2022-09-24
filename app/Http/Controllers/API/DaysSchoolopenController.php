<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Activitity_log;
use App\Models\DaysSchoolOpen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DaysSchoolopenController extends Controller
{
    // save new record here...

    public function saveOpen_days(Request $request)
    {
        if (auth('sanctum')->check()) {

            $validator = Validator::make($request->all(), [
                'days_open' => 'required|max:191',
                'open_term' => 'required|max:191',
                'open_year' => 'required|max:191',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {

                $userDetails = auth('sanctum')->user();
                $resumption_date = DaysSchoolOpen::where('open_year', $request->open_year)->where('open_term', $request->open_term)->first();
                if (!empty($resumption_date)) {
                    return response()->json([
                        'status' => 402,
                        'message' => 'Record exist already',
                    ]);
                } else if (empty($resumption_date)) {
                    $save_new = new DaysSchoolOpen();
                    $save_new->days_open = $request['days_open'];
                    $save_new->open_term = $request['open_term'];
                    $save_new->open_year = $request['open_year'];
                    $save_new->open_status = "Active";
                    $save_new->open_date = date('d/m/Y H:i:s');
                    $save_new->open_addedby = $userDetails->username;

                    $save_new->save();

                    if ($save_new->save()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Created days school open";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, added number of days school opening details";
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

    // get record details when edit button is clicked
    public function getNumber_days($id)
    {
        if (auth('sanctum')->check()) {
            $getopenDetails = DaysSchoolOpen::where('id', $id)->first();
            if ($getopenDetails) {
                return response()->json([
                    'status' => 200,
                    'open_Details' => $getopenDetails,
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

    // fetch all the details here....
    public function getAll()
    {
        if (auth('sanctum')->check()) {
            $open_details = DaysSchoolOpen::where('open_status', 'Active')->orderByDesc('id')->get();

            // $open_details = DaysSchoolOpen::query()
            //     ->where('open_status', 'Active')
            //     ->orderByDesc('id')
            //     ->paginate('15');
            if ($open_details) {
                return response()->json([
                    'status' => 200,
                    'opend_record' => $open_details,
                ]);
            } else if (empty($open_details)) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No record at the moment'
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    // fetch all record
    public function fetchCategory()
    {
        if (auth('sanctum')->check()) {
            //$allcategory_details = DaysSchoolOpen::where('open_status', 'Active')->orderByDesc('id')->get();
            $allcategory_details = DaysSchoolOpen::query()
                ->where('open_status', 'Active')
                ->orderByDesc('id')
                ->paginate('15');
            if ($allcategory_details) {
                return response()->json([
                    'status' => 200,
                    'category_record' => $allcategory_details,
                ]);
            } else if (empty($allcategory_details)) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No record at the moment'
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    //update details here...
    public function updateOpen(Request $request)
    {
        if (auth('sanctum')->check()) {

            $validator = Validator::make($request->all(), [
                'days_open' => 'required|max:191',
                'open_term' => 'required|max:191',
                'open_year' => 'required|max:191',
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
                $openNumber = DaysSchoolOpen::where('id', $request->id)->first();
                if (!empty($openNumber)) {
                    $openNumber->update([
                        'days_open' => $request->days_open,
                        'open_term' => $request->open_term,
                        'open_year' => $request->open_year,
                    ]);
                    $logs = new Activitity_log();
                    $logs->m_username = $userDetails->username;
                    $logs->m_action = "Updated number of days school open";
                    $logs->m_status = "Successful";
                    $logs->m_details = "$userDetails->name, Updated number of days school opened details";
                    $logs->m_date = date('d/m/Y H:i:s');
                    $logs->m_uid = $userDetails->id;
                    $logs->m_ip = request()->ip;
                    $logs->m_record_id = $openNumber->id;

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

    // delete operation goes here...
    public function deleteOpen($id)
    {
        $userDetails = auth('sanctum')->user();
        $delete_open = DaysSchoolOpen::find($id);
        if (!empty($delete_open)) {
            $delete_open->update([
                'open_status' => 'Deleted',
            ]);
            $logs = new Activitity_log();
            $logs->m_username = $userDetails->username;
            $logs->m_action = "Deleted number of school open";
            $logs->m_status = "Successful";
            $logs->m_details = "$userDetails->name, Delete number of days school opened details";
            $logs->m_date = date('d/m/Y H:i:s');
            $logs->m_uid = $userDetails->id;
            $logs->m_ip = request()->ip;
            $logs->m_record_id = $id;

            $logs->save();
            return response()->json([
                'status' => 200,
                'message' => 'Deleted Successfully',
            ]);
        } else {
            return response()->json([
                'status' => 402,
                'message' => 'Operation failed! Try again',
            ]);
        }
    }
}