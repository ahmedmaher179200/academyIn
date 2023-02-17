<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\year_cost\add;
use App\Models\Cost_year;
use App\Models\Curriculum;
use App\Models\Year;
use Illuminate\Support\Facades\DB;

class years_cost extends Controller
{
    public function index(){
        $years_cost = Cost_year::get();
        return view('admins.years_cost.index')->with('years_cost', $years_cost);
    }

    public function createView(){
        $curriculums= Curriculum::active()->get();
        $years = Year::active()->get();
        return view('admins.years_cost.create')->with([
            'years'        => $years,
            'curriculums'  => $curriculums,
        ]);
    }

    public function create(add $request){
        try{
            DB::beginTransaction();
                //create years cost
                Cost_year::create([
                    'cost'          => $request->cost,
                    'year_id'       => $request->year_id,
                ]);

            DB::commit();
            return redirect('admins/years_cost')->with('success', 'add years cost success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/years_cost')->with('error', 'add years cost faild');
        }
    }

    public function delete($cost_year_id){
        //get cost_year 
        $cost_year = Cost_year::find($cost_year_id);

        if($cost_year == null)
            return redirect('admins/years_cost')->with('error', 'delete faild');

        $cost_year->delete();

        return redirect('admins/years_cost')->with('success', 'delete year cost success');
    }

    public function editView($cost_year_id){
        $cost_year  = Cost_year::find($cost_year_id);
        $years      = Year::active()->get();

        //if year not found
        if($cost_year == null)
            return redirect('admins/years_cost');
        
        return view('admins.years_cost.edit')->with([
            'year_cost' => $cost_year,
            'years'     => $years,
        ]);
    }

    public function edit($cost_year_id,add $request){
        $cost_year = Cost_year::find($cost_year_id);

        try{
            DB::beginTransaction();
                //edit country
                $cost_year->cost              = $request->cost;
                $cost_year->year_id           = $request->year_id;
                $cost_year->save();

            DB::commit();
            return redirect('admins/years_cost')->with('success', 'add years cost success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/years_cost')->with('error', 'add years cost faild');
        }
    }
}
