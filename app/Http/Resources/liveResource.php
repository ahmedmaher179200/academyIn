<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class liveResource extends JsonResource
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
            'id'                => $this->id,
            'title'             => $this->title,
            'cost'              => [
                                        'value'    => $this->cost, 
                                        'currency' => trans('site.SAR'), 
                                    ],
            'from'              => $this->from,
            'company_percentage'=> $this->company_percentage,
            'subject'           => [    
                                        'id'    => $this->subject_id,
                                        'name'  => $this->subject->Main_subject->translate($lang)->name,
                                    ],
            'teacher'           => [
                                        'id'        => $this->Teacher->id,
                                        'username'  => $this->Teacher->username,
                                        'image'     => $this->Teacher->getImage(),
                                    ],
        ];
    }
}
