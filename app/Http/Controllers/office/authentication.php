<?php

namespace App\Http\Controllers\office;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class authentication extends Controller
{
    public function loginView(){

        return view('offices.login');
    }

    public function login(login $Request){
        $credentials = ['username' => $Request->username, 'password' => $Request->password];
        if (Auth::guard('office')->attempt($credentials)) {
            return redirect('offices');
        }

        return redirect()->back()->with('error', 'username or password is wrong' );
    }
    

    public function logout(){
        Auth::guard('office')->logout();

        return redirect('offices/login');
    }
}
