<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\countries_cost\add;
use App\Models\Cost_country;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class countries_cost extends Controller
{
    public function index(){
        $countries_cost = Cost_country::get();
        return view('admins.countries_cost.index')->with('countries_cost', $countries_cost);
    }

    public function createView(){
        $countries = Country::active()->get();
        return view('admins.countries_cost.create')->with([
            'countries' => $countries,
        ]);
    }

    public function create(add $request){
        try{
            DB::beginTransaction();
                //create countries cost
                Cost_country::create([
                    'cost'          => $request->cost,
                    'country_id'    => $request->country_id,
                ]);

            DB::commit();
            return redirect('admins/countries_cost')->with('success', 'add countries cost success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/countries_cost')->with('error', 'add countries cost faild');
        }
    }

    public function delete($cost_country_id){
        //get cost country 
        $cost_country = Cost_country::find($cost_country_id);

        if($cost_country == null)
            return redirect('admins/countries_cost')->with('error', 'delete faild');

        $cost_country->delete();

        return redirect('admins/countries_cost')->with('success', 'delete countries cost success');
    }

    public function editView($cost_country_id){
        $cost_country = Cost_country::find($cost_country_id);
        $Countries = Country::active()->get();

        //if admin not found
        if($cost_country == null)
            return redirect('admins/countries_cost');
        
        return view('admins.countries_cost.edit')->with([
            'countries_cost' => $cost_country,
            'countries'      => $Countries,
        ]);
    }

    public function edit($cost_country_id,add $request){
        $cost_country = Cost_country::find($cost_country_id);

        try{
            DB::beginTransaction();
                //edit country
                $cost_country->cost              = $request->cost;
                $cost_country->country_id        = $request->country_id;
                $cost_country->save();

            DB::commit();
            return redirect('admins/countries_cost')->with('success', 'add country cost success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/countries_cost')->with('error', 'add country cost faild');
        }
    }
}
