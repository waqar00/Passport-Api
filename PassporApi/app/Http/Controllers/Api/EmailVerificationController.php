<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;

class EmailVerificationController extends Controller
{

        public function verify(Request $request){
            // return $request->all();
            $user=User::find($request->id);
            if($user->hasVerifiedEmail()){

                return redirect('/');
            }
            if($user->markEmailAsVerified()){
                return redirect('/');
            }
        }
        public function resend(Request $request){
            $user=User::whereEmail($request->email)->first();
            if($user->hasVerifiedEmail()){
                return $this->successMessage('Email is already verified');
            }
            $user->sendEmailVerificationNotification();
            return $this->successMessage('verification email send succesfully');
        }
}
