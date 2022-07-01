<?php

namespace App\Http\Controllers\Manger\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manger\Employee\Notifictions\AddRequest;
use App\services\ManagerServices\ManagerNoticationsServices;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function NotificateAUser(AddRequest $request ,$id) {
        return (new ManagerNoticationsServices)->privateNoticator($request , $id);
    }


    public function getPrivateNotifications($y , $m , $id) {
        return (new ManagerNoticationsServices)->getPrivate($y , $m , $id);
    }

    public function getPublicNotifications($y,$m) {
        return (new ManagerNoticationsServices)->getPublic($y , $m);

    }


    public function NotificateAllUsers(AddRequest $request) {
        return (new ManagerNoticationsServices)->publicNoticator($request);
    }
}
