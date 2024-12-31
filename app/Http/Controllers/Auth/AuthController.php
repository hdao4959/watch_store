<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{

    public function handleErrorNotDefine($th){
        return response()->json(
            [
                'success' => false,
                'message' => 'Có lỗi xảy ra! ' . $th
            ],500
            );
    }


    public function infor(){
        try {
            return request()->user();
        } catch (\Throwable $th) {
            return $this->handleErrorNotDefine($th);
        }
    }

    public function login(LoginRequest $request){
        try {
            $data = $request->validated();

            $account = User::where('email', $data['email'])->first();

            if($account && $data['password'] == $account->password){
                $token = $account->createToken($account->id)->plainTextToken;
                $account_name = strstr($account->email, '@', true);
                return response()->json([
                    'success' => true, 
                    'token' => $token,
                    'account_name' => $account_name,
                    'role' => $account->role
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản hoặc mật khẩu không chính xác'
            ],404);
        } catch (\Throwable $th) {
            return $this->handleErrorNotDefine($th);
        }
    }
    public function register(RegisterRequest $request){
        try {
            $data = $request->validated();
            $registered_email = User::where('email', $data['email'])->exists();

            if($registered_email){
                return response()->json([
                    'success' => false,
                    'message' => 'Email này đã được sử dụng!'
                ],422);
            }

            $data['role'] = '2';
            $user = User::create($data);
            $token = $user->createToken($user->id)->plainTextToken;
            $account_name = strstr($user->email, '@', true);
            return response()->json([
                'success' => true,
                'token' => $token,
                'account_name' => $account_name
            ]);
        } catch (\Throwable $th) {
            return $this->handleErrorNotDefine($th);
        }
    }

    public function logout(Request $request){
        try {
            if(!$request->user()){
                return response()->json([
                    'success' => false,
                    'message' => 'Phiên đăng nhập của bạn đã hết!'
                ],403);
            }


            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'success' => true,
                'message' => 'Đăng xuất thành công!'
            ]);
        } catch (\Throwable $th) {
            return $this->handleErrorNotDefine($th);
        }
    }

}
