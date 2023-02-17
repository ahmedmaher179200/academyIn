<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\terms\add;
use App\Models\Term;
use App\Models\TermsTranslation;
use App\Models\TermTranslation;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class terms extends Controller
{
    public function index(Request $request){
        $terms = Term::where('status', '!=', -1)
                        ->where('year_id', $request->get('year'))
                        ->get();
        

        return view('admins.terms.index')->with([
            'terms'             => $terms,
            'curriculum_id'     => $request->get('curriculum'),
            'level_id'          => $request->get('level'),
            'year_id'           => $request->get('year'),
            'parms'             => 'curriculum=' . $request->get('curriculum') .
                                    '&&level=' . $request->get('level') . 
                                    '&&year=' . $request->get('year'),
        ]);
    }

    public function delete($term_id){
        //get term 
        $term = Term::find($term_id);
        $parms = 'curriculum=' . $_GET['curriculum'] .
                '&&level=' . $_GET['level'] .
                '&&year=' . $_GET['year'];

        if($term == null)
            return redirect('admins/terms?' . $parms)->with('error', 'delete faild');

        $term->status = -1;
        $term->save();

        return redirect('admins/terms?' . $parms)->with('success', 'delete term success');
    }

    public function createView(Request $request){
        $year_id = $request->get('year');
        $years = Year::active()->where('level_id', $request->get('level'))->get();
        return view('admins.terms.create')->with([
            'years'     => $years,
            'year_id'   => $year_id
        ]);
    }

    public function create(add $request){
        $parms = 'curriculum=' . $_GET['curriculum'] .
                '&&level=' . $_GET['level'] .
                '&&year=' . $_GET['year'];
        try{
            DB::beginTransaction();
                //get cate status
                ($request->status== 1)? $active = 1: $active = 0;

                //create country
                $new_term = Term::create([
                    'status'            => $active,
                    'year_id'           => $request->year_id,
                ]);

                foreach($request->terms as $key=>$term){
                    TermTranslation::create([
                        'name'              => $term['name'],
                        'locale'            => $key,
                        'term_id'           => $new_term['id'],
                    ]);
                }
            DB::commit();
            return redirect('admins/terms?' . $parms)->with('success', 'add term success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/terms?' . $parms)->with('error', 'add term faild');
        }
    }

    public function editView($term_id, Request $request){
        $term = Term::find($term_id);
        $years = Year::active()->where('level_id', $request->get('level'))->get();
        $parms = 'curriculum=' . $_GET['curriculum'] .
                '&&level=' . $_GET['level'] .
                '&&year=' . $_GET['year'];
        
        //if Curriculum not found
        if($term == null)
            return redirect('admins/terms?' . $parms);
        
        return view('admins.terms.edit')->with([
            'term'          => $term,
            'years'         => $years,
        ]);
    }

    public function edit($term_id, add $request){
        $parms = 'curriculum=' . $_GET['curriculum'] .
                 '&&level=' . $_GET['level'] .
                 '&&year=' . $_GET['year'];
        try{
            $term = Term::find($term_id);
            $termsTranslation = TermTranslation::where('term_id', $term_id)->get();
            
            DB::beginTransaction();
                //get cate status
                ($request->status== 1)? $active = 1: $active = 0;

                //edit term
                $term->status           = $active;
                $term->year_id          = $request->year_id;
                $term->save();

                //change all term trans name
                foreach($termsTranslation as $termTranslation){
                    $termTranslation->name = $request->terms[$termTranslation->locale]['name'];
                    $termTranslation->save();
                }

            DB::commit();
            return redirect('admins/terms?' . $parms)->with('success', 'add term success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/terms?' . $parms)->with('error', 'add term faild');
        }
    }
}
