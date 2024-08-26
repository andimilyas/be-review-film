<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\Roles;
use App\Models\Users;
use App\Models\OtpCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|string|min:8|same:password',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $roleUser = Roles::where('name', 'user')->first();

        $user = Users::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'password_confirmation' => Hash::make($request->password_confirmation),
            'role_id' => $roleUser->id
        ]);

        $user->generateOtpCode();

        $token = JWTAuth::fromUser($user);
        
        Mail::to($user->email)->queue(new RegisterMail($user));

        return response()->json([
            "message" => "Register berhasil",
            "user" => $user,
            "token" => $token
        ], 201);
    }

    public function generateOtpCode (Request $request) {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = Users::where('email', $request->email)->first();

        $user->generateOtpCode();

        $token = JWTAuth::fromUser($user);
        
        Mail::to($user->email)->queue(new OtpMail($user));
        
        return response()->json([
            "message" => "Berhasil generate ulang OTP",
            "user" => $user
        ]);
    }

    public function accountVerification(Request $request){
        $request->validate([
            'otp' => 'required'
        ]);

        //cek data otp code di db sudah ada atau belum
        $otp_code = OtpCode::where('otp', $request->otp)->first();

        if (!$otp_code) {
            return response()->json([
                "message" => "OTP code tidak ditemukan"
            ], 404);
        }

        $now = Carbon::now();
        
        //cek otp sudah kadaluarsa atau belum
        if ($now > $otp_code->valid_until) {
            return response()->json([
                "message" => "OTP Code sudah kadaluarsa, silahkan generate ulang",
            ], 400);
        }

        //update user
        $user = Users::find($otp_code->user_id);
        $user->email_verified_at = $now;

        $user->save();

        $otp_code->delete();

        return response()->json([
            "message" => "Berhasil verifikasi akun"
        ], 200);

    }

    public function getUser()
    {
        $user = auth()->user();

        $currentUser = Users::with('profile','historyReview')->find($user->id);

        return response()->json([
            "message" => "Berhasil get user",
            "user" => $currentUser
        ]);
    }

    public function signin(Request $request)
    {
        $credentials = request([
            'email', 
            'password'
        ]);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'User invalid'
            ], 401);
        }

        $userData = Users::with('role')->where('email', $request['email'])->first();

        $token = JWTAuth::fromUser($userData);

        return response()->json([
            'message' => 'Login berhasil',
            "user" => $userData,
            "token" => $token
        ]);
        
    }

    public function logout()
    {
        auth()->logout();

        return response()->json([
            'message' => 'Success Sign Out'
        ]);
    }

    public function updateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]); 

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $currentUser = auth()->user();

        $userId = Users::find($currentUser->id);

        $userId->name = $request['name'];

        $userId->save();

        return response()->json([
            "message" => "Data berhasil diupdate",
            "user" => $currentUser
        ], 201);

    }
}
