<?php 
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Worker;
use App\Services\WorkerService\WorkerRegisterService\WorkerRegisterService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\WorkerService\WorkerLoginService\WorkerLoginService;

class WorkerAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:worker', ['except' => ['login', 'register','verify']]);
    }

    public function login(Request $request)
    {
        // $request->validate([
        //     'email' => 'required|string|email',
        //     'password' => 'required|string',
        // ]);
        // $credentials = $request->only('email', 'password');
        // $token = Auth::guard('worker')->attempt($credentials);
        
        // if (!$token) {
        //     return response()->json([
        //         'message' => 'Unauthorized',
        //     ], 401);
        // }

        // $user = Auth::guard('worker')->user();
        // return response()->json([
        //     'user' => $user,
        //     'authorization' => [
        //         'token' => $token,
        //         'type' => 'bearer',
        //     ]
        // ]);

        return (new WorkerLoginService())->login($request);
    }

    public function register(Request $request)
    {
        // $rules = [

        //     //            "name" => "required",
        //     //            "phone" => "required",
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:workers',
        //     'password' => 'required|string|min:6',
        //     'phone' => 'required|string|min:6',
        //     'photo'=> 'required|image',
        //     'location'=> 'required|string|min:6',
        //             ];
        //             $validator = Validator::make($request->all(), $rules);
        //             if ($validator->fails()){
        //                 return response()->json([
        //                     'msg'=>'تحقق من ادخال كافة البيانات ',
        //                     'code'=>'404',
        //                     'errors'=>$validator->errors()
        //                 ]);
        //             }
        // // $request->validate([
        // //     'name' => 'required|string|max:255',
        // //     'email' => 'required|string|email|max:255|unique:admins',
        // //     'password' => 'required|string|min:6',
        // // ]);

        // $user = Worker::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        //     'phone'=> $request->phone ,
        //     'photo'=>$request->file('photo')->store('workers'),
        //     'location'=>$request->location

        // ]);
        

        // return response()->json([
        //     'message' => 'User created successfully',
        //     'user' => $user
        // ]);


        return (new WorkerRegisterService())->register($request);
    }

    public function verify($token){

        $worker = Worker::whereVerificationToken($token)->first();
        if(!$worker){
            return response()->json([
                'message'=>'this token is invalid'
            ]);
        }
        $worker->verification_token = null ;
        $worker->verified_at = now();
        $worker->save();
        return response()->json([
            'message'=>'your account has been verified succesfully'
        ]);
    }

    public function logout()
    {
        Auth::guard('worker')->logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'user' => Auth::guard('worker')->user(),
            'authorisation' => [
                'token' => Auth::guard('worker')->refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
    public function userProfile(){
        return response()->json([
            Auth::guard('worker')->user()
        ]);
    }
}