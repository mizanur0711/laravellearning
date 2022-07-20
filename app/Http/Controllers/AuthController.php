<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        //validation login form
       $credential =  $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        //login
        if(\Auth::attempt($request->only('email','password')))
        {
            return redirect('home');
        }

        return  redirect('login')->withErrors('invalid credential');
    }

    public function register(Request $request)
    {
        //validation
        $request->validate([
            'name' =>'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|confirmed'
        ]);

        //save in user table
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Hash::make($request->password)

        ]);

        //user login here
        if(\Auth::attempt($request->only('email','password')))
        {
            return redirect('home');
        }

        return  redirect('register')->withErrors('Error');
    }

    public function register_view()
    {
        return view('auth.register');
    }

    public function home()
    {
        return view('home');
    }
    public function logout()
    {
        \Session::flush();
        \Auth::logout();
        return redirect('login');
    }
}
