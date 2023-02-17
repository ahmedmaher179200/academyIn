<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\promocode\add;
use App\Models\Promo_code;
use Illuminate\Http\Request;

class promo_codes extends Controller
{
    public function index(){
        //select all admin
        $promo_codes = Promo_code::where('status', '!=', -1)->get();
        return view('admins.promo_codes.index')->with('promo_codes', $promo_codes);
    }

    public function createView(){
        return view('admins.promo_codes.create');
    }

    public function create(add $request){
        ($request->status== 1)? $active = 1: $active = 0;

        Promo_code::create([
            'code'          => $request->code,
            'percentage'    => $request->percentage,
            'expiration'    => $request->expiration,
            'status'        => $active,
        ]);

        return redirect('admins/promo_codes')->with('success', 'add promoCode success');
    }

    public function delete($id){
        //sellect Promo_code
        $promo_code = Promo_code::find($id);

        if($promo_code == null)
            return redirect('admins/promo_codes')->with('error', 'delete faild');
        
        $promo_code->status = -1;
        $promo_code->save();

        return redirect('admins/promo_codes')->with('success', 'delete success');
        
    }

    public function editView($promo_code_id){
        $promo_code = Promo_code::find($promo_code_id);

        return view('admins.promo_codes.edit')->with('promo_code', $promo_code);
    }

    public function edit(add $request, $promo_code_id){
        //get promocode
        $promo_code = Promo_code::find($promo_code_id);

        ($request->status== 1)? $active = 1: $active = 0;

        //update promo code
        $promo_code->code          = $request->code;
        $promo_code->percentage    = $request->percentage;
        $promo_code->expiration    = $request->expiration;
        $promo_code->status        = $active;
        $promo_code->save();

        return redirect('admins/promo_codes')->with('success', 'add promoCode success');
    }
}
