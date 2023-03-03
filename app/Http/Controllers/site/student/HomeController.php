<?php

namespace App\Http\Controllers\site\student;

use App\Http\Controllers\Controller;
use App\Http\Resources\student_classResource;
use App\Http\Resources\subjectsResource;
use App\Models\Subject;
use App\Traits\response;
use App\Services\firbaseNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    use response;
    public $firbaseNotifications;
    public function __construct(firbaseNotifications $firbaseNotifications)
    {
        $this->firbaseNotifications = $firbaseNotifications;
    }

    public function show(){
        $student = auth('student')->user();

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

    public function my_reservations(){
        $student = auth('student')->user();

        return $this->success(
            trans('auth.success'),
            200,
            'reservations',
            student_classResource::collection($student->Student_classes)
        );
    }
}
