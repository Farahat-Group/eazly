<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\services\ManagerServices\SettingsServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function __invoke()
    {
        return (new SettingsServices())->getAllSettings(Auth::user()->manager_id);
    }
}
