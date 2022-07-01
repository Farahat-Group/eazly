<?php

namespace App\services\ManagerServices;

use App\Http\Resources\Employee\NoteResource;
use App\Models\Note;
use App\Traits\ApiTraits;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NoteServices
{
    use ApiTraits;


    // Manager Add Note

    public function store($request) {
        $manager = Auth::guard('manger-api')->user();
        DB::table('notes')->insert([
            'content' => $request->content,
            'user_id' => 0,
            'manager_id' => Auth::user()->id ,
            'created_at' => Carbon::now(),

        ]);
        return $this->responseJsonWithoutData();
    }

    // Manager Get All Notes

    public function index() {
        $notes = Note::where('manager_id' , Auth::user()->id)->get();
        if (count($notes) == 0)
            return $this->responseJsonWithoutData(200 , 'No Notes');

        return $this->responseJson(200 , 'Data Returned' , NoteResource::collection($notes));
    }

    //Manager Get A User Notes

    public function show() {
        $notes = Note::query()->where([
            'user_id' => 0,
            'manager_id' => Auth::user()->id
        ])->get();
        if (count($notes) == 0)
            return $this->responseJsonWithoutData(200 , 'No Notes');

        return $this->responseJson(200 , 'Data Returned' , NoteResource::collection($notes));
    }


    public function delete($id) {
        Note::find($id)->delete();
        return $this->responseJsonWithoutData(200  , 'Note Deleted');
    }

}
