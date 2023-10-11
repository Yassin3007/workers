<?php

namespace App\Services\WorkerService\WorkerLoginService;
use App\Models\Worker;
use Illuminate\Support\Facades\Auth;

class WorkerLoginService
{
    protected $model ;
     public function __construct(){
        $this->model  =new Worker ;
     }

     function validation($request){

      return $request->validate([
         'email' => 'required|string|email',
         'password' => 'required|string',
     ]);
     }

     function is_valid($request){

      $credentials = $request->only('email', 'password');
        $token = Auth::guard('worker')->attempt($credentials);
        
        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::guard('worker')->user();
        return response()->json([
         'user' => $user,
         'authorization' => [
             'token' => $token,
             'type' => 'bearer',
         ]
     ]);
     }

     public function login($request){
       $data=$this->validation($request);
       return  $is_valid= $this->is_valid($request) ;
     }

}
