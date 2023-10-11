<?php

namespace App\Http\Controllers\AdminDashboard;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Notifications\AdminPost;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\Posts\PostStatusRequest;


class PostStatusController extends Controller
{
    public function changeStatus(PostStatusRequest $request){

        $post =Post::findOrFail($request->post_id);
        $post->update([
            'status'=>$request->status ,
            'rejected_reason'=>$request->rejected_reason
        ]);
        Notification::send($post->worker , new AdminPost($post->worker ,$post));
        return response()->json([
            'message'=>'status has been changed successfully'
        ]);
    }
}
