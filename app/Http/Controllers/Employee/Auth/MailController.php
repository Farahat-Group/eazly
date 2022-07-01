<?php

namespace App\Http\Controllers\Employee\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use App\Traits\ApiTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use phpseclib3\Crypt\Hash;
use Psy\Util\Str;

class MailController extends Controller
{

    use ApiTraits;

    public function changePassword($email) {

        $user = User::where('email' , $email)->first();
        $password = \Illuminate\Support\Str::random(8);
        $user->password = $password;
        $user->save();

        return $password;

    }
    public function sendEmail(Request $request) {



        $email = $request->email;

        $user = User::where('email' , $email)->exists();
        if (!$user){
            return $this->responseJsonFailed(422 , 'Email is Invalid');
        }

        $password = $this->changePassword($email);

        $sendmail = Mail::to($email)
            ->send(new ResetPasswordMail('Reset Password' , $email , $password));

        if ($sendmail) {
            return response()->json(['message' => 'Mail Sent Successfully Please Check Your Spam If You Cant Get It'], 200);
        }
        else{
            return response()->json(['message' => 'Mail Sent fail'], 400);
        }

    }
}
