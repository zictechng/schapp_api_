<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Activitity_log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AcademicSessionController extends Controller
{
    // save academic here...

    public function saveAcademic(Request $request)
    {
        if (auth('sanctum')->check()) {

            $validator = Validator::make($request->all(), [
                'academic_name' => 'required|max:191',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {

                $userDetails = auth('sanctum')->user();
                $academic = AcademicSession::where('academic_name', $request->academic_name)->first();
                if (!empty($academic)) {
                    return response()->json([
                        'status' => 402,
                        'message' => 'Record exist already',
                    ]);
                } else if (empty($academic)) {
                    $save_academic = new AcademicSession();
                    $save_academic->academic_name = $request['academic_name'];
                    $save_academic->add_by = $userDetails->username;
                    $save_academic->a_status = "Active";
                    $save_academic->a_date = date('d/m/Y H:i:s');

                    $save_academic->save();

                    if ($save_academic->save()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Created academic session";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, added academic session details";
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

    // fetch all session here...

    public function fetch_session()
    {
        if (auth('sanctum')->check()) {

            $user_details = auth('sanctum')->user();
            // $allsession_details = AcademicSession::where('a_status', 'Active')->orderByDesc('id')->get();
            $allsession_details = AcademicSession::query()
                ->where('a_status', 'Active')
                ->orderByDesc('id')
                ->paginate('15');
            if ($allsession_details) {
                return response()->json([
                    'status' => 200,
                    'session_record' => $allsession_details,
                ]);
            } else if (empty($allsession_details)) {
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

    // fetch data when button is clicked here...

    public function get_session($id)
    {
        if (auth('sanctum')->check()) {
            $sessionDetails = AcademicSession::where('id', $id)->first();
            if ($sessionDetails) {
                return response()->json([
                    'status' => 200,
                    'sessionDetails' => $sessionDetails,
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

    // update academic session here....

    public function update_session(Request $request)
    {
        if (auth('sanctum')->check()) {

            $validator = Validator::make($request->all(), [
                'academic_name' => 'required|max:191',
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
                $session_academic = AcademicSession::where('id', $request->id)->first();
                if (!empty($session_academic)) {
                    $session_academic->update([
                        'academic_name' => $request->academic_name,
                    ]);
                    $logs = new Activitity_log();
                    $logs->m_username = $userDetails->username;
                    $logs->m_action = "Updated session";
                    $logs->m_status = "Successful";
                    $logs->m_details = "$userDetails->name, Updated academic details";
                    $logs->m_date = date('d/m/Y H:i:s');
                    $logs->m_uid = $userDetails->id;
                    $logs->m_ip = request()->ip;
                    $logs->m_record_id = $session_academic->id;

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
        $seesion_record = AcademicSession::find($id);
        if (!empty($seesion_record)) {
            $seesion_record->update([
                'a_status' => 'Deleted',
            ]);
            $logs = new Activitity_log();
            $logs->m_username = $userDetails->username;
            $logs->m_action = "Deleted academic session";
            $logs->m_status = "Successful";
            $logs->m_details = "$userDetails->name, Delete academic session details";
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

    // fetch all school session to school resumption front end
    public function fetchSchoolSession()
    {
        if (auth('sanctum')->check()) {
            $sessDetails = AcademicSession::where('a_status', 'Active')->get();
            if ($sessDetails) {
                return response()->json([
                    'status' => 200,
                    'session_Details' => $sessDetails,
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
}