<?php

namespace App\Http\Resources;

use App\Http\Controllers\site\teacher\answers;
use App\Models\Answer;
use App\Models\Year;
use Illuminate\Http\Resources\Json\JsonResource;

class teacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        ($request->header('lang') == 'ar')? $lang = 'ar': $lang = 'en';

        $years = Year::whereHas('Teacher_years', function($query){
            $query->where('teacher_id', $this->id);
        })->whereHas('Terms', function($query){
            $query->whereHas('Subjects', function($q){
                $q->active()->where('main_subject_id', $this->main_subject_id);
            });
        })->get();

        return [
            'id'            => $this->id,
            'username'      => $this->username,
            'email'         => $this->email,
            'phone'         => [
                                'dialing_code'  =>$this->dialing_code,
                                'phone'         =>$this->phone,
                            ],
            'country'       => [
                                'id'   => $this->country_id,
                                'name' => $this->Country->translate($lang)->name,
                            ],
            'curriculum'    => [
                                'id'   => $this->curriculum_id,
                                'name' => $this->getCurriculum($lang),
                            ],
            'subject'       => [
                                'id'      => $this->main_subject_id,
                                'name' => $this->getMain_subject($lang),
                            ],
            'balance'              => [
                                            'value'    => $this->balance, 
                                            'currency' => trans('site.SAR'), 
                                        ],
            'birth'         => $this->birth,
            'about'         => $this->about,
            'class_count'   => count($this->Available_classes->where('status', 2)),
            'gender'        => $this->getGender(),
            'gender_boolean'=> $this->gender,
            'rating'        => $this->getRating(),
            'image'         => $this->getImage(),
            'years'         => yearResource::collection($years),
            'videos'        => videoResource::collection($this->Videos),
        ];
    }
}
