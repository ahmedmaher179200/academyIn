<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\company_percentages\add;
use App\Models\Cost_company_percentage;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class company_percentages extends Controller
{
    public function index(){
        $company_percentages = Cost_company_percentage::get();
        return view('admins.company_percentages.index')->with('company_percentages', $company_percentages);
    }

    public function createView(){
        $countries = Country::active()->get();
        return view('admins.company_percentages.create')->with([
            'countries' => $countries,
        ]);
    }

    public function create(add $request){
        try{
            DB::beginTransaction();
                //create countries percentage
                Cost_company_percentage::create([
                    'percentage'    => $request->percentage,
                    'country_id'    => $request->country_id,
                ]);

            DB::commit();
            return redirect('admins/company_percentages')->with('success', 'add countries percentage success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/company_percentages')->with('error', 'add countries percentage faild');
        }
    }

    public function delete($percentage_country_id){
        //get percentage country 
        $percentage_country = Cost_company_percentage::find($percentage_country_id);

        if($percentage_country == null)
            return redirect('admins/company_percentages')->with('error', 'delete faild');

        $percentage_country->delete();

        return redirect('admins/company_percentages')->with('success', 'delete countries percentage success');
    }

    public function editView($percentage_country_id){
        $percentage_country = Cost_company_percentage::find($percentage_country_id);
        $countries = Country::active()->get();

        //if percentage_country not found
        if($percentage_country == null)
            return redirect('admins/company_percentages');
        
        return view('admins.company_percentages.edit')->with([
            'country_percentage' => $percentage_country,
            'countries'          => $countries,
        ]);
    }

    public function edit($percentage_country_id,add $request){
        $percentage_country = Cost_company_percentage::find($percentage_country_id);

        try{
            DB::beginTransaction();
                //edit country
                $percentage_country->percentage        = $request->percentage;
                $percentage_country->country_id        = $request->country_id;
                $percentage_country->save();

            DB::commit();
            return redirect('admins/company_percentages')->with('success', 'add country percentage success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/company_percentages')->with('error', 'add country percentage faild');
        }
    }
}
