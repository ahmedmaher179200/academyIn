<?php

namespace App\Http\Controllers\site\guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\answersResource;
use App\Http\Resources\classTypeResource;
use App\Http\Resources\countryResource;
use App\Http\Resources\curriculumResource;
use App\Http\Resources\main_subjectResource;
use App\Http\Resources\materialResource;
use App\Http\Resources\questionsResource;
use App\Http\Resources\subjectsResource;
use App\Http\Resources\teacher_classesTypeResourc;
use App\Models\Answer;
use App\Models\Class_type;
use App\Models\Contact_us;
use App\Models\Country;
use App\Models\Curriculum;
use App\Models\Main_subject;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Teacher;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class home extends Controller
{
    use response;
    public function teachersBysubject(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'subject_id'    => 'required|exists:subjects,id',
        ]);

        if($validator->fails()){
            return response::faild($validator->errors()->first(), 403, 'E03');
        }
        //gey subject
        $subject = Subject::find($request->subject_id);

        //online
        $online_teachers = Teacher::active()
                                    ->where('online', 1)
                                    ->where('main_subject_id', $subject->main_subject_id)
                                    ->whereHas('Teacher_years', function($qeury) use($subject){
                                        $qeury->where('year_id', $subject->Term->year_id);
                                    })
                                    ->limit(5);
        //offline
        $offline_teachers = Teacher::active()
                                    ->where('online', 0)
                                    // ->whereHas('Available_classes', function($qeury) use($request){
                                    //     $qeury->where('to', '>', date('Y-m-d H:i:s'))
                                    //             ->where('subject_id', $request->get('subject_id'))
                                    //             ->whereDoesntHave('Student_classes')
                                    //             ->whereHas('Class_type', function($q){
                                    //                 $q->active();
                                    //             });
                                    // })
                                    ->where('main_subject_id', $subject->main_subject_id)
                                    ->whereHas('Teacher_years', function($qeury) use($subject){
                                        $qeury->where('year_id', $subject->Term->year_id);
                                    });
        // ->inRandomOrder()

        return response()->json([
            'successful'                => true,
            'message'                   => trans('auth.success'),
            'online_teachers_count'     => $online_teachers->count(),
            'offline_teachers_count'     => $offline_teachers->count(),
            'online_teachers'   => teacher_classesTypeResourc::collection($online_teachers->get()),
            'offline_teachers'  => teacher_classesTypeResourc::collection($offline_teachers->paginate(5))->response()->getData(true),
        ], 200);
    }

    public function online_teachers_bysubject(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'subject_id'    => 'required|exists:subjects,id',
        ]);

        if($validator->fails()){
            return response::faild($validator->errors()->first(), 403, 'E03');
        }
        
        //get subject
        $subject = Subject::find($request->subject_id);

        //online
        $online_teachers = Teacher::active()
                                    ->where('online', 1)
                                    ->where('main_subject_id', $subject->main_subject_id)
                                    ->whereHas('Teacher_years', function($qeury) use($subject){
                                        $qeury->where('year_id', $subject->Term->year_id);
                                    })
                                    ->limit(5);

        return response()->json([
            'successful'            => true,
            'message'               => trans('auth.success'),
            'online_teachers_count' => $online_teachers->count(),
            'online_teachers'       => teacher_classesTypeResourc::collection($online_teachers->get()),
        ], 200);
    }

    public function materials(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'subject_id'    => 'required|exists:subjects,id',
        ]);

        if($validator->fails()){
            return response::faild($validator->errors()->first(), 403, 'E03');
        }

        //get materials
        $materials = Subject::find($request->get('subject_id'))->Materials;

        return $this->success(
            trans('auth.success'),
            200,
            'materials',
            materialResource::collection($materials)
        );
    }

    public function classes_type_cost(Request $request){
        // validate registeration request
        $validator = Validator::make($request->all(), [
            'teacher_id'     => 'required|integer|exists:teachers,id',
            'subject_id'     => 'required|integer|exists:subjects,id',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403);
        }

        $classes_type = Class_type::active()->get();

        return $this->success(
            trans('auth.success'),
            200,
            'classes_type',
            classTypeResource::collection($classes_type)
        );
    }

    public function countries(){
        $countries = Country::active()->get();

        return $this->success(
            trans('auth.success'),
            200,
            'countries',
            countryResource::collection($countries)
        );
    }

    public function curriculums(){
        $curriculums = Curriculum::active()->get();

        return $this->success(
            trans('auth.success'),
            200,
            'curriculums',
            curriculumResource::collection($curriculums)
        );
    }

    public function main_subjects(){
        $main_subjects = Main_subject::active()->get();

        return $this->success(
            trans('auth.success'),
            200,
            'subjects',
            main_subjectResource::collection($main_subjects)
        );
    }

    public function subjects(){
        $subject = Subject::active()->get();

        return $this->success(
            trans('auth.success'),
            200,
            'subjects',
            subjectsResource::collection($subject)
        );
    }

    public function answers(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'question_id'    => 'required|exists:questions,id',
        ]);

        if($validator->fails()){
            return response::faild($validator->errors()->first(), 403, 'E03');
        }

        //get answers
        $answers = Answer::active()
                            ->where('question_id', $request->get('question_id'))
                            ->orderBy('id', 'desc')
                            ->paginate(5);

        return response()->json([
            'successful'        => true,
            'message'           => trans('auth.success'),
            'answers_count'     => Answer::where('question_id', $request->get('question_id'))->count(),
            'answers'           => answersResource::collection($answers)->response()->getData(true),
        ], 200);
    }

    public function questions(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'subject_id'       => 'required|exists:subjects,id',
        ]);

        if($validator->fails()){
            return response::faild($validator->errors()->first(), 403, 'E03');
        }

        //get questions
        $questions = Question::active()
                                ->where('subject_id', $request->get('subject_id'))
                                ->orderBy('id', 'desc')
                                ->paginate(5);

        return response()->json([
            'successful'        => true,
            'message'           => trans('auth.success'),
            'questions_count'   => Question::where('subject_id', $request->get('subject_id'))->count(),
            'questions'         => questionsResource::collection($questions)->response()->getData(true),
        ], 200);
    }

    public function Terms_and_Conditions(Request $request){
        ($request->header('lang') == 'ar')? $lang = 'ar': $lang = 'en';

        if($lang == 'en'){
            return view('terms_and_conditions.en');
        }
        return view('terms_and_conditions.ar');
    }

    public function contact_us(Request $request){
        $validator = Validator::make($request->all(), [
            'email'       => 'required|string',
            'title'       => 'required|string',
            'content'     => 'required|string',
        ]);

        Contact_us::create([
            'email' => $request->email,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        if($validator->fails())
            return response::faild($validator->errors()->first(), 403, 'E03');

        return response()->json([
            'successful'        => true,
            'message'           => trans('auth.success'),
        ], 200); 
    }
}
