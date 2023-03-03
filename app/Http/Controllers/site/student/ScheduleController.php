<?php

namespace App\Http\Controllers\site\student;

use App\Http\Controllers\Controller;
use App\Http\Resources\availableClassResource;
use App\Http\Resources\classType_availableClassResource;
use App\Models\Available_class;
use Illuminate\Http\Request;
use App\Models\Class_type;
use App\Services\AvailableClassService;
use App\Services\PromoCodeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    public $AvailableClassService;
    public $PromoCodeService;
    public function __construct(AvailableClassService $AvailableClassService,PromoCodeService $PromoCodeService)
    {
        $this->AvailableClassService = $AvailableClassService;
        $this->PromoCodeService = $PromoCodeService;
    }
    
    public function schedule(Request $request){
        $student = auth('student')->user();

        $request->request->add(['student' => $student]);

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
    }

    public function available_classes(Request $request){
        $validator = Validator::make($request->all(), [
            'teacher_id'     => 'required|integer|exists:teachers,id',
            'subject_id'     => 'required|integer|exists:subjects,id',
        ]);

        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403);

        $student = auth('student')->user();

        $request->request->add(['student_id' => $student->id]);

        $class_type = Class_type::active()->get();

        return $this->success(trans('auth.success'), 200, 'class_types', classType_availableClassResource::collection($class_type));
    }

    public function booking(Request $request){
        $validator = Validator::make($request->all(), [
            'available_class_id'     => 'required|integer|exists:available_classes,id',
            'promo_code'             => 'nullable|string',
            'pusher'                 => 'nullable|integer',
        ]);

        $successful = true;
        $not_enough = true;
        $message    =trans('admin.success');
        
        if($validator->fails()){
            $successful = false;
            $not_enough = false;
            $message    = $validator->errors()->first();
        }

        try{
            DB::beginTransaction();
            $available_class = Available_class::find($request->available_class_id);

            $student = auth('student')->user();
            
            if($this->AvailableClassService->is_student_booking_this_schedule($student, $request->available_class_id)){
                $successful = false;
                $not_enough = false;
                $message    = trans('site.student already booking this schedule');
            }

            if($this->AvailableClassService->classIsComplete($request->available_class_id)){
                $successful = false;
                $not_enough = false;
                $message    = trans('site.this class is complete');
            }

            $discount_percentage = $this->PromoCodeService->promo_code_percentage($request->promo_code);
            $available_class_cost_after_discount = $this->PromoCodeService->get_price_after_discount($available_class->cost, $discount_percentage);

            if($this->AvailableClassService->check_student_balance_and_freeClasses($student, $available_class_cost_after_discount) == false){
                $successful = false;
                $not_enough = false;
                $message    = trans('site.your balance not enough');
            }

            $pay = $this->AvailableClassService->Take_booking_money($student, $available_class_cost_after_discount);
            

            $this->AvailableClassService->booking($student,
                                                    $available_class,
                                                    $discount_percentage,
                                                    $pay,
                                                    $request->pusher
                                                );
            if($successful == false){
                return response()->json([
                    'successful'    => $successful,
                    'not_enough'    => $not_enough,
                    'message'       => $message,
                ], 400);
            }
            DB::commit();
            return $this->success(trans('auth.success'), 200);
        } catch(\Exception $ex){
            return $ex;
            return $this->faild(trans('auth.faild'), 200);
        }
    }

    public function cancel(Request $request){
        $validator = Validator::make($request->all(), [
            'schedule_id'       => 'required|exists:available_classes,id',
        ]);

        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403, 'E03');

        $student = auth('student')->user();

        DB::table('student_class')
            ->where('available_class_id', $request->schedule_id)
            ->where('student_id', $student->id)
            ->delete();

        return $this->success(trans('auth.success'), 200);
    }
}
