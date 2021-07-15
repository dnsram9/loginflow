<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ForgotPasswordModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class ForgotPasswordController extends Controller
{
    public function forgotpassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email'
        ]);

        $input = $request->only('email');

        //Check if email exists in users db
        $user = User::where('email',$input['email'])->first();
        if(empty($user))
        {
            $code = 404;
            $output = [
                'code' => $code,
                'message' => 'Email not found'
            ];
            return respose()->json($output, $code);
        }
        

        //Generate a JWT
        $token = auth()->tokenById($user->id);


        //Saving into forgot password database

        $forgot = new ForgotPasswordModel;
        $forgot->user_id = $user->id;   
        $forgot->token = $token;
        $forgot->active_status = 1;
        $forgot->save();


        //Send Email - 3rd week


        return response()->json(['message' => 'Succesfully generated'],200);
    }
}
