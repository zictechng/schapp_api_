<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\Activitity_log;
use App\Models\FinanceReport;
use App\Models\GeneratePin;
use App\Models\ResultCA;
use App\Models\ResultTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UploadFilesController extends Controller
{
    // upload pin file here...

    public function uploadPinFile(Request $request)
    {
        //dd($request->all());
        $userDetails = auth('sanctum')->user();
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $tid = substr(str_shuffle($permitted_chars), 0, 16);

        if ($request->file_pin == "undefined" || $request->file_pin == " ") {
            return response()->json([
                'status' => 422,
                'message' => "Select File Please",
            ]);
            if (!$request->hasFile('file_pin')) {
                return response()->json([
                    'status' => 403,
                    'message' => "Empty File Selected",
                ]);
            }
        } else {
            foreach ($request->data as $data) {
                GeneratePin::create([
                    'card_pin' => $data['Pin'],
                    'card_status' => 'Pending',
                    'card_date' => date('d/m/Y H:i:s'),
                    'card_addedby' => $userDetails->username,
                    'card_tid' => $tid,
                ]);
            }
            $tID = FinanceReport::where('card_tid', '=', $tid)->first();
            if ($tID) {
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Pin Uploaded";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Upload student scratch card pin details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();
                return response()->json([
                    'status' => 200,
                    'message' => "Pin uploaded successfully",
                ]);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => "Failed! Try again",
                ]);
            }
        }
    }

    // upload finance report here...
    public function uploadFinanceReport(Request $request)
    {
        $userDetails = auth('sanctum')->user();
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $tid = substr(str_shuffle($permitted_chars), 0, 16);
        if ($request->file_pin == "undefined" || $request->file_pin == " ") {
            return response()->json([
                'status' => 422,
                'message' => "Select File Please",
            ]);
            if (!$request->hasFile('file_pin')) {
                return response()->json([
                    'status' => 403,
                    'message' => "Empty File Selected",
                ]);
            }
        } else {
            foreach ($request->data as $data) {
                FinanceReport::create([
                    'amt' => $data['amt'],
                    'type' => $data['type'],
                    'qty' => $data['qty'],
                    'nature' => $data['nature'],
                    'disc' => $data['disc'],
                    'addedby' => $userDetails->username,
                    'status' => 'Pending',
                    'fin_tid' => $tid,
                    'add_date' => date('d/m/Y H:i:s'),
                ]);
            }
            $tID = FinanceReport::where('fin_tid', '=', $tid)->first();
            if ($tID) {
                // history record here...
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Finance report uploaded";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Upload finance report details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();
                return response()->json([
                    'status' => 200,
                    'message' => "Finance report uploaded successfully",
                ]);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => "Failed! Try again",
                ]);
            }
        }
    }

    // upload CA file here...
    public function uploadCAReport(Request $request)
    {
        $userDetails = auth('sanctum')->user();
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $tid = substr(str_shuffle($permitted_chars), 0, 16);

        if ($request->file_pin == "undefined" || $request->file_pin == " ") {
            return response()->json([
                'status' => 422,
                'message' => "Select File Please",
            ]);
            if (!$request->hasFile('file_pin')) {
                return response()->json([
                    'status' => 403,
                    'message' => "Empty File Selected",
                ]);
            }
        } else {
            foreach ($request->data as $data) {
                ResultCA::create([
                    'st_admin_id' => $data['st_admin_id'],
                    'st_name' => $data['st_name'],
                    'ca1' => $data['ca1'],
                    'ca2' => $data['ca2'],
                    'ca_total' => $data['ca_total'],
                    'rst_year' => $data['rst_year'],
                    'rst_term' => $data['rst_term'],
                    'rst_subject' => $data['rst_subject'],
                    'rst_category' => $data['rst_category'],
                    'rst_class' => $data['rst_class'],
                    'rst_tid' => $tid,
                    'rst_date' => date('d/m/Y H:i:s'),
                    'rst_status' => 'Active',
                    'rst_addby' => $userDetails->username,
                ]);
            }
            $tID = ResultCA::where('rst_tid', '=', $tid)->first();
            if ($tID) {
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "CA report uploaded";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Upload CA report details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();
                return response()->json([
                    'status' => 200,
                    'message' => "CA report uploaded successfully",
                ]);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => "Failed! Try again",
                ]);
            }
        }
    }

    // upload result file here...
    public function uploadResultReport(Request $request)
    {
        $userDetails = auth('sanctum')->user();
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $tid = substr(str_shuffle($permitted_chars), 0, 16);

        if ($request->file_pin == "undefined" || $request->file_pin == " ") {
            return response()->json([
                'status' => 422,
                'message' => "Select File Please",
            ]);
            if (!$request->hasFile('file_pin')) {
                return response()->json([
                    'status' => 403,
                    'message' => "Empty File Selected",
                ]);
            }
        } else {
            foreach ($request->data as $data) {
                ResultTable::create([
                    'admin_number' => $data['admin_number'],
                    'academic_year' => $data['academic_year'],
                    'academy_term' => $data['academy_term'],
                    'subject' => $data['subject'],
                    'class' => $data['class'],
                    'school_category' => $data['school_category'],
                    'first_ca' => $data['first_ca'],
                    'second_ca' => $data['second_ca'],
                    'tca_score' => $data['tca_score'],
                    'exam_scores' => $data['exam_scores'],
                    'total_scores' => $data['total_scores'],
                    'grade' => $data['grade'],
                    'remark' => $data['remark'],
                    'position' => $data['position'],
                    'average_scores' => $data['average_scores'],
                    'tid_code' => $tid,
                    'username' => $userDetails->username,
                    'student_name' => $data['student_name'],
                    'result_date' => date('d/m/Y H:i:s'),
                    'result_status' => 'Active',
                    'result_lowest' => $data['result_lowest'],
                    'result_highest' => $data['result_highest'],

                ]);
            }
            $tID = ResultTable::where('tid_code', '=', $tid)->first();
            if ($tID) {
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Result report uploaded";
                $logs->m_status = "Successful";
                $logs->m_details = "$userDetails->name, Upload result report details";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();
                return response()->json([
                    'status' => 200,
                    'message' => "Result report uploaded successfully",
                ]);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => "Failed! Try again",
                ]);
            }
        }
    }
}