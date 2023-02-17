<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\levels\add;
use App\Models\Curriculum;
use App\Models\Level;
use App\Models\LevelTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class levels extends Controller
{
    public function index(Request $request){
        $levels = Level::where('status', '!=', -1)
                            ->where('curriculum_id', $request->get('curriculum'))
                            ->get();
                            
        return view('admins.levels.index')->with([
            'levels'            => $levels,
            'parms'             => 'curriculum=' . $request->get('curriculum'),
        ]);
    }

    public function delete($level_id){
        //get level 
        $level = Level::find($level_id);
        $parms = 'curriculum=' . $_GET['curriculum'];


        if($level == null)
            return redirect('admins/levels?' . $parms)->with('error', 'delete faild');

        $level->status = -1;
        $level->save();

        return redirect('admins/levels?' . $parms)->with('success', 'delete level success');
    }

    public function createView(Request $request){
        $curriculums    = Curriculum::active()->get();
        $curriculum_id  = $request->get('curriculum');
        return view('admins.levels.create')->with([
            'curriculums'   => $curriculums,
            'curriculum_id' => $curriculum_id,
        ]);
    }

    public function create(add $request){
        $parms = 'curriculum=' . $_GET['curriculum'];
        try{
            DB::beginTransaction();
                //get cate status
                ($request->status== 1)? $active = 1: $active = 0;

                //create country
                $new_level = Level::create([
                    'status'            => $active,
                    'curriculum_id'     => $request->curriculum_id,
                ]);

                foreach($request->levels as $key=>$level){
                    LevelTranslation::create([
                        'name'              => $level['name'],
                        'locale'            => $key,
                        'level_id'          => $new_level['id'],
                    ]);
                }
            DB::commit();
            return redirect('admins/levels?' . $parms)->with('success', 'add level success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/levels?' . $parms)->with('error', 'add level faild');
        }
    }

    public function editView($level_id){
        $level = Level::find($level_id);
        $curriculums = Curriculum::active()->get();
        $parms = 'curriculum=' . $_GET['curriculum'];

        //if Curriculum not found
        if($level == null)
            return redirect('admins/levels?' . $parms);
        
        return view('admins.levels.edit')->with([
            'level'         => $level,
            'curriculums'   => $curriculums,
        ]);
    }

    public function edit($level_id, add $request){
        $parms = 'curriculum=' . $_GET['curriculum'];

        try{
            $level = Level::find($level_id);
            $levelsTranslation = LevelTranslation::where('level_id', $level_id)->get();
            
            DB::beginTransaction();
                //get cate status
                ($request->status== 1)? $active = 1: $active = 0;

                //edit curriculum
                $level->status            = $active;
                $level->curriculum_id     = $request->curriculum_id;
                $level->save();

                //change all level trans name
                foreach($levelsTranslation as $levelsTranslation){
                    $levelsTranslation->name = $request->levels[$levelsTranslation->locale]['name'];
                    $levelsTranslation->save();
                }

            DB::commit();
            return redirect('admins/levels?' . $parms)->with('success', 'add level success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/levels?' . $parms)->with('error', 'add level faild');
        }
    }
}
