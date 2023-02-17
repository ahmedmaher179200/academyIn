<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class authentication extends Controller
{
    public function loginView(){

        return view('admins.login');
    }

    public function login(login $Request){
        $credentials = ['username' => $Request->username, 'password' => $Request->password];
        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect('admins');
        }

        return redirect()->back()->with('error', 'username or password is wrong' );
    }
    

    public function logout(){
        Auth::guard('admin')->logout();

        return redirect('admins/login');
    }
}
