<?php

namespace App\Listeners;

use App\Events\MoneyChanged;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SendNontification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(MoneyChanged $changed)
    {
        $insert =  DB::table('notifications')->insert([
            'content' => $changed->change['content'],
            'user_id' => $changed->change['id'],
            'money_changes' => $changed->change['change'],
            'type' => 0,
            'manager_id' => Auth::user()->id ,
            'created_at' => Carbon::now(),
        ]);

        return $insert;
    }
}
