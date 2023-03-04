<?php

namespace App\Http\Controllers\site\teacher;

use App\Events\studentNotification;
use App\Http\Controllers\Controller;
use App\Http\Resources\availableClassResource;
use App\Http\Resources\notificationResource;
use App\Models\Available_class;
use App\Models\Class_type;
use App\Models\Student;
use App\Models\student_notification;
use App\Models\Subject;
use App\Services\AgoraService;
use App\Services\AvailableClassService;
use App\Services\firbaseNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    public $AvailableClassService;
    public $AgoraService;
    public $firbaseNotifications;
    public function __construct(AvailableClassService $AvailableClassService,
                                AgoraService $AgoraService,
                                firbaseNotifications $firbaseNotifications)
    {
        $this->AvailableClassService = $AvailableClassService;
        $this->AgoraService         = $AgoraService;
        $this->firbaseNotifications = $firbaseNotifications;
    }


    public function schedule_date(Request $request){
        $validator = Validator::make($request->all(), [
            'month'            => 'nullable|min:1|max:12',
        ]);

        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403, 'E03');
        
        $teacher = auth('teacher')->user();

        $request->request->add(['teacher_id' => $teacher->id]);

        $schedules_date = Available_class::where('teacher_id', $teacher->id)
                                            ->schedule()
                                            ->select('from_date as date')
                                            // ->whereHas('Student_classes')
                                            ->distinct('from_date');


        if($request->get('month'))
            $schedules_date->whereMonth('from','=', $request->get('month'));


        $schedules_date->orderBy('from')->get();

        return response()->json([
            'successful'    => true,
            'message'       => trans('auth.success'),
            'schedules_date'     => $schedules_date->pluck('date'),
        ], 200);                       
    }

    public function schedule(Request $request){
        $validator = Validator::make($request->all(), [
            'date'      => 'required|date_format:Y-m-d',
        ]);

        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403, 'E03');
        
        $teacher = auth('teacher')->user();

        $available_class = Available_class::where('teacher_id', $teacher->id)
                                            ->whereDate('from', '=', $request->get('date'))
                                            ->schedule()
                                            // ->whereHas('Student_classes')
                                            ->orderBy('from')
                                            ->get();

        return response()->json([
            'successful'    => true,
            'message'       => trans('auth.success'),
            'schedules'     => availableClassResource::collection($available_class),
        ], 200);                       
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'year_id'          => 'required|exists:years,id',
            'class_type_id'    => 'required|exists:class_types,id',
            'from'             => 'required|date_format:Y-m-d H:i:s',
            'note'             => 'nullable|exists:class_types,id',
        ]);

        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403, 'E03');

        $teacher = auth('teacher')->user();

        $class_type = Class_type::find($request->class_type_id);

        //get subject_id
        $year_id = $request->get('year_id');
        $subject = Subject::where('main_subject_id', $teacher->main_subject_id)
                            ->whereHas('Term', function($query) use($year_id){
                                $query->where('year_id', $year_id);
                            })
                            ->first();
        
        if($subject == null)
            return $this->faild(trans('site.your subject not in this year'), 404, 'E04');
        
        $schedule = $this->AvailableClassService->crate($request, $teacher, $subject, $class_type);

        return $this->success(trans('auth.success'), 200, 'schedule', new availableClassResource($schedule));
    }

    public function cancel(Request $request){
        $validator = Validator::make($request->all(), [
            'schedule_id'       => 'required|exists:available_classes,id',
        ]);

        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403, 'E03');

        $teacher = auth('teacher')->user();

        $available_class = Available_class::where('status','!=' ,-1)
                                            ->where('teacher_id', $teacher->id)
                                            ->find($request->schedule_id);

        if($available_class == null)
            return $this->faild(trans('site.schedule not found'), 400, 'E04');
        
        $this->AvailableClassService->cancel($available_class);

        return $this->success(trans('auth.success'), 200);
    }

    public function class_type(){
        $classes_type = Class_type::select('id', 'long')->active()->get();

        return $this->success(trans('auth.success'), 200, 'classes_type', $classes_type);
    }

    public function start_class(Request $request){
        $validator = Validator::make($request->all(), [
            'schedule_id'       => 'required|exists:available_classes,id',
        ]);
        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403, 'E03');

        $teacher = auth('teacher')->user();

        $available_class = Available_class::find($request->schedule_id);

        $students = Student::WhereHas('Student_classes', function($query) use($request){
                                $query->where('available_class_id', $request->get('schedule_id'));
                            })->get();

        if($available_class->agora_token != null){  
            $data = [
                'token'         => $available_class->agora_token,
                'rtm_token'     => $available_class->agora_rtm_token,
                'rtm_user_id'   => 'teacher_' . $teacher->id,
                'channel_name'  => $available_class->channel_name,
                'teacher'       => [
                    'id'        => $teacher->id,
                    'username'  => $teacher->username,
                    'image'     => $teacher->getImage(),
                ],
                'students'       => $students->map(function ($data) {
                    return [
                        'id'        => $data->id,
                        'username'  => $data->username,
                        'image'     => $data->getImage(),
                    ];
                }),
            ];
            
            return $this->success(trans('auth.success'), 200, 'agora', $data);
        }

        //if teacher do not make call
        $student_classes = DB::table('student_class')   
                                ->where('available_class_id', ($available_class->id))
                                ->get();
        
        if(count($student_classes) == 0)
            return $this->faild(trans('auth.no student booking this class'), 400);

        //creat agora room
        $agora          = $this->AgoraService->generateToken('teacher_' . $teacher->id);

        //change available_class status
        $available_class->status = 2;
        $available_class->save();

        foreach($student_classes as $student_class){
            $title = 'يوجد حصه الان';
            $body  = 'حصه قمت بحجزها سارع بالانضمام ' . $teacher->username . ' بدأ';

            //make notification to student
            $student_notification = student_notification::create([
                'title'             => $title,
                'content'           => $body,
                'teacher_id'        => $teacher->id,
                'student_id'        => $student_class->student_id,
                'available_class_id'=> $student_class->available_class_id,
                'type'              => 3,
                'agora_token'       => $agora['token'],
                'agora_rtm_token'         => $agora['rtm_token'],
                'agora_channel_name'=> $agora['channel_name'],
            ]);

            //save agora_token in class
            $available_class->agora_token  = $agora['token'];
            $available_class->agora_rtm_token  = $agora['rtm_token'];
            $available_class->channel_name = $agora['channel_name'];
            $available_class->save();

            //send firbase notifications
            if($request->get('pusher') == 1){
                config(['queue.default' => 'sync']);
                event(new studentNotification($student_class->student_id,new notificationResource($student_notification)));
            } else {
                $student = Student::find($student_class->student_id);
                $this->firbaseNotifications->send_notification($title,
                                                                $body,
                                                                $student->token_firebase,
                                                                new notificationResource($student_notification),    
                                                            );
            }
        }

        $data = [
            'token'         => $agora['token'],
            'rtm_token'     => $available_class->agora_rtm_token,
            'rtm_user_id'   => 'teacher_' . $teacher->id,
            'channel_name'  => $agora['channel_name'],
            'teacher'       => [
                'id'        => $teacher->id,
                'username'  => $teacher->username,
                'image'     => $teacher->getImage(),
            ],
            'students'       => $students->map(function ($data) {
                return [
                    'id'        => $data->id,
                    'username'  => $data->username,
                    'image'     => $data->getImage(),
                ];
            }),
        ];
        
        return $this->success(trans('auth.success'), 200, 'agora', $data);
    }
}
