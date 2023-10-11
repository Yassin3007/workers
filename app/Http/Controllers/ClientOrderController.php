<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\ClientOrderRequest;
use App\Interfaces\CrudRepoInterface;
use App\Models\ClientOrder;
use Illuminate\Http\Request;

class ClientOrderController extends Controller
{

 
    public function addOrder(ClientOrderRequest $request){
        
        $client_id = auth()->guard('client')->id()  ;
        if(ClientOrder::where('client_id',$client_id)->where('post_id',$request->post_id)->exists()){
            return response()->json([
                'message'=>'the order is repeated'
            ],406);
        }
       

        $data = $request->all();
        $data['client_id'] =auth()->guard('client')->id() ;
        $order = ClientOrder::create($data);
        return response()->json([
            'message'=>'success'
        ]);
    }

    public function workerOrder(){
        $orders = ClientOrder::with('post','client')->whereStatus('pending')->whereHas('post',function($query){
            $query->where('worker_id',auth()->guard('worker')->id());
        })->get();
        return response()->json([
            'orders'=>$orders
        ]);
    }

    public function update($id , Request $request){

        $order = ClientOrder::findOrFail($id);
        $order->setAttribute('status',$request->status)->save();
        return response()->json([
            'message'=>'updated'
        ]);
    }
}
