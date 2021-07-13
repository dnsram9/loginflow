<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

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

    public function register(Request $request);
    {
        // To validate
        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        // To register
        try {
            
            $user = User;
            $user->first_name = $request->input(first_name);
            $user->last_name = $request->input(last_name);
            $user->email = $request->input(email);

            $password = $request->input(password);
            $user->password = app('hash')->make($password);

        } catch (Exception $e) {
            dd($e->getMessage());             
        }
    }
    //
}
