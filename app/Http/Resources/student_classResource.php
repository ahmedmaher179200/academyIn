<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class student_classResource extends JsonResource
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
        return [
            'from'           => $this->Available_class->from,
            'subject'        => $this->Available_class->subject->Main_subject->translate($lang)->name,
            'long'           => $this->Available_class->long,
            'cost'              => [
                                    'value'    => $this->price, 
                                    'currency' => trans('site.SAR'), 
                                ],
            'teacher'        => [
                                    'id'        => $this->Available_class->Teacher->id,
                                    'username'  => $this->Available_class->Teacher->username,
                                    'image'     => $this->Available_class->Teacher->getImage(),
                                ],
        ];
    }
}
