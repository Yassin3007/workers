<?php

namespace App\Http\Controllers\AdminDashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminNotificationController extends Controller
{
    public function index(){
        $admin = Admin::findOrFail(auth('admin')->user()->id);
        return response()->json([
            'notifications'=>$admin->notifications
        ]);
    }
    public function unread(){
        $admin = Admin::findOrFail(auth('admin')->user()->id);
        return response()->json([
            'notifications'=>$admin->unreadNotifications 
        ]);
    }

    public function markRead(){
        $admin = Admin::findOrFail(auth('admin')->user()->id);
        foreach ($admin->unreadNotifications as $notification) {
            $notification->markAsRead();
        }
    }
    

    public function deleteAll(){
        $admin = Admin::findOrFail(auth('admin')->user()->id);
        $admin->notifications()->delete();
        return response()->json([
            'message'=>'deleted'
        ]);
    }

    public function delete($id){

        DB::table('notifications')->where('id',$id)->delete();
        return response()->json([
            'message'=>'deleted'
        ]);
    }
}
