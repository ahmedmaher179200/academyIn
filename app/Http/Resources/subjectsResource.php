<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class subjectsResource extends JsonResource
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
            'id'            => $this->id,
            'name'          => $this->Main_subject->translate($lang)->name,
            'image'         => $this->Main_subject->getImage(),
            'status'        => $this->status,
        ];
    }
}
