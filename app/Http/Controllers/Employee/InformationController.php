<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Traits\ApiTraits;
use App\Models\Information;
use App\Http\Resources\Employee\InformatioResource;
use Illuminate\Support\Facades\Auth;

class InformationController extends Controller
{
    use ApiTraits;



    public function list($y , $m){
        $managerId = Auth::user()->manager_id ? Auth::user()->manager_id : Auth::user()->id;
        $data = Information::where('manager_id' , $managerId)->whereYear('created_at' , $y)
            ->whereMonth('created_at', '=',$m)
            ->orderBy('id', 'DESC')->get();
        if (count($data) == 0){
            return $this->responseJsonWithoutData(200 , 'No Information');
        }
        return $this->responseJson(200, "Information List", InformatioResource::collection($data));
    }
}
