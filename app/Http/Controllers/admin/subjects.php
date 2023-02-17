<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\subjects\add;
use App\Models\Image;
use App\Models\Main_subject;
use App\Models\Subject;
use App\Models\SubjectTranslation;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class subjects extends Controller
{
    public function index(Request $request){
        $subjects = Subject::where('status', '!=', -1)
                            ->where('term_id', $request->get('term'))
                            ->orderBy('order_by')
                            ->get();

        return view('admins.subjects.index')->with([
            'subjects'          => $subjects,
            'curriculum_id'     => $request->get('curriculum'),
            'level_id'          => $request->get('level'),
            'year_id'           => $request->get('year'),
            'term_id'           => $request->get('term'),
            'parms'             => 'curriculum=' . $request->get('curriculum') .
                                    '&&level=' . $request->get('level') . 
                                    '&&year=' . $request->get('year').
                                    '&&term=' . $request->get('term'),
        ]);
    }

    public function delete($subject_id){
        //get Subject 
        $subject = Subject::find($subject_id);
        $parms = 'curriculum=' . $_GET['curriculum'] .
                '&&level=' . $_GET['level'] .
                '&&year=' . $_GET['year'] .
                '&&term=' . $_GET['term'];

        if($subject == null)
            return redirect('admins/subjects?' . $parms)->with('error', 'delete faild');

        $subject->status = -1;
        $subject->save();

        return redirect('admins/subjects?' . $parms)->with('success', 'delete subject success');
    }

    public function createView(Request $request){
        $term_id = $request->get('term');
        $terms = Term::active()->where('year_id', $request->get('year'))->get();
        $main_subjects = Main_subject::active()->get();
        return view('admins.subjects.create')->with([
            'terms'         => $terms,
            'term_id'       => $term_id,
            'main_subjects' => $main_subjects,
        ]);
    }

    public function create(add $request){
        $parms= 'curriculum=' . $_GET['curriculum'] .
                '&&level=' . $_GET['level'] .
                '&&year=' . $_GET['year'] .
                '&&term=' . $_GET['term'];
        try{
            DB::beginTransaction();
                //get cate status
                ($request->status== 1)? $active = 1: $active = 0;

                //create Subject
                Subject::create([
                    'status'            => $active,
                    'term_id'           => $request->term_id,
                    'main_subject_id'   => $request->main_subject_id,
                    'order_by'          => $request->order_by,
                ]);

            DB::commit();
            return redirect('admins/subjects?' . $parms)->with('success', 'add subject success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/subjects?' . $parms)->with('error', 'add subject faild');
        }
    }

    public function editView($subject_id, Request $request){
        $subject = Subject::find($subject_id);
        $terms = Term::active()->where('year_id', $request->get('year'))->get();
        $main_subjects = Main_subject::active()->get();

        $parms= 'curriculum=' . $_GET['curriculum'] .
                '&&level=' . $_GET['level'] .
                '&&year=' . $_GET['year'] .
                '&&term=' . $_GET['term'];

        //if Curriculum not found
        if($subject == null)
            return redirect('admins/subjects?' . $parms);
        
        return view('admins.subjects.edit')->with([
            'subject'       => $subject,
            'terms'         => $terms,
            'main_subjects' => $main_subjects,
        ]);
    }

    public function edit($subject_id, Request $request){
        $parms= 'curriculum=' . $_GET['curriculum'] .
                '&&level=' . $_GET['level'] .
                '&&year=' . $_GET['year'] .
                '&&term=' . $_GET['term'];
        try{
            $subject = Subject::find($subject_id);
            
            DB::beginTransaction();
                //get cate status
                ($request->status== 1)? $active = 1: $active = 0;

                //edit subject
                $subject->status           = $active;
                $subject->order_by         = $request->order_by;
                $subject->save();

            DB::commit();
            return redirect('admins/subjects?' . $parms)->with('success', 'add subject success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/subjects?' . $parms)->with('error', 'add subject faild');
        }
    }
}
