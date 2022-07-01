<?php

namespace App\Http\Controllers\Manger\Job;

use App\Http\Controllers\Controller;
use App\services\ManagerServices\ManagerJobsServices;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Http\Resources\Jobs\JobResource;
use App\Http\Requests\Manger\Job\AddRequest;
use App\Traits\ApiTraits;

class JobController extends Controller
{
    use ApiTraits;

    public function index(Request $request){
        return (new ManagerJobsServices)->getJobsList($request);
    }

    public function add(AddRequest $request){
       return (new ManagerJobsServices)->addJob($request);
    }

    public function delete($id){
        return (new ManagerJobsServices())->deleteJob($id);
    }
}
