<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Traits\response;
use Illuminate\Support\Facades\DB;

class AvailableClassService extends Controller
{
    use response;
    public $TeacherNotificationService;
    public function __construct(TeacherNotificationService $TeacherNotificationService)
    {
        $this->TeacherNotificationService = $TeacherNotificationService;
    }

    public function classIsComplete($available_class_id){
        $row = DB::table('student_class')->where([
            'available_class_id'   => $available_class_id,
        ])->count();

        if($row > env('MAX_STUDENT_IN_CLASS')){
            return true;
        }

        return false;
    }

    public function is_student_booking_this_schedule($student, $available_class_id){
        $student_class = DB::table('student_class')->where([
            'available_class_id'   => $available_class_id,
            'student_id'           => $student->id,
        ])->count();

        if($student_class > 0){
            return true;
        }

        return false;
    }

    public function booking($student, $available_class, $discount_percentage = 0, $pay = 1, $pusher = 0){
        DB::table('student_class')->insert([
            'student_id'            =>  $student->id,
            'available_class_id'    =>  $available_class->id,
            'promocode_descount'    =>  $discount_percentage,
            'pay'                   =>  $pay,
        ]);

        $this->TeacherNotificationService->booking_notigication($student, $available_class, $pusher);
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

    public function check_student_balance_and_freeClasses($student, $available_class_cost_after_discount){
        //check if student balance Not enough and don't has free classes
        if(($student->balance - $available_class_cost_after_discount < 0) && $student->free <= 0)
            return false;

        return true;
    }
}