<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\years\add;
use App\Models\Level;
use App\Models\Term;
use App\Models\TermTranslation;
use App\Models\Year;
use App\Models\YearTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class years extends Controller
{
    public function index(Request $request){
        $years = Year::where('status', '!=', -1)
                        ->where('level_id', $request->get('level'))
                        ->get();

        return view('admins.years.index')->with([
            'years'             => $years,
            'curriculum_id'     => $request->get('curriculum'),
            'level_id'          => $request->get('level'),
            'parms'             => 'curriculum=' . $request->get('curriculum') .
                                    '&&level=' . $request->get('level'),
        ]);
    }

    public function delete($level_id){
        //get level 
        $year = Year::find($level_id);
        $parms = 'curriculum=' . $_GET['curriculum'] . '&&level=' . $_GET['level'];

        if($year == null)
            return redirect('admins/years?' . $parms)->with('error', 'delete faild');

        $year->status = -1;
        $year->save();

        return redirect('admins/years?' . $parms)->with('success', 'delete level success');
    }

    public function createView(Request $request){
        $level_id = $request->get('level');
        $levels = Level::active()->where('curriculum_id', $request->get('curriculum'))->get();
        return view('admins.years.create')->with([
            'levels'    => $levels,
            'level_id'  => $level_id,
        ]);
    }

    public function create(add $request){
        $parms = 'curriculum=' . $_GET['curriculum'] . '&&level=' . $_GET['level'];
        try{
            DB::beginTransaction();
                //get cate status
                ($request->status== 1)? $active = 1: $active = 0;

                //create country
                $new_year = Year::create([
                    'status'            => $active,
                    'level_id'          => $request->level_id,
                ]);

                foreach($request->years as $key=>$year){
                    YearTranslation::create([
                        'name'              => $year['name'],
                        'locale'            => $key,
                        'year_id'          => $new_year['id'],
                    ]);
                }

                //create term
                $new_term = Term::create([
                    'status'            => 1,
                    'year_id'           => $new_year->id,
                ]);
                $data = [
                    ['name' => 'first term' , 'locale' => 'en','term_id' => $new_term['id']],
                    ['name' => 'الترم الاول', 'locale' => 'ar','term_id' => $new_term['id']],
                ];
                TermTranslation::insert($data);

            DB::commit();
            return redirect('admins/years?' . $parms)->with('success', 'add year success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/years?' . $parms)->with('error', 'add year faild');
        }
    }

    public function editView($year_id,Request $request){
        $year = Year::find($year_id);
        $levels = Level::active()->where('curriculum_id', $request->get('curriculum'))->get();
        $parms = 'curriculum=' . $_GET['curriculum'] . '&&level=' . $_GET['level'];

        //if Curriculum not found
        if($year == null)
            return redirect('admins/years?' . $parms);
        
        return view('admins.years.edit')->with([
            'year'          => $year,
            'levels'        => $levels,
        ]);
    }

    public function edit($year_id, add $request){
        $parms = 'curriculum=' . $_GET['curriculum'] . '&&level=' . $_GET['level'];
        try{
            $year = Year::find($year_id);
            $yearsTranslation = YearTranslation::where('year_id', $year_id)->get();
            
            DB::beginTransaction();
                //get cate status
                ($request->status== 1)? $active = 1: $active = 0;

                //edit curriculum
                $year->status            = $active;
                $year->level_id          = $request->level_id;
                $year->save();

                //change all year trans name
                foreach($yearsTranslation as $yearTranslation){
                    $yearTranslation->name = $request->years[$yearTranslation->locale]['name'];
                    $yearTranslation->save();
                }

            DB::commit();
            return redirect('admins/years?' . $parms)->with('success', 'add year success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/years?' . $parms)->with('error', 'add year faild');
        }
    }
}
