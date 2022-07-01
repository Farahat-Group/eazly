<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Resources\Employee\NoteResource;
use App\Models\Note;
use App\Traits\ApiTraits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Matcher\Not;

class NoteController extends Controller
{

    use  ApiTraits;

    public function store(Request $request) {
        $employee = Auth::user();
        $employee->notes()->create([
            'content' => $request->content,
            'manager_id' => Auth::user()->manager_id
        ]);
        return $this->responseJsonWithoutData();
    }


    // Get A User Notes

    public function show() {
        $notes = Note::query()->where('user_id' , Auth::user()->id)->get();
        if (count($notes) == 0)
            return $this->responseJsonWithoutData(200 , 'No Notes');

        return $this->responseJson(200 , 'Data Returned' , NoteResource::collection($notes));
    }


    public function delete($id) {


        $note =Note::find($id);
        if ($note) {
            Note::find($id)->delete();
            return  $this->responseJsonWithoutData(200 , 'Note Deleted');
        } else{
            return $this->responseJsonWithoutData(200 , 'No Note Exists');
        }



        return $this->responseJsonWithoutData(200  , 'Note Deleted');
    }
}
