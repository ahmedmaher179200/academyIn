<?php

namespace App\Http\Controllers\site\student;

use App\Http\Controllers\Controller;
use App\Http\Resources\questionsResource;
use App\Models\Image;
use App\Models\Question;
use App\Services\QuestionService;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class QuestionController extends Controller
{
    use response;
    public $QuestionService;
    public function __construct(QuestionService $QuestionService)
    {
        $this->QuestionService = $QuestionService;
    }

    public function index(Request $request){
        $validator = Validator::make($request->all(), [
            'subject_id'       => 'required|exists:subjects,id',
        ]);

        if($validator->fails())
            return response::faild($validator->errors()->first(), 403, 'E03');

        $questions = Question::active()
                                ->where('subject_id', $request->get('subject_id'))
                                ->orderBy('id', 'desc');
        
        $student = auth('student')->user();

        //to check if student question owner
        $request->request->add(['user_id' => $student->id]);
        $request->request->add(['guard' => 'Student']);

        return response()->json([
            'successful'        => true,
            'message'           => trans('auth.success'),
            'questions_count'   => $questions->count(),
            'questions'         => questionsResource::collection($questions->paginate(5))->response()->getData(true),
        ], 200);
    }

    public function myQuestion(Request $request){
        $student = auth('student')->user();

        $questions = $student->Questions()
                                ->active()
                                ->orderBy('id', 'desc');

        //to check if student question owner
        $request->request->add(['user_id' => $student->id]);
        $request->request->add(['guard' => 'Student']);

        return response()->json([
            'successful'        => true,
            'message'           => trans('auth.success'),
            'questions_count'   => $questions->count(),
            'questions'         => questionsResource::collection($questions->paginate(5))->response()->getData(true),
        ], 200);    
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'question'         => 'required|string|max:2000',
            'subject_id'       => 'required|exists:subjects,id',
            'image'            => 'nullable|mimes:jpeg,jpg,png,gif',
        ]);

        if($validator->fails())
            return response::faild($validator->errors()->first(), 403, 'E03');

        $student = auth('student')->user();

        $question = $this->QuestionService->create($student->id,
                                                    $request->subject_id,
                                                    $request->question,
                                                    $request->image);

        //to check if student question owner
        $request->request->add(['user_id' => $student->id]);
        $request->request->add(['guard' => 'Student']);

        return $this->success(trans('site.success'), 200, 'question', new questionsResource($question));
    }

    public function delete(Request $request){
        $validator = Validator::make($request->all(), [
            'question_id'         => 'required|string|exists:questions,id',
        ]);

        if($validator->fails())
            return response::faild($validator->errors()->first(), 403, 'E03');

        $student = auth('student')->user();

        $question = Question::where('student_id', $student->id)->find($request->get('question_id'));

        if($question == null)
            return $this::faild(trans('site.question not found'), 404, 'E04');

        $question->delete();
        return $this->success(trans('site.delete question success'), 200);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'question'         => 'required|string|max:2000',
            'question_id'         => 'required|exists:questions,id',
        ]);

        if($validator->fails())
            return response::faild($validator->errors()->first(), 403, 'E03');

        $student = auth('student')->user();

        $question = Question::where('student_id', $student->id)->find($request->get('question_id'));

        if($question == null)
            return $this::faild(trans('site.question not found'), 404, 'E04');

        $this->QuestionService->update($question, $request->question, $request->file('image'));

        //to check if student question owner
        $request->request->add(['user_id' => $student->id]);
        $request->request->add(['guard' => 'Student']);

        return $this->success(trans('site.success'), 200, 'question', new questionsResource($question));
    }
}
