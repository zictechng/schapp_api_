<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Activitity_log;
use App\Models\GeneratePin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GeneratePinController extends Controller
{
    // start to generate pin here....
    public function startPIN(Request $request)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

            $tid = substr(str_shuffle($permitted_chars), 0, 16);
            // validate input entry first
            $validator = Validator::make($request->all(), [
                'pin_number' => 'required|max:191',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 422,
                    'errors' => $errors,
                ]);
            } else {
                for ($i = 1; $i <= $request->pin_number; $i++) {
                    //$pin = rand(0000,9999);
                    $pin = substr(str_shuffle('0123456789ABCDEF'), 6, 10);
                    $save_pin = new GeneratePin();
                    $save_pin->card_pin = $pin;
                    $save_pin->card_status = 'Pending';
                    $save_pin->card_date = date('d/m/Y H:i:s');
                    $save_pin->card_addedby = $userDetails->username;
                    $save_pin->card_tid = $tid;
                    $save_pin->save();
                    //dd($pin);
                }
                // if everything is fine create history
                if ($save_pin->save()) {
                    $logs = new Activitity_log();
                    $logs->m_username = $userDetails->username;
                    $logs->m_action = "Generate Pin";
                    $logs->m_status = "Successful";
                    $logs->m_details = "Scratch card pins details added";
                    $logs->m_date = date('d/m/Y H:i:s');
                    $logs->m_uid = $userDetails->id;
                    $logs->m_ip = request()->ip;
                    $logs->save();
                }

                return response()->json([
                    $errors = $validator->errors(),
                    'status' => 200,
                    'message' => 'Pins Generated Successfully',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    // fetch all generated pin and group by TID
    public function fetchPIN()
    {
        // always check if user login before requesting this route
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            //query database to fetch details here
            $get_pinsAll = DB::table('generate_pins')
                ->selectRaw('count(card_pin) as total_pin, id, card_status, card_addedby
                ,card_date, card_tid')
                ->orderBy('card_date', 'desc')
                ->groupBy('card_tid')
                ->get();
            if (!empty($get_pinsAll)) {
                return response()->json([
                    'status' => 200,
                    'resultAll' => $get_pinsAll,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'No details found!',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    // fetch all pins base on the batch number passed here...
    public function getAllPins($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $get_pin_id = GeneratePin::where('id', $id)->first();
            $pinsDetails = GeneratePin::where('card_tid', $get_pin_id->card_tid)->get();
            if (!empty($pinsDetails)) {
                return response()->json([
                    'status' => 200,
                    'attenDetails' => [
                        'proDetails' => $pinsDetails,
                        'pDetails' => $get_pin_id,
                    ]
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "No record found at the moment",
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }
    // activate single pin here...
    public function activatePin($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $mark_pin = GeneratePin::where('id', $id)->first();
            // activate pin in pin table here...
            if (!empty($mark_pin)) {
                $mark_pin->update([
                    'card_status' => "Active",
                ]);
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Scratch card activated";
                $logs->m_details = "Scratch card pins details was activated";
                $logs->m_status = "Successful";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Activated Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "No record found at the moment",
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // delete pin operation here...
    public function deletePin($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $mark_pin = GeneratePin::where('id', $id)->first();
            // delete from pin table here...
            if (!empty($mark_pin)) {
                $mark_pin->update([
                    'card_status' => "Deleted",
                ]);
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Scratch card deleted";
                $logs->m_details = "Scratch card pins details was deleted";
                $logs->m_status = "Successful";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Deleted Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "No record found at the moment",
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // activate all scratch card at once by the TID
    public function activateAllPin($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();

            $get_all_Attend = GeneratePin::where('card_tid', $id)
                ->first();
            // Activate card pin table here...
            if (!empty($get_all_Attend)) {
                // update multiple row here...
                GeneratePin::query()
                    ->where('card_tid', $get_all_Attend->card_tid)
                    ->where('card_status', '!=', 'Deleted')
                    ->update([
                        'card_status' => 'Active',
                    ]);

                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Scratch card activated";
                $logs->m_details = "Scratch card pins details was activated";
                $logs->m_status = "Successful";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Card Activated Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "No active pins at the moment",
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // de-activate single scratch card here

    public function deActivatePin($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $mark_pin = GeneratePin::where('id', $id)
                ->where('card_status', '!=', 'Pending')->first();
            // delete from pin table here...
            if (!empty($mark_pin)) {
                $mark_pin->update([
                    'card_status' => "Pending",
                ]);
                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Scratch card de-activated";
                $logs->m_details = "Scratch card pins details was de-activated";
                $logs->m_status = "Successful";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'De-activated Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "No active card found at the moment",
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, login to continue',
            ]);
        }
    }

    // De-activate all scratch card at once here...
    public function deActivateAllPin($id)
    {
        if (auth('sanctum')->check()) {
            $userDetails = auth('sanctum')->user();
            $de_all_Attend = GeneratePin::where('card_tid', $id)
                ->first();
            // Activate card pin table here...
            if (!empty($de_all_Attend)) {
                // update multiple row here...
                GeneratePin::query()
                    ->where('card_tid', $de_all_Attend->card_tid)
                    ->where('card_status', '!=', 'Deleted')
                    ->update([
                        'card_status' => 'Pending',
                    ]);

                // history record here...
                $logs = new Activitity_log();
                $logs->m_username = $userDetails->username;
                $logs->m_action = "Scratch card de-activated";
                $logs->m_details = "Scratch card pins details was de-activated";
                $logs->m_status = "Successful";
                $logs->m_date = date('d/m/Y H:i:s');
                $logs->m_uid = $userDetails->id;
                $logs->m_ip = request()->ip;
                $logs->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Card De-activated Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "No record found at the moment",
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