<?php

namespace App\Http\Controllers\manger\Rules;

use App\Http\Controllers\Controller;
use App\Models\Rules;
use App\Traits\ApiTraits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Exception\JsonException;

class RulesController extends Controller
{
    use ApiTraits;

    public function index(){

        $manager_id = Auth::user()->manager_id ?? Auth::user()->id;
        $rules = Rules::where('manager_id' , $manager_id)->first();
        if ($rules)
            return $this->responseJson(200 , 'Rules Returned' , $rules);
        $default = Rules::first();
        return $this->responseJson(200 , 'Default Rules Returned' , $default);

    }


    public function store(Request $request) {
        $rules =Rules::where('manager_id' , Auth::user()->id)->first();
        if (! $rules){
            $request->validate(['rules' => 'required']);
            $request['manager_id'] = Auth::user()->id;
            try {
                Rules::create($request->all());
                return $this->responseJsonWithoutData(200 , 'Rules Is Set !');
            }catch ( JsonException $e) {
                return $e;
            }
        } else{
            return $this->responseJsonFailed(422 , 'Rules Already Set !');
        }

    }

    public function update(Request $request){
        $rules = Rules::where('manager_id' , Auth::user()->id)->first();
        $request['manager_id'] = Auth::user()->id;
        if (!$rules){
            try {

                Rules::create($request->all());
                return $this->responseJsonWithoutData(200 , 'Rules Is Set !');
            }catch ( JsonException $e) {
                return $e;
            }        }

        if ($request['rules'] != null){
            $rules->rules = $request['rules'];
            $rules->save();
        }
        return $this->responseJsonWithoutData(200 , "Rules Updated");


    }
}
