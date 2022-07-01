<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manger;
use App\Http\Controllers\Employee;



/*
|--------------------------------------------------------------------------
| General
|--------------------------------------------------------------------------

|--------------------------------------------------------------------------
| Manger
|--------------------------------------------------------------------------
*/
// Auth
Route::post('manger-login',[Manger\Auth\AuthController::class, 'login']);


Route::middleware(["auth:manger-api"])->group(function () {

    Route::post('manger-password-change',[Manger\Auth\AuthController::class, 'changePasword']);
    Route::get('manger-logout',[Manger\Auth\AuthController::class, 'logout']);

    // Employee
    Route::post('manger-add-employee',[Manger\Employee\EmployeeController::class, 'store']);
    Route::post('manger-update-employee/{id}',[Manger\Employee\EmployeeController::class, 'update']);
    Route::get('manger-info-employee/{id}',[Manger\Employee\EmployeeController::class, 'show']);
    Route::get('manger-employees-list',[Manger\Employee\EmployeeController::class, 'index']);
    Route::post('manger-delete-employee/{id}',[Manger\Employee\EmployeeController::class, 'delete']);
    Route::post('emoloyee/moneychange/{id}' , [Manger\Employee\EmployeeController::class, 'changeMoney']);
    Route::get('employee/{id}/money-details/{year}/{month}' , [Manger\Employee\EmployeeController::class, 'moneyDetails']);
    Route::get('employee/money-details/{year}/{month}' , [Manger\Employee\EmployeeController::class, 'employeesMoneyDetails']);


    // jobs
    Route::get('manger-jobs',[Manger\Job\JobController::class, 'index']);

    Route::post('manger-add-job',[Manger\Job\JobController::class, 'add']);
    Route::delete('job/{id}',[Manger\Job\JobController::class, 'delete']);



    // Attendance

    Route::get('attendance/{y}/{m}/{d}' , [Manger\Employee\AttendanceController::class , 'index']);
    Route::get('attendance/{y}/{m}/{id}' , [Manger\Employee\AttendanceController::class , 'show']);


    // Notfications

    Route::post('private-notification/{id}' , [Manger\Employee\NotificationsController::class, 'NotificateAUser' ]);
    Route::post('public-notification' , [Manger\Employee\NotificationsController::class, 'NotificateAllUsers' ]);
    Route::get('public-notifications/{y}/{m}' , [Manger\Employee\NotificationsController::class, 'getPublicNotifications' ]);
    Route::get('private-notifications/{y}/{m}/{id}' , [Manger\Employee\NotificationsController::class, 'getPrivateNotifications' ]);



    //Notes

    Route::post('manager/notes' , [Manger\notes\NoteController::class , 'add']);
    Route::get('manager/notes' , [Manger\notes\NoteController::class , 'list']);
    Route::get('manager/my-notes' , [Manger\notes\NoteController::class , 'show']);
    Route::delete('manager/notes/{id}' , [Manger\notes\NoteController::class , 'delete']);


    // Information

    Route::get('information/{y}/{m}' , [Employee\InformationController::class , 'list']);
    Route::post('information' , [Manger\Information\InformationController::class , 'store']);
    Route::delete('information/{id}' , [Manger\Information\InformationController::class , 'delete']);


    //Setting

    Route::post('/settings' , [Manger\Setting\SettingsController::class , 'store']);
    Route::get('/settings' , [Manger\Setting\SettingsController::class , 'index']);
    Route::post('/settings/update' , [Manger\Setting\SettingsController::class , 'update']);

    // Rules

    Route::post('rules' , [Manger\Rules\RulesController::class , 'store'] );
    Route::get('rules' , [\App\Http\Controllers\manger\Rules\RulesController::class , 'index'] );
    Route::post('rules/update' , [\App\Http\Controllers\manger\Rules\RulesController::class , 'update'] );

});


/*
|--------------------------------------------------------------------------
| Employee
|--------------------------------------------------------------------------
*/

// Auth
Route::post('employee-login',[Employee\Auth\AuthController::class, 'login']);
Route::post('employee-register',[Employee\Auth\AuthController::class, 'register']);
Route::post('reset-password',[Employee\Auth\MailController::class, 'sendEmail']);





// ---------------------------Employee --------------------------

Route::middleware('auth:user-api')->group(function () {
    // Auth
    Route::post('employee-password-change',[Employee\Auth\AuthController::class, 'changePasword']);
    Route::get('employee-profile',[Employee\Auth\AuthController::class, 'profile']);
    Route::post('employee-update-profile',[Employee\Auth\AuthController::class, 'updateProfile']);
    Route::get('employee-logout',[Employee\Auth\AuthController::class, 'logout']);




    // Follow Up
    Route::post('employee-attendance-registration',[Employee\FollowUpController::class, 'attendanceRegistration']);
    Route::post('employee-checkout',[Employee\FollowUpController::class, 'checkout']);


    // Attendance
    Route::get('employee-attendence-list/{y}/{m}',[Employee\FollowUpController::class, 'list']);

    // Notes
    Route::get('employee-note-list',[Employee\NoteController::class, 'show']);
    Route::post('employee-note-add',[Employee\NoteController::class, 'store']);
    Route::delete('employee-note-delete/{id}',[Employee\NoteController::class, 'delete']);


    // Information
    Route::get('employee-information-list/{y}/{m}',[Employee\InformationController::class, 'list']);


    // Notifcation
    Route::get('employee-public-notifications/{y}/{m}',[Employee\NotificationController::class , 'publicList']);
    Route::get('employee-private-notifications/{y}/{m}' , [Employee\NotificationController::class , 'privteList' ]);


    //salary

    Route::get('money-details/{m}' , [Employee\MoneyController::class , 'show'] );
    Route::post('money-change' , [Employee\MoneyController::class , 'moneyChange'] )->name('changeMoney');

    // Settings

    Route::get('setting-list' , Employee\SettingsController::class);

    Route::get('employee-rules' , [Manger\Rules\RulesController::class , 'index' ]);

});







