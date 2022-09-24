<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Activitity_log;
use App\Models\SchoolResumption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SchoolResumptionController extends Controller
{
    // save new record here
    public function saveNew(Request $request)
    {
        if (auth('sanctum')->check()) {

            $validator = Validator::make($request->all(), [
                'start_date' => 'required|max:191',
                'close_date' => 'required|max:191',
                'next_resumption' => 'required|max:191',
                'school_year' => 'required|max:191',
                'school_term' => 'required|max:191',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {

                $userDetails = auth('sanctum')->user();
                $resumption_date = SchoolResumption::where('start_date', $request->start_date)->where('school_year', $request->school_year)->where('school_term', $request->school_term)->first();
                if (!empty($resumption_date)) {
                    return response()->json([
                        'status' => 402,
                        'message' => 'Record exist already',
                    ]);
                } else if (empty($resumption_date)) {
                    $save_new = new SchoolResumption();
                    $save_new->start_date = $request['start_date'];
                    $save_new->close_date = $request['close_date'];
                    $save_new->next_resumption = $request['next_resumption'];
                    $save_new->school_year = $request['school_year'];
                    $save_new->school_term = $request['school_term'];
                    $save_new->added_by = $userDetails->username;
                    $save_new->status = "Active";
                    $save_new->add_date = date('d/m/Y H:i:s');

                    $save_new->save();

                    if ($save_new->save()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Created resumption date";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, added academic resumption date details";
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

    // fetch all details here...
    public function fetchResumption()
    {
        if (auth('sanctum')->check()) {
            //$resump_details = SchoolResumption::where('status', 'Active')->orderByDesc('id')->get();

            $resump_details = SchoolResumption::query()
                ->where('status', 'Active')
                ->orderByDesc('id')
                ->paginate('15');
            if ($resump_details) {
                return response()->json([
                    'status' => 200,
                    'resump_record' => $resump_details,
                ]);
            } else if (empty($resump_details)) {
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
    // get detail to edit form when edit button is clicked
    public function getResumption($id)
    {
        if (auth('sanctum')->check()) {
            $getDetails = SchoolResumption::where('id', $id)->first();
            if ($getDetails) {
                return response()->json([
                    'status' => 200,
                    'sDetails' => $getDetails,
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

    //update details here...
    public function updateResumption(Request $request)
    {
        if (auth('sanctum')->check()) {

            $validator = Validator::make($request->all(), [
                'start_date' => 'required|max:191',
                'close_date' => 'required|max:191',
                'next_resumption' => 'required|max:191',
                'school_year' => 'required|max:191',
                'school_term' => 'required|max:191',
                'id_name' => 'required|max:191',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {

                $userDetails = auth('sanctum')->user();
                $resum_academic = SchoolResumption::where('id', $request->id_name)->first();
                if (!empty($resum_academic)) {
                    $resum_academic->update([
                        'start_date' => $request->start_date,
                        'close_date' => $request->close_date,
                        'next_resumption' => $request->next_resumption,
                        'school_year' => $request->school_year,
                        'school_term' => $request->school_term,
                    ]);
                    $logs = new Activitity_log();
                    $logs->m_username = $userDetails->username;
                    $logs->m_action = "Updated resumption";
                    $logs->m_status = "Successful";
                    $logs->m_details = "$userDetails->name, Updated academic resumption details";
                    $logs->m_date = date('d/m/Y H:i:s');
                    $logs->m_uid = $userDetails->id;
                    $logs->m_ip = request()->ip;
                    $logs->m_record_id = $resum_academic->id;

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

    //delete record operation comes here...
    public function deleteResumption($id)
    {
        $userDetails = auth('sanctum')->user();
        $delete_record = SchoolResumption::find($id);
        if (!empty($delete_record)) {
            $delete_record->update([
                'status' => 'Deleted',
            ]);
            $logs = new Activitity_log();
            $logs->m_username = $userDetails->username;
            $logs->m_action = "Deleted academic resumption";
            $logs->m_status = "Successful";
            $logs->m_details = "$userDetails->name, Delete academic resumption details";
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