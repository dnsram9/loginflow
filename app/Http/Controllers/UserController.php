<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    
    }

    public function register(Request $request)
    {
        // To validate
        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string'
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
            $user->role = 0;


            if($user->save())
            {
                $code = 201;
                $output = [
                    'user' => $user,
                    'code' => $code,
                    'message' => 'Registration is successfully done'
                ];
                //Send a Welcome email to the registered mail.

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
            $code = 500;
            $output = [
                'code' => $code,
                'message' => 'Oops!!!'
            ];

        }

        return response()->json($output, $code);
    }
    

    public function login(Request $request)
    {
        // To validate
        // Password should have Alpha numericals 

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
    

        $input = $request->only('email', 'password');

        //Check email exists or not in database 
        $user = User::where('email',$input['email'])->first();

        if(empty($user))
        {
            $code = 404;
            $output = [
                'code' => $code,
                'message' => 'Account with this email address is not found. Register now ?'
            ];
            return response()->json($output, $code);
        }

        if(! $authorized = Auth::attempt($input))
        {
                $code = 401;
                $output = [
                    'code' => $code,
                    'message' => 'Your password is incorrect'
                ];
        }
        else
        {
            $token = $this->respondWithToken($authorized);
            $code = 200;
                $output = [
                    'code' => $code,
                    'message' => 'Logged in successfully',
                    'token' => $token
                ];
        }

        return response()->json($output, $code);
    }  
    
    public function show()
    {
        //$results = app('db')->select("SELECT * FROM users");
        //return $results;


        $user = auth()->user();
        if($user->role)
        {
            $users = User::select('first_name','email','role')->get();
        }
        else
        {
            $users = User::select('first_name','email')->where('id',$user->id)->get();
        }
        return response()->json($users,200);
    }

    public function details()
    {
        $user = auth()->user();
        $users = User::select('first_name','email')->where('id',$user->id)->first();
        return response()->json($users,200);
    }
}
