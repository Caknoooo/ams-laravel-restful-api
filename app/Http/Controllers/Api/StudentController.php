<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index() {
        $student = Student::all();
        if ($student->count() > 0) {
            return response()->json([
                'success' => true,
                'message' => 'List Data Student',
                'data' => $student
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Student Not Found',
                'data' => null
            ], 404);
        }
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'course' => 'required|string|max:191',
            'email' => 'required|string|max:191',
            'phone' => 'required|digits_between:10,12',
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages(),
                'data' => $validator->errors()
            ], 400);
        } else {
            $student = Student::create([
                'name' => $request->input('name'),
                'course' => $request->input('course'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
            ]);

            if ($student) {
                return response()->json([
                    'success' => true,
                    'message' => 'Create Student Success',
                    'data' => $student
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Create Student Failed',
                    'data' => null
                ], 400);
            }
        }
    }

    public function show($id) {
        $student = Student::find($id);
        if ($student) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Data Student',
                'data' => $student
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Student Not Found',
                'data' => null
            ], 404);
        }
    }

    public function update(Request $request, $id) {
        $student = Student::find($id);
        if ($student) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:191',
                'course' => 'required|string|max:191',
                'email' => 'required|string|max:191',   
                'phone' => 'required|digits_between:10,12',
            ]);

            if($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->messages(),
                    'data' => $validator->errors()
                ], 400);
            } else {
                $student->update([
                    'name' => $request->input('name'),
                    'course' => $request->input('course'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                ]);

                if ($student) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Update Student Success',
                        'data' => $student
                    ], 201);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Update Student Failed',
                        'data' => null
                    ], 400);
                }
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Student Not Found',
                'data' => null
            ], 404);
        }
    }

    public function destroy($id) {
        $student = Student::find($id);
        if ($student) {
            $student->delete();
            return response()->json([
                'success' => true,
                'message' => 'Delete Student Success',
                'data' => $student
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Delete Student Failed',
                'data' => null
            ], 400);
        }
    }
}
