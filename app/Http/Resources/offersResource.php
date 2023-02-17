<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class offersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'price'              => [
                                        'value'    => $this->price, 
                                        'currency' => trans('site.SAR'), 
                                    ],
            'price_after_discount'  => Controller::get_price_after_discount($this->price, $this->discount),
            'classes_count'         => $this->classes_count,
        ];
    }
}
