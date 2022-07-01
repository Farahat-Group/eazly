<?php

namespace App\services\ManagerServices;

use App\Events\MoneyChanged;
use App\Http\Resources\Employee\ProfileResource;
use App\Models\MoneyChanges;
use App\Models\User;
use App\Traits\ApiTraits;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManagerEmployeesServices
{
    use ApiTraits;


    public function store($request){


        $request['manager_id'] = Auth::guard('manger-api')->user()->id;

        if($request->image){
            $employee = User::create($request->except('image'));
            $image = $this->uploadImages($request->image, "images/employee/profile");
            $employee->update(['image' => $image]);
        }else{
            $employee = User::create($request->all());
        }
        return $this->responseJsonWithoutData(200 , 'Employee Added');
    }


    public function update($request , $id) {
        $employee = User::findorFail($id);
        if($request->image){
            if($employee->image){
                if (file_exists($employee->image)){
                    unlink($employee->image);
                }
            }
            $image = $this->uploadImages($request->image, "images/employee/profile");
            $employee->update(['image' => $image]);
        }
        $employee->update($request->except('image'));
        return $this->responseJsonWithoutData();

    }

    public function getEmployeeList($request) {
        if($request->header('lang')){
            if($request->header('lang') == 'ar'){
                $lang = 'ar';
            }else{$lang = 'en';}
        }else{
            $lang = 'ar';
        }



        $employees = User::filterUsers();
        $data= [];
        foreach($employees as $employee){
            $single_employee = new ProfileResource($employee ,$lang);
            array_push($data,$single_employee);
        }
        return $this->responseJsonPaginate(200, "Successfully", $data);
    }


    public function getEmployeeInfo($request , $id){
        if($request->header('lang')){
            if($request->header('lang') == 'ar'){
                $lang = 'ar';

            }else{$lang = 'en';}
        }else{
            $lang = 'ar';
        }
        $employee = User::find($id);
        $employee->update($request->except('image'));
        return $this->responseJson(200 , "Successfully", new ProfileResource($employee, $lang));
    }


    public function changeDeservedMoney($request , $id , $cost = 0) {
        if ($request->change){
            $cost = $request->change;
        }


        $user = User::find($id);
        $change = $cost;

        $msg = "No Content";
        if ($request->content)
            $msg = $request->content;

        try {

            $mChange = MoneyChanges::query()->where('user_id' , $id)->first();
            if (isset($mChange)){
                $mChange->cost += $change;
                $mChange->save();
            } else{
                DB::table('money_changes')->insert([
                    'cost' => $change,
                    'user_id' => $id,
                    'created_at' => Carbon::now()
                ]);
            }
             event(new MoneyChanged($request));

            return $this->responseJsonWithoutData(200,'Deserved Money Changed');

        }catch (\Exception $e) {
            return "Error";
        }

    }


    public function deleteEmployee($id) {
        $employee = User::find($id);
        if (! $employee)
            return $this->responseJsonFailed(422 , 'User Not Found !');
        if($employee->image){
            if (file_exists($employee->image)){
                unlink($employee->image);
            }
        }
        $employee->delete();
        return $this->responseJsonWithoutData();
    }


}
