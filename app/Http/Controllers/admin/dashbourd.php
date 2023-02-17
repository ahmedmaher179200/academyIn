<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class dashbourd extends Controller
{
    public function index(){
        return view('admins.dashbourd');
    }
}
