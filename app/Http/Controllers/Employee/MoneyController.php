<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Resources\Employee\MoneyResource;
use App\Models\Setting;
use App\Models\User;
use App\services\ManagerServices\ManagerEmployeesServices;
use App\services\MoneyChanges;
use App\Traits\ApiTraits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MoneyController extends Controller
{
    use ApiTraits;
    public function show($m) {

        $user = Auth::user();
        $user->month = $m;
        return $this->responseJson(200, 'Data Returned' , (new MoneyResource(Auth::user()) ));
    }

    public function moneyChange(Request $request) {
        return (new ManagerEmployeesServices())->changeDeservedMoney($request , Auth::user()->id);
    }




}
