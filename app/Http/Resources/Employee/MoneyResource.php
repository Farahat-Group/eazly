<?php

namespace App\Http\Resources\Employee;

use App\Models\Attendance;
use App\Models\MoneyChanges;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MoneyResource extends JsonResource
{




    public function toArray($request)
    {

            $change = MoneyChanges::query()
                ->whereYear('created_at' , $this->year)
                ->whereMonth('created_at' , $this->month)
                ->where('user_id' , $this->id)->select('cost')->get();

            $obj = new \App\services\MoneyChanges($this->salery , $this->month);
            $absenceDays = $obj->getDiscountDays($this->id);
            $discount = $obj->getTotalDiscount($this->id);
            $daily = $obj->getDailySalary();
            $workedDays = $obj->workedDays($this->id);
            $fridays = $obj->getAllowedAbsenceDays(Carbon::now());


            $changeMoney = 0;
            if (count($change) > 0) {
                $changeMoney = $change[0]['cost'];
            }

            $deserved = ($workedDays + $fridays) * $daily + $changeMoney ;
            if ($deserved < 0)
                $deserved = 0;


            return [
                'Employee_name' => $this->name ,
                'Month' => $this->month,
                'salary' => $this->salery,
                'moneyChange' => $changeMoney,
                'absenceDays' => $absenceDays,
                'absenceDiscount' => $discount,
                'DeservedMoney' =>  $deserved ,
            ];
        }




}
