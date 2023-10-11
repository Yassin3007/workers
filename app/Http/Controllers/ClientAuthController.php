<?php 
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Worker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ClientAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:client', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        $token = Auth::guard('client')->attempt($credentials);
        
        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::guard('client')->user();
        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function register(Request $request)
    {
        // return response()->json([
        //     'fsdfgfs'
        // ]);
        $rules = [

            //            "name" => "required",
            //            "phone" => "required",
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients',
            'password' => 'required|string|min:6',
            'photo'=> 'required|image',
           
                    ];
                    $validator = Validator::make($request->all(), $rules);
                    if ($validator->fails()){
                        return response()->json([
                            'msg'=>'تحقق من ادخال كافة البيانات ',
                            'code'=>'404',
                            'errors'=>$validator->errors()
                        ]);
                    }
   

        $user = Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'photo'=>$request->file('photo')->store('clients'),

        ]);
        

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }

    public function logout()
    {
        Auth::guard('client')->logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'user' => Auth::guard('client')->user(),
            'authorisation' => [
                'token' => Auth::guard('worker')->refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
    public function userProfile(){
        return response()->json([
            Auth::guard('client')->user()
        ]);
    }
}