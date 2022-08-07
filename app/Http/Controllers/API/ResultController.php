<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Activitity_log;
use App\Models\ResultProcessStart;
use App\Models\ResultTable;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResultController extends Controller
{
    // fetch all result details here...
    public function getAllResult()
    {
        if (auth('sanctum')->check()) {
            $user_details = auth('sanctum')->user();
            //$userWallet = User::where('id', $sender_details->id)->where('acct_status', 'Active')->sum('gamount');
            $all_result_details = ResultTable::where('result_status', 'Active')->orderByDesc('id')->get();
            if ($all_result_details) {
                return response()->json([
                    'status' => 200,
                    'result_record' => $all_result_details,
                ]);
            } else if (empty($$all_details)) {
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
                'message' => 'Login to continue',
            ]);
        }
    }

    // start result process here...
    public function resultProcessStart(Request $request)
    {
        /* Generate unique transaction ID for each cash request record */
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $tid = substr(str_shuffle($permitted_chars), 0, 16);
        if (auth('sanctum')->check()) {
            $validator = Validator::make($request->all(), [
                'school_year' => 'required|max:191',
                'school_term' => 'required|max:191',
                'class' => 'required|max:191',
                'subject' => 'required',
                'school_category' => 'required',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {

                $userDetails = auth('sanctum')->user();
                $check_result = ResultProcessStart::where('r_tid', $tid)->first();
                if (!empty($check_result)) {
                    return response()->json([
                        'status' => 402,
                        'message' => 'Record already exist',
                    ]);
                } else if (empty($check_result)) {
                    $save_new = new ResultProcessStart();
                    $save_new->school_year = $request['school_year'];
                    $save_new->school_term = $request['school_term'];
                    $save_new->class = $request['class'];
                    $save_new->school_category = $request['subject'];
                    $save_new->subject = $request['school_category'];
                    $save_new->r_tid = $tid;
                    $save_new->addby = $userDetails->username;
                    $save_new->r_date = date('d/m/Y H:i:s');
                    $save_new->r_status = 'Active';

                    $save_new->save();

                    if ($save_new->save()) {
                        $logs = new Activitity_log();
                        $logs->m_username = $userDetails->username;
                        $logs->m_action = "Initiated result processing";
                        $logs->m_status = "Successful";
                        $logs->m_details = "$userDetails->name, Initiated new result processing details";
                        $logs->m_date = date('d/m/Y H:i:s');
                        $logs->m_uid = $userDetails->id;
                        $logs->m_ip = request()->ip;

                        $logs->save();

                        // get the result detail to pass to front end
                        $fetch_resultdetails = ResultProcessStart::where('r_tid', $tid)->first();
                        if ($fetch_resultdetails) {
                            return response()->json([
                                'status' => 200,
                                'allResultDetails' => [
                                    'message' => 'Result Initiated Successfully',
                                    'result_record_details' => $fetch_resultdetails,
                                ]

                            ]);
                        } else if (empty($fetch_resultdetails)) {
                            return response()->json([
                                'status' => 404,
                                'message' => 'Error Occurred, Try Again',
                            ]);
                        }
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

    // process result save here...
    public function resultSave(Request $request)
    {
    }

    // fetch the result start inserted....
    public function getFetchResult($id)
    {
        $get_etDetails = ResultProcessStart::where('r_tid', $id)->first();
        $get_stDetails = Student::where('class_apply', $get_etDetails->class)->get();
        if ($get_stDetails) {
            return response()->json([
                'status' => 200,
                'all_details' => [
                    'start_item' => $get_etDetails,
                    'student_result' => $get_stDetails,
                ]

            ]);
        } else {
            return response()->json([
                'status' => 402,
                'message' => "something went wrong! Try again",
            ]);
        }
    }

    // save the result details send from the processing page.

    public function processSaveResult(Request $request)
    {
        dd($request->all());

        return response()->json([
            'status' => 200,
            'message' => "something went wrong! Try again",
        ]);
    }
}