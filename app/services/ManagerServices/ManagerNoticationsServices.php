<?php

namespace App\services\ManagerServices;

use App\Http\Resources\Employee\NotificationResource;
use App\Models\Notification;
use App\Traits\ApiTraits;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManagerNoticationsServices
{

    use ApiTraits;

    public function privateNoticator($request , $id) {

        try {
            DB::table('notifications')->insert([
                'content' => $request->content,
                'user_id' => $id ,
                'manager_id' => Auth::user()->id ,
                'type' => 0,
                'money_changes' => 0,
                'created_at' => Carbon::now()
            ]);

            return $this->responseJsonWithoutData(200 , 'Notification Sent To The Employee');


        }catch (\Exception $e){
            return $this->responseJsonFailed();
        }

    }

    public function getPrivate($y , $m , $id) {
        $nots = Notification::query()->where([
            'type' => 0,
            'user_id' => $id
        ])->whereYear('created_at' , $y)->whereMonth(
            'created_at'  , '=' , $m
        )->get();

        if (count($nots) == 0)
            return $this->responseJsonWithoutData(200 , 'No Notifications Yet !');

        return $this->responseJson(200 , 'Notifications Returned' , NotificationResource::collection($nots));
    }

    public function getPublic($y , $m) {

        $custom = 0;
        $manager = Auth::guard('manger-api')->user()->id;
        if ($manager){
            $custom = $manager;
        }elseif(Auth::user()->id){
            $custom = Auth::user()->manager_id;
        }

        $nots = Notification::query()->where([
            'type' => 1,
            'user_id' => null,
            'manager_id' => $custom
        ])->whereYear('created_at' , $y)->whereMonth(
            'created_at' , '=' , $m
        )->get();

        if (count($nots) == 0)
            return $this->responseJsonWithoutData(200 , 'No Notifications Yet !');

        return $this->responseJson(200 , 'Notifications Returned' , NotificationResource::collection($nots));
    }


    public function publicNoticator($request) {
        try {
            DB::table('notifications')->insert([
                'content' => $request->content,
                'user_id' => null ,
                'type' => 1,
                'money_changes' => 0,
                'manager_id' => Auth::guard('manger-api')->user()->id ,
                'created_at' => Carbon::now()

            ]);

            return $this->responseJsonWithoutData(200 , 'Notification Sent To All Employees');


        }catch (\Exception $e){
            return $this->responseJsonFailed();
        }

    }
}
