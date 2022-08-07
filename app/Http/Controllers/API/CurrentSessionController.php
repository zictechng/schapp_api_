<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Activitity_log;
use App\Models\CurrentSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CurrentSessionController extends Controller
{
    //save new record here...
    public function saveCurrent_Session(Request $request)
    {
        if (auth('sanctum')->check()) {

            $validator = Validator::make($request->all(), [
                'current_session' => 'required|max:191',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {

                $userDetails = auth('sanctum')->user();
                $sess_date = CurrentSession::where('running_session', $request->current_session)->first();
                if (!empty($sess_date)) {
                    return response()->json([
                        'status' => 402,
                        'message' => 'Record exist already',
                    ]);
                } else if (empty($sess_date)) {
                    $save_new = new CurrentSession();
                    $save_new->running_session = $request['current_session'];
                    $save_new->session_status = "Active";
                    $save_new->session_date = date('d/m/Y H:i:s');
                    $save_new->session_addedby = $userDetails->username;

                    $save_new->save();

                    if ($save_new->save()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Created academic running session";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, added academic current running session details";
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
    // fetch all details here ....
    public function fetchAll()
    {
        if (auth('sanctum')->check()) {

            $current_details = CurrentSession::where('session_status', 'Active')->orderByDesc('id')->get();
            return response()->json([
                'status' => 200,
                'c_record' => $current_details,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    // fetch details when edit button is clicked...
    public function getSession($id)
    {
        if (auth('sanctum')->check()) {
            $currentDetails = CurrentSession::where('id', $id)->first();
            if ($currentDetails) {
                return response()->json([
                    'status' => 200,
                    'current_Details' => $currentDetails,
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

    // update record here...
    public function updateCurrentSession(Request $request)
    {
        if (auth('sanctum')->check()) {

            $validator = Validator::make($request->all(), [
                'running_session' => 'required|max:191',
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
                $check_session = CurrentSession::where('id', $request->id)->first();
                if (!empty($check_session)) {
                    $check_session->update([
                        'running_session' => $request->running_session,
                    ]);
                    $logs = new Activitity_log();
                    $logs->m_username = $userDetails->username;
                    $logs->m_action = "Updated running session";
                    $logs->m_status = "Successful";
                    $logs->m_details = "$userDetails->name, Updated current academic running session details";
                    $logs->m_date = date('d/m/Y H:i:s');
                    $logs->m_uid = $userDetails->id;
                    $logs->m_ip = request()->ip;
                    $logs->m_record_id = $check_session->id;

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
    public function deleteSession($id)
    {
        $userDetails = auth('sanctum')->user();
        $delete_open = CurrentSession::find($id);
        if (!empty($delete_open)) {
            $delete_open->update([
                'session_status' => 'Deleted',
            ]);
            $logs = new Activitity_log();
            $logs->m_username = $userDetails->username;
            $logs->m_action = "Deleted current running session open";
            $logs->m_status = "Successful";
            $logs->m_details = "$userDetails->name, Delete academic current running session details";
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