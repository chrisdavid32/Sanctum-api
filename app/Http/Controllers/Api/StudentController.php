<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:students,email',
            'password' => 'required|confirmed'
        ]);
        $students = new Student();
        $students->name = $request->name;
        $students->email = $request->email;
        $students->password = bcrypt($request->password);
        $students->phone = isset($request->phone) ? $request->phone : "";
        // dd($students);
        $students->save();
        return response()->json([
            "status" => 200,
            "message" => 'Student created successfully'
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $student = Student::where('email', "=", $request->email)->first();
       if(isset($student->id)){
           if(Hash::check($request->password, $student->password)){
            $token = $student->createToken("auth_token")->plainTextToken;
            return response()->json([
                'message' => 'login successfully',
                'status' => 200,
                'access_token' => $token
            ]);
           }else{
               return response()->json([
                   'message' => 'Invalid user credential',
                   'status' => 404
               ]);
           }

       }else{
           return response()->json([
               'message' => 'student not found',
               'status' => 404
           ]);
       }
    }

    public function profile()
    {
        return response()->json([
            'message' => 'Student Information',
            'status' => 200,
            'data' => auth()->user()
        ]);
    }

    public function logout()
    {
        // Auth::user()->tokens->each(function($token, $key) {
        //     $token->delete();
        // });
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'logout successfully'
        ]);
    }
}
