<?php

namespace App\Http\Controllers\Manger\Employee;

use App\Http\Controllers\Controller;
use App\Http\Resources\Employee\MoneyResource;
use App\Models\Attendance;
use App\services\ManagerServices\ManagerEmployeesServices;
use Illuminate\Http\Request;
use App\Http\Requests\Manger\Employee\AddRequest;
use App\Http\Requests\Manger\Employee\UpdateRequest;
use App\Models\User;
use App\Traits\ApiTraits;
use App\Traits\HelperTrait;
use App\Http\Resources\Employee\ProfileResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class EmployeeController extends Controller
{
    use ApiTraits, HelperTrait;


    public function index(Request $request){
        return (new ManagerEmployeesServices)->getEmployeeList($request);
    }

    public function show(Request $request,$id){
        return (new ManagerEmployeesServices)->getEmployeeInfo($request , $id);
    }

    public function store(AddRequest $request){
        return (new ManagerEmployeesServices)->store($request);
    }

    public function update(UpdateRequest $request, $id){
        return (new ManagerEmployeesServices)->update($request , $id);
    }

    public function delete($id){
        return (new ManagerEmployeesServices)->deleteEmployee($id);
    }

    public function changeMoney(Request $request , $id) {
        return (new ManagerEmployeesServices)->changeDeservedMoney($request , $id);
    }

    public function moneyDetails($id , $year ,$month ) {

        $isDeserved = Attendance::query()->where('user_id' , $id)
            ->whereMonth('created_at' , $month)
            ->whereYear('created_at' , $year)
            ->exists();

        if (!$isDeserved)
            return $this->responseJsonFailed(422 , 'No Money For Details For This Month');


        $user = User::find($id);
        $user->month = $month;
        $user->year = $year;
        return $this->responseJson(200, 'Data Returned' , (new MoneyResource($user)));

    }

    public function employeesMoneyDetails($year , $month) {

        $users = collect(User::where('manager_id' , Auth::user()->id)->get());

        $isDeserved = Attendance::query()
            ->where('manager_id' , Auth::user()->id)
            ->whereMonth('created_at' , $month)
            ->whereYear('created_at' , $year)
            ->first();

        if (!$isDeserved)
            return $this->responseJsonFailed(422 , 'No Money Details For Your Employee This Month');


        $users->each(function ($e) use ($month , $year) {
            $e->month = $month;
            $e->year = $year;
        });

        return $this->responseJson(
            200,
            'Data Returned' ,
            (MoneyResource::collection($users)));

    }



}
