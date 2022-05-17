<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPassword;
use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller {
    public function login(Request $request) {

        $request->validate([
            'email'             =>  'required|email',
            'password'          =>  'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                "status"    =>  false,
                "message"   =>  "This email doesn't exist"
            ], 401);
        }

        $credentials = ['email' => $user->email, 'password' => $request->password];
        if(!Auth::attempt($credentials)){
            return response()->json([
                'status'    => false,
                'message'   =>'provided credentials are incorrect',
            ], 401);
        }

        Auth::user()->tokens()->delete();
        $user = $request->user();
        $tokenResult = $user->createToken('auth_token')->plainTextToken;

        if ($user->image){
            if (Storage::disk('local')->exists('public/users/'.$user->image)){
                $user->image = url('/').'/storage/users/'.$user->image;
            }else{
                $user->image = null;
            }
        }
        //$this->authenticated($request,$user);

//        $user = getUserData($user->id);
//
//        if (!$user->email_verified_at) {
//            Auth::logout();
//            return response()->json([
//                "status"        =>  false,
//                "verified"      =>  false,
//                "message"       =>  'Your account not verified'
//            ], 200);
//        }

        return response()->json([
            'status'        => true,
            "verified"      => true,
            'message'       => 'Login Success',
            'user'          =>  $user,
            'access_token'  =>  $tokenResult,
            'token_type'    =>  'Bearer'
        ], 200);

    }

    public function register(Request $request) {

        $request->validate([
            'name'              =>  'required|string',
            'email'             =>  'required|email|unique:users,email',
            'password'          =>  'required',
            'confirmPassword'   =>  'required',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = 'User';
        $user->password = Hash::make($request->password);
        $user->save();

        $this->sendVerifyEmailNotification($user);

        return response()->json([
            'status'        => true,
            'message'       => 'Account Created successfully',
        ], 201);
    }

    public function forgotPassword(Request $request) {
        $request->validate([
            'email'       => [
                'required', function ($attribute, $value, $fail) {
                    if (!User::where('email', $value)->first()) {
                        $fail("Email doesn't exist");
                    }
                },
            ],
        ]);

        $user = User::where('email', $request->email)->first();

        $verify_code = mt_rand(100000,999999);

        $code_validity = date("Y-m-d h:i:s", strtotime( date( "Y-m-d h:i:s", strtotime( date("Y-m-d h:i:s") ) ) . "+10 minute" ) );
        $user->verify_code = $verify_code;
        $user->code_validity = $code_validity;
        $user->save();

        Mail::to($user->email)->send(new ForgotPassword($user, $verify_code));

        return response()->json([
            'status'        =>  true,
            'message'       =>  "We have sent a verification code to your email, please check and enter the code.",
        ], 200);

    }

    public function verifyForgotPassword(Request $request) {
        $request->validate([
            'email'       => [
                'required', function ($attribute, $value, $fail) {
                    if (!User::where('email', $value)->first()) {
                        $fail("Email doesn't exist");
                    }
                },
            ],
            'code' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        $status = true;
        $message = 'Code verify success';

        if ( $user->verify_code != $request->code ){
            $status = false;
            $message = 'Invalid Code';
        }

        if ( $user->code_validity < date("Y-m-d h:i:s") ){
            $status = true;
            $message = 'The validity of the code has expired';
        }

        return response()->json([
            'status'        =>  $status,
            'message'       =>  $message,
        ], 200);

    }

    public function resetPassword(Request $request) {
        $request->validate([
            'password'  => 'required|min:8',
            'confirmPassword'   => 'required|same:password',
            'email'       => [
                'required', function ($attribute, $value, $fail) {
                    if (!User::where('email', $value)->first()) {
                        $fail("Email doesn't exist");
                    }
                },
            ],
            'code'              => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ( $user->verify_code != $request->code ){
            return response()->json([
                'status'        =>  false,
                'message'       =>  'Invalid Code'
            ], 200);
        }

        if ( $user->code_validity < date("Y-m-d h:i:s") ){
            return response()->json([
                'status'        =>  false,
                'message'       =>  'The validity of the code has expired'
            ], 200);
        }

        $user->password = Hash::make($request->confirmPassword);
        $user->verify_code = null;
        $user->code_validity = null;
        $user->save();

        return response()->json([
            'status'        =>  true,
            'message'       =>  'Password reset successfully'
        ], 200);
    }

    public function logout (Request $request) {
        Auth::user()->tokens()->delete();
        return response()->json([
            'status'=> true,
            'message'=> 'Logout Success'
        ], 200);
    }

    public function verifyEmail(Request $request) {
        $request->validate([
            'code'      => 'required',
            'email'     => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message'   =>  "Email doesn't Exist"
            ], 200);
        }
        if ($user->verify_code != $request->code){
            return response()->json([
                'status' => false,
                'message'   =>  "Invalid code"
            ], 200);
        }

        $user->markEmailAsVerified();
        $user->verify_code = null;
        $user->save();

        return response()->json([
            'status' => true,
            'message'   =>  "Email has been verified."
        ], 200);
    }

    public function sendVerificationCode(Request $request) {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();
        $send = $this->sendVerifyEmailNotification($user);
        if ($send){
            return response()->json([
                'status'        => true,
                'message'       => 'Verification code has been sent to your email.',
            ], 200);
        }else{
            return response()->json([
                'status'        => false,
                'message'       => 'Something went wrong!',
            ], 200);
        }

    }

    private function sendVerifyEmailNotification($user) {

        $verify_code = mt_rand(100000,999999);

        $user = User::where('email', $user->email)->first();
        $user->verify_code = $verify_code;
        $user->save();

        Mail::to($user->email)->send(new SendEmailVerificationCode($user, $verify_code));

        return true;
    }

    public function changePassword(Request $request) {
        $request->validate([
            'oldPassword'       => 'required',
            'newPassword'       => 'required',
            'confirmPassword'   => 'required',
        ]);

        $user = Auth::user();
        $old_password = $request->oldPassword;
        $new_password = $request->newPassword;
        $confirm_password = $request->confirmPassword;

        $userInfo = User::where('email', '=', $user->email)->first();
        if (!Hash::check($old_password, $userInfo->password)){
            return response()->json([
                'status'        =>  false,
                'message'       =>  'Incorrect old password',
            ], 200);
        }

        if ($new_password !== $confirm_password){
            return response()->json([
                'status'        =>  false,
                'message'       =>  "Don't match confirm password",
            ], 200);
        }

        $userInfo->password = Hash::make($confirm_password);
        $userInfo->save();

        return response()->json([
            'status'        =>  true,
            'message'       =>  "Password changed successfully, Please login again.",
        ], 200);
    }





//    /**
//    * Create a new AuthController instance.
//    *
//    * @return void
//    */
//    public function __construct()
//    {
//        $this->middleware('auth:api', ['except' => ['login']]);
//    }
//
//    /**
//     * Get a JWT token via given credentials.
//     *
//     * @param Request $request
//     *
//     * @return JsonResponse
//     */
//    public function login(Request $request)
//    {
//        $request->validate([
//            'email' => 'required|email',
//            'password' => 'required'
//        ]);
//
//        $credentials = $request->only('email', 'password');
//
//        if ($token = $this->guard()->attempt($credentials)) {
//            return $this->respondWithToken($token);
//        }
//
//        return response()->json(['error' => 'Unauthorized'], 401);
//    }
//
//    /**
//     * Get the authenticated User
//     *
//     * @return JsonResponse
//     */
//    public function me()
//    {
//        //return response()->json($this->guard()->user());
//        return response()->json(['success' => true, 'user' => $this->guard()->user()]);
//    }
//
//    /**
//     * Log the user out (Invalidate the token)
//     *
//     * @return JsonResponse
//     */
//    public function logout()
//    {
//        $this->guard()->logout();
//
//        return response()->json(['success' => true, 'message' => 'Successfully logged out']);
//    }
//
//    /**
//     * Refresh a token.
//     *
//     * @return JsonResponse
//     */
//    public function refresh()
//    {
//        return $this->respondWithToken($this->guard()->refresh());
//    }
//
//    /**
//     * Get the token array structure.
//     *
//     * @param  string $token
//     *
//     * @return JsonResponse
//     */
//    protected function respondWithToken($token)
//    {
//        return response()->json([
//            'access_token' => $token,
//            'token_type' => 'bearer',
//            'expires_in' => $this->guard()->factory()->getTTL() * 60
//        ]);
//    }
//
//    /**
//     * Get the guard to be used during authentication.
//     *
//     * @return Guard
//     */
//    public function guard()
//    {
//        return Auth::guard();
//    }

}
