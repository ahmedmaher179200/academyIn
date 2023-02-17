<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use App\Models\Available_class;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Resources\Json\JsonResource;

class classType_availableClassResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $date = date_create(date('Y-m-d H:i:s'));
        // date_add($date, date_interval_create_from_date_string('5 minute'));
        // $new_date = date_format($date, 'Y-m-d H:i:s');

        $available_classes = Available_class::where('class_type_id', $this->id)
                                            ->where('subject_id', $request->get('subject_id'))
                                            ->where('teacher_id', $request->get('teacher_id'))
                                            ->where('to', '>', date('Y-m-d H:i:s'))
                                            ->where('agora_token', null)
                                            ->withCount('Student_classes')
                                            ->whereDoesntHave('Student_classes', function($query) use($request){
                                                $query->where('student_id', '=', $request->student_id);
                                            })
                                            ->orderBy('from')
                                            ->get()
                                            ->where('student_classes_count', '<', env('MAX_STUDENT_IN_CLASS'));

        // $subject = Subject::find($request->get('subject_id'));
        // $teacher = Teacher::find($request->get('teacher_id'));
        return [
            'id'                => $this->id,
            'long'              => $this->long,
            'cost'              => [
                                        'value'    => $this->long * $this->long_cost, 
                                        'currency' => trans('site.SAR'), 
                                    ],
            'available_classes' => availableClassResource::collection($available_classes),
        ];
    }
}
