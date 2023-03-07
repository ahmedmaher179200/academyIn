<?php

namespace App\Http\Controllers\site\guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\answersResource;
use App\Http\Resources\classTypeResource;
use App\Http\Resources\countryResource;
use App\Http\Resources\curriculumResource;
use App\Http\Resources\level_year_subjectsResource;
use App\Http\Resources\level_yearResource;
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
use App\Models\Level;
use App\Models\Main_subject;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Teacher;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    use response;
    public function countries(){
        $countries = Country::active()->get();

        return $this->success(
            trans('auth.success'),
            200,
            'countries',
            countryResource::collection($countries)
        );
    }

    public function answers(Request $request){
        $validator = Validator::make($request->all(), [
            'question_id'    => 'required|exists:questions,id',
        ]);

        if($validator->fails())
            return response::faild($validator->errors()->first(), 403, 'E03');

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
        $validator = Validator::make($request->all(), [
            'subject_id'       => 'required|exists:subjects,id',
        ]);

        if($validator->fails())
            return response::faild($validator->errors()->first(), 403, 'E03');

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

    public function curriculums(){
        $curriculums = Curriculum::active()->get();

        return $this->success(
            trans('auth.success'),
            200,
            'curriculums',
            curriculumResource::collection($curriculums)
        );
    }
    
    public function level_year(Request $request){
        $validator = Validator::make($request->all(), [
            'curriculum_id'    => 'required|exists:curriculums,id',
        ]);

        if($validator->fails())
            return response::faild($validator->errors()->first(), 403, 'E03');

        $level = Level::active()
                            ->where('curriculum_id', $request->get('curriculum_id'))
                            ->with('Years')
                            ->get();

        return $this->success(trans('auth.success'), 200, 'levels', level_yearResource::collection($level));
    }

    public function level_year_subjects(Request $request){
        $validator = Validator::make($request->all(), [
            'curriculum_id'    => 'required|exists:curriculums,id',
        ]);

        if($validator->fails())
            return response::faild($validator->errors()->first(), 403, 'E03');

        $levels = Level::active()
                            ->where('curriculum_id', $request->get('curriculum_id'))
                            ->with('Years.Subjects')
                            ->get();

        return $this->success(trans('auth.success'), 200, 'levels', level_year_subjectsResource::collection($levels));
    }

    public function classes_type_cost(Request $request){
        $validator = Validator::make($request->all(), [
            'teacher_id'     => 'required|integer|exists:teachers,id',
            'subject_id'     => 'required|integer|exists:subjects,id',
        ]);

        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403);

        $classes_type = Class_type::active()->get();

        return $this->success(
            trans('auth.success'),
            200,
            'classes_type',
            classTypeResource::collection($classes_type)
        );
    }

    public function Terms_and_Conditions(Request $request){
        ($request->header('lang') == 'ar')? $lang = 'ar': $lang = 'en';

        if($lang == 'en'){
            return view('terms_and_conditions.en');
        }
        return view('terms_and_conditions.ar');
    }
}
