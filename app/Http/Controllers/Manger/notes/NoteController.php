<?php

namespace App\Http\Controllers\Manger\notes;

use App\Http\Controllers\Controller;
use App\services\ManagerServices\NoteServices;
use Illuminate\Http\Request;
use App\Http\Requests\Employee\Note\AddRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Note;
use App\Traits\ApiTraits;
use App\Http\Resources\Employee\NoteResource;

class NoteController extends Controller
{
    use ApiTraits;

    public function add(AddRequest $request){
        return (new NoteServices())->store($request);
    }

    public function show(){
        return (new NoteServices())->show();
    }

    public function delete($id){
        return (new NoteServices())->delete($id);
    }

    public function list(){
        return (new NoteServices)->index();
    }
}
