<?php

namespace App\Http\Controllers\site\student;

use App\Http\Controllers\Controller;
use App\Http\Resources\answersResource;
use App\Http\Resources\notificationResource;
use App\Models\Answer;
use App\Models\student_notification;
use App\Services\firbaseNotifications;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class answers extends Controller
{
    use response;
    public function __construct(firbaseNotifications $firbaseNotifications)
    {
        $this->firbaseNotifications = $firbaseNotifications;
    }

    public function index(Request $request){
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
            'answers_count'     => $answers->count(),
            'answers'         => answersResource::collection($answers->paginate(5))->response()->getData(true),
        ], 200);
    }

    public function create(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'answer'         => 'required|string|max:2000',
            'question_id'    => 'required|exists:questions,id',
            'image'          => 'nullable|mimes:jpeg,jpg,png,gif',
        ]);

        if($validator->fails())
            return response::faild($validator->errors()->first(), 403, 'E03');

        if (! $student = auth('student')->user())
            return $this::faild(trans('auth.student not found'), 404, 'E04');

        //create Question
        $answer = Answer::create([
            'answerable_id'    => $student->id,
            'answerable_type'  => 'App\Models\Student',
            'question_id'      => $request->get('question_id'),
            'answer'           => $request->get('answer'),
        ]);

        if($request->has('image') != null){
            //update image
            $path = $this->upload_image($request->file('image'),'uploads/answers', 450, 300);

            $answer->image = $path;
            $answer->save();
        }

        //to check if student question owner
        $request->user_id   = $student->id;
        $request->guard     = 'Student';

        $this->send_notification($answer->Question->Student, $answer->id);
        
        return $this->success(trans('site.add answer success'), 200, 'answer', new answersResource($answer));
    }

    public function send_notification($question_owner, $answer_id){
        $title = $question_owner->username .' add answer for your question';
        $body = $question_owner->username .' add answer for your question';

        $notification = student_notification::create([
            'student_id'        => $question_owner->id,
            'answer_id'         => $answer_id,
            'title'             => $title,
            'content'           => $body,
            'type'              => 4,
        ]);

        $this->firbaseNotifications->send_notification($title, 
                                                        $body,
                                                        $question_owner->token_firebase,
                                                        new notificationResource($notification),    
                                                    );
    }

    public function delete(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'answer_id'         => 'required|exists:answers,id',
        ]);

        if($validator->fails()){
            return response::faild($validator->errors()->first(), 403, 'E03');
        }

        //get student or vender
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }

        //get answer
        $answer = Answer::where('answerable_id', $student->id)
                            ->where('answerable_type', 'App\Models\Student')
                            ->find($request->get('answer_id'));

        if($answer == null)
            return $this::faild(trans('site.answer not found'), 404, 'E04');

        //delete question
        if($answer->delete())
            return $this->success(trans('site.delete answer success'), 200);

        return $this::faild(trans('site.delete answer faild'), 400, 'E00');
    }

    public function update(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'answer'         => 'required|string|max:2000',
            'answer_id'      => 'required|exists:answers,id',
        ]);

        if($validator->fails()){
            return response::faild($validator->errors()->first(), 403, 'E03');
        }

        //get student or vender
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }

        //get answer
        $answer = Answer::where('answerable_id', $student->id)
                        ->where('answerable_type', 'App\Models\Student')
                        ->find($request->get('answer_id'));

        if($answer == null)
            return $this::faild(trans('site.answer not found'), 404, 'E04');

        if($request->has('image') != null){
            //update image
            $path = $this->upload_image($request->file('image'),'uploads/answers', 150, 100);

            $answer->image = $path;
            $answer->save();
        }

        //to check if student question owner
        $request->user_id   = $student->id;
        $request->guard     = 'Student';

        //update answer
        if($answer->update(['answer'=> $request->get('answer')]))
            return $this->success(trans('site.update answer success'), 200,'answer', new answersResource($answer));

        return $this::faild(trans('site.update answer faild'), 400, 'E00');
    }
}
