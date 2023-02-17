<?php

namespace App\Http\Resources;

use App\Models\Available_class;
use Illuminate\Http\Resources\Json\JsonResource;

class schedules_dateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $available_class = Available_class::where('teacher_id', $request->teacher_id)
                                    ->whereDate('from', '=', $this->from_date)
                                    ->schedule()
                                    // ->whereHas('Student_classes')
                                    ->get();
        return [
            'date'              => $this->from_date,
            'day_schedules'     => availableClassResource::collection($available_class),
        ];
    }
}
