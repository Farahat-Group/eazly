<?php

namespace App\Models;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;


class User extends Authenticatable implements CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'job_type',
        'job_id',
        'salery',
        'contract_start',
        'contract_end',
        'image',
        'manager_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function setPasswordAttribute($value)
    {
        $this->attributes["password"] = bcrypt($value);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'user_id' , 'id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'user_id' , 'id')->orderBy('id', 'DESC');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id' , 'id');
    }



    public function changes() {

       return $this->hasOne(MoneyChanges::class , 'user_id' , 'id');

    }



    public function getdeservedMoneyAttribute()
    {

        $moneyAfterChange = $this->salery;
        $mChange = MoneyChanges::query()->where('user_id', $this->id)->first();
        if ($mChange) {
            $moneyAfterChange += $mChange->cost;
        };
        return $moneyAfterChange;

    }


    public static function filterUsers() {
        $users = User::query()->where('manager_id' , Auth::user()->id);
        if (request()->has('name')){
            $users->where('name' , 'LIKE' , '%' . request()->name . '%');
        }
        if (request()->has('job')){
            $users->where('job_id' , request()->job);
        }

        return $users->get();
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($user) { // before delete() method call this
            $user->attendance()->delete();
            $user->notes()->delete();

        });
    }

}
