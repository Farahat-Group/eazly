<?php

namespace App\services\ManagerServices;

use App\Http\Resources\Manger\SettingsResorce;
use App\Models\Setting;
use App\Traits\ApiTraits;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\throwException;

class SettingsServices
{

    use ApiTraits;


    public function getAllSettings($managerId) {


        $settings = Setting::where('manager_id' , $managerId)->first();
        return $this->responseJson(200,'Settings Returned'  , (new SettingsResorce($settings)));
    }

    public function setSettings($request) {


        $managerId = Auth::user()->id;
        $settings = Setting::where('manager_id' , $managerId)->first();
        if (!$settings){
            try {
                $request['manager_id'] = $managerId;
                $request['days_of_work'] = 7 - $request['allowed_absence_days'];
                Setting::create($request->all());
                return $this->responseJsonWithoutData(200 , 'Settings Is Set');

            }catch (\Exception $exception){
                throwException($exception);
            }
        } else {
            return $this->responseJsonWithoutData(200 , 'Settings Already Set !');
        }


    }

    public function updateSettings($request)
    {


        $managerId = Auth::user()->id;
        $exceptions = ['manager_id'];
        if ($request['allowed_absence_days'] != null){
            $request['days_of_work'] = 7 - $request['allowed_absence_days'];
        }
        $settings = Setting::where('manager_id' , $managerId)->first();

        foreach ($request->all() as $key => $r) {
           if ($r == null)
               array_push($exceptions , $key);
        }

        try {
            $settings->update($request->except($exceptions));
            return $this->responseJsonWithoutData(200 , 'Settings Updated');
        }catch (\Exception $e) {
            throwException($e);
        }

    }
}
