<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthApiController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'c_password' => 'required|same:password',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'خطأ في التحقق من البيانات',
                    'error_code' => 'VALIDATION_ERROR',
                    'details' => $validator->errors()
                ], 422);
            }

            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $token = $user->createToken('MyApp')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'تم إنشاء الحساب بنجاح',
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email
                    ]
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء إنشاء الحساب',
                'error_code' => 'REGISTRATION_ERROR',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'خطأ في التحقق من البيانات',
                    'error_code' => 'VALIDATION_ERROR',
                    'details' => $validator->errors()
                ], 422);
            }

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                $token = $user->createToken('MyApp')->plainTextToken;

                return response()->json([
                    'status' => 'success',
                    'message' => 'تم تسجيل الدخول بنجاح',
                    'data' => [
                        'token' => $token,
                        'user' => [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email
                        ]
                    ]
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'فشل تسجيل الدخول',
                'error_code' => 'INVALID_CREDENTIALS',
                'details' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة'
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء تسجيل الدخول',
                'error_code' => 'LOGIN_ERROR',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
