<?php

namespace App\Http\Controllers\site\teacher;

use App\Events\MyEvent;
use App\Events\studentNotification;
use App\Http\Controllers\Controller;
use App\Http\Resources\availableClassResource;
use App\Http\Resources\notificationResource;
use App\Http\Resources\yearResource;
use App\Models\Available_class;
use App\Models\Class_type;
use App\Models\Student;
use App\Models\student_notification;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Year;
use App\Services\AgoraService;
use App\Services\firbaseNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Jobs\teacherSalary as JobsTeacherSalary;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function teacher_years(){
        $teacher = auth('teacher')->user();
        
        $years = Year::whereHas('Teacher_years', function($query) use($teacher){
                            $query->where('teacher_id', $teacher->id);
                        })
                        ->whereHas('Terms', function($query) use($teacher){
                            $query->whereHas('Subjects', function($q) use($teacher){
                                $q->active()->where('main_subject_id', $teacher->main_subject_id);
                            });
                        })
                        ->get();
        
        return $this->success(trans('auth.success'), 200, 'years', yearResource::collection($years));
    }
}
