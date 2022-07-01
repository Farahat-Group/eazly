<?php

namespace App\Http\Controllers\Manger\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manger;
use App\Traits\ApiTraits;
use App\Traits\HelperTrait;
use App\Http\Requests\Manger\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Manger\MangerResource;
use App\Http\Requests\Employee\Auth\ChangePasswordRequest;

class AuthController extends Controller
{
    use ApiTraits, HelperTrait;

    public function login(LoginRequest $request){
        try {
            $auth = Auth::guard('manger-web')->attempt(['email' => $request->email, 'password' => $request->password]);
            $auth_user = Manger::where("email", $request->email)->first();

            $code = $auth_user->code->status;


            if ($code == 'active'){
                $apiToke  = $auth_user->createToken('auth_token')->accessToken;

                if (!Auth::guard('manger-web')->attempt(['email' => $request->email, 'password' => $request->password])) {
                    return $this->responseJsonFailed(422, 'user password is incorrect');
                }else{
                    $auth_user->api_token = $apiToke;
                    return $this->responseJson(200, "Manger Login Successfully", new MangerResource($auth_user));
                }
            } else{
                return  $this->responseJsonFailed(400 , 'Get A Code First');
            }


        } catch (Throwable $e) {
            return $this->responseJsonFailed();
        }
    }

    public function changePasword(ChangePasswordRequest $request){
        try {
            if (\Hash::check( $request->old_password, Auth::user()->password)) {
                Auth::user()->update(["password" => $request->new_password]);
                $manger = Auth::user();
                $token = $request->bearerToken();
                $manger->api_token = $token;
                return $this->responseJson(200 , "Changed Successfully", new MangerResource($manger));
            }else{
                return $this->responseJsonFailed(422, 'Manger password is incorrect');
            }
        } catch (Throwable $e) {
            return $this->responseJsonFailed();
        }
    }

    public function logout(){
        Auth::user()->token()->revoke();
        return $this->responseJsonWithoutData();
    }
}
