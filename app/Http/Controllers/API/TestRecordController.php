<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestRecordController extends Controller
{
    //save text record here

    public function processSaveResult(Request $request)
    {
        dd($request->all());

        return response()->json([
            'status' => 200,
            'message' => "something went good! Thank You",
        ]);
    }
}