<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Activitity_log;
use App\Models\TermModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TermController extends Controller
{
    // create new term record here...

    public function saveTerm(Request $request)
    {
        if (auth('sanctum')->check()) {

            $validator = Validator::make($request->all(), [
                'term_name' => 'required|max:191',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {

                $userDetails = auth('sanctum')->user();
                $academic_term = TermModel::where('term_name', $request->term_name)->where('t_status', 'Active')->first();
                if (!empty($academic_term)) {
                    return response()->json([
                        'status' => 402,
                        'message' => 'Record exist already',
                    ]);
                } else if (empty($academic_term)) {
                    $save_term = new TermModel();
                    $save_term->term_name = $request['term_name'];
                    $save_term->add_by = $userDetails->username;
                    $save_term->t_status = "Active";
                    $save_term->t_date = date('d/m/Y H:i:s');

                    $save_term->save();

                    if ($save_term->save()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Created academic term";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, added new academic term details";
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

    // fetch all terms here

    public function fetchTerm()
    {
        if (auth('sanctum')->check()) {
            $allterm_details = TermModel::where('t_status', 'Active')->orderByDesc('id')->get();
            return response()->json([
                'status' => 200,
                'term_record' => $allterm_details,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    // get term details when edit button is click
    public function getTerm($id)
    {
        if (auth('sanctum')->check()) {
            $termDetails = TermModel::where('id', $id)->first();
            if ($termDetails) {
                return response()->json([
                    'status' => 200,
                    'termDetails' => $termDetails,
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

    // update term here...
    public function termUpdate(Request $request)
    {
        if (auth('sanctum')->check()) {

            $validator = Validator::make($request->all(), [
                'term_name' => 'required|max:191',
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
                $term = TermModel::where('id', $request->id)->first();
                if (!empty($term)) {
                    $term->update([
                        'term_name' => $request->term_name,
                    ]);
                    $logs = new Activitity_log();
                    $logs->m_username = $userDetails->username;
                    $logs->m_action = "Updated academic term";
                    $logs->m_status = "Successful";
                    $logs->m_details = "$userDetails->name, Updated academic term details";
                    $logs->m_date = date('d/m/Y H:i:s');
                    $logs->m_uid = $userDetails->id;
                    $logs->m_ip = request()->ip;
                    $logs->m_record_id = $term->id;

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
    public function deleteTerm($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $session_record = TermModel::find($id);
            if (!empty($session_record)) {
                $session_record->update([
                    't_status' => 'Deleted',
                ]);
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Deleted academic term";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Delete academic term details";
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

    // fetch all term to resumption front end page here...
    public function fetchSchoolTerm()
    {
        if (auth('sanctum')->check()) {
            $term_details = TermModel::where('t_status', 'Active')->orderByDesc('id')->get();
            return response()->json([
                'status' => 200,
                'termrecord' => $term_details,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }
}