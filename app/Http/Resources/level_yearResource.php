<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class level_yearResource extends JsonResource
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
            'name'              => $this->translate($lang)->name,
            'curriculum_id'     => $this->curriculum_id,
            'status'            => $this->status,
            'years'             => yearResource::collection($this->Years->where('status', 1)),
        ];
    }
}
