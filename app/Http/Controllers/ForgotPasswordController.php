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
        $temp = $user->id;


        if(empty($user))
        {
            $code = 404;
            $output = [
                'code' => $code,
                'message' => 'Email not found'
            ];
            return respose()->json($output, $code);
        }

        //Check if email exists in forgotpassword table already, then send that token
        $forgot = ForgotPasswordModel::where('user_id',$user->id)->where('active_status' , 1)->first();

        if(empty($forgot))
        {
            //Generate a JWT
            $token = auth()->tokenById($temp);  

            //Saving into forgot password database

            $forgot = new ForgotPasswordModel;
            $forgot->user_id = $user->id;   
            $forgot->token = $token;
            $forgot->active_status = 1;
            $forgot->save();


            //Send Email - 3rd week

            return response()->json(['message' => 'Succesfully generated'],200);
        }
        else
        {
            return response()->json(['message' => 'Reset Link already sent'], 200);
        }
        
    }

    public function resetverify(Request $request)
    {
        // Take token and new password in request 
        $this->validate($request, [
            //'email' => 'required|email',
            'token_rec' => 'required',
            'newpassword' => 'required|string'
        ]);

        $input = $request->only(/*'email',*/'token_rec','newpassword');

        //Get the user_id
        //$user = User::where('email',$input['email'])->first();

        $forgot = ForgotPasswordModel::join('users','forgotpassword.user_id','=','users.id')->where('token',$input['token_rec'])->first();
        //dd($forgot->user_id);
        // Verify token
        if(empty($forgot))
        {
            return response()->json(['message'=>'Token is incorrect'],401);  
        }
        else
        {
            // Update db with hash of new password
            $password = $input['newpassword'];
            $hashed_pd = app('hash')->make($password);

            $affected = User::where('id',$forgot->user_id)->update(['password' => $hashed_pd]);

            //Change active_status to 0
            $affected = ForgotPasswordModel::where('user_id',$forgot->user_id)->update(['active_status' => 0]);


        }
        
        return response()->json(['message' => 'Succesfully password changed'],200);
    }
}
