<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiTraits;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Resources\Employee\NotificationResource;

class NotificationController extends Controller
{
    use ApiTraits;

    public function publicList($y,$m){
        $data = Notification::where([
            'type' => 1,
            'manager_id' => Auth::user()->manager_id

        ])->whereYear('created_at' , $y)->whereMonth('created_at', '=', $m)->orderBy('id', 'DESC')->get();
        return $this->responseJson(200, "Notifications Returned", NotificationResource::collection($data));
    }

    public function privteList($y,$m){
        $data = Notification::where(['user_id' => Auth::user()->id, 'type' => 0])->whereYear('created_at' , $y)->whereMonth('created_at', '=',$m)->orderBy('id', 'DESC')->get();
        return $this->responseJson(200, "Notifications Returned", NotificationResource::collection($data));
    }
}
