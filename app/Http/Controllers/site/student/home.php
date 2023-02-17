<?php

namespace App\Http\Controllers\site\student;

use App\Events\teacherNotification;
use App\Http\Controllers\Controller;
use App\Http\Resources\availableClassResource;
use App\Http\Resources\classType_availableClassResource;
use App\Http\Resources\notificationResource;
use App\Http\Resources\student_classResource;
use App\Http\Resources\subjectsResource;
use App\Models\Available_class;
use App\Models\Class_type;
use App\Models\Rating;
use App\Models\Subject;
use App\Models\Teacher_notification;
use App\Models\Video;
use App\Traits\response;
use App\Services\AgoraService;
use App\Services\firbaseNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class home extends Controller
{
    use response;
    public function __construct(AgoraService $AgoraService, firbaseNotifications $firbaseNotifications)
    {
        $this->AgoraService         = $AgoraService;
        $this->firbaseNotifications = $firbaseNotifications;
    }

    public function index(){
        //get student or vender
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }

        if($student->year_id == null)
            return $this::faild(trans('site.student must choose his grade'), 400, 'E00');

        $subjects = Subject::whereHas('Term', function($query) use($student){
                                $query->where('year_id', $student->year_id);
                            })
                            ->active()
                            ->orderBy('order_by')
                            ->get();

        return $this::success(trans('auth.success'), 200, 'subjects', subjectsResource::collection($subjects));
    }

    public function leave(){
        //get student
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }

        //change online and offline
        $student->online = 0;
        $student->save();

        return $this->success(trans('auth.success'), 200);
    }

    public function available_classes(Request $request){
        // validate registeration request
        $validator = Validator::make($request->all(), [
            'teacher_id'     => 'required|integer|exists:teachers,id',
            'subject_id'     => 'required|integer|exists:subjects,id',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403);
        }

        //get student
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }

        $request->student_id     = $student->id;

        //get get class type available class
        $class_type = Class_type::active()->get();

        return $this->success(trans('auth.success'), 200, 'class_types', classType_availableClassResource::collection($class_type));
    }

    public function generate_agora_rtm_token(Request $request){
        $validator = Validator::make($request->all(), [
            'schedule_id'       => 'required|exists:available_classes,id',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403);
        }

        //get student
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }
        $user_id =  'student_' . $student->id;

        $student_class = DB::table('student_class')   
                            ->where('available_class_id', $request->get('schedule_id'))
                            ->where('student_id', $student->id);

        if($student_class->first() == null){
            return $this->faild(trans('auth.you do not booking'), 200);
        }

        if($student_class->first()->agora_rtm_token != null){
            return response()->json([
                'successful'                => true,
                'message'                   => trans('auth.success'),
                'agora_rtm_token'           => $student_class->first()->agora_rtm_token,
                'user_id'                   => $user_id,
            ], 200);
        }

        $agora_rtm_token = $this->AgoraService->generateToken($user_id)['rtm_token'];
        $student_class->update([
            'agora_rtm_token'   => $agora_rtm_token,
        ]);

        return response()->json([
            'successful'                => true,
            'message'                   => trans('auth.success'),
            'agora_rtm_token'           => $agora_rtm_token,
            'user_id'                   => $user_id,
        ], 200);
    }

    //***//
    public function booking(Request $request){  //class
        // validate
        $validator = Validator::make($request->all(), [
            'available_class_id'     => 'required|integer|exists:available_classes,id',
            'promo_code'             => 'nullable|string',
            'pusher'                 => 'nullable|integer',
        ]);

        if($validator->fails()){
            return response()->json([
                'successful'=> false,
                'not_enough' => false,
                'message'    => $validator->errors()->first(),
            ], 400);
        }

        try{
            DB::beginTransaction();
            $available_class_id = $request->get('available_class_id');

            $available_class = Available_class::find($available_class_id);

            if (! $student = auth('student')->user())
                return $this::faild(trans('auth.student not found'), 404, 'E04');
            
            if($this->check_if_student_booking_this_schedule($student, $available_class_id) == true){
                return response()->json([
                    'successful'=> false,
                    'not_enough' => false,
                    'message'    => trans('site.student already booking this schedule'),
                ], 400);
            }

            //check if class is complete
            if($this->class_complete($available_class_id) == true){
                return response()->json([
                    'successful'=> false,
                    'not_enough' => false,
                    'message'    => trans('site.this class is complete'),
                ], 400);
            }

            //get discount from promo code if exist
            $discount_percentage = $this->promo_code_percentage($request->get('promo_code'));
            $available_class_cost_after_discount = $this->get_price_after_discount($available_class->cost, $discount_percentage);

            if($this->check_student_balance_and_freeClasses($student, $available_class_cost_after_discount) == false){
                return response()->json([
                    'successful'    => false,
                    'not_enough'    => true,
                    'message'       => trans('site.your balance not enough'),
                ], 400);
            }

            $pay = $this->Take_booking_money($student, $available_class_cost_after_discount);
            
            //booking
            DB::table('student_class')->insert([
                'student_id'            =>  $student->id,
                'available_class_id'    =>  $available_class_id,
                'promocode_descount'    =>  $discount_percentage,
                'pay'                   =>  $pay,
            ]);

            $this->booking_notigication($student, $available_class, $request);

            DB::commit();
            return $this->success(trans('auth.success'), 200);
        } catch(\Exception $ex){
            //if there are error
            return $this->faild(trans('auth.faild'), 200);
        }
    }

    public function check_if_student_booking_this_schedule($student, $available_class_id){
        $student_class = DB::table('student_class')->where([
            'available_class_id'   => $available_class_id,
            'student_id'           => $student->id,
        ])->count();

        if($student_class > 0){
            return true;
        }

        return false;
    }

    public function class_complete($available_class_id){
        $row = DB::table('student_class')->where([
            'available_class_id'   => $available_class_id,
        ])->count();

        if($row > env('MAX_STUDENT_IN_CLASS')){
            return true;
        }

        return false;
    }

    public function booking_notigication($student, $available_class,$request){
        $title = 'تم الحجز';
        $body = ' حصة ' . $student->username . ' حجذ';

        //create notification to teacher
        $teacher_notification = Teacher_notification::create([
            'title'             => $title,
            'content'           => $body,
            'teacher_id'        => $available_class->teacher_id,
            'student_id'        => $student->id,
            'available_class_id'=> $available_class->id,
            'type'              => 1,
        ]);

        //sent firbase notifications
        if($request->get('pusher') == 1){
            config(['queue.default' => 'sync']);
            event(new teacherNotification($available_class->teacher_id,new notificationResource($teacher_notification)));
        } else {
            $this->firbaseNotifications->send_notification(
                    $title,
                    $body,
                    $available_class->Teacher->token_firebase,
                    new notificationResource($teacher_notification),
                );
        }
    }

    public function check_student_balance_and_freeClasses($student, $available_class_cost_after_discount){
        //check if student balance Not enough and do not has free classes
        if(($student->balance - $available_class_cost_after_discount < 0) && $student->free <= 0)
            return false;

        return true;
    }

    public function Take_booking_money($student, $available_class_cost_after_discount){
        if($student->free > 0){  //if student have free classes
            $student->free -= 1;
            $student->save();
            $pay = 1;
        } else {            
            $student->balance       -= $available_class_cost_after_discount;    //take class cost from student
            $student->save();
            $pay = 0;
        }

        return $pay;  //if pay == 0 (student has free class) if pay == 1 its mean student buy by mony
    }

    //****//

    public function buy_video(Request $request){ //video
        // validate registeration request
        $validator = Validator::make($request->all(), [
            'video_id'     => 'required|integer|exists:videos,id',
        ]);
        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403);
        }

        try{
            DB::beginTransaction();

            //get student
            if (! $student = auth('student')->user()) {
                return $this::faild(trans('auth.student not found'), 404, 'E04');
            }
            //get video
            $video = Video::find($request->get('video_id'));
            $teacher =  $video->Teacher;

            //check if student already buy this video
            $row = DB::table('student_video')->where([
                'student_id'   => $student->id,
                'video_id'     => $request->get('video_id'),
            ])->first();

            if($row != null)
                return $this->faild(trans('site.student already buy this video'), 200);

            //check if student balance Not enough
            if($student->balance - $video->cost < 0)
                return $this->faild(trans('site.your balance not enough'), 200);

            //booking
            DB::table('student_video')->insert([
                'student_id'   => $student->id,
                'video_id'     => $request->get('video_id'),
            ]);
            $student->balance -= $video->cost;    //tack video cost from student
            $student->save();

            $teacher->balance += $video->cost;    //add video cost for teacher
            $teacher->save();

            DB::commit();
            return $this->success(trans('auth.success'), 200);
        } catch(\Exception $ex){
            //if there are error
            return $this->faild(trans('auth.faild'), 200);
        }
    }

    public function my_reservations(){
        //get student or vender
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }

        return $this->success(
            trans('auth.success'),
            200,
            'reservations',
            student_classResource::collection($student->Student_classes)
        );

    }

    public function schedule(Request $request){
        
        //get student or vender
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }

        $request->student = $student;

        $available_classes = available_class::whereHas('Student_classes', function($query) use($student){
            $query->where('student_id', $student->id);
        })
        ->orderBy('from')
        ->schedule();

        return response()->json([
            'successful'            => true,
            'message'               => trans('auth.success'),
            'schedules_count'       => $available_classes->count(),
            'schedules'             => availableClassResource::collection($available_classes->paginate(5))->response()->getData(true),
        ], 200);

        return $this->success(
            trans('auth.success'),
            200,
            'schedules',
        );

    }

    public function cancel_schedule(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'schedule_id'       => 'required|exists:available_classes,id',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403, 'E03');
        }

        //get student
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }

        DB::table('student_class')
            ->where('available_class_id', $request->get('schedule_id'))
            ->where('student_id', $student->id)
            ->delete();

        return $this->success(trans('auth.success'), 200);
    }

    public function add_rating(Request $request){
        // validate registeration request
        $validator = Validator::make($request->all(), [
            'teacher_id'     => 'required|integer|exists:teachers,id',
            'rating'             => 'required|integer|min:0|max:5',
        ]);

        if($validator->fails()){
            return response()->json([
                'successful'=> false,
                'message'    => $validator->errors()->first(),
            ], 400);
        }

        //get student or vender
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }

        Rating::create([
            'teacher_id'    => $request->get('teacher_id'),
            'stars'         => $request->get('rating'),
        ]);

        return $this->success(trans('auth.success'), 200);
    }

    public function whiteboard(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'schedule_id'       => 'required|exists:available_classes,id',
        ]);
        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403, 'E03');
        }

        $available_class = Available_class::find($request->get('schedule_id'));
        if($available_class->whiteboard_uuid == null){
            return response()->json([
                'successful'        => false,
                'message'           => trans('site.teacher don\'t creat board'),
            ], 400);
        }

        if($available_class->whiteboard_student_token != null){
            return response()->json([
                'successful'        => true,
                'message'           => trans('auth.success'),
                'token'             => $available_class->whiteboard_student_token,
                'uuid'              => $available_class->whiteboard_uuid,
            ], 200);
        }

        $response2 = Http::withHeaders([
            'region' => 'sg',
            'Content-Type'  => 'application/json',
            'token' => "NETLESSSDK_YWs9QjZsQTREM2RwUkI1enhueiZub25jZT0xNjUxNjgwNjI1NTczMDAmcm9sZT0wJnNpZz0yNzMyNzQ2OWI0ZTg3YjkyODJlMDIyNTg2OTk3ZWU1NmI1OTZkMmQxODYxNjFhZjc3ZjU1YTc0MmU3YzkzNDQ0",
        ])->post('https://api.netless.link/v5/tokens/rooms/' . $available_class->whiteboard_uuid, [
            "ak"=>         "B6lA4D3dpRB5zxnz",
            "lifespan"=>         0,
            "role"=> "reader"
        ]);

        $token = json_decode($response2, true);

        $available_class->whiteboard_student_token = $token;
        $available_class->save();

        return response()->json([
            'successful'        => true,
            'message'           => trans('auth.success'),
            'token'             => $token,
            'uuid'              => $available_class->whiteboard_uuid,
        ], 200);
    }
}
