<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {  
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $status = 200;
            $response = [
                'user' => Auth::user(),
                'token' => Auth::user()->createToken('userToken')->accessToken,
            ];
        }else{
            $status = 401;
            $response = ['error' => 'The email or password is incorrect.'];
        }

        return response()->json($response, $status);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:25',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        } 

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        
        $user = User::create($input);
 
        $response = [
            'user' => $user,
            'token' => $user->createToken('userToken')->accessToken,
        ];
        
        return response()->json($response, 200);
    } 

    public function show(User $user)
    {
        return response()->json($user,200);
    }
}
