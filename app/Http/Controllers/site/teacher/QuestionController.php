<?php

namespace App\Http\Controllers\site\teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\questionsResource;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Teacher;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    use response;
    public function index(Request $request){
        $validator = Validator::make($request->all(), [
            'year_id'          => 'nullable|exists:years,id',
        ]);

        if($validator->fails())
            return response::faild($validator->errors()->first(), 403, 'E03');

        $teacher = auth('teacher')->user();

        if($request->year_id == null){
            //if teacher not enter year_id
            $questions = Question::active()
                                    ->whereHas('Subject', function($query) use($teacher){
                                        $query->where('main_subject_id', $teacher->main_subject_id);
                                    })
                                    ->orderBy('id', 'desc');

        } else {    
            //if teacher enter year_id
            $subject = Subject::active()
                            ->where('main_subject_id', $teacher->main_subject_id)
                            ->whereHas('Term', function($query) use($request){
                                $query->where('year_id', $request->get('year_id'));
                            })
                            ->first();

            if($subject == null)
                return $this->faild(trans('site.this year not has your subject'), 404,'E04');

            $questions = Question::active()
                                    ->where('subject_id', $subject->id)
                                    ->orderBy('id', 'desc');
        }

        return response()->json([
            'successful'        => true,
            'message'           => trans('auth.success'),
            'questions_count'   => $questions->count(),
            'questions'         => questionsResource::collection($questions->paginate(5))->response()->getData(true),
        ], 200);
    }

    public function myAnswersQuestions(Request $request){
        $validator = Validator::make($request->all(), [
            'teacher_id'          => 'required|exists:teachers,id',
        ]);

        if($validator->fails())
            return response::faild($validator->errors()->first(), 403, 'E03');

        $teacher = Teacher::find($request->get('teacher_id'));

        $questions = Question::whereHas('Answers', function($query) use($teacher){
                                    $query->where('answerable_id', $teacher->id)
                                            ->where('answerable_type', 'App\Models\Teacher');
                                });

        return response()->json([
            'successful'        => true,
            'message'           => trans('auth.success'),
            'questions_count'   => $questions->count(),
            'questions'         => questionsResource::collection($questions->paginate(5))->response()->getData(true),
        ], 200);
    }
}
