<?php

namespace App\Http\Controllers\Employee\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\ApiTraits;
use App\Traits\HelperTrait;
use App\Http\Requests\Employee\Auth\LoginRequest;
use App\Http\Requests\Employee\Auth\RegisterRequest;
use App\Http\Requests\Employee\Auth\ChangePasswordRequest;
use App\Http\Requests\Employee\Auth\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Employee\EmployeeResource;
use App\Http\Resources\Employee\ProfileResource;

class AuthController extends Controller
{
    use ApiTraits, HelperTrait;




    public function login(LoginRequest $request){


        try {
            $auth = Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password]);
            $auth_user = User::where("email", $request->email)->first();
            $apiToke  = $auth_user->createToken('auth_token')->accessToken;
            if (!Auth::guard('web')->attempt([
                'email' => $request->email,
                'password' => $request->password
            ])) {
                return $this->responseJsonFailed(422, 'user password is incorrect');
            }else{
                $auth_user->api_token = $apiToke;
                return $this->responseJson(200, "Employee Login Successfully", new EmployeeResource($auth_user));
            }
        } catch (Throwable $e) {
            return $this->responseJsonFailed();
        }
    }


    public function register(RegisterRequest $request){
        try {
            $employee = User::create($request->all());
            $apiToke  = $employee->createToken('auth_token')->accessToken;
            $employee->api_token = $apiToke;
            return $this->responseJson(200 , "Registration Successfully", new EmployeeResource($employee));
        } catch (Throwable $e) {
            return $this->responseJsonFailed();
        }
    }



    public function changePasword(ChangePasswordRequest $request){
        try {
            if (\Hash::check( $request->old_password, Auth::user()->password)) {
                Auth::user()->update(["password" => $request->new_password]);
                $employee = Auth::user();
                $token = $request->bearerToken();
                $employee->api_token = $token;
                return $this->responseJson(200 , "Changed Successfully", new EmployeeResource($employee));
            }else{
                return $this->responseJsonFailed(422, 'user password is incorrect');
            }
        } catch (Throwable $e) {
            return $this->responseJsonFailed();
        }
    }

    public function profile(Request $request){
        if($request->header('lang')){
            if($request->header('lang') == 'ar'){
                $lang = 'ar';
            }else{$lang = 'en';}
        }else{
            $lang = 'ar';
        }
        return $this->responseJson(200 , "Successfully", new ProfileResource(Auth::user(),$lang));
    }

    public function updateProfile(ProfileRequest $request){


        $exceptions = [];
        if($request->header('lang')){
            if($request->header('lang') == 'ar'){
                $lang = 'ar';
            }else{$lang = 'en';}
        }else{
            $lang = 'ar';
        }
        $exceptions = ['image'];
        $employee = Auth::user();

        $image = $request->file('image');
        if($request->hasFile('image')){
            $new_name = time() . $image->getClientOriginalName();
            $image->move(public_path('/images/employee/profile'), $new_name);
            $employee->update(['image' => $new_name]);


        }

        if ($request->name == null){
           array_push($exceptions,'name');
        }
        if ($request->phone == null){
            array_push($exceptions,'phone');
        }
        if ($request->email == null){
            array_push($exceptions,'email');
        }


        $employee->update($request->except($exceptions));
        return $this->responseJson(200 , "Successfully", new ProfileResource($employee, $lang));
    }

    public function logout(){
        Auth::user()->token()->revoke();
        return $this->responseJsonWithoutData();
    }


    public function reset() {
        return $this->responseJsonWithoutData(200 , 'Password Sent To Your Email');
    }

}

