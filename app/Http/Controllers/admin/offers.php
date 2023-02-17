<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\offers\add;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class offers extends Controller
{
    public function index(){
        $offers = Offer::get();
        return view('admins.offers.index')->with('offers', $offers);
    }

    public function delete($offer_id){
        //get offer 
        $offer = Offer::find($offer_id);

        if($offer == null)
            return redirect('admins/offers')->with('error', 'delete faild');

        $offer->delete();

        return redirect('admins/offers')->with('success', 'delete offer success');
    }

    public function createView(){
        return view('admins.offers.create');
    }

    public function create(add $request){
        try{
            DB::beginTransaction();
                //create offer
                Offer::create([
                    'price'            => $request->price,
                    'discount'         => $request->discount,
                    'classes_count'    => $request->classes_count,
                ]);
                
            DB::commit();
            return redirect('admins/offers')->with('success', 'add offers success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/offers')->with('error', 'add offers faild');
        }
    }

    public function editView($offer_id){
        $offer = Offer::find($offer_id);

        //if admin not found
        if($offer == null)
            return redirect('admins/offers');
        
        return view('admins.offers.edit')->with([
            'offer' => $offer,
        ]);
    }

    public function edit($offer_id,add $request){
        $offer = Offer::find($offer_id);

        try{
            DB::beginTransaction();
                //edit offer
                $offer->price              = $request->price;
                $offer->discount           = $request->discount;
                $offer->classes_count      = $request->classes_count;
                $offer->save();
            DB::commit();
            return redirect('admins/offers')->with('success', 'add offer success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/offers')->with('error', 'add offer faild');
        }
    }
}
