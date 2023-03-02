<?php

namespace App\Http\Controllers\site\student;

use App\Http\Controllers\Controller;
use App\Http\Resources\answersResource;
use App\Http\Resources\notificationResource;
use App\Models\Answer;
use App\Models\student_notification;
use App\Services\AnswerService;
use App\Services\firbaseNotifications;
use App\Services\StudentNotificationService;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnswerController extends Controller
{
    use response;
    public $StudentNotificationService;
    public $AnswerService;
    public function __construct(StudentNotificationService $StudentNotificationService, AnswerService $AnswerService)
    {
        $this->StudentNotificationService = $StudentNotificationService;
        $this->AnswerService = $AnswerService;
    }

    public function index(Request $request){
        $validator = Validator::make($request->all(), [
            'question_id'    => 'required|exists:questions,id',
        ]);

        if($validator->fails())
            return response::faild($validator->errors()->first(), 403, 'E03');

        $answers = Answer::active()
                            ->where('question_id', $request->get('question_id'))
                            ->orderBy('id', 'desc');

        $student = auth('student')->user();

        //to check if student question owner
        $request->request->add(['user_id' => $student->id]);
        $request->request->add(['guard' => 'Student']);

        return response()->json([
            'successful'        => true,
            'message'           => trans('auth.success'),
            'answers_count'     => $answers->count(),
            'answers'         => answersResource::collection($answers->paginate(5))->response()->getData(true),
        ], 200);
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'answer'         => 'required|string|max:2000',
            'question_id'    => 'required|exists:questions,id',
            'image'          => 'nullable|mimes:jpeg,jpg,png,gif',
        ]);

        if($validator->fails())
            return response::faild($validator->errors()->first(), 403, 'E03');

        $student = auth('student')->user();

        $answer = $this->AnswerService->create($student->id,
                                                $request->question_id,
                                                $request->answer,
                                                'Student',
                                                $request->has('image'));

        //to check if student question owner
        $request->request->add(['user_id' => $student->id]);
        $request->request->add(['guard' => 'Student']);

        $this->StudentNotificationService->send($answer->Question->Student, $answer->id);
        
        return $this->success(trans('site.add answer success'), 200, 'answer', new answersResource($answer));
    }

    public function delete(Request $request){
        $validator = Validator::make($request->all(), [
            'answer_id'         => 'required|exists:answers,id',
        ]);

        if($validator->fails())
            return response::faild($validator->errors()->first(), 403, 'E03');

        $student = auth('student')->user();

        $answer = Answer::where('answerable_id', $student->id)
                            ->where('answerable_type', 'App\Models\Student')
                            ->find($request->get('answer_id'));

        if(!$answer)
            return $this::faild(trans('site.answer not found'), 404, 'E04');

        $answer->delete();
        return $this->success(trans('site.delete answer success'), 200);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'answer'         => 'required|string|max:2000',
            'answer_id'      => 'required|exists:answers,id',
        ]);

        if($validator->fails())
            return response::faild($validator->errors()->first(), 403, 'E03');

        $student = auth('student')->user();

        $answer = Answer::where('answerable_id', $student->id)
                        ->where('answerable_type', 'App\Models\Student')
                        ->find($request->get('answer_id'));

        if($answer == null)
            return $this::faild(trans('site.answer not found'), 404, 'E04');

        $this->AnswerService->update($answer,
                                        $request->answer,
                                        $request->has('image'));
        

        //to check if student question owner
        $request->request->add(['user_id' => $student->id]);
        $request->request->add(['guard' => 'Student']);

        return $this->success(trans('site.update answer success'), 200,'answer', new answersResource($answer));
    }
}
