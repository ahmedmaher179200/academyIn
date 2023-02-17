<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use App\Http\Controllers\site\student\home;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Resources\Json\JsonResource;

class classTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $subject = Subject::find($request->get('subject_id'));
        // $teacher = Teacher::find($request->get('teacher_id'));
        return [
            'id'        => $this->id,
            'long'      => $this->long,
            'cost'              => [
                                        'value'    => $this->long * $this->long_cost, 
                                        'currency' => trans('site.SAR'), 
                                    ],
        ];
    }
}
