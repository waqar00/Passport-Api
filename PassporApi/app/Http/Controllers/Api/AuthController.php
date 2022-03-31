<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Traits\UserQuery;
class AuthController extends Controller
{
    use UserQuery;
    public function login(Request $request)
    {
        $validation = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $password = $request['password'];
        $user = User::whereEmail($request->email)->first();
        try{
            if (!$user) {
                return $this->errorMessage('No user found with the provided email, Please sure to enter a valid email address', 404);
            }
            if (Hash::check($password, $user->password)) {
                $token = $user->createToken('MyToken')->accessToken;
                return $this->successResponse(['user' => $user, 'token' => $token], 'Logged in successfully', 200);
            } else {
                return $this->errorMessage('Failed to login, the provided password is invalid.', 401);
            }
        }
        catch(\Exception $exception){
            return $this->errorMessage($exception->getMessage(), 500);
        }

    }
    public function register(Request $request)
    {
        $validator = $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users|regex:/(.+)@(.+)\.(.+)/i',
        'password' => 'required',
        ]);
        $udata=[
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request['password'])
        ];

        $user = User::create($udata);
        $user->sendEmailVerificationNotification();
        // $success =  $user->createToken('MyApp')->accessToken;
        return $this->successResponse(['user' => $user], 'You have Register successfully', 200);
    }

    public function getSingleUser($id){

        $singleUser= $this->singleUser($id);
        return $this->successResponse(['user' => $singleUser], 'You have Register successfully', 200);
    }
}
