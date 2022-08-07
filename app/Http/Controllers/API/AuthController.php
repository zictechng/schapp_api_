<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    // user registration code goes here
    public function registerUser(Request $request)
    {
        /* validate details here */
        $validator = Validator::make($request->all(), [

            'fname' => 'required|max:191',
            'lname' => 'required|max:191',
            'email' => 'required|email|max:191|unique:users,email,id',
            'phone' => 'required|max:11',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json([
                $errors = $validator->errors(),
                // 'validator_err' => $validator->messages(),
                'validation_errors' => $errors,
            ]);
        } elseif (empty($errors)) {
            $user = User::create([
                'name' => $request->fname . ' ' . $request->lname,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);
            // generate token and assigned to use record
            $token = $user->createToken($user->email . '_token')->plainTextToken;
            return response()->json([
                'status' => 200,
                'username' => $user->name,
                'token' => $token,
                'message' => 'Registration Successful.'
            ]);
        }
        // if no validation errors, proceed and register user
        else {
            return response()->json([
                'status' => 401,
                // 'validator_err' => $validator->messages(),
                'message' => 'Something went wrong! Try again',
            ]);
        }
    }


    // function to login here
    public function loginUser(Request $request)
    {
        /* validate details here */
        $validator = Validator::make($request->all(), [

            'email' => 'required|email|max:191',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json([
                $errors = $validator->errors(),
                // 'validator_err' => $validator->messages(),
                'validation_errors' => $errors,
            ]);
        } else {
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 401,
                    // 'validator_err' => $validator->messages(),
                    'message' => 'Invalid Credentials',
                ]);
                // throw ValidationException::withMessages([
                //     'email' => ['The provided credentials are incorrect.'],
                // ]);
            } else {
                /* this will help check user role permission */
                if ($user->role == 'Admin') // 1= admin, 0 = normal user
                {
                    $role = 'admin';
                    $token = $user->createToken($user->email . '_AdminToken', ['server:admin'])->plainTextToken;
                } else if ($user->role == 'User') {
                    $role = 'user';
                    $token = $user->createToken($user->email . '_Token', [''])->plainTextToken;
                } else {
                    $role = 'staff';
                    $token = $user->createToken($user->email . '_Token', [''])->plainTextToken;
                }
                /* ends here */

                return response()->json([
                    'status' => 200,
                    'username' => $user->name,
                    'token' => $token,
                    'message' => 'Logged In Successful.',
                    'role' => $user->role,
                    'userDetails' => $user,
                    'email' => $user->email,
                ]);
            }
        }
    }

    // save the result details send from the processing page.

    public function saveProcessResult(Request $request)
    {
        dd($request->all());
    }
}