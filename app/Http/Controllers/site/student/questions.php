<?php

namespace App\Http\Controllers\site\student;

use App\Http\Controllers\Controller;
use App\Http\Resources\questionsResource;
use App\Models\Image;
use App\Models\Question;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class questions extends Controller
{
    use response;
    public function index(Request $request){
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
                                ->orderBy('id', 'desc');
        
        //get student or vender
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }

        //to check if student question owner
        $request->user_id   = $student->id;
        $request->guard     = 'Student';

        return response()->json([
            'successful'        => true,
            'message'           => trans('auth.success'),
            'questions_count'   => $questions->count(),
            'questions'         => questionsResource::collection($questions->paginate(5))->response()->getData(true),
        ], 200);
    }

    public function create(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'question'         => 'required|string|max:2000',
            'subject_id'       => 'required|exists:subjects,id',
            'image'            => 'nullable|mimes:jpeg,jpg,png,gif',
        ]);

        if($validator->fails()){
            return response::faild($validator->errors()->first(), 403, 'E03');
        }

        //get student or vender
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }

        //create Question
        $question = Question::create([
            'student_id'    => $student->id,
            'subject_id'    => $request->get('subject_id'),
            'question'      => $request->get('question'),
        ]);

        if($request->has('image') != null){
            //update image
            $path = $this->upload_image($request->file('image'),'uploads/questions', 150, 100);

            $question->image = $path;
            $question->save();
        }

        //to check if student question owner
        $request->user_id   = $student->id;
        $request->guard     = 'Student';

        return $this->success(trans('site.add question success'), 200, 'question', new questionsResource($question));
    }

    public function delete(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'question_id'         => 'required|string|exists:questions,id',
        ]);

        if($validator->fails()){
            return response::faild($validator->errors()->first(), 403, 'E03');
        }

        //get student or vender
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }

        //get question
        $question = Question::where('student_id', $student->id)->find($request->get('question_id'));

        if($question == null)
            return $this::faild(trans('site.question not found'), 404, 'E04');

        //delete question
        if($question->delete())
            return $this->success(trans('site.delete question success'), 200);

        return $this::faild(trans('site.delete question faild'), 400, 'E00');
    }

    public function update(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'question'         => 'required|string|max:2000',
            'question_id'         => 'required|exists:questions,id',
        ]);

        if($validator->fails()){
            return response::faild($validator->errors()->first(), 403, 'E03');
        }

        //get student or vender
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }

        //get question
        $question = Question::where('student_id', $student->id)->find($request->get('question_id'));

        if($question == null)
            return $this::faild(trans('site.question not found'), 404, 'E04');

        if($request->has('image') != null){
            //update image
            $path = $this->upload_image($request->file('image'),'uploads/questions', 150, 100);

            $question->image = $path;
            $question->save();
        }

        //to check if student question owner
        $request->user_id   = $student->id;
        $request->guard     = 'Student';

        //update question
        if($question->update(['question'=> $request->get('question')]))
            return $this->success(trans('site.update question success'), 200, 'question', new questionsResource($question));

        return $this::faild(trans('site.update question faild'), 400, 'E00');
    }

    public function myQuestion(Request $request){
        //get student or vender
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }


        $questions = $student->Questions()
                                ->active()
                                ->orderBy('id', 'desc');

        //to check if student question owner
        $request->user_id   = $student->id;
        $request->guard     = 'Student';

        return response()->json([
            'successful'        => true,
            'message'           => trans('auth.success'),
            'questions_count'   => $questions->count(),
            'questions'         => questionsResource::collection($questions->paginate(5))->response()->getData(true),
        ], 200);    
    }
}
