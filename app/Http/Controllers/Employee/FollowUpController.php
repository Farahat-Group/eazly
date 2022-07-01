<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\services\ManagerServices\ManagerEmployeesServices;
use App\services\MoneyChanges;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiTraits;
use App\Traits\HelperTrait;
Use App\Http\Requests\Employee\FollowUp\AttendanceRequest;
Use App\Http\Requests\Employee\FollowUp\CheckOutRequest;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Http\Resources\Employee\AttendanceResource;


class FollowUpController extends Controller
{
    use ApiTraits, HelperTrait;

    public function attendanceRegistration(AttendanceRequest $request){

        try {
            if($request->header('lang')){
                if($request->header('lang') == 'ar'){
                    $lang = 'ar';
                }else{$lang = 'en';}
            }else{
                $lang = 'ar';
            }
            $employee = Auth::user();

            $exist = Attendance::query()
                ->where('user_id' , Auth::user()->id)
                ->whereMonth('created_at' , Carbon::now()->month)
                ->whereDay('created_at' , '=' , Carbon::now()->day)
                ->exists();

            if ($exist){
               return $this->responseJsonWithoutData(200 , 'You Already Signed Attendance');
            }

            $o = new MoneyChanges(Auth::user()->salery , Carbon::now()->month);
             $o->discountPerDay($request->time);




            $employee->attendance()->create([
                "time_in" => $request->time,
                "time_out" => NULL,
                'user_id' => Auth::user()->id,
                'manager_id' => Auth::user()->manager_id,
                'created_at' => Carbon::now()
            ]);


            return $this->responseJsonWithoutData();
        } catch (Throwable $e) {
            return $this->responseJsonFailed();
        }
    }

    public function checkout(CheckOutRequest $request)
    {
        try {

            $exist = Attendance::query()->where('user_id', Auth::user()->id)
                ->whereMonth('created_at','=' ,  Carbon::now()->month)
                ->whereYear('created_at' ,'=', Carbon::now()->year)
                ->whereDay('created_at', '=', Carbon::now()->day)
                ->first();


            if ($exist['time_out'] != null){
                    return $this->responseJsonWithoutData(200, 'You Already Signed Checkout');
            }

            $exist->time_out = $request->time;
            $exist->save();
            return $this->responseJsonWithoutData(200,'Checked Out !');

        } catch (\Exception $e) {
            return $this->responseJsonFailed();
        }
    }

    public function list($y , $m){
        $data = Attendance::whereYear('created_at', '=', $y)->whereMonth('created_at', '=',$m)->where([
            'user_id' => Auth::user()->id
        ])->get();
        return $this->responseJson(200, "Employee Attendance List", AttendanceResource::collection($data));
    }
}
