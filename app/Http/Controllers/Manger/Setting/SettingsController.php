<?php

namespace App\Http\Controllers\Manger\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manger\Settings\SettingRequest;
use App\Http\Requests\Manger\Settings\SettingUpdateRequest;
use App\services\ManagerServices\SettingsServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{


    public function index() {
        return(new SettingsServices)->getAllSettings(Auth::user()->id);
    }

    public function store(SettingRequest $request) {
        return (new SettingsServices())->setSettings($request);
    }

    public function update(SettingUpdateRequest $request) {
        return (new SettingsServices)->updateSettings($request);
    }
}
