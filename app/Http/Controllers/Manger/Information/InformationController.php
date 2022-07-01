<?php

namespace App\Http\Controllers\Manger\information;

use App\Http\Controllers\Controller;
use App\Models\Information;
use App\Traits\ApiTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InformationController extends Controller
{
    use ApiTraits;
    public function store(Request $request) {
        $request->validate([
            'content' => 'required',
            'content_link' => 'nullable'
        ]);

        Information::create([
            'content' => $request['content'],
            'content_link' => $request['content_link'],
            'manager_id' => Auth::user()->id
        ]);

        return $this->responseJsonWithoutData(200 , 'Information Added');


    }

    public function delete($id) {
        $info = Information::find($id);

        if ($info){
            Information::find($id)->delete();
            return $this->responseJsonWithoutData(200 , 'Information Deleted');
        }

        return $this->responseJsonWithoutData(200 , 'No Information Found');
    }
}
