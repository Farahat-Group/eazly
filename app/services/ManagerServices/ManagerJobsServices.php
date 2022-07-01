<?php

namespace App\services\ManagerServices;

use App\Http\Resources\Jobs\JobResource;
use App\Models\Job;
use App\Traits\ApiTraits;
use Illuminate\Support\Facades\Auth;

class ManagerJobsServices
{
    use ApiTraits;

    public function getJobsList($request) {
        if($request->header('lang')){
            if($request->header('lang') == 'ar'){
                $lang = 'ar';
            }else{$lang = 'en';}
        }else{
            $lang = 'ar';
        }
        $jobs = Job::where('manager_id' , Auth::user()->id)->get();
        $data= [];
        foreach($jobs as $job){
            $single_job = new JobResource($job ,$lang);
            array_push($data,$single_job);
        }
        return $this->responseJson(200, "Successfully", $data);
    }

    public function addJob($request) {
        $request['manager_id'] = Auth::guard('manger-api')->user()->id;
        Job::create($request->all());
        return $this->responseJsonWithoutData();
    }


    public function deleteJob($id){
        Job::findOrFail($id)->delete();
        return $this->responseJsonWithoutData(200 , 'Job Deleted');
    }

}
