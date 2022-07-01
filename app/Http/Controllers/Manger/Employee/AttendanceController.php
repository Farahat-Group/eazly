<?php

namespace App\Http\Controllers\Manger\Employee;

use App\Http\Controllers\Controller;
use App\services\ManagerServices\ManagerAttendanceServices;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index($y,$m , $d) {
        return (new ManagerAttendanceServices)->getAllUsersAttendance($y,$m , $d);
    }

    public function show($y , $m , $id) {
        return (new ManagerAttendanceServices)->getUserDetails($y , $m , $id);
    }
}
