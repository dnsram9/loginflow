<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function register(Request $request)
    {
        //dd($request);


        // To validate
        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $input = $request->only('first_name', 'last_name', 'email', 'password');

        // To register
        try 
        {
            
            $user = new User;
            $user->first_name = $input['first_name'];
            $user->last_name = $input['last_name'];
            $user->email = $input['email'];

            $password = $input['password'];
            $user->password = app('hash')->make($password);


            if($user->save())
            {
                $code = 200;
                $output = [
                    'user' => $user,
                    'code' => 200,
                    'message' => 'Registration is successfully done'
                ];
            }
            else
            {
                $code = 500;
                $output = [
                    'code' => 500,
                    'message' => 'Error has occured'
                ];

            }

        } 
        catch (Exception $e) 
        {
            //dd($e->getMessage()); 
            $code = 500;
            $output = [
                'code' => 500,
                'message' => 'Oops!!!'
            ];

        }

        return response()->json($output, $code);
    }
    
}
