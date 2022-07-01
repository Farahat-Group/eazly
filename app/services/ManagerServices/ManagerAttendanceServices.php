<?php

namespace App\services\ManagerServices;


use App\Http\Resources\Employee\AttendanceResource;
use App\Models\Attendance;
use App\Traits\ApiTraits;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ManagerAttendanceServices {

    use ApiTraits;


    public function getAllUsersAttendance($y,$m , $d) {

        $attendances = Attendance::query()
            ->where('manager_id' , Auth::user()->id)
            ->whereYear('created_at' , '=' , $y)
            ->whereMonth('created_at' , '=' , $m)
            ->whereDay('created_at' , '=', $d)->get();
        return $this->responseJson(200, "Employees Attendance List For Month " . $m, AttendanceResource::collection($attendances));

    }

    public function getUserDetails($y , $m , $id) {
        $attendances = Attendance::query()->whereYear('created_at' , '=' , $y)->whereMonth('created_at' , '=' , $m)->where('user_id' , $id)->get();
        return $this->responseJson(200, "Employee Attendance List For Month " . $m, AttendanceResource::collection($attendances));
    }

}
