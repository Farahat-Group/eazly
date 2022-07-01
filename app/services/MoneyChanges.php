<?php

namespace App\services;

use App\Models\Attendance;
use App\Models\Setting;
use App\services\ManagerServices\ManagerEmployeesServices;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MoneyChanges
{

    protected $settings;
    protected $salary;
    public $discount;
    protected $month;

    public function __construct($salary,$month)
    {

        $manID = Auth::user()->manager_id ? Auth::user()->manager_id : Auth::user()->id;

        $this->settings = Setting::where('manager_id' , $manID)->first();
        $this->salary = $salary;
        $this->month = $month;
    }



    public function getDailySalary() :float {

        // Get The Employee Daily Salary To Calculate Discounts

        $daysOfWork = cal_days_in_month( CAL_GREGORIAN,  $this->month,  2022);

        //return  $this->salary / $daysOfWork;
        return $this->salary / $daysOfWork;

    }


    public function discountPerDay($timeIn){


        // Get The Employee Discount When Logged In Late

        $firstDiscountTime =Carbon::createFromTimeString( $this->settings['start_discount_time1']);
        $secondDiscountTime = Carbon::createFromTimeString($this->settings['start_discount_time2']);
        $firstDiscountRate = $this->settings['first_discount_rate1'];
        $secondDiscountRate = $this->settings['second_discount_rate2'];

        $discount = 0;
        $dailySalary = $this->getDailySalary();

        $time_in = Carbon::createFromTimeString($timeIn);

        if ($time_in->greaterThan($secondDiscountTime)){
            $discount = ($dailySalary * $secondDiscountRate ) / 100 ;
        } elseif($time_in->greaterThan($firstDiscountTime)) {
            $discount = ($dailySalary * $firstDiscountRate) / 100;
        }

        $this->discount =  -$discount;

        $change = new ManagerEmployeesServices();
        $change->changeDeservedMoney(\request() , Auth::user()->id , $this->discount);


    }




    public function getAllowedAbsenceDays($endDate) {

        // Get The Week OffDays -- If Manager Select 1 Day It Will Return The Number Of Fridays And If 2 Will Return Number
        // Of Fridays + Saturdays --

        $allowed = $this->settings['allowed_absence_days'];
        $count = 0;
        $fridays = [];
        $SATURDAYS = [];
        $startDate = Carbon::now()->firstOfMonth()->next(Carbon::FRIDAY); // Get the first friday.
        for ($date = $startDate; $date->lte($endDate); $date->addWeek()) {
            $fridays[] = $date->format('Y-m-d');
        }
        $startDate = Carbon::now()->firstOfMonth()->next(Carbon::SATURDAY); // Get the first saterday.
        for ($date = $startDate; $date->lte($endDate); $date->addWeek()) {
            $SATURDAYS[] = $date->format('Y-m-d');
        }

        if ($allowed == 1) {
            $count = count($fridays);
        } elseif ($allowed==2) {
            $count = count($fridays) + count($SATURDAYS);
        }

        return $count;



    }


    public function getDiscountDays($id): int
    {

        // Get The Number Of Days Will Be Discounted From Employee Salary Till Now

        $current = Carbon::now()->format('d');
        $allowed = $this->getAllowedAbsenceDays(Carbon::now());
        $workedDays = $this->workedDays($id);

        return $current - $workedDays - $allowed;

    }

    public function getTotalDiscount($id): float
    {

        // Calculate The Total Amount Of Discount
        $days = $this->getDiscountDays($id);
        return $this->getDailySalary() * $days;
    }

    public function workedDays($id): int
    {

        // Get The Total Days Employee Worked Till Now In Current Month

        return Attendance::query()->where('user_id' , $id)
            ->whereYear('created_at' , Carbon::now()->year)
            ->whereMonth('created_at' , $this->month)
            ->where('time_out' ,  '!=' , null)
            ->count();

    }

}
