<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\levels_cost\add;
use App\Models\Cost_level;
use App\Models\Curriculum;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class levels_cost extends Controller
{
    public function index(){
        $levels_cost = Cost_level::get();
        return view('admins.levels_cost.index')->with('levels_cost', $levels_cost);
    }

    public function createView(){
        $curriculums= Curriculum::active()->get();
        $levels = Level::active()->get();
        return view('admins.levels_cost.create')->with([
            'levels'        => $levels,
            'curriculums'   => $curriculums,
        ]);
    }

    public function create(add $request){
        try{
            DB::beginTransaction();
                //create levels cost
                Cost_level::create([
                    'cost'          => $request->cost,
                    'level_id'      => $request->level_id,
                ]);

            DB::commit();
            return redirect('admins/levels_cost')->with('success', 'add levels cost success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/levels_cost')->with('error', 'add levels cost faild');
        }
    }

    public function delete($cost_level_id){
        //get cost_levelcost_level 
        $cost_level = Cost_level::find($cost_level_id);

        if($cost_level == null)
            return redirect('admins/levels_cost')->with('error', 'delete faild');

        $cost_level->delete();

        return redirect('admins/levels_cost')->with('success', 'delete level cost success');
    }

    public function editView($cost_level_id){
        $cost_level = Cost_level::find($cost_level_id);
        $levels = Level::active()->get();

        //if admin not found
        if($cost_level == null)
            return redirect('admins/levels_cost');
        
        return view('admins.levels_cost.edit')->with([
            'level_cost' => $cost_level,
            'levels'     => $levels,
        ]);
    }

    public function edit($cost_level_id,add $request){
        $cost_level = Cost_level::find($cost_level_id);

        try{
            DB::beginTransaction();
                //edit country
                $cost_level->cost              = $request->cost;
                $cost_level->level_id          = $request->level_id;
                $cost_level->save();

            DB::commit();
            return redirect('admins/levels_cost')->with('success', 'add levels cost success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/levels_cost')->with('error', 'add levels cost faild');
        }
    }
}
