<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Available_class;
use Illuminate\Http\Request;

class classes extends Controller
{
    public function index(){
        $classes = Available_class::get();
        return view('admins.classes.index')->with('classes', $classes);
    }

    public function join($class_id){
        $available_class =  Available_class::find($class_id);

        if(!$available_class)
            redirect('admins/classes');

        return view('agora')->with([
            'token'     => $available_class->agora_token,
            'chennel'   => $available_class->channel_name,
        ]);
    }
}
