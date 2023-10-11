<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Post;
use App\Models\Worker;
use App\Models\WorkerReview;
use Illuminate\Http\Request;
use App\Http\Requests\Worker\UpdatingProfileRequest;
use App\Services\WorkerService\UpdatingProfileService;

class WorkerProfileController extends Controller
{
    function userProfile()  {
        $worker_id = auth('worker')->id();
        $worker =Worker::with('posts.reviews')->findOrFail($worker_id)->makeHidden('status','verified_at','verification_token');
        $reviews = WorkerReview::whereIn('post_id',$worker->posts->pluck('id'))->get();
        $rate = round($reviews->sum('rate')/$reviews->count(),1) ;
        return response()->json([
            'data'=>array_merge( $worker->toArray() ,['rate'=>$rate]),
            
        ]);
    }

    function edit()  {
        
        return response()->json([
            'worker'=>Worker::findOrFail( auth('worker')->id())->makeHidden('status','verified_at','verification_token')
        ]);
    }

    public function update(Request $request)
    {
        try{
            $request->validate([
                'name' => 'required|string|between:2,100',
                'email' => 'required|string|email|max:100|unique:workers,email,' . auth()->guard('worker')->id(),
                'password' => 'nullable|string|min:6',
                'phone' => 'required|string|max:17',
                'photo' => 'nullable',
                'location' => 'required|string|min:6',
            ]);
            return (new UpdatingProfileService())->update($request);

        }catch(Exception $e){
            return response()->json([
                'errors'=>$e->getMessage()
            ]) ;
        }
    }
    public function delete()
    {
        Post::where('worker_id', auth()->guard('worker')->id())->delete();
        return response()->json([
            "message" => "deleted"
        ]);
    }
}
